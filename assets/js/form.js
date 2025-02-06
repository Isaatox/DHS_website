var form_1 = document.querySelector(".form_1");
var form_2 = document.querySelector(".form_2");
var form_3 = document.querySelector(".form_3");


var form_1_btns = document.querySelector(".form_1_btns");
var form_2_btns = document.querySelector(".form_2_btns");
var form_3_btns = document.querySelector(".form_3_btns");


var form_1_next_btn = document.querySelector(".form_1_btns .btn_next");
var form_2_back_btn = document.querySelector(".form_2_btns .btn_back");
var form_2_next_btn = document.querySelector(".form_2_btns .btn_next");
var form_3_back_btn = document.querySelector(".form_3_btns .btn_back");

var form_2_progessbar = document.querySelector(".form_2_progessbar");
var form_3_progessbar = document.querySelector(".form_3_progessbar");

var btn_done = document.querySelector(".btn_done");
var modal_wrapper = document.querySelector(".modal_wrapper");
var shadow = document.querySelector(".shadow");

form_1_next_btn.addEventListener("click", function () {
    form_1.style.display = "none";
    form_2.style.display = "block";

    form_1_btns.style.display = "none";
    form_2_btns.style.display = "flex";

    form_2_progessbar.classList.add("active");
});

form_2_back_btn.addEventListener("click", function () {
    form_1.style.display = "block";
    form_2.style.display = "none";

    form_1_btns.style.display = "flex";
    form_2_btns.style.display = "none";

    form_2_progessbar.classList.remove("active");
});

form_2_next_btn.addEventListener("click", function () {
    form_2.style.display = "none";
    form_3.style.display = "block";

    form_3_btns.style.display = "flex";
    form_2_btns.style.display = "none";

    form_3_progessbar.classList.add("active");
});

form_3_back_btn.addEventListener("click", function () {
    form_2.style.display = "block";
    form_3.style.display = "none";

    form_3_btns.style.display = "none";
    form_2_btns.style.display = "flex";

    form_3_progessbar.classList.remove("active");
});

// btn_done.addEventListener("click", function(){
// 	modal_wrapper.classList.add("active");
// })

// shadow.addEventListener("click", function(){
// 	modal_wrapper.classList.remove("active");
// })
const nomInput = document.querySelector('#nom');
const dateNaissanceInput = document.querySelector('#naissance');
const profilInput = document.getElementById('profil');
const telephoneInput = document.getElementById('telephone');
const lieuNaissanceInput = document.getElementById('lieunaissance');
const genreInput = document.getElementById('genre');
const motivationsTextarea = document.getElementById("motivations");
const motivationsCounter = document.getElementById("motivations-counter");
const discordInput = document.getElementById("id_discord");
const discordRegex = /^[0-9]{18}$/;

const inputs = document.querySelectorAll('input[required], select[required], textarea[required]');

inputs.forEach(input => {
    input.addEventListener('input', () => {
        if (input.validity.valid) {
            input.classList.remove('invalid');
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
            input.classList.add('invalid');
        }
    });
});

profilInput.addEventListener('input', validateProfil);
telephoneInput.addEventListener('input', validateTelephone);
nomInput.addEventListener('input', checkNom);
dateNaissanceInput.addEventListener('input', checkDateNaissance);

// Fonction pour vérifier si le nom est valide
function checkNom() {
    if (nomInput.value.trim() === '') {
        nomInput.nextElementSibling.innerHTML = "Veuillez saisir un nom & prénom valide ex : John Doe, de plus le nom et prénom doit avoir deux mots minimum.";
    } else {
        const nomParts = nomInput.value.trim().split(' ');
        if (nomParts.length < 2) {
            nomInput.nextElementSibling.innerHTML = "Le nom et prénom doivent avoir deux mots minimum.";
            nomInput.classList.remove("valid");
            nomInput.classList.add("invalid");
        } else {
            nomInput.nextElementSibling.innerHTML = "";
            nomInput.classList.remove("invalid");
            nomInput.classList.add("valid");
        }
    }
}

// Fonction pour vérifier si la date de naissance est valide
function checkDateNaissance() {
    if (dateNaissanceInput.value.trim() === '') {
        dateNaissanceInput.nextElementSibling.innerHTML = "Veuillez saisir une date de naissance valide ex : 17/08/1995";
    } else {
        const dateNaissance = new Date(dateNaissanceInput.value);
        const now = new Date();
        const age = now.getFullYear() - dateNaissance.getFullYear();
        if (age < 21) {
            dateNaissanceInput.nextElementSibling.innerHTML = "Vous devez avoir au moins 21 ans pour postuler.";
            dateNaissanceInput.classList.remove("valid");
            dateNaissanceInput.classList.add("invalid");
        } else {
            dateNaissanceInput.nextElementSibling.innerHTML = "";
            dateNaissanceInput.classList.remove("invalid");
            dateNaissanceInput.classList.add("valid");
        }
    }
}

