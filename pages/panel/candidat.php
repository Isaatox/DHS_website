<?php
include_once '../../conf.php';
date_default_timezone_set('Europe/Paris');

session_start();

// Vérification de la session
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ./error');
    exit();
}

// Extraction des données de la session
extract($_SESSION['userData']);

// URL de l'avatar Discord
$avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";

// Tableau des rôles prioritaires
$priorityRoles = ["1077734410977300612", "840669406635229184", "1039222038231003136", "1100462168245276835", "1100462247572156446", "1105918989143654430", "1116517000806682624", "1116517325441605672", "1116516462388072469", "1116524077218287647"];

// Recherche du rôle prioritaire
$roleFort = null;
if ($roles && is_array($roles)) {
    $intersection = array_intersect($priorityRoles, $roles);
    if (!empty($intersection)) {
        $roleFort = reset($intersection);
    }
}

// Initialisation des variables de rôle et de logoService
$role = "<b>🔍 | HLS - Recruteur</b>";
$logoService = "https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg";
$carriere = null;

// Mappage des rôles prioritaires à des valeurs spécifiques
$rolesMapping = [
    "829635131874344961" => ["🔍 | HLS - Recruteur", "https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg"],
    "1116524077218287647" => ["💰 | Recruteur IRS", "https://upload.wikimedia.org/wikipedia/commons/d/d4/Seal_of_the_United_States_Internal_Revenue_Service.svg"],
    "1116516462388072469" => ["🔍 | Recruteur FIB", "https://img2.wikia.nocookie.net/__cb20140815050523/gtawiki/images/3/3c/FIB_logoC.png"],
    "1116517325441605672" => ["⭐️ | Recruteur USSS", "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Logo_of_the_United_States_Secret_Service.svg/1200px-Logo_of_the_United_States_Secret_Service.svg.png"],
    "1116517000806682624" => ["🗽 | Recruteur USMS", "https://media.discordapp.net/attachments/1054099325287940266/1151615004873871411/1200px-Seal_of_the_United_States_Marshals_Service.png"],
    "848188795890434069" => ["👥 | HLS - Supervisor", "https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg"],
    "1105918989143654430" => ["👥 | HLS - Direction Recruiting IRS", "https://upload.wikimedia.org/wikipedia/commons/d/d4/Seal_of_the_United_States_Internal_Revenue_Service.svg"],
    "1100462247572156446" => ["👥 | HLS - Direction Recruiting FIB", "https://img2.wikia.nocookie.net/__cb20140815050523/gtawiki/images/3/3c/FIB_logoC.png"],
    "1100462168245276835" => ["👥 | HLS - Direction Recruiting USMS", "https://media.discordapp.net/attachments/1054099325287940266/1151615004873871411/1200px-Seal_of_the_United_States_Marshals_Service.png"],
    "1039222038231003136" => ["👥 | HLS - Direction Recruiting USSS", "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Logo_of_the_United_States_Secret_Service.svg/1200px-Logo_of_the_United_States_Secret_Service.svg.png"],
    "840669406635229184" => ["👥 | HLS - Chief Office", "https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg"],
    "1077734410977300612" => ["⭐️ | Développeur", "https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg"],
];

// Vérification du rôle prioritaire et assignation des valeurs correspondantes
if (isset($rolesMapping[$roleFort])) {
    list($role, $logoService) = $rolesMapping[$roleFort];
}

function couleur_etapes($etat)
{
    switch ($etat) {
        case 0:
            echo 'bg-red-500';
            break;
        case 1:
            echo 'bg-yellow-500';
            break;
        case 2:
            echo 'bg-green-500';
            break;
        default:
            echo 'bg-gray-500';
            break;
    }
}

function svg_etapes($etat)
{
    switch ($etat) {
        case 0:
            echo '<svg fill="#FFFFFF" width="24px" height="24px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                <path d="M1599.04 1523.627 396.373 320.96C546.88 188.053 743.787 106.667 960 106.667c470.507 0 853.333 382.826 853.333 853.333 0 216.107-81.386 413.12-214.293 563.627M106.667 960c0-216.213 81.28-413.12 214.293-563.627L1523.627 1599.04c-150.507 132.907-347.52 214.293-563.627 214.293-470.507 0-853.333-382.826-853.333-853.333M960 0C429.76 0 0 429.76 0 960s429.76 960 960 960c530.133 0 960-429.76 960-960S1490.133 0 960 0" fill-rule="evenodd" />
            </svg>';
            break;
        case 1:
            echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin-top: -13px; margin-left: -13px; background: none; display: block; shape-rendering: auto;" width="50px" height="50px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <g transform="rotate(0 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.8928571428571428s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(25.714285714285715 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.8241758241758241s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(51.42857142857143 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.7554945054945055s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(77.14285714285714 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.6868131868131868s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(102.85714285714286 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.6181318681318682s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(128.57142857142858 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.5494505494505494s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(154.28571428571428 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.4807692307692307s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(180 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.41208791208791207s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(205.71428571428572 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.3434065934065934s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(231.42857142857142 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.2747252747252747s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(257.14285714285717 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.20604395604395603s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(282.85714285714283 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.13736263736263735s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(308.57142857142856 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="-0.06868131868131867s" repeatCount="indefinite"></animate>
              </rect>
            </g><g transform="rotate(334.2857142857143 50 50)">
              <rect x="48.5" y="33.5" rx="1.5" ry="2.5" width="3" height="5" fill="#ffffff">
                <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="0.9615384615384615s" begin="0s" repeatCount="indefinite"></animate>
              </rect>
            </g>
            <!-- [ldio] generated by https://loading.io/ --></svg>';
            break;
        case 2:
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="24px" height="24px" fill="#FFFFFF" class="mt-px">
                <path d="M 41.9375 8.625 C 41.273438 8.648438 40.664063 9 40.3125 9.5625 L 21.5 38.34375 L 9.3125 27.8125 C 8.789063 27.269531 8.003906 27.066406 7.28125 27.292969 C 6.5625 27.515625 6.027344 28.125 5.902344 28.867188 C 5.777344 29.613281 6.078125 30.363281 6.6875 30.8125 L 20.625 42.875 C 21.0625 43.246094 21.640625 43.410156 22.207031 43.328125 C 22.777344 43.242188 23.28125 42.917969 23.59375 42.4375 L 43.6875 11.75 C 44.117188 11.121094 44.152344 10.308594 43.78125 9.644531 C 43.410156 8.984375 42.695313 8.589844 41.9375 8.625 Z" />
            </svg>';
            break;
            // Ajoutez d'autres cas selon vos besoins
        default:
            // Par défaut, vous pouvez afficher un SVG générique
            echo '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/></svg>';
            break;
    }
}

