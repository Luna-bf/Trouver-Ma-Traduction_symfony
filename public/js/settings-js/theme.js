// Theme switcher
let darkMode = localStorage.getItem('darkMode');
const themeSelect = document.getElementById('theme-select');

// Activation du mode sombre
function enableDarkMode() {

    document.body.classList.add("dark-mode"); // Je donne la classe 'dark-mode' à la balise body

    // Puis je met à jour l'état du mode sombre dans le localStorage
    window.localStorage.setItem("darkMode", 'active'); // Je ne peux mettre que des strings dans le localStorage, au lieu de mettre un boléen (true ou false) je met juste 'active'
    themeSelect.value = "dark";
};

// Désactive le mode sombre
function disableDarkMode() {

    document.body.classList.remove("dark-mode"); // Je donne la classe 'dark-mode' à la balise body

    // Puis je met à jour l'état du mode sombre dans le localStorage
    window.localStorage.removeItem("darkMode"); // Je ne peux mettre que des strings dans le localStorage, au lieu de mettre un boléen (true ou false) je met juste 'active'
    themeSelect.value = "light";
};

// Fonction de changement du thème
function themeSwitcher() {

    darkMode = localStorage.getItem('darkMode');
    
    // Vérification de la valeur de l'élément "darkMode" du localStorage
    if (localStorage.getItem('darkMode') === "active") {
        enableDarkMode();
    } else {
        disableDarkMode();
    }

    // J'ajoute un événement de changement au select qui change le thème du site
    themeSelect.addEventListener('change', function () {

        // Je vérifie la valeur sélectionnée par l'utilisateur (light ou dark)
        if (themeSelect.value === "dark") {
            enableDarkMode(); // Si la valeur est 'dark' (thème sombre) j'appelle la fonction "enableDarkMode()"
        } else {
            disableDarkMode(); // Sinon, je donne la possibilité de le désactiver
        }
    });
}

// Appel de la fonction
themeSwitcher();