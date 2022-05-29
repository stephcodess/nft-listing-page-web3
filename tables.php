<?php

include "./database.php";
global $conn;

$query="CREATE TABLE IF NOT EXISTS accounts(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  email VARCHAR(200),
  metamask VARCHAR(100),
  tera_wallet VARCHAR(100),
  phantom_wallet VARCHAR(100),
  verified TINYINT(2)
  )ENGINE myISAM";
  $result=mysqli_query($conn,$query);
  if ($result){
    $ans='successfully created table accounts';
    echo "<pre>".$ans ."</pre>";
  }else {
    $ans='An error occurred while creating table accounts';
    echo "<pre>".$ans ."</pre>";
  }

  ?>