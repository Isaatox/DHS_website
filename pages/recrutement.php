<?php

include_once '../conf.php';
date_default_timezone_set('Europe/Paris');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:title" content="Department of Homeland Security" />
    <meta property="og:title" content="Department of Homeland Security" />
    <meta property="og:type" content="Department of Homeland Security" />
    <meta property="og:site_name" content="Recrutement" />
    <meta property="og:image" content="https://www.join-hls.us/assets/img/logoHLS.png" />
    <meta property="og:description" content="Site internet du Homeland Security" />

    <link rel="shortcut icon" href="../assets/img/logoHLS.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

    <script src="https://kit.fontawesome.com/b78998cfc3.js" crossorigin="anonymous"></script>
    <title>Homeland Security | Recrutement</title>

</head>


<body>
    <?php include_once('../layouts/nav.php'); ?>
    <main>
        <div class="banner-container">
            <div class="banner" id="bannerRecrutement">
                <h1>Recrutement</h1>
            </div>
        </div>
        <div class="contenu-container">
            <div class="form-container">
                <div class="wrapper">
                    <form id="recrutement-form" method="POST">
                        <div class="header">
                            <ul>
                                <li class="active form_1_progessbar">
                                    <div>
                                        <p>1</p>
                                    </div>
                                </li>
                                <li class="form_2_progessbar">
                                    <div>
                                        <p>2</p>
                                    </div>
                                </li>
                                <li class="form_3_progessbar">
                                    <div>
                                        <p>3</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="form_wrap">
                            <div class="form_1 data_info">
                                <h2>Informations personnelles</h2>
                                <div class="form_container">
                                    <div class="input_wrap">
                                        <label for="nom" required>Nom - Prénom : </label>
                                        <input type="text" class="input" name="nom" id="nom" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="naissance" required>Date de naissance : </label>
                                        <input type="date" class="input" name="naissance" id="naissance" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="lieunaissance" required>Lieu de naissance : </label>
                                        <input type="text" name="lieunaissance" class="input" id="lieunaissance" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="telephone" required>Téléphone : </label>
                                        <input type="text" class="input" name="telephone" id="telephone" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="profil" required>Photo de profil (lien type imgur): </label>
                                        <p class="help">Taille recomandée : 280px X 350px | Format profil <a href="https://i.imgur.com/YNrdxlN.png" target="_blank" rel="noopener noreferrer">Exemple</a></p>
                                        <input type="text" name="profil" id="profil" class="input" value="" placeholder="Photo de votre tête." required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="genre" required>Genre : </label>
                                        <select name="genre" class="input" id="genre" required>
                                            <option value="homme">Homme</option>
                                            <option value="femme">Femme</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                        <span class="error_message"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form_2 data_info" style="display: none;">
                                <h2>Motivations & Experience</h2>
                                <div class="form_container">
                                    <div class="input_wrap">
                                        <label for="experience" required>Expériences professionnelles : </label>
                                        <input type="text" name="experience" id="experience" class="input" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="motivations" required>Vos motivations : </label>
                                        <textarea name="motivations" id="motivations" oninput="javascript:MaxLengthTextarea(this, 1024);" required></textarea>
                                        <span class="error_message"></span>
                                        <div id="motivations-counter" class="error_message">-500 caractère(s) restant(s)</div>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="carriere" required>Choix de carrière : </label>
                                        <select name="carriere" class="input" id="carriere" required>
                                            <?php
                                            $sth = $bdd->prepare("SELECT value, label FROM categorie WHERE active = :active;");
                                            $sth->execute(array(":active" => 1));
                                            $results = $sth->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <?php foreach ($results as $row => $link) { ?>
                                                <option value="<?php echo ($link['value']) ?>"><?php echo ($link['label']) ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="disponibilites" required>Disponibilités, Je peux assurer un minimum de 10h de présence par semaine : </label>
                                        <input type="text" name="disponibilites" id="disponibilites" class="input" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form_3 data_info" style="display: none;">
                                <h2>Informations supplémentaires</h2>
                                <div class="form_container">
                                    <div class="input_wrap">
                                        <label for="id_discord" required>ID Discord | (Exemple: 554718XXXXXXXXXXXX) : </label>
                                        <input type="text" class="input" name="id_discord" id="id_discord" value="" required>
                                        <span class="error_message"></span>
                                    </div>
                                    <div class="input_wrap">
                                        <label for="paragraphe">Paragraphe libre : </label>
                                        <textarea name="paragraphe" id="paragraphe" oninput="javascript:MaxLengthTextarea(this, 1024);"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btns_wrap">
                            <div class="common_btns form_1_btns">
                                <button type="button" class="btn_next" id="btn-next-1">Suivant <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span></button>
                            </div>
                            <div class="common_btns form_2_btns" style="display: none;">
                                <button type="button" class="btn_back"><span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour</button>
                                <button type="button" class="btn_next">Suivant <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span></button>
                            </div>
                            <div class="common_btns form_3_btns" style="display: none;">
                                <button type="button" class="btn_back"><span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour</button>
                                <input type="submit" class="btn_done" value="Envoyer">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal_wrapper">
                    <div class="shadow"></div>
                    <div class="success_wrap" id="flash">
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php include_once('../layouts/footer.php'); ?>

    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/form.js"></script>
</body>

</html>