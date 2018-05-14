<?php

  //Usa la lista de palabras de https://github.com/bitcoin/bips/blob/master/bip-0039/spanish.txt

  $slackWebhook = "https://hooks.slack.com/services/XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

  $wordIndex = 0;

  if (file_exists("data/wordlist.index")) {
    $fileIndex = file_get_contents("data/wordlist.index");
    if (is_numeric($fileIndex)) {
      $wordIndex = intval($fileIndex);
    }
  }

  $wordData = file("data/wordlist.txt", FILE_IGNORE_NEW_LINES);

  if ($wordIndex >= count($wordData)) {
    $wordIndex = 0;
  }

  $output = ":pencil2: La palabra del día es *";
  $output.= $wordData[$wordIndex] . "*";

  $json = array('text' => $output);
  $json_string = json_encode($json);    

  $wordIndex++;
  file_put_contents("data/wordlist.index", $wordIndex);

  $c = curl_init();
  curl_setopt_array($c, array(
      CURLOPT_URL => $slackWebhook,
      CURLOPT_POST => true, 
      CURLOPT_POSTFIELDS => $json_string,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($json_string))));
  $result = curl_exec($c);
  //print_r(curl_getinfo($c));
  //echo $result;
  curl_close($c);