#!/usr/bin/php

<?php

  $output = shell_exec("sysctl -n kern.boottime");

  echo $output;

  echo time();


?>
