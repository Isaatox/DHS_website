<?php

$username = "DB_HOSTNAME"; 
$port = "DB_PORT";
$password = "DB_PASS"; 
$hostname = "DB_HOST"; 
$namebase = "DB_NAME"; 
$webhookURL = "https://example.com/webhook";

try
 {
    $bdd = new PDO("pgsql:host=$hostname;port=$port;dbname=$namebase;user=$username;password=$password");
 }
  catch (Exception $e)
 {
  die('Erreur : ' . $e->getMessage());
 }