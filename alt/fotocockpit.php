<h1>Foto Cockpit</h1>

<?php

if (!isLoggedIn()) {
    echo("Not logged in. => die");
    die();
}

?>

<div id="MediaEventFormDiv" style="display: flex; flex-direction: column; width: 100%; ">
    <div style="display: flex; flex-direction: row; margin: 5px">
        <label for="mediaeventId" style="width: 120px"> MediaEventId: </label>
        <input type="number" readonly="readonly" disabled="disabled" style="width: 500px" id="mediaeventId" name="mediaeventId"/>
    </div>
    <div style="display: flex; flex-direction: row; margin: 5px">
        <label for="eventName" style="width: 120px"> Event Name: </label>
        <input type="text" id="eventName" name="eventName" style="width: 500px"/>
    </div>
    <div style="display: flex; flex-direction: row; margin: 5px">
        <label for="year" style="width: 120px"> Jahr: </label>
        <input type="number" id="year" min="2010" max="2050" style="width: 500px" value="<?php echo(date("Y")); ?>" name="year"/>
    </div>
    <div style="display: flex; flex-direction: row; margin: 5px">
        <label for="directoryEvent" style="width: 120px"> Event Verzeichnis: </label>
        <input type="text" id="directoryEvent" name="directoryEvent" style="width: 500px"/>
    </div>
    <div style="display: flex; flex-direction: row; margin: 5px">
        <div  style="width: 120px">  </div>
        <button  style="width: 100px" onclick="onClickStore()"> Speichern </button>
        <button  style="width: 100px" onclick="onClickCancel()"> Abbrechen </button>
    </div>
</div>

<div id="addFotoDiv" style="display: none; flex-direction: column; width: 400px; margin: 15px; padding:8px; border: 1px solid #777777; border-radius: 5px; gap: 12px">
    <div style="display: flex; " >Foto hinzufügen zu:</div>
    <div style="display: flex; " id="addFotoHeadline"></div>
    <form method="post" enctype="multipart/form-data" id="fotoUploadForm">
    <input type="file" name="file" id="fileFoto" accept="image/jpeg, .jpg, .jpeg" style="width: 400px" />
    </form>
    <input type="hidden" id="fileFotoUploadMediaEventId">
    <div style="display: flex; flex-direction: row">
    <button onclick="sendFoto();" style="height: 20px; width: 200px">Foto uploaden</button>
    <button onclick="sendFotoCancel();" style="height: 20px; width: 200px">Cancel</button>
    </div>

</div>

<?php $allMediaEvents = MediaBean::getAllMediaEvents(); ?>

<div id="MediaEventFormDiv" style="display: flex; flex-direction: column; width: 100%; ">

<?php $mediaEvents = array(); ?>




