<?php

include_once '../../conf.php';

date_default_timezone_set('Europe/Paris');

$nom = $_POST['nom'];
$date_naissance = $_POST['naissance'];
$lieu_naissance = $_POST['lieunaissance'];
$telephone = $_POST['telephone'];
$profil = $_POST['profil'];
$genre = $_POST['genre'];

$exp_pro = $_POST['experience'];
$motivations = $_POST['motivations'];
$carriere = $_POST['carriere'];
$disponibilites = $_POST['disponibilites'];

$id_discord = $_POST['id_discord'];

if ($_POST['paragraphe'] != null) {
    $paragraphe_libre = $_POST['paragraphe'];
} else {
    $paragraphe_libre = "N/A";
}

$ip = $_SERVER['REMOTE_ADDR'];

$dateToday = new DateTime();

$ipsearch = $bdd->prepare('SELECT ip, created_at FROM recrutement WHERE ip = :ip');
$ipsearch->execute(array(':ip' => $ip));
$ipconnue = $ipsearch->fetch(PDO::FETCH_ASSOC);
if ($ipconnue != false) {
    $ipconnuecreate = date("Y-m-d", strtotime($ipconnue['created_at']));
}
if ($ipconnue['ip'] === $ip && $dateToday->format('Y-m-d') === $ipconnuecreate) {
    echo json_encode(array('erreur1' => 'error1'));
} else {
    try {
        if (!$nom || !$date_naissance || !$lieu_naissance || !$telephone || !$profil || !$genre || !$exp_pro || !$motivations || !$carriere || !$disponibilites || !$id_discord) {
            if (!$nom) {
                echo "La variable $nom est vide.";
            }

            if (!$date_naissance) {
                echo "La variable $date_naissance est vide.";
            }

            if (!$lieu_naissance) {
                echo "La variable $lieu_naissance est vide.";
            }

            if (!$telephone) {
                echo "La variable $telephone est vide.";
            }

            if (!$profil) {
                echo "La variable $profil est vide.";
            }

            if (!$genre) {
                echo "La variable $genre est vide.";
            }

            if (!$exp_pro) {
                echo "La variable $exp_pro est vide.";
            }

            if (!$motivations) {
                echo "La variable $motivations est vide.";
            }

            if (!$carriere) {
                echo "La variable $carriere est vide.";
            }

            if (!$disponibilites) {
                echo "La variable $disponibilites est vide.";
            }

            if (!$id_discord) {
                echo "La variable $id_discord est vide.";
            }
            echo json_encode(array('erreur2' => 'error2'));
        } else {

            $stmt = $bdd->prepare('INSERT INTO recrutement (uuid, nom, date_naissance, lieu_naissance, telephone, profil, genre, exp_pro, motivations, carriere, disponibilites, id_discord, paragraphe_libre, ip) VALUES (uuid_generate_v4(),?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING uuid;');

            if ($stmt->execute(array($nom, $date_naissance, $lieu_naissance, $telephone, $profil, $genre, $exp_pro, $motivations, $carriere, $disponibilites, $id_discord, $paragraphe_libre, $ip))) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $idCandid = $row['uuid'];

                $etapes = $bdd->prepare('INSERT INTO etapes (uuid, candid_uuid, etat_candidature, position_candidature) VALUES (uuid_generate_v4(),?,?,?);');
                $etapes->execute(array($idCandid, 2, 0));

                $dateDISCORD = new DateTime();
                $date = $dateDISCORD->format('Y-m-d H:i:s');
                $url = $webhookURL;

                $thumbnailImg = $carriere === "usss"
                    ? "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Logo_of_the_United_States_Secret_Service.svg/1200px-Logo_of_the_United_States_Secret_Service.svg.png"
                    : ($carriere === "usms"
                        ? "https://join-hls.us/assets/img/usms.png"
                        : "https://join-hls.us/assets/img/logoFib.png");

                $hookObject = json_encode([
                    "content" => "**Nouveau CV de " . $nom . "**",
                    "username" => "Recruitor of Homeland Security",
                    "avatar_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg/247px-Seal_of_the_United_States_Department_of_Homeland_Security.svg.png",
                    "tts" => false,
                    "embeds" => [
                        [
                            "type" => "rich",
                            "description" => "**Nom :** " . $nom . "\n
                            **Discord :** <@!" . $id_discord . "> \n
                            **Date :** " . $date,
                            "color" => hexdec("e68e13"),
                            "thumbnail" => [
                                "url" => $thumbnailImg
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $POST = ['username' => 'Testing BOT', 'content' => 'Testing message'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $hookObject);
                $response   = curl_exec($ch);
                echo json_encode(array('idCandid' => $idCandid));
            } else {
                echo json_encode(array('erreur3' => 'error3'));
            }
        }
    } catch (PDOException $e) {
        echo 'Erreur PDO : ' . $e->getMessage();
    }
}
