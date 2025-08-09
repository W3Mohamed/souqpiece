document.addEventListener('DOMContentLoaded', function() {

    var btnNav = document.getElementById('btn-nav');
    var ulRwd = document.getElementById('ul-rwd');
    
        btnNav.onclick = function(){
            if(ulRwd.style.display == 'none' || ulRwd.style.display == ''){
                ulRwd.style.display = 'block';
            }else{
                ulRwd.style.display = 'none';
            }
        }
    
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.querySelector('.suggestions-box');
    console.log("DOM chargé");
    if (searchInput && suggestionsBox) {
         console.log("DOM chargéééééééééé");
        let timeoutId;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                timeoutId = setTimeout(() => {
                    fetchSuggestions(query);
                }, 300);
            } else {
                hideSuggestions();
            }
        });

        function fetchSuggestions(query) {
            console.log("Envoi requête pour:", query);
            fetch('dashboard/get_suggestions.php?query=' + encodeURIComponent(query))
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    console.log("Réponse reçue:", data);
                    if (data && data.length > 0) {
                        showSuggestions(data);
                    } else {
                        hideSuggestions();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    hideSuggestions();
                });
        }

        function showSuggestions(items) {
            suggestionsBox.innerHTML = '';
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.textContent = item;
                div.addEventListener('click', function() {
                    searchInput.value = item;
                    document.getElementById('searchForm').submit();
                });
                suggestionsBox.appendChild(div);
            });
            suggestionsBox.style.display = 'block';
        }

        function hideSuggestions() {
            suggestionsBox.style.display = 'none';
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-autocomplete')) {
                hideSuggestions();
            }
        });
    }



    // Autocomplétion pour le deuxième formulaire
    const searchInput2 = document.getElementById('searchInput2');
    const suggestionsBox2 = document.querySelector('#searchForm2 .suggestions-box');
    
    if (searchInput2 && suggestionsBox2) {
        let timeoutId;
    
        searchInput2.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                timeoutId = setTimeout(() => {
                    fetchSuggestions(query, suggestionsBox2, searchInput2);
                }, 300);
            } else {
                suggestionsBox2.style.display = 'none';
            }
        });
    
        // Gestion du clic sur les suggestions
        suggestionsBox2.addEventListener('click', (e) => {
            if (e.target.classList.contains('suggestion-item')) {
                searchInput2.value = e.target.textContent;
                suggestionsBox2.style.display = 'none';
                document.getElementById('searchForm2').submit();
            }
        });
    
        // Fermer les suggestions quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-autocomplete')) {
                suggestionsBox2.style.display = 'none';
            }
        });
    }
    
    // Fonction fetchSuggestions modifiée pour être réutilisable
    async function fetchSuggestions(query, suggestionsBox, inputElement) {
        try {
            const response = await fetch(`dashboard/get_suggestions.php?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data?.length > 0) {
                suggestionsBox.innerHTML = data.map(item => 
                    `<div class="suggestion-item">${item}</div>`
                ).join('');
                suggestionsBox.style.display = 'block';
                
                // Ajustement du border-radius quand il y a des suggestions
                inputElement.style.borderRadius = '25px 0 0 0';
            } else {
                suggestionsBox.style.display = 'none';
                inputElement.style.borderRadius = '25px 0 0 25px';
            }
        } catch (error) {
            console.error('Error:', error);
            suggestionsBox.style.display = 'none';
        }
    }
    


});