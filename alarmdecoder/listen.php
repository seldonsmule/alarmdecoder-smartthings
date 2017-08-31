<?php


require_once('config.php');
require_once('alarmmessage.php');
require_once('alarmstate.php');
require_once('db.php');
require_once('logmsg.php');



// main

if(count($argv) != 3){
  printf("Usage listen.php hostname port\n");
  exit(1);
}

$hostname = $argv[1];
$port = $argv[2];


$mylog = new LogMsg("logs/listen.log");

$db = new MyDB();
if(!$db) {
   $mylog->log(__FILE__,__LINE__, $db->lastErrorMsg());
} else {
   $mylog->log(__FILE__,__LINE__, "Opened database successfully");
}

$state = new AlarmState($db,true);

if($state->setup()){
  $mylog->log(__FILE__,__LINE__,"Last state saved in DB");
  $mylog->log(__FILE__,__LINE__,json_encode($state));
  //print_r($state);
}else{
  $mylog->log(__FILE__,__LINE__,"NEW DATABASE\n");
}


//-------
// put read from database last stored state here
//-------

while(true){

  $mylog->log(__FILE__,__LINE__,
               sprintf("Opening socket:%s:%d", $hostname,$port));

  $fp = fsockopen($hostname, $port, $errno, $errstr, 30);

  if (!$fp) {
      $mylog->log(__FILE__,__LINE__, "$errstr ($errno)<br />");
  } else {


    while (!feof($fp)) { // keep reading while we can

          if($g_admin_mode){
            printf("Admin mode on\n");
            $mylog->log(__FILE__,__LINE__,"WARNING - Admin Mode On");
          }

          if( ($buffer = fgets($fp)) == false){
            $mylog->log(__FILE__,__LINE__,"Ha!  trapped read error!");
            break;
          }

          $msg = new AlarmMsg($mylog, $buffer);
          $msg->log_buffer("logs/raw.out");
          //$mylog->log(__FILE__,__LINE__,json_encode($msg));

          if($msg->b_cmd_resp){
            unset($msg);
            continue;
          }

          if($state->check_state($msg)){
//-------
// put new state logic - allows us to have a socket error and recover
//                       but not create a new state in the DB record if not 
//                         needed
// did the socket read state differ from the last stored database state?
//-------

             $mylog->log(__FILE__,__LINE__,"---New state change: Begin----");
             $mylog->log(__FILE__,__LINE__, json_encode($state));
             //$db->save_state_change($state);
             $mylog->log(__FILE__,__LINE__,"---New state change: End----");
          }


       unset($msg);
    } // end while loop
    fclose($fp);

  } // end else

  $mylog->log(__FILE__,__LINE__,"End of while true - lets go do it again! lets sleep a min\n");
  sleep(60);

} // end while true



?>
