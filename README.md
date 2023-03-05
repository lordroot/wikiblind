# Générateur de vidéos Wikipedia - WikiBlind

Ce projet PHP est conçu pour créer automatiquement des vidéos basées sur les pages de Wikipedia. Le projet utilise les technologies CURL, DOMXPATH, ffmpeg, et l'API de texte en voix (text-to-speech) de PHP. Les vidéos sont générées en utilisant une capture d'écran de la page Wikipedia, ainsi qu'une bande son créée à partir du texte de la page.

# Configuration requise

    - PHP 5.6 ou supérieur
    - ffmpeg
    - IE Capt

# Installation

    Téléchargez le code source depuis Github.
    Installez les dépendances mentionnées ci-dessus.
    Configurez les détails de la base de données dans config.php.
    Lancez index.php à partir de votre navigateur.

# Comment ça marche

Le code source est divisé en trois sections principales, chacune responsable de la génération d'une partie différente de la vidéo finale.

# Générer l'image

Le premier bloc de code utilise CURL pour récupérer les données de la page Wikipedia la plus populaire qui n'a pas encore été téléchargée. Ensuite, le code appelle IE Capt pour capturer une image de la page. L'image est sauvegardée dans le dossier images pour une utilisation ultérieure.

# Générer l'audio

Le deuxième bloc de code utilise DOMXPATH pour extraire le texte de la page Wikipedia et le transformer en un script de texte à voix. Le script est ensuite lu à haute voix par l'API de texte en voix de PHP, et la sortie audio est sauvegardée dans le dossier sounds.

# Générer la vidéo

Le dernier bloc de code utilise ffmpeg pour combiner l'image et l'audio en une seule vidéo. Il crée un fichier vidéo MKV qui est sauvegardé dans le dossier videos.

# Conclusion

C'est tout ! Avec ce code source, vous pouvez générer des vidéos Wikipedia en un rien de temps. Si vous avez des questions ou des commentaires, n'hésitez pas à les poser en créant une issue sur Github.