function validateProfil() {
    const pProfil = profilInput.value;
    if (!(pProfil.startsWith("https://") || pProfil.startsWith("http://"))) {
        profilInput.classList.remove("valid");
        profilInput.classList.add("invalid");
        profilInput.nextElementSibling.innerHTML = "Veuillez saisir un lien valide commencant par https ou http.";
    }else if (!(pProfil.endsWith(".png") || pProfil.endsWith(".jpg"))) {
        // Email invalide : ajout de la classe "invalide" et affichage du message d'erreur
        profilInput.classList.remove("valid");
        profilInput.classList.add("invalid");
        profilInput.nextElementSibling.innerHTML = "Veuillez saisir un lien valide se terminant par .png ou .jpg.";
    } else {
        // Email valide : suppression de la classe "invalide" et suppression du message d'erreur
        profilInput.classList.remove("invalid");
        profilInput.classList.add("valid");
        profilInput.nextElementSibling.innerHTML = "";
    }
}

function validateTelephone() {
    const telephone = telephoneInput.value;
    if (!telephone.startsWith("555-") || !/^555-\d{3,5}$/.test(telephone)) {
        // Téléphone invalide : ajout de la classe "invalide" et affichage du message d'erreur
        telephoneInput.classList.remove("valid");
        telephoneInput.classList.add("invalid");
        telephoneInput.nextElementSibling.innerHTML = "Veuillez saisir un numéro de téléphone valide (format : 555-12345).";
    } else {
        // Téléphone valide : suppression de la classe "invalide" et suppression du message d'erreur
        telephoneInput.classList.remove("invalid");
        telephoneInput.classList.add("valid");
        telephoneInput.nextElementSibling.innerHTML = "";
    }
}

// Nombre de caractères minimum
const minMotivationsLength = -500;

// Fonction pour mettre à jour le compteur de caractères
function updateMotivationsCounter() {
    const remainingChars = minMotivationsLength + motivationsTextarea.value.replace(/\s/g, "").length;
    motivationsCounter.textContent = `${remainingChars} caractère(s) restant(s)`;
    if (remainingChars < 0) {
        motivationsTextarea.classList.remove("valid");
        motivationsTextarea.classList.add("invalid");

        motivationsCounter.classList.remove("success_message");
        motivationsCounter.classList.add("error_message");

        motivationsTextarea.nextElementSibling.innerHTML = "Votre paragraphe de motivation doit avoir au minimum 500 caractère.";
    } else {
        motivationsTextarea.classList.remove("invalid");
        motivationsTextarea.classList.add("valid");

        motivationsCounter.classList.remove("error_message");
        motivationsCounter.classList.add("success_message");

        motivationsTextarea.nextElementSibling.innerHTML = "";
    }
}

// Mise à jour du compteur de caractères à chaque changement dans le textarea
motivationsTextarea.addEventListener("input", updateMotivationsCounter);

// Fonction pour valider l'input Discord
function validateDiscord() {
    const discordValue = discordInput.value.trim();
    if (isNaN(discordValue)) {
        discordInput.classList.remove("valid");
        discordInput.classList.add("invalid");
        discordInput.nextElementSibling.innerHTML = "L'ID Discord doit contenir uniquement des chiffres.";
    } else if (discordValue.length < 16) {
        discordInput.classList.remove("valid");
        discordInput.classList.add("invalid");
        discordInput.nextElementSibling.innerHTML = "L'ID Discord doit contenir 16 chiffres.";
    } else {
        discordInput.classList.remove("invalid");
        discordInput.classList.add("valid");
        discordInput.nextElementSibling.innerHTML = "";
    }
}

discordInput.addEventListener("input", validateDiscord);

const form = document.querySelector('#recrutement-form');


