<?php
// Antworten immer als JSON zurückgeben
header('Content-Type: application/json');

include_once('include/blechophon.inc.php');
include_once('include/media.inc.php');

if (session_id() == '') {
    $started = session_start();
}

if (!isLoggedIn()) {
    echo("Not logged in. => die");
    die();
}

// 1. Konfiguration
$maxPhotoWidth  = 1200; // Maximale Breite Hauptbild (px)
$maxPhotoHeight = 1200; // Maximale Höhe Hauptbild (px)
$thumbWidth     = 200;  // Maximale Breite Thumbnail (px)
$thumbHeight    = 200;  // Maximale Höhe Thumbnail (px)

$jpegQuality    = 70;   // Speicher-Qualität (0-100)

// 2. Grundlegende Prüfungen
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfragemethode.']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dateiupload fehlgeschlagen oder keine Datei gesendet.']);
    exit;
}

$mediaEventId = isset($_POST['mediaEventId']) ? intval($_POST['mediaEventId']) : 0;
$tmpPath = $_FILES['file']['tmp_name'];

if ($mediaEventId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Media Event ID invalid.' . $mediaEventId]);
    exit;
}

$mediaevent = MediaBean::getMediaeventById($mediaEventId);

if ($mediaevent == null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'MediaEvent mit Id ' . $mediaEventId . ' existiert nicht.']);
    exit;
}

$pathPrefix = __DIR__ . "/../fotos/event/";
$pathyear = $pathPrefix . $mediaevent->getYear();


function createDirectoryIfNotExists(string $pathyear): void
{
    if (!is_dir($pathyear)) {
        mkdir($pathyear, 0755, true);
    }
}

createDirectoryIfNotExists($pathyear);

$pathMediaEventDirectory = $pathyear . "/" . $mediaevent->getDirectoryEvent();

createDirectoryIfNotExists($pathMediaEventDirectory);

$pathMediaEventFotosDirectory = $pathMediaEventDirectory . "/" . $mediaevent->getDirectoryMain();
createDirectoryIfNotExists($pathMediaEventFotosDirectory);

$pathMediaEventThumbnailsDirectory = $pathMediaEventDirectory . "/" . $mediaevent->getDirectoryThumbs();
createDirectoryIfNotExists($pathMediaEventThumbnailsDirectory);

// 3. Echte JPG-Validierung (Inhaltsprüfung)
$imageInfo = @getimagesize($tmpPath);

// MIME-Type und GD-Kompatibilität prüfen
if ($imageInfo === false || $imageInfo['mime'] !== 'image/jpeg') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Die Datei ist kein gültiges JPG-Bild.']);
    exit;
}

// 5. Bild aus Quelldatei laden
$srcImage = @imagecreatefromjpeg($tmpPath);
if (!$srcImage) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Bild konnte nicht verarbeitet werden.']);
    exit;
}

// 6. Dateinamen generieren
$filename = $mediaevent->getDirectoryEvent() . '_' . time() . '.jpg';

// 7. Skalierungs-Funktion (Proportional mit Beibehaltung des Seitenverhältnisses)
function resizeAndSave($srcImg, $targetPath, $maxWidth, $maxHeight, $quality) {
    $origWidth  = imagesx($srcImg);
    $origHeight = imagesy($srcImg);

    // Berechne neue Skalierung ohne Verzerrung
    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);

    // Wenn das Bild kleiner als das Limit ist, Originalgröße beibehalten
    if ($ratio >= 1) {
        $newWidth  = $origWidth;
        $newHeight = $origHeight;
    } else {
        $newWidth  = (int)round($origWidth * $ratio);
        $newHeight = (int)round($origHeight * $ratio);
    }

    // Neues Canvas erstellen und Bild neu berechnen (resample)
    $newImg = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    // Als JPG speichern
    $status = imagejpeg($newImg, $targetPath, $quality);
    imagedestroy($newImg);

    return [
        'status'  => $status,
        'width'   => $newWidth,
        'height'  => $newHeight
    ];
}

// 8. Hauptbild & Thumbnail verarbeiten und speichern
$photoCreateResult = resizeAndSave($srcImage, $pathMediaEventFotosDirectory . "/" . $filename, $maxPhotoWidth, $maxPhotoHeight, $jpegQuality);
$thumbCreateResult = resizeAndSave($srcImage, $pathMediaEventThumbnailsDirectory . "/" . $filename, $thumbWidth, $thumbHeight, $jpegQuality);

$mediaItem = new MediaItem();
$mediaItem->setFilename($filename);
$mediaItem->setMainwidth($photoCreateResult['width']);
$mediaItem->setMainheight($photoCreateResult['height']);
$mediaItem->setThumbwidth($thumbCreateResult['width']);
$mediaItem->setThumbheight($thumbCreateResult['height']);
$mediaItem->setComment("");
$mediaItem->setDuration(0);
$mediaItem->setBytes(0);
$mediaItem->setMediaeventId($mediaEventId);

// Hauptbild-Ressource freigeben
imagedestroy($srcImage);

// 9. Status an den Client zurückgeben
if ($photoCreateResult['status'] && $thumbCreateResult['status']) {

    // Store To DB
    try {
        MediaBean::storeMediaItem($mediaItem);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Interner Serverfehler: ' . $e->getMessage()
        ]);
        die;
    }

    echo json_encode([
        'success'  => true,
        'message'  => 'Upload und Skalierung erfolgreich!',
        'id'       => $mediaEventId,
        'filename' => $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern der Bilder.']);
}