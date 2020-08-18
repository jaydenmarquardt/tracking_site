<?php

/**
404 page Hero
 **/
ob_start();
?>
<h1>404 Page</h1>
<h2>Page Not Found</h2>
<p>
    <a class="button" href="/home"> Go Home </a>
</p>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "big", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);

?>