function isFormValid() {
    var isValid = true;

    // Vérification du nom
    if (nomInput.value.trim() === '') {
        nomInput.nextElementSibling.innerHTML = "Veuillez saisir un nom & prénom valide ex : John Doe, de plus le nom et prénom doit avoir deux mots minimum.";
        isValid = false;
    } else {
        const nomParts = nomInput.value.trim().split(' ');
        if (nomParts.length < 2) {
            nomInput.nextElementSibling.innerHTML = "Le nom et prénom doivent avoir deux mots minimum.";
            isValid = false;
        } else {
            nomInput.nextElementSibling.innerHTML = "";
        }
    }

    // Vérification de la date de naissance
    if (dateNaissanceInput.value.trim() === '') {
        dateNaissanceInput.nextElementSibling.innerHTML = "Veuillez saisir une date de naissance valide ex : 17/08/1995";
        isValid = false;
    } else {
        const dateNaissance = new Date(dateNaissanceInput.value);
        const now = new Date();
        const age = now.getFullYear() - dateNaissance.getFullYear();
        if (age < 21) {
            dateNaissanceInput.nextElementSibling.innerHTML = "Vous devez avoir au moins 21 ans pour postuler.";
            isValid = false;
        } else {
            dateNaissanceInput.nextElementSibling.innerHTML = "";
        }
    }

    // Vérification de l'email
    if (profilInput.value.trim() === '') {
        profilInput.nextElementSibling.innerHTML = "Veuillez saisir une lien valide.";
        isValid = false;
    }else {
        profilInput.nextElementSibling.innerHTML = "";
    }

    // Vérification du téléphone
    if (telephoneInput.value.trim() === '') {
        telephoneInput.nextElementSibling.innerHTML = "Veuillez saisir un numéro de téléphone valide (format : 555-12345).";
        isValid = false;
    } else if (!telephoneInput.value.startsWith("555-") || !/^555-\d{4,5}$/.test(telephoneInput.value)) {
        telephoneInput.nextElementSibling.innerHTML = "Veuillez saisir un numéro de téléphone valide (format : 555-12345).";
        isValid = false;
    } else {
        telephoneInput.nextElementSibling.innerHTML = "";
    }

    // Vérification du lieu de naissance
    if (lieuNaissanceInput.value.trim() === '') {
        lieuNaissanceInput.nextElementSibling.innerHTML = "Veuillez saisir un lieu de naissance valide.";

    } else {
        lieuNaissanceInput.nextElementSibling.innerHTML = "";
    }

    // Vérification du genre
    if (genreInput.value === '') {
        genreInput.nextElementSibling.innerHTML = "Veuillez sélectionner votre genre.";
        isValid = false;
    } else {
        genreInput.nextElementSibling.innerHTML = "";
    }

    // Vérification des motivations
    if (motivationsTextarea.value.trim() === '') {
        // ErrorMessage.innerHTML = 'Le champ des motivations ne peut pas être vide.';
        motivationsTextarea.nextElementSibling.innerHTML = 'Le champ des motivations ne peut pas être vide.';
        isValid = false;
    }

    if (discordInput.value.trim() === '') {
        discordInput.nextElementSibling.innerHTML = 'Le champ de l\'id discord ne peut pas être vide.';
        isValid = false;
    } else if (isNaN(discordInput.value)) {
        discordInput.nextElementSibling.innerHTML = "L'ID Discord doit contenir uniquement des chiffres.";
        isValid = false;
    } else if (discordInput.value.trim().length <= 15) {
        discordInput.nextElementSibling.innerHTML = "L'ID Discord doit contenir 16 chiffres.";
        isValid = false;
    } else {
        discordInput.nextElementSibling.innerHTML = "";
    }

    return isValid;
}

const footer = document.getElementById('content');
const flash = document.getElementById('flash');
// Envoyer le formulaire
form.addEventListener('submit', (event) => {
    event.preventDefault(); // Empêche la page de se rafraîchir
    modal_wrapper.classList.add("active");
    footer.style.display = 'none';
    if (isFormValid() === true) {
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/pages/process/recrutement_process.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const error = response.erreur;
                if (error === 'error') {
                    flash.style.background = '#ec9898';
                    flash.style.color = 'white';
                    flash.innerHTML = ` <p><center>ERREUR ! Votre candidature n'a pas été envoyée.<br><br> Vous avez déjà postulé aujourd'hui. Si cela pas le cas rejoignez l'intranet du Département de recrutement de L'USSS. Puis envoyer un message à join@usss.us <br><a href='https://discord.gg/7zTD4XRRRu' ;' target='_blank' class='usa-button' style='padding: 20px'>Rejoindre l'intranet <span style='color: red;'>(Obligatoire)</span></a></center></p>`;
                } else {
                    flash.style.background = '#28a745';
                    flash.style.color = 'white';
                    flash.innerHTML = ` <p><center>Félicitations ! Votre candidature a bien été envoyée.<br><br>Pensez à rejoindre l'intranet du Département de recrutement de L'USSS. Un messade de : join@usss.us vous sera envoyé. <br><a href='https://discord.gg/7zTD4XRRRu' ;' target='_blank' class='usa-button' style='padding: 20px'>Rejoindre l'intranet <span style='color: red;'>(Obligatoire)</span></a></center></p>`;
                    form.reset();
                }
            }
        };
        xhr.send(formData);
    } else {
        flash.style.background = '#ec9898';
        flash.style.color = 'white';
        flash.innerHTML = ` <p><center>ERREUR ! Votre candidature n'a pas été envoyée.<br><br> Pensez à revérifier tous les champs du formulaire. Si cela ne marche toujours pas rejoignez l'intranet du Département de recrutement de L'USSS. Puis envoyer un message à join@usss.us <br><a href='https://discord.gg/7zTD4XRRRu' ;' target='_blank' class='usa-button' style='padding: 20px'>Rejoindre l'intranet <span style='color: red;'>(Obligatoire)</span></a></center></p>`;
    }
});


shadow.addEventListener("click", function () {
    modal_wrapper.classList.remove("active");
    footer.style.display = 'flex';
})


function MaxLengthTextarea(textarea, maxLength) {
    if (textarea.value.length > maxLength) {
        textarea.value = textarea.value.substring(0, maxLength);
        alert("Limite de caractères dépassée !");
    }
}