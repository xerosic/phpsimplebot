<?php

function request($method, $args = [])
{
  $c = curl_init();
  curl_setopt_array($c, [
    CURLOPT_URL => 'https://api.telegram.org/bot' . $token . '/' . $method,
    CURLOPT_RETURNTRANSFER => True,
    CURLOPT_POST => True,
    CURLOPT_POSTFIELDS => $args
    ]);
  $r = curl_exec($c);
  curl_close($c);
  return json_decode($r, True);
}

function sendMessage($chatID, $message){
  return request("sendMessage", ["chat_id" => $chatID, "text" => $message]);
}

// Add your own functions

