<?php


header('Content-Type: application/json; charset=utf-8');

include_once('include/blechophon.inc.php');
include_once('include/media.inc.php');

if (session_id() == '') {
     $started = session_start();
}

if (!isLoggedIn()) {
    echo("Not logged in. => die");
    die();
}


// Nur POST-Anfragen erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

// Raw JSON-Body aus dem Input-Stream lesen
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

// Prüfen, ob das JSON valide war
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Ungültiges JSON-Format'
    ]);
    exit;
}

try {
    $action = $data['action'];

    $ret = false;
    if ($action === "update") {
        $mediaEventId = $data['mediaEventId'];
        $mediaEvent = MediaBean::getMediaeventById($mediaEventId);
        $mediaEvent->setEventName($data["eventName"]);
        $ret = MediaBean::storeMediaEvent($mediaEvent);
    } else if ($action === "insert") {
        $mediaEvent = new MediaEvent();
        $mediaEvent->setDirectoryEvent($data["directoryEvent"]);
        $mediaEvent->setDirectoryMain("photos");
        $mediaEvent->setDirectoryThumbs("thumbs");
        $mediaEvent->setEventName($data["eventName"]);
        $mediaEvent->setYear($data["year"]);
        $mediaEvent->setMediacategoryId(1);
        $mediaEvent->setMediakindId(1);
        $mediaEvent->setMediaItems(null);
        $ret = MediaBean::storeMediaEvent($mediaEvent);
    } else if ($action === "deleteMediaItem") {
        $mediaItemId = $data['mediaItemId'];
        $mediaEventId = $data['mediaEventId'];
        $mediaEvent = MediaBean::getMediaeventById($mediaEventId);
        $mediaItem = MediaBean::getMediaItemById($mediaItemId);

        $pathPrefix = __DIR__ . "/../fotos/event/";
        $pathyear = $pathPrefix . $mediaEvent->getYear();
        $pathMediaEventDirectory = $pathyear . "/" . $mediaEvent->getDirectoryEvent();

        $pathMediaEventFotosDirectory = $pathMediaEventDirectory . "/" . $mediaEvent->getDirectoryMain();
        $pathMediaEventThumbnailsDirectory = $pathMediaEventDirectory . "/" . $mediaEvent->getDirectoryThumbs();

        $pathFoto = $pathMediaEventFotosDirectory . "/" . $mediaItem->getFilename();
        $pathThumb = $pathMediaEventThumbnailsDirectory . "/" . $mediaItem->getFilename();

        if (is_file($pathFoto)) {
            unlink($pathFoto);
        }

        if (is_file($pathThumb)) {
            unlink($pathThumb);
        }

        MediaBean::deleteMediaItemId($mediaItemId);
    }

    // Beispielhafte Erfolgsrückgabe:
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Daten erfolgreich verarbeitet',
        'data' => $data
    ]);

} catch (Throwable $e) {
    // Fehlerbehandlung in der Businesslogik
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Interner Serverfehler: ' . $e->getMessage()
    ]);
}