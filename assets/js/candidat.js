document.addEventListener('DOMContentLoaded', function () {
    const infoPerso = document.getElementById("infoPerso");
    const expPro = document.getElementById("expPro");
    const contenuInfoPerso = document.getElementById("contenuInfoPerso");
    const contenuExpPro = document.getElementById("contenuExpPro");

    // Ajouter des gestionnaires d'événements clic aux boutons d'onglet
    infoPerso.addEventListener("click", () => {
        // Afficher le contenu de l'onglet 1 et masquer le contenu de l'onglet 2
        contenuInfoPerso.style.display = "block";
        contenuExpPro.style.display = "none";
        // Mettre en surbrillance le bouton de l'onglet 1
        infoPerso.classList.add("bg-blue-500", "hover:bg-blue-600", "text-white", "font-semibold");
        infoPerso.classList.remove("bg-gray-200", "hover:bg-gray-300");
        expPro.classList.remove("bg-blue-500", "hover:bg-blue-600", "text-white", "font-semibold");
        expPro.classList.add("bg-gray-200", "hover:bg-gray-300");
    });

    expPro.addEventListener("click", () => {
        // Afficher le contenu de l'onglet 2 et masquer le contenu de l'onglet 1
        contenuInfoPerso.style.display = "none";
        contenuExpPro.style.display = "block";
        // Mettre en surbrillance le bouton de l'onglet 2
        infoPerso.classList.remove("bg-blue-500", "hover:bg-blue-600", "text-white", "font-semibold");
        infoPerso.classList.add("bg-gray-200", "hover:bg-gray-300");
        expPro.classList.add("bg-blue-500", "hover:bg-blue-600", "text-white", "font-semibold");
        expPro.classList.remove("bg-gray-200", "hover:bg-gray-300");
    });

    // Par défaut, afficher le contenu de l'onglet 1
    contenuInfoPerso.style.display = "block";


    const chatBubble = document.querySelector(".chat-bubble");
    const chatContainer = document.getElementById('chat-container');

    chatBubble.addEventListener("click", function () {
        chatContainer.classList.toggle("d-block");
        chatContainer.classList.toggle('translate-y-0');
    });

    function isSafe(input) {
        // Liste des balises HTML et attributs dangereux à rechercher
        const dangerousTags = ['script', 'iframe', 'a', 'img', 'onload', 'onclick', 'onmouseover'];

        // Convertir la chaîne en minuscules pour une correspondance insensible à la casse
        const lowercaseInput = input.toLowerCase();

        // Recherchez chaque balise ou attribut dangereux dans la chaîne
        for (const tag of dangerousTags) {
            if (lowercaseInput.includes(`<${tag}`) || lowercaseInput.includes(`on${tag}`)) {
                // La chaîne contient une balise ou un attribut dangereux
                return false;
            }
        }

        // La chaîne est considérée comme sûre
        return true;
    }

    function validateForm() {
        const userDiscord = document.getElementById('userDiscord').value;
        const candidId = document.getElementById('candidId').value;
        const notes = document.getElementById('notes').value;

        if (userDiscord === '' || candidId === '' || notes === '') {
            alert('Veuillez remplir tous les champs.');
            return false;
        }

        if (!isSafe(notes)) {
            alert('Le champ "notes" contient du code malveillant.');
            return false;
        }

        return true;
    }

    document.getElementById('notes-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const message = document.getElementById('message');
        const formNote = document.getElementById('notes-form');

        if (!validateForm()) {
            e.preventDefault();
        } else {
            const formData = new FormData(formNote);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/pages/panel/process/notes_process.php');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const responseText = xhr.responseText;

                        if (responseText.trim() !== "") {
                            try {
                                const response = JSON.parse(responseText);
                                const error = response.erreur;
                                const success = response.success;

                                if (error === 'error') {
                                    message.style.color = 'red';
                                    message.textContent = "Erreur lors de l'envoi de la note";
                                }

                                if (success === 'success') {
                                    message.style.color = 'green';
                                    message.textContent = 'Note envoyée';

                                    const notesTextarea = document.getElementById('notes');
                                    const notes = notesTextarea.value;

                                    const notesList = document.getElementById('notes-list');
                                    const newNoteHTML = `
                                        <div class="mb-4 flex flex-col items-end">
                                            <div class="font-semibold text-green-500">Vous : </div>
                                            <div class="text-gray-700">${notes}</div>
                                        </div>
                                    `;
                                    notesList.insertAdjacentHTML('beforeend', newNoteHTML);

                                    formNote.reset();
                                }

                            } catch (error) {
                                console.error(error);
                            }
                        } else {
                            console.error('La réponse du serveur est vide.');
                        }
                    } else {
                        console.error('La requête a échoué avec le statut : ' + xhr.status);
                    }
                }
            };
            xhr.send(formData);
        }
    });

    const etapeForm = document.getElementById('etapeForm');

    const errorDiv = document.createElement('div'); // Créez l'élément div d'erreur
    errorDiv.className = 'fixed bottom-1 inset-x-0 flex items-center justify-center z-50';


    etapeForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(etapeForm);

        fetch('./process/newEtape_process.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success === 'success') {
                    const successDiv = document.createElement('div');
                    successDiv.className = 'fixed bottom-1 inset-x-0 flex items-center justify-center z-50';
                    successDiv.innerHTML = `
                    <div class="bg-green-500 text-white p-4 rounded-lg shadow-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                <g id="SVGRepo_iconCarrier">
                                <path fill="#ffffff" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896zm-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336L456.192 600.384z"/>
                                </g>
                            </svg>
                            Succès
                        </div>
                    </div>
                `;

                    // Ajoutez le message de succès au document
                    document.body.appendChild(successDiv);

                    // Supprimez le message de succès après 5 secondes
                    setTimeout(() => {
                        document.body.removeChild(successDiv);
                    }, 5000);

                    // Rechargez la page si la soumission est réussie
                    location.reload();
                } else {
                    errorDiv.innerHTML = `
                    <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                        <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0" />
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8984 3.61441C12.5328 2.86669 11.4672 2.86669 11.1016 3.61441L3.30562 19.5608C2.98083 20.2251 3.46451 21 4.204 21H19.796C20.5355 21 21.0192 20.2251 20.6944 19.5608L12.8984 3.61441ZM9.30485 2.73599C10.4015 0.492834 13.5985 0.492825 14.6952 2.73599L22.4912 18.6824C23.4655 20.6754 22.0145 23 19.796 23H4.204C1.98555 23 0.534479 20.6754 1.50885 18.6824L9.30485 2.73599Z" fill="#ffffff" />
                            <path d="M11 8.49999C11 7.94771 11.4477 7.49999 12 7.49999C12.5523 7.49999 13 7.94771 13 8.49999V14C13 14.5523 12.5523 15 12 15C11.4477 15 11 14.5523 11 14V8.49999Z" fill="#ffffff" />
                            <path d="M13.5 18C13.5 18.8284 12.8285 19.5 12 19.5C11.1716 19.5 10.5 18.8284 10.5 18C10.5 17.1716 11.1716 16.5 12 16.5C12.8285 16.5 13.5 17.1716 13.5 18Z" fill="#ffffff" />
                        </g>
                    </svg>
                    ${data.erreur}
                
                        </div>
                    </div>`;
                    // Affichez l'erreur
                    document.body.appendChild(errorDiv);

                    // Supprimez l'erreur après 5 secondes
                    setTimeout(() => {
                        document.body.removeChild(errorDiv);
                    }, 5000);

                    console.error('Erreur lors de la soumission du formulaire:', data.erreur);
                }
            })
            .catch(error => {
                errorDiv.innerHTML = `
                <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                    <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0" />
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8984 3.61441C12.5328 2.86669 11.4672 2.86669 11.1016 3.61441L3.30562 19.5608C2.98083 20.2251 3.46451 21 4.204 21H19.796C20.5355 21 21.0192 20.2251 20.6944 19.5608L12.8984 3.61441ZM9.30485 2.73599C10.4015 0.492834 13.5985 0.492825 14.6952 2.73599L22.4912 18.6824C23.4655 20.6754 22.0145 23 19.796 23H4.204C1.98555 23 0.534479 20.6754 1.50885 18.6824L9.30485 2.73599Z" fill="#ffffff" />
                        <path d="M11 8.49999C11 7.94771 11.4477 7.49999 12 7.49999C12.5523 7.49999 13 7.94771 13 8.49999V14C13 14.5523 12.5523 15 12 15C11.4477 15 11 14.5523 11 14V8.49999Z" fill="#ffffff" />
                        <path d="M13.5 18C13.5 18.8284 12.8285 19.5 12 19.5C11.1716 19.5 10.5 18.8284 10.5 18C10.5 17.1716 11.1716 16.5 12 16.5C12.8285 16.5 13.5 17.1716 13.5 18Z" fill="#ffffff" />
                    </g>
                </svg>
                Erreur réseau : ${error.message}
                    </div>
                </div>`;
                // Affichez l'erreur réseau
                document.body.appendChild(errorDiv);

                // Supprimez l'erreur après 5 secondes
                setTimeout(() => {
                    document.body.removeChild(errorDiv);
                }, 5000);

                console.error('Erreur réseau :', error);
            });
    });



});