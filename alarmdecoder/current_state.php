<?php

require_once('alarmmessage.php');
require_once('alarmstate.php');
require_once('db.php');


// main



$db = new MyDB();
if(!$db) {
   echo $db->lastErrorMsg();
} else {
   echo "Opened database successfully\n";
}

$state = new AlarmState($db, true);

if($state->setup()){ 

  printf("Last state saved in DB\n");
  print_r($state);
//printf("raw[%s]\n", str_replace("\r\n","",$state->last_raw_msg));

}else{
  printf("NEW DATABASE\n");
}

$db->close();


?>
