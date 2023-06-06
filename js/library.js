function getAllManga() {
  const now = document.getElementById('manga');

  fetch("./json/manga.php")
    .then(response => response.json())
    .then(data => {
      // récupérer les résultats de la page actuelle
      console.log(data);
      // itérer sur les résultats et créer les éléments DOM pour chaque film
      for (let i = 0; i < data.length; i++) {
        const movie = data[i];

        const title = movie.title;
        const image = `https://cdn.statically.io/gh/Anime-Sama/IMG/img/contenu/${movie.path_img}.jpg`;
        const id = movie.id;
        const url = movie.url;
        // créer l'élément Poster
        const movieElem = document.createElement('div');
        // détail des  films
        const titleElem = document.createElement('h3');
        const imageElem = document.createElement('img');

        movieElem.classList.add('movie_poster');
        // titleElem.classList.add('title_poster');
        imageElem.classList.add('image_poster');

        // ajouter le contenu
        titleElem.textContent = title;
        imageElem.src = image;
        imageElem.dataset.id = id; // stocker l'ID du film dans un attribut personnalisé
      //   imageElem.dataset.type = type;
        // ajouter un gestionnaire d'événements pour le clic sur l'image
        imageElem.addEventListener('click', function() {
          window.location.href = 'details.php?manga=' + url; // rediriger l'utilisateur vers la page détaillée avec l'ID du film dans l'URL
        });

        // ajouter les éléments au DOM
        // movieElem.appendChild(titleElem);
        movieElem.appendChild(imageElem);

        now.appendChild(movieElem);
      }

    });
}


getAllManga();



  
//   // Fonction pour afficher la sélection d'épisodes correspondant à la saison choisie
// function afficherEpisodes(data) {
//     var saisonSelect = document.getElementById('saison');
//     var episodesContainer = document.getElementById('episodesContainer');
//     var episodeSelect = document.getElementById('episode');
//     // Réinitialiser la sélection des épisodes
//     episodeSelect.innerHTML = '<option value="">Sélectionner un épisode</option>';

//     if (saisonSelect.value !== '') {
//         console.log(saisonSelect.value);

//         console.log(data[0]);
//         episodesContainer.style.display = 'block';
//         console.log(data);
//       var saison_choice = saisonSelect.value;
//       console.log(saison);
//       var episodes = Object.keys(data[0].saison[saison_choice].episode);
//         console.log(episodes);
//       episodes.forEach(function(episode) {
//         var option = document.createElement('option');
//         option.value = episode;
//         option.innerHTML = episode;
//         episodeSelect.appendChild(option);
//       });
//     } else {
//       episodesContainer.style.display = 'none';
//     }
//   }
