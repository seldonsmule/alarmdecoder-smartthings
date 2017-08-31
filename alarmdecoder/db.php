<?php

require_once('alarmstate.php');
require_once('config.php');


// Reference https://www.tutorialspoint.com/sqlite/sqlite_php.htm
// if not familiar with using sqlite3
//


   class MyDB extends SQLite3 {

      var $config;

      function __construct() {
        $config = new EgcConfig();
        $this->open($config->database_name);

        $this->create_tables();
      }

      function create_tables(){

        $this->create_table_history();
        $this->create_table_apikey();
        $this->create_table_admin(); // build admin class to do stuff
        $this->create_last_buffer();

      } // end crete_state_table

      function create_table_history(){

        $sql="CREATE TABLE IF NOT EXISTS `history` ( `LAST_STATE` varchar(50) DEFAULT NULL, `DISARMED` int , `AWAY` int , `STAY` int , `INSTANT` int, `TIMESTAMP` int , `DATESTRING` varchar(50) DEFAULT NULL, `PANEL` varchar(50) DEFAULT NULL, `RAW` varchar(150) DEFAULT NULL);";
 
//        echo "$sql\n";

        $ret = $this->exec($sql);
        if(!$ret){
           //printf("%s%d: %s\n", basename(__FILE__),__LINE__, $this->lastErrorMsg());
        }else {
          //printf("%s:%d: History Table created successfully\n", basename(__FILE__),__LINE__);
        }


      } // end create_table_history

      function create_table_admin(){

        $sql="CREATE TABLE IF NOT EXISTS `admin` ( `LOGROLLTIME` int , `LOGROLLDATESTRING` varchar(50) DEFAULT NULL);";
 
//        echo "$sql\n";

        $ret = $this->exec($sql);

      } // end create_table_history

      function create_table_apikey(){

        $sql="CREATE TABLE IF NOT EXISTS `apikey` ( `key` varchar(50) DEFAULT NULL);";
 
        $ret = $this->exec($sql);
        if(!$ret){
           //printf("%s%d: %s\n", basename(__FILE__),__LINE__, $this->lastErrorMsg());
        }else {
          //printf("%s:%d: ApiKey Table created successfully\n", basename(__FILE__),__LINE__);
        }


      } // end create_table_apikey

      function create_last_buffer(){

        $sql="CREATE TABLE IF NOT EXISTS `lastbuffer` ( `ID` int, `BUFFER` varchar(50) DEFAULT NULL);";
 
        $ret = $this->exec($sql);
        if(!$ret){
           //printf("%s%d: %s\n", basename(__FILE__),__LINE__, $this->lastErrorMsg());
        }else {
          //printf("%s:%d: ApiKey Table created successfully\n", basename(__FILE__),__LINE__);
        }

        $sql="SELECT * FROM lastbuffer;";

        $ret = $this->query($sql);
        if(!$ret){
          //printf("%s%d: %s\n", basename(__FILE__),__LINE__, $this->lastErrorMsg());
        }else {
          //printf("%s:%d: executed sql\n", basename(__FILE__),__LINE__);
        }

        if( $row = $ret->fetchArray(SQLITE3_BOTH) ){
  
//print_r($row);

        }else{
//printf("empty\n");
          $sql="INSERT INTO lastbuffer (ID, BUFFER) VALUES (1, 'No Buffers Read Yet');" ;
          $this->exec($sql);
        }


      } // end create_table_apikey

      function save_state_change($state){

       $b_disarmed = $b_armed_away = $b_armed_stay = $b_armed_instant= 0;

        //printf("%s:%d: state_change()\n", basename(__FILE__),__LINE__); 

        if(!$this->check_if_new_state($state)){
          //printf("%s:%d: state_change() Same state as in DB.  Not updating\n", basename(__FILE__),__LINE__); 
          return;
        }

        // doing the because sqlite3 does not support BOOLEAN, using
        // int (0 or 1)

        if($state->b_disarmed) $b_disarmed = 1;
        if($state->b_armed_away) $b_armed_away = 1;
        if($state->b_armed_stay) $b_armed_stay = 1;
        if($state->b_armed_instant) $b_armed_instant = 1;
       

        $sql="INSERT INTO HISTORY (LAST_STATE, DISARMED, AWAY, STAY, INSTANT, TIMESTAMP, DATESTRING, PANEL, RAW) VALUES ('$state->state_text', $b_disarmed, $b_armed_away, $b_armed_stay, $b_armed_instant, $state->timestamp, '$state->datestring', '$state->panel', '$state->last_raw_msg');" ;

        $ret = $this->exec($sql);
        if(!$ret) {
           //printf("%s:%d: %s\n", basename(__FILE__),__LINE__, $this->lastErrorMsg());
        } else {
           //printf("%s:%d: Insert success\n", basename(__FILE__),__LINE__);
        }


      } //end state_change

      // test and see if we really have a new state from what was last
      // stored in the DB
      function check_if_new_state($state){

        //printf("%s:%d: check_if_new_state()\n", basename(__FILE__),__LINE__); 

        if( ($last = $this->get_last_saved_state()) != null){
          if(strcmp($last->state_text,$state->state_text) != 0){
            //printf("%s:%d: check_if_new_state(true!!)\n", basename(__FILE__),__LINE__); 
            return true;
          }
          else{
            //printf("%s:%d: check_if_new_state(false!!)\n", basename(__FILE__),__LINE__); 
            return false;
          }
        }

        //printf("%s:%d: check_if_new_state(IF HERE - EMPTY)\n", basename(__FILE__),__LINE__); 
        return true;

      } // check_if_new_state


      function get_last_saved_state($existing_state = null){
   

        //printf("%s:%d: get_last_saved_state()\n", basename(__FILE__),__LINE__); 

        // get the last entry
        $sql="select * from history where timestamp = (select max(timestamp) from history);";

        $ret = $this->query($sql);

        if( $row = $ret->fetchArray(SQLITE3_BOTH) ){

          //printf("%s:%d: get_last_saved_state(loading)\n", basename(__FILE__),__LINE__); 

          $last_buffer = $this->get_last_buffer();

          if($existing_state != null){
            $existing_state->load($row['LAST_STATE'],
                       $row['DISARMED'],
                       $row['AWAY'],
                       $row['STAY'],
                       $row['INSTANT'],
                       $row['TIMESTAMP'],
                       $row['DATESTRING'],
                       $row['PANEL'],
                       $last_buffer);
//                       $row['RAW']);
            return $existing_state;
          }

          $state = new AlarmState($this);

          $state->load($row['LAST_STATE'],
                       $row['DISARMED'],
                       $row['AWAY'],
                       $row['STAY'],
                       $row['INSTANT'],
                       $row['TIMESTAMP'],
                       $row['DATESTRING'],
                       $row['PANEL'],
                       $last_buffer);
//                       $row['RAW']);

          //print_r($state);
        
          return $state;
        }

        return null;

      }

      function update_last_buffer($buffer){
        $sql= sprintf("UPDATE lastbuffer SET BUFFER='%s' WHERE ID='1';", $buffer); 
//printf("sql: [%s]\n", $sql);
        $ret = $this->exec($sql);

      }

      function apikey_find($key){
        $sql="select * from apikey where key = '$key';";

        $ret = $this->query($sql);
        if( $row = $ret->fetchArray(SQLITE3_BOTH) ){
          return $row; 
        }

        return null;
      }

      function get_last_buffer(){
        $sql="select * from lastbuffer where ID = '1';";

        $ret = $this->query($sql);
        if( $row = $ret->fetchArray(SQLITE3_BOTH) ){
          return (str_replace("\r\n","",$row['BUFFER']));
        }

        return "Error Fetching Last Buffer";

      }

      function apikey_add($newkey){
        $sql="INSERT INTO apikey (key) VALUES ('$newkey');" ;

        $ret = $this->exec($sql);
        if($ret) {
          return true; 
        }
        return false;

      }

      function apikey_delete($delkey){
        $sql="delete from apikey where key = '$delkey';";
        $ret = $this->exec($sql);
        return true;
      }

      function apikey_dump(){

        $my_array = array();

        $sql="select * from apikey;";

        $ret = $this->query($sql);
        while( $row = $ret->fetchArray(SQLITE3_BOTH) ){
          array_push($my_array, $row[0]);
        }

        //print_r($my_array);
        return($my_array);
      }


   } // end MyDb


?>
