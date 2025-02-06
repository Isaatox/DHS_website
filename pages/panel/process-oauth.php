<?php
// Désactiver les erreurs pour la production
error_reporting(0);

if (!isset($_GET['code'])) {
    header('Location: ./error');
    exit();
}

// Configuration des paramètres de l'API Discord
$clientID = '1161011120925057065';
$clientSecret = '1OeRTAfcMhZy6TxONPLH0vHpvDMqbHwR';
$redirectURI = 'https://join-hls.us/pages/panel/process-oauth.php';
$scope = 'identify guilds guilds.members.read guilds.join';

// Échanger le code Discord contre un jeton d'accès
$tokenURL = 'https://discord.com/api/oauth2/token';
$payload = [
    'code' => $_GET['code'],
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
    'grant_type' => 'authorization_code',
    'redirect_uri' => $redirectURI,
    'scope' => $scope,
];


$ch = curl_init($tokenURL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

// Vérifier si l'échange du code a réussi
if (!isset($response['access_token'])) {
    header('Location: ./error');
    exit();
}

// Récupérer les informations de l'utilisateur depuis l'API Discord
$discord_users_url = "https://discordapp.com/api/users/@me";
$ch = curl_init();
$headers = ['Authorization: Bearer ' . $response['access_token']];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $discord_users_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result   = curl_exec($ch);
$user_info  = json_decode($result, true);

$discord_guilds_academy_url = "https://discordapp.com/api//users/@me/guilds/829635065293832192/member";

curl_setopt($ch, CURLOPT_URL, $discord_guilds_academy_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
$guilds_academy_info = json_decode($result, true);

// Vérifier les rôles de l'utilisateur
if (isset($guilds_academy_info['roles']) && is_array($guilds_academy_info['roles'])) {
    if (!in_array("840669406635229184", $guilds_academy_info['roles']) && !in_array("848188795890434069", $guilds_academy_info['roles']) && !in_array("829635131874344961", $guilds_academy_info['roles']) && !in_array("834893593788547113", $guilds_academy_info['roles'])) {
        header("location: ./error");
        exit();
    }
}

// Démarrer la session et définir la durée de vie du cookie à 7 jours
ini_set('session.gc_maxlifetime', 7 * 24 * 60 * 60);
session_set_cookie_params(7 * 24 * 60 * 60);
session_start();

// Stocker les informations de l'utilisateur dans la session
$_SESSION['logged_in'] = true;
$_SESSION['userData'] = [
    'name' => $user_info['username'],
    'discord_id' => $user_info['id'],
    'avatar' => $user_info['avatar'],
    'discord_server' => '1069338268556087506',
    'roles' => $guilds_academy_info['roles'],
];

// Rediriger vers le tableau de bord
header('Location: ./dashboard');
exit();
