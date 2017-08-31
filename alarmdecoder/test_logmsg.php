<?php

require_once('logmsg.php');


 $mylog = new LogMsg("egc.out");

 $mylog->set_roll_log(1);

$cnt = 0;

while(true){
 for($i = 0; $i < 5; $i++){
   $mylog->log(__FILE__,__LINE__,sprintf("Test msg-%d", $cnt++));
  }

 $mylog->log(__FILE__,__LINE__,"breaker");

 for($i = 5; $i < 10; $i++){
   $mylog->log(__FILE__,__LINE__,sprintf("Test msg-%d", $cnt++));
  }

  sleep(10);
}

?>
