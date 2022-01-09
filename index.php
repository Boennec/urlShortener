<!-- Is sending a form -->
<?php
//vérifier si le champ est saisi, créer une variable

use Symfony\Component\Mime\Message;

if (isset($_POST['url'])) {

    $url = $_POST['url'];

    //verification de l'url saisie et le filtre doit valider l'url
    //Ici : si l'url n'est pas valide (!filter_var) on redirige avec un header
    if (!filter_var($url, FILTER_VALIDATE_URL)) {

        //si ce n'est pas un lien,on redirige vers la page d'accueil avec un header('location')
        header('location: /udemy/urlShortener?error=true&Message=Adresse url non valide');
        //en parametre : on met error=true pour détecter qu'il y a une erreur; et un message a afficher
        //on met un exit() pour stopper le script après une redirection


    }
    //créer un shortcut de l'url avec la fonction crypt() prend en paramètre le texte a encrypter (ici l'url)
    //et un salt (ou grain de sel) une particule que crypt() va coupler a  l'url afin d'avoir un hash
    // totalement unique : ici le rand() sera un nombre aléatoire
    $shortcut = crypt($url, rand());

    //vérifier si l'url a déjà été raccourcie "has already been sent"
    //On se connecte a la bdd
    $bdd = new PDO('mysql:host=localhost;dbname=urlshortener;charset=utf8', 'root', '');
    //on fait un requete pour voir les fois ou l'url a déjà été utilisée
    //avec COUNT en sql, ca compte le nb de fois que l'évênement est arrivé 
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    //tant que $result trouve a chaque fois une ligne de la requete
    while ($result = $req->fetch()) {

        if ($result['x'] != 0) {
            header('location: /?error=true&message=Adresse déjà raccourcie');
            exit();
        }
    }
    //envoyer l'adresse url et l'adresse raccourcie dans la bdd
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
    $req->execute(array($url, $shortcut));

    header('location: /udemy/urlShortener?short=' . $shortcut);
    exit();
}
//afficher la valeur raccourcie sous la barre de saisie :


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>UrlShortener</title>
    <link rel="stylesheet" href="/udemy/urlShortener/design/default.css">
    <link rel="icon" type="image/png" href="pictures/favico.png">
</head>

<body>

    <section id="hello">
        <div class="container">

            <header>

                <img src="pictures/logo.png" alt="logo" id="logo">

            </header>
            <h1>Une url longue ? Raccourcissez-là</h1>
            <h2>Largement meilleure et plus courte que les autres</h2>
            <form method="POST" action="index.php">
                <input type="url" name="url" placeholder="collez ici votre lien à raccourcir">
                <input type="submit" value="raccourcir">
            </form>

            <?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>
                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET['message']);  ?><b />
                    </div>
                </div>

            <?php } else if (isset($_GET['short'])) {
            ?>
                <div class="center">
                    <div id="result">
                        <b>URL Raccourcie :</b>
                        http://localhost/q=<?php echo htmlspecialchars($_GET['short']); ?>
                    </div>
                </div>
            <?php } ?>


        </div>
    </section>

    <section id="brands">
        <div class="container">

            <h3>Ces marques nous font confiance</h3>
            <img src="pictures/1.png" alt="1" class="picture">
            <img src="pictures/2.png" alt="2" class="picture">
            <img src="pictures/3.png" alt="3" class="picture">
            <img src="pictures/4.png" alt="4" class="picture">

        </div>
    </section>

    <footer id="footer">

        <img src="pictures/logo2.png" alt="logo" id="logo"><br>
        2022 ©<br>
        <a href="">Contact</a> - <a href="#">A propos</a>


    </footer>
</body>

</html>