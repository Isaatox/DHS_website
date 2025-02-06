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

// Traitement du formulaire de filtrage
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["carriere"])) {
  $carriere = $_POST["carriere"];

  // Liste des carrières valides
  $carrieresValides = ["usms", "usss", "fib", "irs", "toutes"];

  // Vérification si la carrière choisie est valide
  if (!in_array($carriere, $carrieresValides)) {
    echo "Carrière invalide.";
    exit(); // Arrêter l'exécution du script si la carrière n'est pas valide
  }

  // Requête SQL pour récupérer les candidatures en fonction de la carrière
  if ($carriere === "toutes") {
    $candidsSearch = $bdd->prepare('SELECT r.nom, r.date_naissance, r.lieu_naissance, r.profil, r.telephone, r.uuid, e.position_candidature, e.etat_candidature
      FROM recrutement r
      INNER JOIN (
          SELECT candid_uuid, position_candidature, etat_candidature
          FROM etapes
          WHERE (candid_uuid, created_at) IN (
              SELECT candid_uuid, MAX(created_at) AS max_created_at
              FROM etapes
              GROUP BY candid_uuid
          )
      ) e ON r.uuid = e.candid_uuid
      WHERE r.uuid IS NOT NULL
      ORDER BY position_candidature ASC');
    $candidsSearch->execute();
  } else {
    $candidsSearch = $bdd->prepare('SELECT r.nom, r.date_naissance, r.lieu_naissance, r.profil, r.telephone, r.uuid, e.position_candidature, e.etat_candidature
      FROM recrutement r
      INNER JOIN (
          SELECT candid_uuid, position_candidature, etat_candidature
          FROM etapes
          WHERE (candid_uuid, created_at) IN (
              SELECT candid_uuid, MAX(created_at) AS max_created_at
              FROM etapes
              GROUP BY candid_uuid
          )
      ) e ON r.uuid = e.candid_uuid
      WHERE r.carriere = :carriere
      AND r.uuid IS NOT NULL
      ORDER BY position_candidature ASC');
    $candidsSearch->execute(array(':carriere' => $carriere));
  }
} else {
  // Si la méthode de la requête n'est pas POST ou si le champ "carriere" n'est pas défini, récupérer toutes les candidatures
  $candidsSearch = $bdd->prepare('SELECT r.nom, r.date_naissance, r.lieu_naissance, r.profil, r.telephone, r.uuid, e.position_candidature, e.etat_candidature
  FROM recrutement r
  INNER JOIN (
      SELECT candid_uuid, position_candidature, etat_candidature
      FROM etapes
      WHERE (candid_uuid, created_at) IN (
          SELECT candid_uuid, MAX(created_at) AS max_created_at
          FROM etapes
          GROUP BY candid_uuid
      )
  ) e ON r.uuid = e.candid_uuid
  WHERE r.uuid IS NOT NULL
  ORDER BY position_candidature ASC');
  $candidsSearch->execute();
}

function afficherCandidature($candid)
{
  $etatCandidature = $candid['etat_candidature'];
  $positionCandidature = $candid['position_candidature'];

  $statusClass = 'text-gray-500';
  $statusText = 'Statut inconnu';

  if ($etatCandidature === 2 && $positionCandidature === 4) {
    $statusClass = 'text-green-500';
    $statusText = 'Acceptée';
  } elseif ($etatCandidature === 0 && $positionCandidature === 3) {
    $statusClass = 'text-red-500';
    $statusText = 'Refusée';
  } elseif (($etatCandidature === 1 && $positionCandidature === 1) || ($etatCandidature === 1 && $positionCandidature === 2)) {
    $statusClass = 'text-yellow-500';
    $statusText = 'En cours';
  } elseif ($etatCandidature === 2 && $positionCandidature === 0) {
    $statusClass = 'text-blue-500';
    $statusText = 'Nouvelle';
  }

  $date = new DateTime($candid['date_naissance']);
  $formattedDate = $date->format('d/m/Y');

  echo <<<HTML
    <div class="flex flex-row gap-4">
        <div class="max-w-lg p-2 bg-gray-900 rounded-xl transform transition-all hover:-translate-y-2 duration-300 shadow-lg hover:shadow-2xl">
            <img class="h-40 object-cover rounded-xl" src="{$candid['profil']}" alt="Photo de {$candid['nom']}">
            <div class="p-2">
                <h2 class="font-bold text-lg mb-2 text-gray-200">{$candid['nom']} <span class="{$statusClass}">{$statusText}</span></h2>
                <p class="text-sm text-gray-400">
                    <span class="font-bold">{$candid['nom']}</span> est né(e) le <span class="font-bold">{$formattedDate}</span>
                    à <span class="font-bold">{$candid['lieu_naissance']}</span>, son numéro de téléphone est le <span class="font-bold">{$candid['telephone']}</span>
                </p>
            </div>
            <div class="m-2">
                <a role='button' href="./candidat?id={$candid['uuid']}" target="_blank" class="text-white bg-blue-700 px-3 py-1 rounded-md hover:bg-blue-800">En savoir plus !</a>
            </div>
        </div>
    </div>
HTML;
}

$carriereOptions = [
  "toutes" => "Toutes les carrières",
  "usms" => "USMS",
  "usss" => "USSS",
  "fib" => "FIB",
  "irs" => "IRS"
];

function afficherOptionsCarriere($carriere, $options)
{
  foreach ($options as $value => $label) {
    $selected = ($carriere === $value) ? "selected" : "";
    echo "<option value='$value' $selected>$label</option>";
  }
}

?>

<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="../../assets/css/output.css" rel="stylesheet">
  <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-discord-gray">
  <nav class="flex justify-between mr-3 mt-3 ml-3">
    <div>
      <img class="rounded-full w-12 h-12 mr-3" src="<?php echo $avatar_url ?>" />
      <span class="text-1xl text-white font-semibold"><?php echo $name; ?></span>
    </div>
    <div>
      <img src="<?php echo $logoService ?>" class="w-48 h-48" alt="">
    </div>
    <div>
      <a href="./logout" class="mt-5 text-gray-300">Déconnexion</a>
    </div>
  </nav>
  <div class="flex flex-col items-center justify-center mt-8">
    <div class="text-white text-2xl mb-4">Bienvenue <b><?= $name ?></b> sur l'espace candidature. Votre rôle est : <?= $role ?></div>
    <form method="post" class="bg-gray-100 rounded-lg p-4 shadow-md mb-4">
      <label for="carriere" class="block text-gray-700 font-semibold mb-2">Filtrer par carrière :</label>
      <div class="relative inline-block w-64">
        <select name="carriere" id="carriere" class="block w-full mt-1 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
          <?= afficherOptionsCarriere($carriere, $carriereOptions) ?>
        </select>
      </div>
      <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-md hover:shadow-lg">Filtrer</button>
    </form>
    <div class="flex flex-wrap justify-center gap-4">
      <?php foreach ($candidsSearch as $key => $candid) {
        afficherCandidature($candid);
      } ?>
    </div>
  </div>
</body>

</html>