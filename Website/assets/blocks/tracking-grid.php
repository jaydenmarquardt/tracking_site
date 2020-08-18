<?php

$class = $atts["class"];
$title = $atts["title"];
$columns = $atts["columns"] ? $atts["columns"] : 3;
$list = $atts["list"];

?>
<div class="container <?php echo $class;?>">
    <?php if($title) :?><h1 class="title"><?php echo $title;?></h1><?php endif;?>
    <div class="grid grid-center">
        <?php
        foreach ($list as $blob){
            echo '<div class="col-1-'.$columns.'">';
            render_blob($blob[0], $blob[1],$blob[2],$blob[3], $blob[4]);
            echo '</div>';
        }
        ?>
    </div>
</div>
