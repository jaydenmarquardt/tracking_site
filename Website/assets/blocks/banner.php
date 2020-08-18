<?php

$class = $atts["class"];
$icon = $atts["icon"];
$content_left = $atts["content_left"];
$content_right = $atts["content_right"];

?>
<div class="banner <?php echo $class;?>">
    <div class="container">
        <div class="grid">

            <div class="col-1-4">

                <i class="big-icon <?php echo $icon;?>"></i>

            </div>
            <div class="col-1-4">
                <?php echo $content_left;?>

            </div>

            <div class="col-2-4">

                <?php echo $content_right;?>

            </div>

        </div>
    </div>

</div>
