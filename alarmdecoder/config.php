<?php

  $g_working_dir = "/Applications/MAMP/htdocs/alarmdecoder";
  $g_admin_mode = false; // if true - stop accepting web request

  class EgcConfig{

    var $working_dir;
    var $base_database_name = "egcalarm.db";
    var $database_name;
    var $database_dir;
    var $log_dir;

    var $admin_mode;  // if true - don't accept web requet

    function __construct(){
      $this->working_dir = $GLOBALS['g_working_dir'];
      $this->database_dir = sprintf("%s/db", $this->working_dir);
      $this->log_dir = sprintf("%s/logs", $this->working_dir);

      $this->database_name = sprintf("%s/%s", $this->database_dir, 
                                              $this->base_database_name);

      $this->admin_mode = false;

      if(!file_exists($this->database_dir)){
        mkdir($this->database_dir);
      }

      if(!file_exists($this->log_dir)){
        mkdir($this->log_dir);
      }
    }

  }


?>
