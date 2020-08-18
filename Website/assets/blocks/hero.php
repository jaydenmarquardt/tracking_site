<?php

$class = $atts["class"];
$image = $atts["image"];
$content = $atts["content"];

?>
<div class="hero <?php echo $class;?>" style="background-image: url(<?php echo $image;?>)">
    <div class="hero-overlay">
        <div class="inner">
            <?php echo $content;?>
        </div>
    </div>

</div>
