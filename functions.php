<?php

function request($method, $args)
{
    global $token;
    $args    = http_build_query($args);
    $request = curl_init("https://api.telegram.org/bot$token/$method");
    curl_setopt_array($request, array(
        CURLOPT_CONNECTTIMEOUT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => 'cURL request',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $args
    ));
    $result = curl_exec($request);
    curl_close($request);
    return $result;
}

function sendMessage($chatID, $message){
  $args = ["chat_id" => $chatID, "text" => $message];
  return request("sendMessage", $args);
}

// Add your own functions

