<?php

include_once '../../../conf.php';
date_default_timezone_set('Europe/Paris');
header('Content-Type: application/json');

$candidId = $_POST['candidId'];

function getLastCandid($bdd, $candidId)
{
    $stmt = $bdd->prepare('SELECT uuid, position_candidature, etat_candidature FROM etapes WHERE candid_uuid = ? ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$candidId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row;
    } else {
        return null;
    }
}

$lastCandid = getLastCandid($bdd, $candidId);
$lastPositionCandid = $lastCandid["position_candidature"];
$lastEtatCandid = $lastCandid["etat_candidature"];
$lastCandidUuid = $lastCandid["uuid"];

function mapPositionCandid($positionCandid)
{
    switch ($positionCandid) {
        case 'test_sportif':
            return 1;
        case 'integration':
            return 2;
        case 'refuse':
            return 3;
        case 'accepte':
            return 4;
        default:
            return 1; 
    }
}
$positionCandid = mapPositionCandid($_POST['selectEtapes']);

if ($lastPositionCandid !== null) {
    switch ($lastPositionCandid) {
        case 0:
            if ($positionCandid === 1) {
                $etatCandid = 1;
            } else if ($positionCandid === 3) {
                $etatCandid = 0;
            } else {
                echo json_encode(array('erreur' => 'Position candidature non valide 0'));
                exit;
            }
            break;
        case 1:
            if ($positionCandid === 2) {
                $etatCandid = 1;
            } else if ($positionCandid === 3) {
                $etatCandid = 0;
            } else {
                echo json_encode(array('erreur' => 'Position candidature non valide 1'));
                exit;
            }
            break;
        case 2:
            if ($positionCandid === 4) {
                $etatCandid = 2;
            } else if ($positionCandid === 3) {
                $etatCandid = 0;
            } else {
                echo json_encode(array('erreur' => 'Position candidature non valide 2 '));
                exit;
            }
            break;
        default:
            echo json_encode(array('erreur' => 'Position candidature non valide xxx'));
            exit;
    }
} else {
    echo json_encode(array('erreur' => 'Aucune position candidature précédente trouvée.'));
    exit;
}



if ($lastPositionCandid !== null && $lastPositionCandid == 3) {
    echo json_encode(array('erreur' => 'La dernière position candidature est égale à 3. Aucune insertion autorisée.'));
} else {
    $stmt = $bdd->prepare('INSERT INTO etapes (uuid, candid_uuid, etat_candidature, position_candidature) VALUES (gen_random_uuid(),?,?,?);');
    if ($stmt->execute(array($candidId, $etatCandid, $positionCandid))) {
        if (($positionCandid === 1 || $positionCandid === 2 || $positionCandid === 4) && $lastEtatCandid === 1) {
            $stmt = $bdd->prepare('UPDATE etapes SET etat_candidature = ? WHERE uuid = ? AND position_candidature = ?');
            $stmt->execute(array(2, $lastCandidUuid, $lastPositionCandid));
        } elseif ($positionCandid === 3 && $lastEtatCandid === 1) {
            $stmt = $bdd->prepare('UPDATE etapes SET etat_candidature = ? WHERE uuid = ? AND position_candidature = ?');
            $stmt->execute(array(0, $lastCandidUuid, $lastPositionCandid));
        }
        echo json_encode(array('success' => 'success'));
    } else {
        echo json_encode(array('erreur' => 'error'));
    }
}
