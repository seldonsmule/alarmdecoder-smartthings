<?php

require_once('db.php');

class ApiKey {

  var $db;
  var $b_created;

  function __construct($db=null){

    if($db == null){
      $this->db = new MyDB();
      $this->b_created = true;
    }else{
      $this->db = $db;
      $this->b_created = false;
    }

  }

  function __destruct (){
    $this->db->close();
  }


  function add($newkey){
    // make sure key is not already there
    if($this->db->apikey_find($newkey) == null){
      return ($this->db->apikey_add($newkey));
    }
 
    return false;
  }

  function find($searchkey){
    if($this->db->apikey_find($searchkey) == null){
      return false;
    }
    return true;
  }

  function delete($delkey){
    return ($this->db->apikey_delete($delkey));
  }

  function dump(){
    return($this->db->apikey_dump());
  }

} // end ApiKey:

?>
