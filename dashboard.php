<?php

function login() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://analytics.geoperu.gob.pe/api/v1/security/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "password": "admin",
    "provider": "db",
    "refresh": false,
    "username": "admin"
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return  json_decode($response, true)['access_token'];
}


function getToken(){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://analytics.geoperu.gob.pe/api/v1/security/guest_token/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "resources": [
    {
      "id": "a889aecb-dde0-42f1-b822-b78754a9ecae",
      "type": "dashboard"
    }
  ],
  "rls": [
    {
      "clause": "string",
      "dataset": 0
    }
  ],
  "user": {
    "first_name": "string",
    "last_name": "string",
    "username": "string"
  }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.login()
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return json_decode($response, true)['token'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        body {
    margin: 0;            /* Reset default margin */
}
iframe {
    display: block;       /* iframes are inline by default */
    background: #000;
    border: none;         /* Reset default border */
    height: 100vh;        /* Viewport-relative units */
    width: 100vw;
}
    </style>
</head>
<body>
    <div id="container" style="width: 100vw;height:100vh">

    </div>
</body>

<script src="https://unpkg.com/@superset-ui/embedded-sdk"></script>

<script>
  supersetEmbeddedSdk.embedDashboard({
    id: "a889aecb-dde0-42f1-b822-b78754a9ecae",
    supersetDomain: "https://analytics.geoperu.gob.pe",
    mountPoint: document.getElementById("container"), // any html element that can contain an iframe
    fetchGuestToken: () => "<?php echo(getToken()) ?>",
    dashboardUiConfig: { hideTitle: true }, // dashboard UI config: hideTitle, hideTab, hideChartControls (optional)
  });
</script>
</html>


