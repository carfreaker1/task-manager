<?php

$apiKey = config('services.google.api_key');
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

$options = [
    "http" => [
        "method" => "GET",
        "header" => "Content-type: application/json\r\n",
        "timeout" => 5
    ],
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false
    ]
];
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === false) {
    echo "Request failed!";
} else {
    echo "SUCCESS:\n";
    $json = json_decode($result, true);
    foreach($json['models'] as $m) {
        if (strpos($m['name'], 'gemini') !== false) {
            echo $m['name'] . "\n";
        }
    }
}
