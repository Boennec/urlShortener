<?php

use phpDocumentor\Reflection\Location;

//Has received shortcut
if (isset($_GET['q'])) {

    //on reccupere la variable shortcut
    $shortcut = htmlspecialchars($_GET['q']);

    //vérifier si c'est bien un raccourci qu'on a généré
    $bdd = new PDO('mysql:host=localhost;dbname=urlshortener;charset=utf8', 'root', '');
    $req = $bdd->prepare('SELECT COUNT (*) AS x FROM links WHERE shortcut = ?');
    //on a le nb de fois ou le shortcut existe dans la bdd avec:
    $req->execute(array($shortcut)); //si on a '0' c'estr un faux shortcut, il faut que ce soit '1'

    //tant qu'on a une nouvelle ligne , affiche-là dans $result avec $req->fetch() :
    while ($result = $req->fetch()) {
        if ($result['x'] != 1) {
            //si erreur, on fait un location vers la page d'accueil
            header('location:www/udemy/urlShortener/?error=true&message=Adresse url non connue');
            exit();
        }
    }
    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while ($result = $req->fetch()) {

        header('Location:' . $result['url']);
        exit();
    }
}
//Redirection

//Is sending a form 
//Vérifier si l'url a été envoyé
if (isset($_POST['url'])) {
    //$url recoit la valeur du champ url
    $url = $_POST['url'];

    //verification de l'url saisie et le filtre doit valider l'url
    //Ici : si l'url n'est pas valide (!filter_var) on redirige avec un header
    if (!filter_var($url, FILTER_VALIDATE_URL)) {

        //si ce n'est pas un lien,on redirige vers la page d'accueil avec un header('location')
        header('location: www/udemy/urlShortener?error=true&Message=Adresse url non valide');
        //en parametre : on met error=true pour détecter qu'il y a une erreur; et un message a afficher
        //on met un exit() pour stopper le script après une redirection
        exit();
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
    //on compte si le nombre de fois est différent de 0, donc a déja été utilisée, on affichera une erreur 
    //comme quoi l'adresse url a déjà été utilisée
    //on renomme avec x le nombre d'occurences trouvées
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    //tant que on trouve a chaque fois une ligne de la requete on la met dans $result
    while ($result = $req->fetch()) {
        //si différent de 0, donc déjà utilisé, on renvoit vers la page en cours avec error a true et le message 
        if ($result['x'] != 0) {
            header('location: www/udemy/urlShortener?error=true&message=Adresse déjà raccourcie');
            exit();
        }
    }
    //envoyer l'adresse url et l'adresse raccourcie dans la bdd
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
    $req->execute(array($url, $shortcut));

    header('location: www/udemy/urlShortener?short=' . $shortcut);
    exit();
}
//afficher la valeur raccourcie sous la barre de saisie :
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>UrlShortener</title>
    <link rel="stylesheet" type="text/css" href="/udemy/urlShortener/design/default.css">
    <link rel="icon" type="image/png" href="pictures/favico.png">
</head>

<body>

    <section id="hello">
        <div class="container">

            <header>

<!--                 <img src="pictures/logo.png" alt="logo" id="logo">
 -->
            </header>
            <h1>Une url longue ? Raccourcissez-là</h1>
            <h2>Largement meilleure et plus courte que les autres</h2>
            <form method="POST" action="/udemy/urlShortener/index.php">
                <input type="url" name="url" placeholder="collez ici votre lien à raccourcir">
                <input type="submit" value="raccourcir">
            </form>

            <!-- Si on a une error a true, et un message -->
            <?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>
                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET['message']);  ?><b />
                    </div>
                </div>
                <!-- Afficher l'url raccourcie sous la barre de champ 
Sinon si il existe la valeur $_GET'short'
On ajoute le lien complet avec localhost// devant-->
            <?php } else if (isset($_GET['short'])) {
            ?>
                <div class="center">
                    <div id="result">
                        <b>URL Raccourcie : </b>
                        http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?>
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

<!--         <img src="pictures/logo2.png" alt="logo" id="logo"><br>
 -->        2022 ©<br>
        <a href="">Contact</a> - <a href="#">A propos</a>


    </footer>
</body>

</html>