<!-- Is sending a form -->
<?php



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

<!-- <table>
            <tr>
                <td>Prenom: </td>
                <td><input type="text" name="prenom_users">
                    <td />
            </tr>
            <tr>
                <td>Nom: </td>
                <td><input type="text" name="nom_users">
                    <td />
            </tr> -->