<?php foreach ($allMediaEvents as $oneMediaEvent) { ?>

    <?php
        $mediaEvent = array();
        $mediaEvent['eventName'] = $oneMediaEvent->getEventName();
        $mediaEvent['year'] = $oneMediaEvent->getYear();
        $mediaEvent['mediaeventId'] = $oneMediaEvent->getMediaeventId();
        $mediaEvent['directoryEvent'] = $oneMediaEvent->getDirectoryEvent();
        $mediaEvents[$oneMediaEvent->getMediaeventId()] = $mediaEvent;
    ?>

    <div style="display: flex; flex-direction: row; padding: 5px; margin: 5px;border: 1px solid #777777; border-radius: 5px; padding 5px; margin: 5px">
        <div style="display: flex; flex-direction: column">
            <button onclick="edit(<?php echo($oneMediaEvent->getMediaeventId()); ?>)" style="padding: 5px; margin-right: 5px">Event<br>Name<br>ändern</button>
            <button onclick="addFoto(<?php echo($oneMediaEvent->getMediaeventId()); ?>, '<?php echo($oneMediaEvent->getEventName()); ?>')" style="padding: 5px; margin-right: 5px">Foto<br>hinzu<br>fügen</button>
        </div>
        <div style="display: flex; flex-direction: column;">
            <div title="Event Name" style="font-size: larger; font-weight: bold"><?php echo($oneMediaEvent->getEventName()); ?> </div>
            <div style="display: flex; flex-direction: row; width: 100%; ">
                <div title="Mediaevent Id" style="width: 40px"><?php echo($oneMediaEvent->getMediaeventId()); ?> </div>
                <div title="Jahr" style="width: 40px"><?php echo($oneMediaEvent->getYear()); ?> </div>
                <div title="Event Verzeichnis"><?php echo($oneMediaEvent->getDirectoryEvent()); ?> </div>
            </div>
            <div style="display: flex; flex-direction: column; width: 100%; padding-left: 15px">
                Fotos:
                <?php $count = 1 ?>
                <?php foreach ($oneMediaEvent->getMediaItems() as $mediaItem) { ?>
                    <div style="display: flex; flex-direction: row; width: 100%; ">
                        <div title="laufende Nummer" style="width: 30px"><?php echo($count++); ?> </div>
                        <div title="MediaItem Id" style="width: 50px"><?php echo($mediaItem->getMediaItemId()); ?> </div>
                        <button title="Löschen!!!" style="width: 70px; background-color: #ff0000" onclick="deleteFoto(<?php echo($mediaItem->getMediaItemId()); ?>, <?php echo($oneMediaEvent->getMediaeventId()); ?>)"> Löschen </button>
                        <div title="Dateiname" style="width: 40px; padding-left: 5px"><?php echo($mediaItem->getFilename()); ?> </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
 <?php } ?>


<script>

    <?php $mediaEventsJson = json_encode($mediaEvents); ?>

    var mediaEvents = <?php echo($mediaEventsJson); ?>;

    function onClickStore() {

        let json = {};

        let mediaEventId = document.getElementById("mediaeventId").value;
        if (!mediaEventId) {
            mediaEventId = -1
        } else {
            mediaEventId = parseInt(mediaEventId);
        }
        json["mediaEventId"] = mediaEventId;

        let eventName = document.getElementById("eventName").value;
        json["eventName"] = eventName;

        if (!eventName) {
            alert("An namen soits scho hom oder?");
            return;
        }

        if (mediaEventId > 0) {
            json["action"] = "update";
            //alert("update");
        } else {

            let year = parseInt(document.getElementById("year").value);
            json["year"] = year;
            if (!Number.isInteger(year)) {
                alert("Beim Jahr hats was...");
                return;
            }

            let directoryEvent = document.getElementById("directoryEvent").value;
            json["directoryEvent"] = directoryEvent;
            if (!isValidDirectoryEventString(directoryEvent)) {
                alert("Beim Event Verzeichnis sind nur Großbuchstaben, Kleinbuchstaben, Ziffern und Underscrore erlaubt...");
                return;
            }

            //alert("insert");
            json["action"] = "insert";
        }

        // console.log(json);

        sendDataToServer(json)
            .then(responseData => {
                console.log('Antwort vom Server:', responseData);
                window.location.reload(true);
            })
            .catch(err => {
                console.error('Anfrage fehlgeschlagen:', err);
                alert("Fehler:" + err);
            });
    }

    async function sendDataToServer(data) {
        try {
            const response = await fetch('fotoService.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`HTTP-Fehler! Status: ${response.status}`);
            }

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Fehler beim Senden der Daten:', error);
            throw error;
        }
    }

    function isValidDirectoryEventString(str) {
        // \w entspricht genau [a-zA-Z0-9_]
        return /^\w+$/.test(str);
    }

    function onClickCancel(){
        document.getElementById("mediaeventId").value = "";
        document.getElementById("eventName").value = "";
        document.getElementById("year").value = "<?php echo(date("Y")); ?>";
        document.getElementById("year").disabled = false;
        document.getElementById("directoryEvent").value = "";
        document.getElementById("directoryEvent").disabled = false;
    }

    function edit(mediaeventId) {
        let mediaEvent = mediaEvents[mediaeventId];

        if (mediaEvent) {
            document.getElementById("eventName").value = mediaEvent["eventName"];
            document.getElementById("mediaeventId").value = mediaeventId;
            document.getElementById("year").value = mediaEvent["year"];
            document.getElementById("year").disabled = true;
            document.getElementById("directoryEvent").value = mediaEvent["directoryEvent"];
            document.getElementById("directoryEvent").disabled = true;
        }
    }

    function deleteFoto(mediaItemId, mediaEventId) {
        if (!confirm("Foto wirklich löschen")) {
            return;
        }

        let json = {};
        json["mediaItemId"] = mediaItemId;
        json["mediaEventId"] = mediaEventId;
        json["action"] = "deleteMediaItem";

        sendDataToServer(json)
            .then(responseData => {
                console.log('Antwort vom Server:', responseData);
                window.location.reload(true);
            })
            .catch(err => {
                console.error('Anfrage fehlgeschlagen:', err);
                alert("Fehler:" + err);
            });
    }

    function addFoto(mediaEventId, mediaEventName) {
        let addFotoDiv = document.getElementById("addFotoDiv");
        addFotoDiv.style.display="flex";
        document.getElementById("fileFotoUploadMediaEventId").value = mediaEventId;
        document.getElementById("addFotoHeadline").innerHTML = mediaEventName;
    }

    function sendFotoCancel() {
        let addFotoDiv = document.getElementById("addFotoDiv");
        addFotoDiv.style.display="none";
        document.getElementById("fileFotoUploadMediaEventId").value = "";
        document.getElementById("addFotoHeadline").innerHTML = "";
        document.getElementById('fileFoto').value = '';
    }

    async function sendFoto() {
        console.log("send Foto")
        const fileInput = document.getElementById('fileFoto');

        // Pruefen, ob eine Datei ausgewaehlt wurde
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Bitte wähle zuerst ein Foto aus.');
            return;
        }

        const file = fileInput.files[0];

        // Überprüfung auf JPEG / JPG (MIME-Type)
        const validTypes = ['image/jpeg', 'image/jpg'];
        const isJpgExtension = /\.jpe?g$/i.test(file.name);

        if (!validTypes.includes(file.type) && !isJpgExtension) {
            alert('Bitte wähle eine gültige JPG/JPEG-Datei aus.');
            fileInput.value = ''; // Zurücksetzen der Auswahl
            return;
        }

        // FormData-Objekt erstellen
        const formData = new FormData();
        formData.append('file', file);
        let mediaEventId = document.getElementById("fileFotoUploadMediaEventId").value;
        console.log("mediaEventId=" . mediaEventId);
        formData.append('mediaEventId', mediaEventId); // ID als Formulardaten mitsenden

        try {
            // POST-Request absenden
            // Hinweis: URL an dein Backend anpassen (z. B. '/api/upload' oder `/api/upload/${id}`)
            const response = await fetch('upload.php', {
                method: 'POST',
                body: formData, // Keinen 'Content-Type'-Header setzen! Fetch setzt ihn inkl. Boundary automatisch.
            });

            if (!response.ok) {
                throw new Error(`Server-Fehler: ${response.status}`);
            }

            const data = await response.json();
            console.log('Upload erfolgreich:', data);
            //alert('Foto erfolgreich hochgeladen!');
            window.location.reload(true);

        } catch (error) {
            console.error('Fehler beim Upload:', error);
            alert('Upload fehlgeschlagen.');
        }
    }

</script>


</div>




