<?php

$username = "DB_HOSTNAME"; 
$password = "DB_PASS"; 
$hostname = "DB_HOST"; 
$namebase = "DB_NAME"; 


try
 {
  $bdd = new PDO('mysql:host='.$hostname.';dbname='.$namebase.'', $username, $password);
 }
  catch (Exception $e)
 {
  die('Erreur : ' . $e->getMessage());
 }