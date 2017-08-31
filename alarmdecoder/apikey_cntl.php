#!/usr/bin/php

<?php

require_once('apikey.php');


// main 

  $key = new ApiKey();

  if(count($argv) != 3){
    help();
    exit(1);
  }

  $cmd = $argv[1];
  $keyvalue = $argv[2];

  switch($cmd){

    case "add":
      if(!$key->add($keyvalue)){
        printf("ADD failed - (%s) already in DB\n", $keyvalue);
      }else{
        printf("Add success\n");
      }
    break;

    case "find":
      if($key->find($keyvalue)){
        printf("apikey(%s) found\n", $keyvalue);
      }else{
        printf("apikey(%s) not found\n", $keyvalue);
      }
    break;

    case "del":

      if($keyvalue == "all"){
        print("Delete all\n");
        $dump_array=$key->dump();
        while( ($item = array_pop($dump_array))){
          printf("delete key: %s\n", $item);
          $key->delete($item);
        }
      }else{
        if($key->delete($keyvalue)){
          printf("apikey(%s) deleted\n", $keyvalue);
        }else{
          printf("apikey(%s) delete FAILED\n", $keyvalue);
        }
      }
    break;

    case "test":
      simple_test();
    break;

    case "dump":
      $dump_array=$key->dump();
      print_r($dump_array);
    break;

    default:
      printf("Unknown cmd: %s\n", $cmd);
      help();
    exit(1);

  }

  exit(0);

function help(){
  printf("Usage apikey.php test|add|find|del|dump key\n\n");

  printf("test - Runs a simple test of find, add and del\n");
  printf("add -  Adds a key if unique\n");
  printf("find - Searches and confirms a key\n");
  printf("del -  Deletes a key\n");
  printf("dump - Dumps a list of existing keys\n");
  printf("help - Print this list\n");
}

function simple_test(){

  $key = new ApiKey();

  $keyvalue="123456xx343";

  if($key->find($keyvalue)){
    printf("FIND Test failed - key found(%s)\n", $keyvalue);
  }else{
    printf("FIND Test passed\n");
  }

  if(!$key->add($keyvalue)){
    printf("ADD Test failed - (%s) already in DB\n", $keyvalue);
  }else{
    printf("Add Test passed\n");
  }

  if(!$key->delete($keyvalue)){
    printf("DEL Test failed - (%s)\n", $keyvalue);
  }else{
    printf("Del Test passed\n");
  }
} // end simple test


?>
