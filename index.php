<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#000">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>ISS Tracker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="icon" href="favicon.ico">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/9c0f301054.js"></script>
</head>

<body>
    <h1>Waar is het International Space Station?</h1>
    <p style="margin-top: 2em; margin-bottom: 2em; font-size: 1.2em;">
        <span style="margin-right: 2em; display:inline-block;">
            <i class="fas fa-arrows-alt-h"></i> Breedtegraad: <span id="lat"></span>
        </span>
        <span style="display:inline-block;">
            <i class="fas fa-arrows-alt-v"></i> Lengtegraad: <span id="lon"></span>
        </span>
    </p>

    <div id="map" style="margin-bottom: 1em;"></div>

    <hr>

    <footer style='text-align: center'>
        &copy; Daan Rosendal - <?php echo date("Y"); ?>
    </footer>

    <script>
        // Een map met tiles aanmaken
        const mymap = L.map('map').setView([0, 0], 0);
        const attribution = {
            copyright: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        };
        const tileURL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const tiles = L.tileLayer(tileURL, attribution);
        tiles.addTo(mymap);

        // De marker veranderen in een ISS plaatje
        var ISSIcon = L.icon({
            iconUrl: 'images/iss.png',
            iconSize: [50, 32],
            iconAnchor: [25, 16]
        });

        const marker = L.marker([0, 0], {
            icon: ISSIcon
        }).addTo(mymap);

        // Link naar de API
        const API_URL = 'https://api.wheretheiss.at/v1/satellites/25544';

        // Functie om de latitude en longitude op te vragen van de API
        let firstTime = true;
        async function getISS() {
            const response = await fetch(API_URL);
            const data = await response.json();
            const {
                latitude,
                longitude
            } = data;

            // De locatie van het ISS bepalen
            marker.setLatLng([latitude, longitude]);

            // De map laten inzoomen op het ISS
            if (firstTime) {
                mymap.setView([latitude, longitude], 4);
                firstTime = false;
            }

            // DOM-elementen aanpassen naar de relevante API data
            document.getElementById('lat').textContent = data.latitude.toFixed(2) + "°";
            document.getElementById('lon').textContent = data.longitude.toFixed(2) + "°";
        }
        // Functie aanroepen
        getISS();

        // getISS om de seconde aanroepen zodat het ISS verplaatst op de map
        setInterval(getISS, 1000);
    </script>
</body>

</html>