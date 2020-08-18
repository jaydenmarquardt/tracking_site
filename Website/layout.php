<?php

/**
 * This is the layout for all pages on the site  *
 */

global $page;


?>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="unisite" value="notranslate">
        <meta name="referrer" content="strict-origin">

        <title><?php echo $page["title"];?></title>

        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="/assets/css/style.css">

    </head>

    <body>

        <header> </header>

        <main>

            <article>
                    <?php echo do_content($page["content"]);?>

            </article>

        </main>


        <footer class="block-margin-top">

            <h2>This website was made for a uni assignment</h2>
            <p>Created by Jayden Marquardt (U3175679)</p>

        </footer>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="/assets/js/tracking-live.js"> </script>

    </body>

</html>