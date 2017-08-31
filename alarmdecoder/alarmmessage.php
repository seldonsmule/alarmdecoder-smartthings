<?php

require_once('config.php');
require_once('logmsg.php');

class AlarmMsg {

  var $text;
  var $bit_field;
  var $numeric_code;
  var $raw_msg;
  var $buffer;

  var $b_panel_ready = false;
  var $b_armed_away = false;
  var $b_armed_stay = false;
  var $b_armed_instant = false;
  var $b_armed_fire = false;
  var $b_alarm_sounding = false;
  var $b_on_power = false;
  var $b_chime_on = false;
  var $b_backlight_on = false;
  var $panel_type;
  var $panel_name;

  var $b_cmd_resp = false;
  var $b_cmd_unknown = false;

  var $b_state_unknown = true;

  var $timestamp;

  var $config;
  var $mylog;

  function __construct($mylog, $buffer){

    $this->mylog = $mylog;

    if(strcmp($buffer,"init") == 0){
      return;
    }

    $this->newMessage($buffer);

  }

  function newMessage($buffer){

    $this->timestamp = time();

    if($buffer[0] == '!'){
      $this->process_cmd($buffer);
      return;
    }
   
    if($buffer[0] == '['){
      $this->load_object($buffer);
      return;
    }

  }

  function process_cmd($buffer){
    $this->b_cmd_resp = true;

    $tmp = $buffer;
    $tmp =rtrim($tmp);

    $this->buffer = $buffer; // store this off

    switch($tmp){

      case "!SER2SOCK Connected":
        $this->mylog->log(__FILE__,__LINE__, "connected to ser2sock");
      break;
      
      case "!SER2SOCK SERIAL_CONNECTED":
        $this->mylog->log(__FILE__,__LINE__, "listening to serial port");
      break;

      case "!Sending.done":
        $this->mylog->log(__FILE__,__LINE__, "Send Responce Msg");
      break;

      default:
        //printf("Unknown cmd: [%s]\n", $tmp);
        $this->b_cmd_unknown = true;
      break;
    }
   
  }

  function load_object($buffer){
    $this->buffer = $buffer;

    $this->b_cmd_resp = false;

    // get the 4 basic parts 1st
    $this->bit_field = strstr($this->buffer,",", true);
    $this->numeric_code = strstr($this->buffer,",");
    $this->numeric_code = substr($this->numeric_code,1);
    $this->raw_msg = strstr($this->numeric_code,",");
    $this->raw_msg = substr($this->raw_msg,1);
    
    // clean up bit_field
    $this->bit_field = substr($this->bit_field,1);
    $this->bit_field = strstr($this->bit_field,"]",true);

    // clean up numeric_code
    $this->numeric_code = strstr($this->numeric_code,",", true);

    // clean up raw_msg
    $this->raw_msg = substr($this->raw_msg,1);
    $this->raw_msg = strstr($this->raw_msg,"]",true);

    // clean up text
    $this->text = strstr($this->buffer,"\"");
    $this->text = substr($this->text,1);
    $this->text = strstr($this->text,"\"", true);

    // positions passed in are from the position values in 
    // https://www.alarmdecoder.com/wiki/index.php/Protocol#Special_Keys
    // documentation.
    // the function subtracts 1 to align wit the array itself
    // but i figured this would allow me to find a reference back to the doc

    $this->b_panel_ready = $this->bit_field_bool(1);
    $this->b_armed_away = $this->bit_field_bool(2);
    $this->b_armed_stay = $this->bit_field_bool(3);
    $this->b_backlight_on = $this->bit_field_bool(4);
    $this->b_on_power = $this->bit_field_bool(8);
    $this->b_chime_on = $this->bit_field_bool(9);
    $this->b_alarm_sounding = $this->bit_field_bool(11);
    $this->b_armed_instant = $this->bit_field_bool(13);
    $this->b_armed_fire = $this->bit_field_bool(14);
    $this->panel_type = $this->bit_field_val(18);

    if($this->panel_type == 'A'){
      $this->panel_name = "ADEMCO";
    }else{
      $this->panel_name = "DSC";
    }

    $this->b_state_unknown = false;
  }

  function bit_field_val($pos){
    $pos = $pos-1;
    return($this->bit_field[$pos]);
  }

  function bit_field_bool($pos){
    
    $pos = $pos-1;

    if($this->bit_field[$pos] == "1"){
      return true;
    }else{
      return false;
    } 
  }

  function get_buffer(){
    return $this->buffer;
  }

  function get_bit_field(){
    return $this->bit_field;
  }

  function get_numeric_code(){
    return $this->numeric_code;
  }

  function get_raw_msg(){
    return $this->raw_msg;
  }

  function get_text(){
    return $this->text;
  }

  function log_buffer($filename){

    //date_default_timezone_set('America/New_York');

    //$fp = fopen($filename, 'a');

    $mylog = new LogMsg($filename);

    $len = strlen($this->buffer);
    $mylog->log(__FILE__,__LINE__,sprintf("(%d): %s", $len, rtrim($this->buffer)));

    //$datestring = date("H:i:s-m/d/Y", $this->timestamp);

    //fprintf($fp, "%d:%s:(%d): %s", $this->timestamp, $datestring, $len, $this->buffer);

    //fclose($fp);


  }

}
