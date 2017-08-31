<?php

 class LogMsg{

   var $fp;
   var $filename;
   var $roll_log;
   var $timeopened;
   var $timetoroll;

   function __construct($filename=null){

     if($filename == null){
       $this->fp = STDOUT;
     }else{
       $this->fp = fopen($filename, 'a');
     }     

     $this->filename = $filename;
     $this->roll_log = 0;
     $this->timeopened = time();
     
   }


   function log($fn,$ln,$msg){

     date_default_timezone_set('America/New_York');
     $datestring = date("H:i:s-m/d/Y");

     $base = basename($fn);

     fprintf($this->fp,"%s|%s:%d|%s|%s\n",$datestring,$base, $ln, gethostname(), $msg);

     if($this->test_time_to_roll()){
       $this->roll_logs();
     }

   }

   function set_roll_log($time_in_hours){
     $this->roll_log = $time_in_hours;
     $this->timetoroll = $this->timeopened + ($this->roll_log * 3600);
   }

   function test_time_to_roll(){

     if($this->roll_log == 0){
       return false;
     }

     if(time() > $this->timetoroll){
       return true;
     }

     return false;
   }

   function roll_logs(){
//print_r($this);

     if($this->filename == null){
       return;
     }

     $new_filename = sprintf("%s.bak", $this->filename);
     fclose($this->fp);
     rename($this->filename, $new_filename);

     $this->fp = fopen($this->filename, 'a');
     $this->timeopened = time();

     if($this->roll_log > 0){
       $this->set_roll_log($this->roll_log);
     }

   }

 }


?>
