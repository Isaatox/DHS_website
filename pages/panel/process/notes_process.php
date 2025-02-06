<?php

include_once '../../../conf.php';
date_default_timezone_set('Europe/Paris');

$userDiscord = $_POST['userDiscord'];
$candidId = $_POST['candidId'];
$notes = $_POST['notes'];

$dateToday = new DateTime();

$stmt = $bdd->prepare('INSERT INTO notes (uuid, user_discord, candid_id, notes) VALUES (gen_random_uuid(),?,?,?);');
if($stmt->execute(array($userDiscord, $candidId, $notes))){
    echo json_encode(array('success' => 'success'));
} else {
    echo json_encode(array('erreur' => 'error'));
}
