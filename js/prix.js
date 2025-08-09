document.addEventListener('DOMContentLoaded', function() {
    const wilayaSelect = document.getElementById('wilayaSelect');
    const livraisonRadios = document.querySelectorAll('input[name="livraison"]');
    const priceElement = document.getElementById('price'); // livraison
    const prixTotalElement = document.getElementById('prixTotal');
    const prixDesProduits = parseInt(prixTotalElement.textContent);

    if (wilayaSelect && livraisonRadios && priceElement && prixTotalElement) {

        function updatePrixTotal(prixLivraison) {
            const prixLivraisonInt = parseInt(prixLivraison, 10);
            const prixTotal = prixDesProduits + prixLivraisonInt;
            prixTotalElement.textContent = prixTotal + ' DA';
        }

        function getPrixLivraison() {
            const wilayaId = wilayaSelect.value;
            const typeLivraison = document.querySelector('input[name="livraison"]:checked').value;

            if (!wilayaId) {
                return;
            }

            // Envoyer la requête AJAX pour récupérer le prix de livraison
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        //console.log(xhr.responseText); // Affiche la réponse brute pour le débogage
                        try {
                            const response = JSON.parse(xhr.responseText);
                            const prixLivraison = response.prix;
                            priceElement.textContent = prixLivraison + ' DA';
                            updatePrixTotal(prixLivraison);
                        } catch (e) {
                            console.error('Erreur de parsing JSON:', e);
                            console.error('Réponse reçue:', xhr.responseText);
                        }
                    } else {
                        console.error('Erreur lors de la récupération du prix de livraison');
                    }
                }
            };

            xhr.open('GET', `get_delivery_price.php?wilaya_id=${wilayaId}&type_livraison=${typeLivraison}`, true);
            xhr.send();
        }

        wilayaSelect.addEventListener('change', getPrixLivraison);
        livraisonRadios.forEach(radio => radio.addEventListener('change', getPrixLivraison));

        // Mise à jour initiale si une wilaya est déjà sélectionnée
        if (wilayaSelect.value) {
            getPrixLivraison();
        }
    }
});
