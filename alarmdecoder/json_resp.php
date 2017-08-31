<?php


require_once('alarmmessage.php');
require_once('alarmstate.php');
require_once('db.php');
require_once('logmsg.php');


class getAlarmDecoderResp{

  var $last_message_received;
  var $panel_alarming;
  var $panel_armed;
  var $panel_armed_stay;
  var $panel_bypassed;
  var $panel_fire_detected;
  var $panel_on_battery;
  var $panel_panicked;
  var $panel_powered;
  var $panel_relay_status;
  var $panel_type;
  var $panel_zones_faulted;
  var $egc_hostname;
  var $egc_state_text;
  var $egc_simple_msg;
  var $egc_datestring;

  function __construct($state, $mylog){
    $myarray = explode(',', $state->last_raw_msg);

//    $this->last_message_received = $state->state_text;
    $this->last_message_received = $state->last_raw_msg;
    $this->egc_simple_msg = $myarray[3];
    $this->panel_alarming = false;
    $this->panel_armed = $state->b_armed_away;
    $this->panel_armed_stay = (bool) ($state->b_armed_stay | $state->b_armed_instant);
    $this->panel_bypassed = false;
    $this->panel_fire_detected = false;
    $this->panel_on_battery = false;
    $this->panel_panicked = false;
    $this->panel_powered = false;
    $this->panel_relay_status = array();
    $this->panel_type = $state->panel;
    $this->panel_zones_faulted = array();
    $this->egc_hostname = gethostname();
    $this->egc_state_text = $state->state_text;
    $this->egc_datestring = $state->datestring;

//$mylog->log(__FILE__,__LINE__,print_r($myarray,true));

  }
}


/*
$myResp = new getAlarmDecoderResp($state);
print_r($myResp);
$myJSON = json_encode($myResp);
echo $myJSON;
*/


/*
$myObj->name = "John";
$myObj->age = 30;
$myObj->city = "New York";

$myJSON = json_encode($myObj);

echo $myJSON;
*/

?>
