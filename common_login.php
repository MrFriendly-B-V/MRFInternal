<?php

if(isset($_GET['session'])) {
    $sessionId = $_GET['session'];
    setcookie('session', $sessionId, time() + 60*60*24*30, '/', 'intern.mrfriendly.nl');
    echo "COOKIE";
    echo "<script> window.location.href = \"https://intern.mrfriendly.nl/\"; </script";
    return;
}

if(!isset($_COOKIE['session'])) {
    echo "SESSION";
    if(isset($from)) {
        //echo "https://api.intern.mrfriendly.nl/oauth/login?returnUri=$from";
        echo "<script> window.location.href = \"https://api.intern.mrfriendly.nl/oauth/login?returnUri=$from\";</script";
    } else {
        //echo "https://api.intern.mrfriendly.nl/oauth/login?returnUri=https://intern.mrfriendly.nl/dashboard";
        echo "<script> window.location.href = \"https://api.intern.mrfriendly.nl/oauth/login?returnUri=https://intern.mrfriendly.nl/dashboard\";</script";
    }
    return;
}

//Get the session id
$sessionId = $_COOKIE['session'];

//Build the body for the POST request;
$data = [
    'session_id' => $sessionId
];

$url = "https://api.intern.mrfriendly.nl/oauth/session";

//Make the request and decode it
$response = post($url, json_encode($data));

if(strcmp($response, "OK")) {
    echo "NOK";
    if(isset($from)) {
        //echo "https://api.intern.mrfriendly.nl/oauth/login?returnUri=$from";
        echo "<script> window.location.href = \"https://api.intern.mrfriendly.nl/oauth/login?returnUri=$from\";</script";
    } else {
        //echo "https://api.intern.mrfriendly.nl/oauth/login?returnUri=$from";
        echo "<script> window.location.href = \"https://api.intern.mrfriendly.nl/oauth/login?returnUri=https://intern.mrfriendly.nl/dashboard\";</script";
    }
}

function post($url, $data) {
    $headers = [
        "Content-Type: application/json"
    ];

    //Initialize CURL and build the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    $result = curl_exec($ch);    
    curl_close($ch);

    return $result;
}

?>