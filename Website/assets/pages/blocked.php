<?php

/**
Blocked page Hero
 **/
ob_start();
?>
<h1>Blocked From Website</h1>
<p>You have tried to login more than the allowed number of times.</p>
<p>If there is a mistake please contact administrator</p>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "big", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);

?>