if (isset($_GET['id'])) {
    $candidatId = urldecode($_GET['id']);
};

$candid = $bdd->prepare('SELECT * FROM recrutement WHERE uuid = :uuid');
$candid->execute(array(':uuid' => $candidatId));
$candid = $candid->fetch(PDO::FETCH_ASSOC);

$notesCandids = $bdd->prepare('SELECT * FROM notes WHERE candid_id = :candid_id');
$notesCandids->execute(array(':candid_id' => $candidatId));
$notesCandids = $notesCandids->fetchAll(PDO::FETCH_ASSOC);

$etapes = $bdd->prepare('SELECT * FROM etapes WHERE candid_uuid = :candid_uuid ORDER BY position_candidature ASC ');
$etapes->execute(array(':candid_uuid' => $candidatId));
$etapes = $etapes->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:title" content="Under Pressure" />
    <meta property="og:type" content="music.song" />
    <meta property="og:url" content="http://open.spotify.com/track/2aSFLiDPreOVP6KHiWk4lF" />
    <meta property="og:image" content="http://o.scdn.co/image/e4c7b06c20c17156e46bbe9a71eb0703281cf345" />
    <meta property="og:site_name" content="Spotify" />
    <meta property="fb:app_id" content="174829003346" />
    <meta property="music:musician" content="http://open.spotify.com/artist/1dfeR4HaWDbWqFHLkxsg1d">
    <meta property="music:musician" content="http://open.spotify.com/artist/0oSGxfWSnnOXhD2fKuz2Gy">
    <meta property="music:album" content="http://open.spotify.com/album/7rq68qYz66mNdPfidhIEFa">
    <meta property="music:album:track" content="2">
    <meta property="music:duration" content="236">

    <title>Voir le cv</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="../../assets/css/output.css" rel="stylesheet">
    <script src="../../assets/js/candidat.js"></script>

</head>

<style>
    .chat-bubble {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #007bff;
        color: #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Styles pour le conteneur du carnet de pense-bête (masqué par défaut) */
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 80px;
        /* Ajustez cet espace pour éviter de cacher la bulle de chat */
        width: 300px;
        height: 400px;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        display: none;
        /* Masquez par défaut */
    }

    .d-block {
        display: block !important;
    }
</style>

