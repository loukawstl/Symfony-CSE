// Récupération de tous les liens de la navigation
const navLinks = document.querySelectorAll('a');

// Parcours de tous les liens de la navigation
navLinks.forEach((link) => {
  // Ajout d'un gestionnaire d'événement au clic sur chaque lien
  link.addEventListener('click', function() {
    // Suppression de la classe "active" de tous les liens
    navLinks.forEach((navLink) => {
      navLink.classList.remove('active');
    });
    // Ajout de la classe "active" sur le lien cliqué
    this.classList.add('active');
  });
});

// Récupération de l'URL de la page actuelle
const currentUrl = window.location.href;

// Parcours de tous les liens de la navigation
navLinks.forEach((link) => {
  // Récupération de l'URL du lien
  const linkUrl = link.href;
  // Vérification si l'URL du lien correspond à l'URL de la page actuelle
  if (currentUrl === linkUrl) {
    // Ajout de la classe "active" sur le lien correspondant
    link.classList.add('active');
  }
});
