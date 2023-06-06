<?php
if (isset($_GET['manga']) && isset($_GET['saison']) && isset($_GET['episode'])) { ?>
    <style>
        header {
            display: none;
        }

        .manga-title {
            display: none;
        }

        .manga-image {
            display: none;
        }

        .manga-description {
            display: none;
        }

        .manga-episodes {
            display: none;
        }

        video {
            height: 100%;
            width: 100%;
            display: block;
        }
    </style>
    <script>
        let isPaused = false;
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('keydown', (event) => {
                const videoElem = document.querySelector('video');
                // Variable pour suivre l'état de lecture de la vidéo
                if (event.key === 'Escape') {
                    const mangaName = '<?= $_GET['manga'] ?>'; // Remplacez par le nom du manga correspondant
                    const url = `details.php?manga=${encodeURIComponent(mangaName)}`;
                    window.location.href = url;
                }
                if (event.keyCode === 32) {
                    if (isPaused == false) {
                        videoElem.pause(); // Reprendre la lecture de la vidéo
                        isPaused = true;
                    } else {
                        videoElem.play(); // Mettre en pause la vidéo
                        isPaused = false;
                    }
                }
            });
        });
    </script>
<?php
}
?>
<!DOCTYPE html>

<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once("./inc/include_head.php") ?>
    <script src="./js/details.js" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', () => {
            const elementsToHide = document.querySelectorAll('.manga-title, .manga-image, .manga-description, .manga-episodes');

            elementsToHide.forEach(element => {
                element.style.display = 'none';
            });
        });
    </script>

    <title>Bibliotéque</title>
</head>

<body>
    <header><?php include_once("./inc/include_nav.php") ?></header>
    <main>
        <div id="manga">
        </div>
    </main>
    <footer></footer>


    <?php
    if (isset($_GET['manga'])) {
    ?>
        <script defer>
            function getMangaByURL(mangaURL) {
                const now = document.getElementById('manga');

                fetch("./json/manga.php")
                    .then(response => response.json())
                    .then(data => {
                        // Rechercher le manga spécifique en fonction de son URL
                        const manga = data.find(m => m.url === mangaURL);

                        if (manga) {

                            const id = manga.id;
                            const url = manga.url;
                            const imagePath = manga.path_img;
                            const name = manga.name;
                            const description = manga.description;
                            const seasons = manga.saison;

                            const mangaElem = document.createElement('div');
                            const titleElem = document.createElement('h3');
                            const imageElem = document.createElement('img');
                            const descriptionElem = document.createElement('p');
                            const episodesElem = document.createElement('ul');

                            mangaElem.classList.add('manga');
                            titleElem.classList.add('manga-title');
                            imageElem.classList.add('manga-image');
                            descriptionElem.classList.add('manga-description');
                            episodesElem.classList.add('manga-episodes');

                            titleElem.textContent = name;
                            imageElem.src = `https://cdn.statically.io/gh/Anime-Sama/IMG/img/contenu/${imagePath}.jpg`;
                            descriptionElem.textContent = description;

                            if (Object.keys(seasons).length > 0) {
                                for (const seasonName in seasons) {
                                    if (seasons.hasOwnProperty(seasonName)) {
                                        const season = seasons[seasonName];
                                        const seasonElem = document.createElement('li');
                                        const seasonTitleElem = document.createElement('h4');
                                        const episodesListElem = document.createElement('ul');

                                        seasonTitleElem.textContent = seasonName;

                                        if (Object.keys(season.episode).length > 0) {
                                            for (const episodeName in season.episode) {
                                                if (season.episode.hasOwnProperty(episodeName)) {
                                                    const episode = season.episode[episodeName];
                                                    const episodeElem = document.createElement('li');
                                                    const episodeLinkElem = document.createElement('a');

                                                    episodeLinkElem.textContent = episodeName;
                                                    const decodedSeasonName = seasonName;
                                                    const decodedEpisodeName = episodeName;
                                                    const saison = decodedSeasonName;
                                                    const formattedEpisode = decodedEpisodeName.toString().padStart(2, '0');
                                                    episodeLinkElem.href = `details.php?manga=${encodeURIComponent(url)}&saison=${encodeURIComponent(saison)}&episode=${encodeURIComponent(formattedEpisode)}`;

                                                    episodeElem.appendChild(episodeLinkElem);
                                                    episodesListElem.appendChild(episodeElem);
                                                }
                                            }
                                        }

                                        seasonElem.appendChild(seasonTitleElem);
                                        seasonElem.appendChild(episodesListElem);
                                        episodesElem.appendChild(seasonElem);

                                    }
                                }
                            }

                            mangaElem.appendChild(titleElem);
                            mangaElem.appendChild(imageElem);
                            mangaElem.appendChild(descriptionElem);
                            mangaElem.appendChild(episodesElem);

                            // Recherche de l'URL de la vidéo correspondante
                            const urlParams = new URLSearchParams(window.location.search);
                            const requestedSaison = urlParams.get('saison');
                            const requestedEpisode = urlParams.get('episode');

                            if (requestedSaison && requestedEpisode) {
                                const decodedRequestedSaison = decodeURIComponent(requestedSaison);
                                const decodedRequestedEpisode = decodeURIComponent(requestedEpisode);

                                if (seasons.hasOwnProperty(decodedRequestedSaison)) {
                                    const season = seasons[decodedRequestedSaison];
                                    if (season.episode.hasOwnProperty(decodedRequestedEpisode)) {
                                        const episode = season.episode[decodedRequestedEpisode];
                                        const videoUrl = episode.url;

                                        const videoElem = document.createElement('video');

                                        videoElem.src = videoUrl;
                                        videoElem.controls = true;
                                        videoElem.autoplay = true;

                                        mangaElem.appendChild(videoElem);

                                        // Écouteur d'événement pour l'événement "ended" de la vidéo de l'épisode actuel
                                        videoElem.addEventListener('ended', () => {
                                            const episodeNumber = parseInt(decodedRequestedEpisode.split(' ')[1]);
                                            const seasonNumber = parseInt(decodedRequestedSaison.split(' ')[1]);

                                            const nextEpisodeNumber = episodeNumber + 1;
                                            const nextSeasonNumber = seasonNumber + 1;

                                            const nextEpisodeName = `episode ${nextEpisodeNumber}`;
                                            const nextSeasonName = `saison ${nextSeasonNumber}`;

                                            if (season.episode.hasOwnProperty(nextEpisodeName)) {
                                                const nextEpisode = season.episode[nextEpisodeName];
                                                const nextVideoUrl = nextEpisode.url;
                                                const nextUrl = `details.php?manga=${encodeURIComponent(url)}&saison=${encodeURIComponent(decodedRequestedSaison)}&episode=${encodeURIComponent(nextEpisodeName)}`;
                                                window.location.href = nextUrl;
                                            } else {
                                                const nextUrl = `details.php?manga=${encodeURIComponent(url)}&saison=${encodeURIComponent(nextSeasonName)}&episode=episode%201`;
                                                window.location.href = nextUrl;
                                            }
                                        });

                                    }

                                }

                            }

                            now.appendChild(mangaElem);

                        } else {
                            console.log("Aucun manga trouvé avec cette URL.");
                        }
                    })
                    .catch(error => {
                        console.log("Une erreur s'est produite lors de la récupération des données :", error);
                    });
            }

            getMangaByURL("<?= $_GET['manga'] ?>");
        </script>
    <?php
    }
    ?>
</body>

</html>