<body class="bg-discord-gray font-sans">
    <nav class="flex justify-between mr-3 mt-3 ml-3">
        <div>
            <img class="rounded-full w-12 h-12 mr-3" src="<?php echo $avatar_url ?>" />
            <span class="text-1xl text-white font-semibold"><?php echo $name ?></span>
        </div>
        <div>
            <a href="./dashboard">
                <img src="<?php echo $logoService ?>" class="w-40 h-40" alt="">
            </a>
        </div>
        <div>
            <a href="./logout" class="mt-5 text-gray-300">Déconnexion</a>
        </div>
    </nav>
    <div class="max-w-screen-xl mx-auto p-4">
        <div class="flex items-center justify-center flex-col mb-5">
            <div class="text-white text-2xl mt-3"><b><?php echo $name ?></b> tu est sur l'espace candidature de : <span class="font-bold"><?php echo $candid['nom'] ?></span></div>
        </div>
        <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="flex">
                <div class="w-1/2">
                    <button id="infoPerso" class="w-full py-2 bg-blue-500 text-white hover:bg-blue-600 font-semibold">Informations personnelles</button>
                </div>
                <div class="w-1/2">
                    <button id="expPro" class="w-full py-2 bg-gray-200 hover:bg-gray-300">Expérience professionnelle</button>
                </div>
            </div>
            <div class="p-4">
                <!-- Informations personnelles -->
                <div id="contenuInfoPerso">
                    <div class="bg-gray-700 rounded-lg shadow-md p-6 mb-4">
                        <div class="flex items-center mb-4">
                            <div class="w-1/4 flex justify-center">
                                <img src="<?php echo $candid['profil'] ?>" alt="Photo de profil" class="h-52 w-40 rounded-full border-4 border-orange-500">
                            </div>
                            <div class="w-1/4 ml-4">
                                <h1 class="text-3xl text-gray-200 font-semibold"><?php echo $candid['nom'] ?></h1>
                                <div class="flex items-center text-gray-300 mt-2">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600">
                                        <svg viewBox="0 0 1024 1024" class="icon h-6 w-6 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M883.2 358.4c0-14.08-11.52-25.6-25.6-25.6H166.4c-14.08 0-25.6 11.52-25.6 25.6v153.6h742.4V358.4z" fill="#FDE8C2" />
                                            <path d="M883.2 524.8H140.8c-7.68 0-12.8-5.12-12.8-12.8V358.4c0-21.76 16.64-38.4 38.4-38.4h691.2c21.76 0 38.4 16.64 38.4 38.4v153.6c0 7.68-5.12 12.8-12.8 12.8z m-729.6-25.6h716.8V358.4c0-7.68-5.12-12.8-12.8-12.8H166.4c-7.68 0-12.8 5.12-12.8 12.8v140.8z" fill="#231C1C" />
                                            <path d="M140.8 870.4c0 14.08 11.52 25.6 25.6 25.6h691.2c14.08 0 25.6-11.52 25.6-25.6V704H140.8v166.4z" fill="#6FB0BE" />
                                            <path d="M857.6 908.8H166.4c-21.76 0-38.4-16.64-38.4-38.4V704c0-7.68 5.12-12.8 12.8-12.8h742.4c7.68 0 12.8 5.12 12.8 12.8v166.4c0 21.76-16.64 38.4-38.4 38.4zM153.6 716.8v153.6c0 7.68 5.12 12.8 12.8 12.8h691.2c7.68 0 12.8-5.12 12.8-12.8V716.8H153.6z" fill="#231C1C" />
                                            <path d="M140.8 512h742.4v192H140.8z" fill="#E1E0A6" />
                                            <path d="M883.2 716.8H140.8c-7.68 0-12.8-5.12-12.8-12.8V512c0-7.68 5.12-12.8 12.8-12.8h742.4c7.68 0 12.8 5.12 12.8 12.8v192c0 7.68-5.12 12.8-12.8 12.8z m-729.6-25.6h716.8V524.8H153.6v166.4z" fill="#231C1C" />
                                            <path d="M883.2 345.6H140.8v166.4h89.6v51.2c0 14.08 11.52 25.6 25.6 25.6h12.8c14.08 0 25.6-11.52 25.6-25.6v-51.2h115.2v192c0 14.08 11.52 25.6 25.6 25.6h12.8c14.08 0 25.6-11.52 25.6-25.6V512h89.6v89.6c0 14.08 11.52 25.6 25.6 25.6h12.8c14.08 0 25.6-11.52 25.6-25.6v-89.6h115.2v140.8c0 14.08 11.52 25.6 25.6 25.6h12.8c14.08 0 25.6-11.52 25.6-25.6V512h76.8V345.6z" fill="#F29B54" />
                                            <path d="M448 742.4h-12.8c-21.76 0-38.4-16.64-38.4-38.4V524.8h-89.6v38.4c0 21.76-16.64 38.4-38.4 38.4h-12.8c-21.76 0-38.4-16.64-38.4-38.4v-38.4h-76.8c-7.68 0-12.8-5.12-12.8-12.8V345.6c0-7.68 5.12-12.8 12.8-12.8h742.4c7.68 0 12.8 5.12 12.8 12.8v166.4c0 7.68-5.12 12.8-12.8 12.8h-64v128c0 21.76-16.64 38.4-38.4 38.4h-12.8c-21.76 0-38.4-16.64-38.4-38.4V524.8h-89.6v76.8c0 21.76-16.64 38.4-38.4 38.4h-12.8c-21.76 0-38.4-16.64-38.4-38.4v-76.8h-64v179.2c0 21.76-16.64 38.4-38.4 38.4zM294.4 499.2h115.2c7.68 0 12.8 5.12 12.8 12.8v192c0 7.68 5.12 12.8 12.8 12.8h12.8c7.68 0 12.8-5.12 12.8-12.8V512c0-7.68 5.12-12.8 12.8-12.8h89.6c7.68 0 12.8 5.12 12.8 12.8v89.6c0 7.68 5.12 12.8 12.8 12.8h12.8c7.68 0 12.8-5.12 12.8-12.8v-89.6c0-7.68 5.12-12.8 12.8-12.8h115.2c7.68 0 12.8 5.12 12.8 12.8v140.8c0 7.68 5.12 12.8 12.8 12.8h12.8c7.68 0 12.8-5.12 12.8-12.8V512c0-7.68 5.12-12.8 12.8-12.8h64V358.4H153.6v140.8h76.8c7.68 0 12.8 5.12 12.8 12.8v51.2c0 7.68 5.12 12.8 12.8 12.8h12.8c7.68 0 12.8-5.12 12.8-12.8v-51.2c0-7.68 5.12-12.8 12.8-12.8z" fill="#231C1C" />
                                            <path d="M307.2 243.2h25.6v102.4h-25.6z" fill="#231C1C" />
                                            <path d="M358.4 204.8c0 21.76-16.64 38.4-38.4 38.4s-38.4-16.64-38.4-38.4 16.64-76.8 38.4-76.8 38.4 55.04 38.4 76.8z" fill="#FAC546" />
                                            <path d="M320 256c-28.16 0-51.2-23.04-51.2-51.2 0-17.92 15.36-89.6 51.2-89.6s51.2 71.68 51.2 89.6c0 28.16-23.04 51.2-51.2 51.2z m0-115.2c-8.96 1.28-25.6 42.24-25.6 64 0 14.08 11.52 25.6 25.6 25.6s25.6-11.52 25.6-25.6c0-21.76-16.64-62.72-25.6-64zM499.2 243.2h25.6v102.4h-25.6z" fill="#231C1C" />
                                            <path d="M550.4 204.8c0 21.76-16.64 38.4-38.4 38.4s-38.4-16.64-38.4-38.4 16.64-76.8 38.4-76.8 38.4 55.04 38.4 76.8z" fill="#FAC546" />
                                            <path d="M512 256c-28.16 0-51.2-23.04-51.2-51.2 0-17.92 15.36-89.6 51.2-89.6s51.2 71.68 51.2 89.6c0 28.16-23.04 51.2-51.2 51.2z m0-115.2c-8.96 1.28-25.6 42.24-25.6 64 0 14.08 11.52 25.6 25.6 25.6s25.6-11.52 25.6-25.6c0-21.76-16.64-62.72-25.6-64z" fill="#231C1C" />
                                            <path d="M691.2 243.2h25.6v102.4h-25.6z" fill="#231C1C" />
                                            <path d="M742.4 204.8c0 21.76-16.64 38.4-38.4 38.4s-38.4-16.64-38.4-38.4 16.64-76.8 38.4-76.8 38.4 55.04 38.4 76.8z" fill="#FAC546" />
                                            <path d="M704 256c-28.16 0-51.2-23.04-51.2-51.2 0-17.92 15.36-89.6 51.2-89.6s51.2 71.68 51.2 89.6c0 28.16-23.04 51.2-51.2 51.2z m0-115.2c-8.96 1.28-25.6 42.24-25.6 64 0 14.08 11.52 25.6 25.6 25.6s25.6-11.52 25.6-25.6c0-21.76-16.64-62.72-25.6-64z" fill="#231C1C" />
                                        </svg>
                                        <?php echo date('d/m/Y', strtotime($candid['date_naissance'])); ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-300 mt-1">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ">
                                        <svg viewBox="0 0 1024 1024" class="icon h-6 w-6 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M512 512m-512 0a512 512 0 1 0 1024 0 512 512 0 1 0-1024 0Z" fill="#8DD9FF" />
                                            <path d="M112 412.94h44.954V504H112z" fill="#FFFFFF" />
                                            <path d="M910.666 445.5H966V520h-55.334z" fill="#B2B9C9" />
                                            <path d="M928 592h89.738A515.586 515.586 0 0 0 1024 512c0-8.048-0.238-16.042-0.606-24H928v104z" fill="#9FC3DD" />
                                            <path d="M0.198 506.204C0.17 508.146 0 510.05 0 512c0 27.22 2.17 53.928 6.262 80H98v-85.796H0.198z" fill="#B39191" />
                                            <path d="M144 592h96l-16-176-80 32v144" fill="#B2B9C9" />
                                            <path d="M617.5 432h93v160h-93z" fill="#7FAAB8" />
                                            <path d="M224 352h64v240h-64zM576 384h64v208h-64z" fill="#EEE1C2" />
                                            <path d="M432 344.004h64V592h-64z" fill="#9FC3DD" />
                                            <path d="M734 448h93v144H734z" fill="#B2B9C9" />
                                            <path d="M835 472H928v120h-93z" fill="#B39191" />
                                            <path d="M6.262 592C44.674 836.764 256.45 1024 512 1024s467.324-187.236 505.738-432H6.262z" fill="#43AB5F" />
                                            <path d="M945.754 784c36.036-57.354 61.05-122.334 71.984-192H6.262c10.934 69.666 35.95 134.646 71.984 192h867.508z" fill="#71BE63" />
                                            <path d="M31.23 688h961.54c11.266-30.74 19.762-62.816 24.968-96H6.262c5.208 33.184 13.702 65.26 24.968 96z" fill="#94D75B" />
                                            <path d="M12.498 624h999.006c2.374-10.56 4.54-21.198 6.234-32H6.262c1.696 10.802 3.862 21.44 6.236 32z" fill="#B0EB81" />
                                            <path d="M748.078 966.312L512 592 275.924 966.31C346.574 1003.106 426.828 1024 512 1024s165.428-20.892 236.078-57.688z" fill="#674447" />
                                            <path d="M572.546 688L512 592l-60.546 96h121.092z" fill="#8C665B" />
                                            <path d="M576 592V296l-80-48v344" fill="#FDEFDB" />
                                            <path d="M352 192v400h80V136zM688 260h64v332h-64z" fill="#FFFFFF" />
                                            <path d="M288 592v-80H192v80" fill="#A29B91" />
                                            <path d="M380 592v-192h-92v192" fill="#9FC3DD" />
                                            <path d="M640 592v-64l-96 16v48" fill="#88B7C6" />
                                            <path d="M800 432h64v160h-64z" fill="#FDEFDB" />
                                            <path d="M512 592l211.658 386.198a509.48 509.48 0 0 0 24.42-11.886L512 592zM512 592L275.924 966.31a508.52 508.52 0 0 0 24.418 11.888L512 592z" fill="#FFFFFF" />
                                            <path d="M556.364 1021.864L512 592l14.846 431.546c9.914-0.284 19.754-0.838 29.518-1.682zM512 592l-44.364 429.864c9.764 0.842 19.604 1.398 29.518 1.682L512 592z" fill="#E9B668" />
                                            <path d="M80 480h64v112H80z" fill="#9FC3DD" />
                                            <path d="M659 548.5H766V592h-107z" fill="#A29B91" />
                                            <path d="M432 437h35.636V592H432z" fill="#B39191" />
                                            <path d="M240 560h91.334v32H240z" fill="#9E8282" />
                                            <path d="M164.666 560h42.812v32H164.666zM449.938 560h35.396v32h-35.396zM752 560h57v32H752z" fill="#EEE1C2" />
                                            <path d="M572.546 568.792h44.954V592h-44.954zM787.046 540H832v52h-44.954zM905.524 552.666h93.81V592h-93.81zM57.524 540h44.954v52H57.524z" fill="#9E8282" />
                                            <path d="M360 540h33.962v52H360zM693.52 568.792h33.962V592H693.52zM46.038 568.792H80V592H46.038zM196.704 576h65.266v16H196.704z" fill="#B2B9C9" />
                                        </svg>
                                        <?php echo $candid['lieu_naissance'] ?>
                                    </span>

                                </div>
                                <div class="flex items-center text-gray-300 mt-1">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ">
                                        <svg viewBox="0 0 1024 1024" class="icon h-6 w-6 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M729.6 870.4c0 28.16-23.04 51.2-51.2 51.2H345.6c-28.16 0-51.2-23.04-51.2-51.2V179.2c0-28.16 23.04-51.2 51.2-51.2h332.8c28.16 0 51.2 23.04 51.2 51.2v691.2z" fill="#E1E0A6" />
                                            <path d="M678.4 934.4H345.6c-35.84 0-64-28.16-64-64V179.2c0-35.84 28.16-64 64-64h332.8c35.84 0 64 28.16 64 64v691.2c0 35.84-28.16 64-64 64zM345.6 140.8c-21.76 0-38.4 16.64-38.4 38.4v691.2c0 21.76 16.64 38.4 38.4 38.4h332.8c21.76 0 38.4-16.64 38.4-38.4V179.2c0-21.76-16.64-38.4-38.4-38.4H345.6z" fill="#231C1C" />
                                            <path d="M691.2 744.96c0 12.8-11.52 23.04-25.6 23.04H358.4c-14.08 0-25.6-10.24-25.6-23.04V253.44c0-12.8 11.52-23.04 25.6-23.04h307.2c14.08 0 25.6 10.24 25.6 23.04v491.52z" fill="#F2E5CA" />
                                            <path d="M665.6 780.8H358.4c-21.76 0-38.4-16.64-38.4-35.84V253.44c0-20.48 16.64-35.84 38.4-35.84h307.2c21.76 0 38.4 16.64 38.4 35.84v491.52c0 19.2-16.64 35.84-38.4 35.84zM358.4 243.2c-7.68 0-12.8 5.12-12.8 10.24v491.52c0 5.12 5.12 10.24 12.8 10.24h307.2c7.68 0 12.8-5.12 12.8-10.24V253.44c0-5.12-5.12-10.24-12.8-10.24H358.4z" fill="#231C1C" />
                                            <path d="M512 844.8m-38.4 0a38.4 38.4 0 1 0 76.8 0 38.4 38.4 0 1 0-76.8 0Z" fill="#D4594C" />
                                            <path d="M512 896c-28.16 0-51.2-23.04-51.2-51.2s23.04-51.2 51.2-51.2 51.2 23.04 51.2 51.2-23.04 51.2-51.2 51.2z m0-76.8c-14.08 0-25.6 11.52-25.6 25.6s11.52 25.6 25.6 25.6 25.6-11.52 25.6-25.6-11.52-25.6-25.6-25.6z" fill="#231C1C" />
                                            <path d="M460.8 166.4h102.4v25.6h-102.4z" fill="#231C1C" />
                                        </svg>
                                        <?php echo $candid['telephone'] ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-300 mt-1">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ">
                                        <svg viewBox="0 0 1024 1024" class="icon h-6 w-6 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M 452.152 669.355 c -61.0987 0 -122.083 -23.2107 -168.619 -69.7458 c -92.9565 -92.9565 -92.9565 -244.167 0 -337.123 c 92.9565 -92.9565 244.167 -92.9565 337.123 0 c 92.9565 92.9565 92.9565 244.167 0 337.123 c -46.3075 46.5351 -107.406 69.7458 -168.505 69.7458 Z m 0 -409.373 c -43.8045 0 -87.7227 16.7253 -121.059 50.0622 c -66.7875 66.7875 -66.7875 175.331 0 242.119 c 66.7875 66.7875 175.331 66.7875 242.119 0 c 66.7875 -66.7875 66.7875 -175.331 0 -242.119 c -33.3369 -33.4507 -77.1413 -50.0622 -121.059 -50.0622 Z M 270.563 818.517 L 64.739 612.693 c -14.2222 -14.2222 -14.2222 -37.4329 0 -51.6551 s 37.4329 -14.2222 51.6551 0 L 322.219 766.862 c 14.2222 14.2222 14.2222 37.4329 0 51.6551 c -14.1085 14.2222 -37.4329 14.2222 -51.6551 0 Z" fill="#5aa7f9" />
                                            <path d="M 13.653 869.603 c -14.1085 -14.1085 -14.1085 -37.3191 0 -51.5413 l 257.365 -257.365 c 14.1085 -14.1085 37.3191 -14.1085 51.5413 0 c 14.1085 14.1085 14.1085 37.3191 0 51.5413 L 65.195 869.603 c -14.1085 14.1085 -37.3191 14.1085 -51.5413 0 Z" fill="#5aa7f9" />
                                            <path d="M 1009.21 869.035 c -14.1085 14.1085 -37.3191 14.1085 -51.5413 0 L 700.416 611.669 c -14.1085 -14.1085 -14.1085 -37.3191 0 -51.5413 c 14.1085 -14.1085 37.3191 -14.1085 51.5413 0 L 1009.21 817.493 c 14.1085 14.2222 14.1085 37.3191 0 51.5413 Z" fill="#ff9dc6" />
                                            <path d="M 1019.79 623.957 v 219.477 c 0 20.0249 -16.384 36.4089 -36.4089 36.4089 s -36.4089 -16.384 -36.4089 -36.4089 V 623.957 c 0 -20.0249 16.384 -36.4089 36.4089 -36.4089 s 36.4089 16.384 36.4089 36.4089 Z" fill="#ff9dc6" />
                                            <path d="M 764.131 879.616 h 219.477 c 20.0249 0 36.4089 -16.384 36.4089 -36.4089 s -16.384 -36.4089 -36.4089 -36.4089 H 764.131 c -20.0249 0 -36.4089 16.384 -36.4089 36.4089 s 16.384 36.4089 36.4089 36.4089 Z" fill="#ff9dc6" />
                                            <path d="M 740.238 262.941 c -45.056 -45.056 -104.903 -69.8595 -168.619 -69.8595 c -63.7155 0 -123.563 24.8035 -168.619 69.8595 c -90.5671 90.5671 -92.8427 236.43 -6.94045 329.842 c 37.3191 12.8569 78.1653 12.5155 115.257 -1.13778 c -22.1867 -8.30578 -42.8942 -21.3902 -60.7573 -39.1395 c -66.7875 -66.7875 -66.7875 -175.331 0 -242.119 c 32.3129 -32.3129 75.3209 -50.176 121.059 -50.176 s 88.7467 17.8631 121.059 50.176 c 32.3129 32.3129 50.176 75.3209 50.176 121.059 s -17.8631 88.7467 -50.176 121.059 c -19.2285 19.2285 -41.984 32.8818 -66.1049 41.0738 c -1.93422 2.048 -3.86845 4.096 -5.80267 6.03022 c -31.4027 31.4027 -69.4045 52.224 -109.682 62.3502 c 19.7973 5.12 40.1635 7.73689 60.5298 7.73689 c 61.0987 0 122.083 -23.2107 168.619 -69.7458 c 45.056 -45.056 69.8595 -104.903 69.8595 -168.619 s -24.8035 -123.335 -69.8595 -168.391 Z M 590.848 601.544 h -0.227555 h 0.227555 Z M 550.344 601.315 h 0.341333 h -0.341333 Z M 594.489 601.088 h -0.341333 h 0.341333 Z M 598.13 600.519 h -0.227555 h 0.227555 Z" fill="#ff9dc6" />
                                        </svg>
                                        <?php echo $candid['genre'] ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-300 mt-1">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ">
                                        <svg viewBox="0 0 32 32" class="h-6 w-6 mr-2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M23.6361 9.33998C22.212 8.71399 20.6892 8.25903 19.0973 8C18.9018 8.33209 18.6734 8.77875 18.5159 9.13408C16.8236 8.89498 15.1469 8.89498 13.4857 9.13408C13.3283 8.77875 13.0946 8.33209 12.8974 8C11.3037 8.25903 9.77927 8.71565 8.35518 9.3433C5.48276 13.4213 4.70409 17.3981 5.09342 21.3184C6.99856 22.6551 8.84487 23.467 10.66 23.9983C11.1082 23.4189 11.5079 22.8029 11.8523 22.1536C11.1964 21.9195 10.5683 21.6306 9.9748 21.2951C10.1323 21.1856 10.2863 21.071 10.4351 20.9531C14.0551 22.5438 17.9881 22.5438 21.5649 20.9531C21.7154 21.071 21.8694 21.1856 22.0251 21.2951C21.4299 21.6322 20.8 21.9211 20.1442 22.1553C20.4885 22.8029 20.8865 23.4205 21.3364 24C23.1533 23.4687 25.0013 22.6567 26.9065 21.3184C27.3633 16.7738 26.1261 12.8335 23.6361 9.33998ZM12.3454 18.9075C11.2587 18.9075 10.3676 17.9543 10.3676 16.7937C10.3676 15.6331 11.2397 14.6783 12.3454 14.6783C13.4511 14.6783 14.3422 15.6314 14.3232 16.7937C14.325 17.9543 13.4511 18.9075 12.3454 18.9075ZM19.6545 18.9075C18.5678 18.9075 17.6767 17.9543 17.6767 16.7937C17.6767 15.6331 18.5488 14.6783 19.6545 14.6783C20.7602 14.6783 21.6514 15.6314 21.6323 16.7937C21.6323 17.9543 20.7602 18.9075 19.6545 18.9075Z" fill="#5865F2" />
                                        </svg>
                                        <?php echo $candid['id_discord'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="w-2/4 flex flex-col items-center space-y-4 relative">
                                <div class="container">
                                    <div class="flex flex-col md:grid grid-cols-12 text-gray-50">
                                        <?php foreach ($etapes as $etape) : ?>
                                            <?php if ($etape['position_candidature'] === 0) : ?>
                                                <!-- Première étape -->
                                                <div class="flex md:contents">
                                                    <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                        <div class="h-full w-6 flex items-center justify-center">
                                                            <div class="h-full w-1 <?php couleur_etapes($etape['etat_candidature']) ?> pointer-events-none"></div>
                                                        </div>
                                                        <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full <?php couleur_etapes($etape['etat_candidature']) ?> shadow text-center">
                                                            <?php svg_etapes($etape['etat_candidature']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="<?php couleur_etapes($etape['etat_candidature']) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                        <h3 class="font-semibold text-sm mb-1">Dépôt candidature</h3>
                                                        <p class="leading-tight text-sm text-justify w-full">
                                                            <?php echo date('d/m/Y à H\hi', strtotime($etape['created_at'])); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php elseif ($etape['position_candidature'] === 1) : ?>
                                                <!-- Deuxième étape -->
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <div class="flex md:contents">
                                                            <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                                <div class="h-full w-6 flex items-center justify-center">
                                                                    <div class="h-full w-1 <?php couleur_etapes($etape['etat_candidature']) ?> pointer-events-none"></div>
                                                                </div>
                                                                <div class="w-6 h-6  absolute top-1/2 -mt-3 rounded-full <?php couleur_etapes($etape['etat_candidature']) ?> shadow text-center">
                                                                    <?php svg_etapes($etape['etat_candidature']) ?>
                                                                </div>
                                                            </div>
                                                            <div class="<?php couleur_etapes($etape['etat_candidature']) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                                <h3 class="font-semibold text-sm mb-1">Test sportif</h3>
                                                                <p class="leading-tight text-sm text-justify w-full">
                                                                    <?php echo date('d/m/Y à H\hi', strtotime($etape['created_at'])); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif ($etape['position_candidature'] === 2) : ?>
                                                <!-- Deuxième étape -->
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <div class="flex md:contents">
                                                            <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                                <div class="h-full w-6 flex items-center justify-center">
                                                                    <div class="h-full w-1 <?php couleur_etapes($etape['etat_candidature']) ?> pointer-events-none"></div>
                                                                </div>
                                                                <div class="w-6 h-6 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full <?php couleur_etapes($etape['etat_candidature']) ?> shadow text-center">
                                                                    <?php svg_etapes($etape['etat_candidature']) ?>
                                                                </div>

                                                            </div>
                                                            <div class="<?php couleur_etapes($etape['etat_candidature']) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                                <h3 class="font-semibold text-sm mb-1">Intégration</h3>
                                                                <p class="leading-tight text-sm text-justify w-full">
                                                                    <?php echo date('d/m/Y à H\hi', strtotime($etape['created_at'])); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif ($etape['position_candidature'] === 3) : ?>
                                                <!-- Troisième étape -->
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <div class="flex md:contents">
                                                            <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                                <div class="h-full w-6 flex items-center justify-center">
                                                                    <div class="h-full w-1 <?php couleur_etapes($etape['etat_candidature']) ?> pointer-events-none"></div>
                                                                </div>
                                                                <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full <?php couleur_etapes($etape['etat_candidature']) ?> shadow text-center">
                                                                    <?php svg_etapes($etape['etat_candidature']) ?>
                                                                </div>
                                                            </div>
                                                            <div class="<?php couleur_etapes($etape['etat_candidature']) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                                <h3 class="font-semibold text-sm mb-1 text-gray-50">Refusé</h3>
                                                                <p class="leading-tight text-sm text-justify">
                                                                    <?php echo date('d/m/Y à H\hi', strtotime($etape['created_at'])); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif ($etape['position_candidature'] === 4) : ?>
                                                <!-- quatrième étape -->
                                                <div class="flex md:contents">
                                                    <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                        <div class="h-full w-6 flex items-center justify-center">
                                                            <div class="h-full w-1 <?php couleur_etapes($etape['etat_candidature']) ?> pointer-events-none"></div>
                                                        </div>
                                                        <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full <?php couleur_etapes($etape['etat_candidature']) ?> shadow text-center">
                                                            <?php svg_etapes($etape['etat_candidature']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="<?php couleur_etapes($etape['etat_candidature']) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                        <h3 class="font-semibold text-sm mb-1">Accepté</h3>
                                                        <p class="leading-tight text-sm text-justify w-full">
                                                            <?php echo date('d/m/Y à H\hi', strtotime($etape['created_at'])); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php $lastEtape = end($etapes); ?>
                                        <?php if ($lastEtape['position_candidature'] === 0 && $lastEtape['etat_candidature'] === 2) : ?>
                                            <!-- Si la dernière entrée a position 0 et etat 2, afficher le select correspondant -->
                                            <div class="flex md:contents">
                                                <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                    <div class="h-full w-6 flex items-center justify-center">
                                                        <div class="h-full w-1 bg-gray-500 pointer-events-none"></div>
                                                    </div>
                                                    <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full bg-gray-500 shadow text-center">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 12H20M12 4V20" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div id="select-container" class="<?php couleur_etapes(-1) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                    <form id="etapeForm" class="flex" method="post">
                                                        <select name="selectEtapes" id="select-option" class="appearance-none bg-white border border-gray-300 rounded-l-lg shadow-lg px-4 py-2 text-gray-800 w-full">
                                                            <option disabled selected>Choisir une étape</option>
                                                            <option value="test-sportif">Test Sportif</option>
                                                            <option value="refuse">Refusé</option>
                                                        </select>
                                                        <input type="hidden" name="candidId" value="<?php echo $candidatId ?>">
                                                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-r-lg hover:bg-blue-600 focus:outline-none">→</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php elseif ($lastEtape['position_candidature'] === 1) : ?>
                                            <!-- Si la dernière entrée a position 1, afficher le formulaire correspondant -->
                                            <div class="flex md:contents">
                                                <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                    <div class="h-full w-6 flex items-center justify-center">
                                                        <div class="h-full w-1 bg-gray-500 pointer-events-none"></div>
                                                    </div>
                                                    <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full bg-gray-500 shadow text-center">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 12H20M12 4V20" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div id="select-container" class="<?php couleur_etapes(-1) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                    <form id="etapeForm" class="flex" method="post">
                                                        <select name="selectEtapes" id="select-option" class="appearance-none bg-white border border-gray-300 rounded-l-lg shadow-lg px-4 py-2 text-gray-800 w-full">
                                                            <option disabled selected>Choisir une étape</option>
                                                            <option value="integration">Intégration</option>
                                                            <option value="refuse">Refusé</option>
                                                        </select>
                                                        <input type="hidden" name="candidId" value="<?php echo $candidatId ?>">
                                                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-r-lg hover:bg-blue-600 focus:outline-none">→</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php elseif ($lastEtape['position_candidature'] === 2) : ?>
                                            <!-- Si la dernière entrée a position 2, afficher le formulaire correspondant -->
                                            <div class="flex md:contents">
                                                <div class="col-start-2 col-end-4 mr-10 md:mx-auto relative">
                                                    <div class="h-full w-6 flex items-center justify-center">
                                                        <div class="h-full w-1 bg-gray-500 pointer-events-none"></div>
                                                    </div>
                                                    <div class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full bg-gray-500 shadow text-center">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 12H20M12 4V20" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div id="select-container" class="<?php couleur_etapes(-1) ?> col-start-4 col-end-12 p-3 rounded-xl my-2 mr-auto shadow-md w-full">
                                                    <form id="etapeForm" class="flex" method="post">
                                                        <select name="selectEtapes" id="select-option" class="appearance-none bg-white border border-gray-300 rounded-l-lg shadow-lg px-4 py-2 text-gray-800 w-full">
                                                            <option disabled selected>Choisir une étape</option>
                                                            <option value="accepte">Accepté</option>
                                                            <option value="refuse">Refusé</option>
                                                        </select>
                                                        <input type="hidden" name="candidId" value="<?php echo $candidatId ?>">
                                                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-r-lg hover:bg-blue-600 focus:outline-none">→</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-lg shadow-md p-6">
                        <h2 class="text-2xl text-gray-200 font-semibold">Paragraphes libres : </h2>
                        <p class="text-gray-300"><?php echo $candid['paragraphe_libre'] ?></p>
                    </div>
                </div>
                <div id="contenuExpPro" class="bg-gray-700 rounded-lg shadow-md p-6 mb-4" style="display: none;">
                    <h2 class="text-2xl font-semibold text-gray-200 mb-4">Expérience professionnelle</h2>
                    <div class="flex gap-6">
                        <div class="mb-6 w-3/4">
                            <div class="bg-gray-800 h-full rounded-lg shadow-md p-4 text-white">
                                <h3 class="text-xl font-semibold mb-2">Parcours</h3>
                                <p>
                                    <?php echo $candid['exp_pro'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="mb-6 w-1/4">
                            <div class="bg-gray-800 rounded-lg shadow-md p-4 text-white">
                                <h3 class="text-xl font-semibold mb-2">Carrière</h3>
                                <div class="text-center">
                                    <?php if ($candid['carriere'] === 'usss') { ?>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Logo_of_the_United_States_Secret_Service.svg/1200px-Logo_of_the_United_States_Secret_Service.svg.png" alt="Logo de l'usss" class="h-24 w-24 mx-auto">
                                    <?php } else if ($candid['carriere'] === 'usms') { ?>
                                        <img src="../../assets/img/LogoUSMS.svg" alt="Logo de l'usms" class="h-32 w-32 mx-auto">
                                    <?php } else if ($candid['carriere'] === 'irs') { ?>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Seal_of_the_United_States_Internal_Revenue_Service.svg" alt="Logo de l'irs" class="h-24 w-24 mx-auto">
                                    <?php } else if ($candid['carriere'] === 'fib') { ?>
                                        <img src="../../assets/img/logoFib.png" alt="Logo du fib" class="h-32 w-32 mx-auto">
                                    <?php } else { ?>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Seal_of_the_United_States_Department_of_Homeland_Security.svg" alt="Logo du HLS" class="h-24 w-24 mx-auto">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="bg-gray-800 rounded-lg shadow-md p-4 text-white" style="word-wrap: break-word;">
                            <h3 class="text-xl font-semibold mb-2">Motivation</h3>
                            <p>
                                <?php echo $candid['motivations'] ?>
                            </p>
                        </div>
                    </div>
                    <div>
                        <div class="bg-gray-800 rounded-lg shadow-md p-4 text-white">
                            <h3 class="text-xl font-semibold mb-2">Disponibilité</h3>
                            <p>
                                <?php echo $candid['disponibilites'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="p-4">
        <div class="verification-box absolute w-fit bg-white shadow-md p-4 rounded-lg">
            <?php
            $hasApplied = false; // Remplacez ceci par votre logique de vérification
            $hasSameIp = false; // Remplacez ceci par votre logique de vérification
            $statusText = 'Statut inconnu';

            // Vérification si le candidat a déjà postulé avec le même ID Discord
            $stmtIdDiscord = $bdd->prepare('SELECT r.nom, r.uuid, e.position_candidature, e.etat_candidature FROM recrutement r
        LEFT JOIN (
            SELECT candid_uuid, position_candidature, etat_candidature
            FROM etapes
            WHERE (candid_uuid, created_at) IN (
                SELECT candid_uuid, MAX(created_at) AS max_created_at
                FROM etapes
                GROUP BY candid_uuid
            )
        ) e ON r.uuid = e.candid_uuid WHERE id_discord = :id_discord AND uuid != :uuid ORDER BY position_candidature ASC');
            if ($stmtIdDiscord->execute(array(':id_discord' => $candid['id_discord'], ':uuid' => $candidatId))) {
                $sameIdDiscordCandidatures = $stmtIdDiscord->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($sameIdDiscordCandidatures)) {
                    $hasApplied = true;
                    $etatCandidatureDiscord = $sameIdDiscordCandidatures[0]['etat_candidature'];
                    $positionCandidatureDiscord = $sameIdDiscordCandidatures[0]['position_candidature'];
                }
            }

            // Vérification si le candidat a la même IP dans une autre candidature
            $stmtIp = $bdd->prepare('SELECT r.nom, r.uuid, e.position_candidature, e.etat_candidature FROM recrutement r
        LEFT JOIN (
            SELECT candid_uuid, position_candidature, etat_candidature
            FROM etapes
            WHERE (candid_uuid, created_at) IN (
                SELECT candid_uuid, MAX(created_at) AS max_created_at
                FROM etapes
                GROUP BY candid_uuid
            )
        ) e ON r.uuid = e.candid_uuid WHERE ip = :ip AND uuid != :uuid ORDER BY position_candidature ASC');
            if ($stmtIp->execute(array(':ip' => $candid['ip'], ':uuid' => $candidatId))) {
                $sameIpCandidatures = $stmtIp->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($sameIpCandidatures)) {
                    $hasSameIp = true;
                    $etatCandidatureIP = $sameIpCandidatures[0]['etat_candidature'];
                    $positionCandidatureIP = $sameIpCandidatures[0]['position_candidature'];
                }
            }

            if ($hasApplied || $hasSameIp) {
                echo '<p>Voici les autres candidatures que nous avons trouvées faites par la même personne :</p>';
                echo '<ul>';
                foreach ($sameIdDiscordCandidatures as $candidature) {
                    $statusText = getStatusText($etatCandidatureDiscord, $positionCandidatureDiscord);
                    $statusClass = getStatusClass($etatCandidatureDiscord, $positionCandidatureDiscord);
                    $statusColor = getColorClass($etatCandidatureDiscord, $positionCandidatureDiscord);
                    echo '<li class="mb-2"><a class="block px-4 py-2 rounded ' . $statusClass . ' hover:bg-' . $statusColor . '-800" target="_blank" href="./candidat?id=' . $candidature['uuid'] . '">' . "Candidature au nom de : " . $candidature['nom'] . " " . $statusText . '</a></li>';
                }
                foreach ($sameIpCandidatures as $candidature) {
                    $statusText = getStatusText($etatCandidatureIP, $positionCandidatureIP);
                    $statusClass = getStatusClass($etatCandidatureIP, $positionCandidatureIP);
                    $statusColor = getColorClass($etatCandidatureIP, $positionCandidatureIP);
                    echo '<li class="mb-2"><a class="block px-4 py-2 rounded ' . $statusClass . ' hover:bg-' . $statusColor . '-800" target="_blank" href="./candidat?id=' . $candidature['uuid'] . '">' . "Candidature au nom de : " . $candidature['nom'] . " " . $statusText . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Pas d\'autres candidatures connues.</p>';
            }
            function getColorClass($etatCandidature, $positionCandidature)
            {
                if ($etatCandidature === 2 && $positionCandidature === 4) {
                    return 'green';
                } elseif ($etatCandidature === 0 && $positionCandidature === 3) {
                    return 'red';
                } elseif (($etatCandidature === 1 && $positionCandidature === 1) || ($etatCandidature === 1 && $positionCandidature === 2)) {
                    return 'yellow';
                } elseif ($etatCandidature === 2 && $positionCandidature === 0) {
                    return 'blue';
                } else {
                    return 'gray';
                }
            }

            function getStatusClass($etatCandidature, $positionCandidature)
            {
                if ($etatCandidature === 2 && $positionCandidature === 4) {
                    return 'bg-green-500 text-white';
                } elseif ($etatCandidature === 0 && $positionCandidature === 3) {
                    return 'bg-red-500 text-white';
                } elseif (($etatCandidature === 1 && $positionCandidature === 1) || ($etatCandidature === 1 && $positionCandidature === 2)) {
                    return 'bg-yellow-500 text-black';
                } elseif ($etatCandidature === 2 && $positionCandidature === 0) {
                    return 'bg-blue-500 text-white';
                } else {
                    return 'bg-gray-500 text-white';
                }
            }

            function getStatusText($etatCandidature, $positionCandidature)
            {
                if ($etatCandidature === 2 && $positionCandidature === 4) {
                    return 'Accepté';
                } elseif ($etatCandidature === 0 && $positionCandidature === 3) {
                    return 'Refusé';
                } elseif (($etatCandidature === 1 && $positionCandidature === 1) || ($etatCandidature === 1 && $positionCandidature === 2)) {
                    return 'En cours';
                } elseif ($etatCandidature === 2 && $positionCandidature === 0) {
                    return 'Nouveau';
                } else {
                    return 'Inconnu';
                }
            }
            ?>
        </div>
    </div>

    <div class="chat-bubble cursor-pointer fixed bottom-5 right-5 w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center">
        <div class="text-2xl select-none">&#128221;</div>
    </div>

    <div id="chat-container" class="fixed bottom-5 right-20 w-1/3 h-1/2 bg-white shadow-md rounded-lg hidden transform transition-transform ease-in-out duration-300">
        <div class="p-4 flex flex-col justify-between h-full">
            <div class="border-b border-gray-200 text-lg font-semibold mb-4">Contenu du carnet de pense-bête</div>
            <div class="overflow-y-auto p-2" id="notes-list">
                <?php if (!empty($notesCandids)) { ?>
                    <?php foreach ($notesCandids as $noteCandid) { ?>
                        <?php if ($name === $noteCandid['user_discord']) { ?>
                            <div class="mb-4 flex flex-col items-end">
                                <div class="font-semibold text-green-500">Vous : </div>
                                <div class="text-gray-700"><?php echo $noteCandid['notes'] ?></div>
                            </div>
                        <?php } else { ?>
                            <div class="mb-4">
                                <div class="font-semibold text-blue-500"><?php echo $noteCandid['user_discord'] ?></div>
                                <div class="text-gray-700"><?php echo $noteCandid['notes'] ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div>Aucune note trouvée.</div>
                <?php } ?>
            </div>
            <form id="notes-form" class="fixed bottom-0 right-0 w-full md:w-1/3 bg-white border-t border-gray-200">
                <div class="p-4 flex items-center">
                    <input type="hidden" id="userDiscord" name="userDiscord" value="<?php echo $name ?>">
                    <input type="hidden" id="candidId" name="candidId" value="<?php echo $candidatId ?>">
                    <textarea id="notes" name="notes" class="flex-grow h-16 p-2 border rounded-l-lg resize-none" placeholder="Tapez votre message ici"></textarea>
                    <button type="submit" class="h-16 bg-blue-500 text-white p-2 rounded-r-lg hover:bg-blue-600 focus:outline-none">→</button>
                </div>
            </form>
            <span id="message"></span>
        </div>
    </div>
</body>

</html>