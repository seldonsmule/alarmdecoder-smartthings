#!/usr/bin/php

<?php


  if(count($argv) != 3){
    printf("Usage send.php passcode cmd\n");
    exit(1);
  }

  $passcode = $argv[1];
  $cmd = $argv[2];

  echo "passcode:$passcode cmd:$cmd\n";

  $msg = sprintf("%s%s\n", $passcode, $cmd);

  echo "sending:$msg";

  $fp = fsockopen("macdaddy", 10000, $errno, $errstr, 30);

  if (!$fp) {
    echo "$errstr ($errno)<br />\n";
    exit(0);
  } 

  fwrite($fp, $msg);
  fclose($fp);

?>
