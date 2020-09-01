<?php

global $page;
$report = get_report($_GET["id"]);

$settings = [];
$changed = [];
$browsers = [];
$os = [];

//if not done yet
$start_stats = unserialize($report["stats_start"]);
$end_stats = unserialize($report["stats_end"]);
foreach ($start_stats as $stat)
{
    if(!$stat["slug"])continue;
    $end = 0;
    foreach ($end_stats as $end_stat)
    {
        if($stat["slug"] == $end_stat["slug"])
        {
            $end = $end_stat;
            break;
        }
    }

    if(substr( $stat["slug"], 0, 8 ) === "location" ||
        substr( $stat["slug"], 0, 7 ) === "user-ip" ||
        substr( $stat["slug"], 0, 4 ) === "date"
    )continue;
    $setting = [
        "slug" => $stat["slug"],
        "title" => $stat["title"],
        "before" => intval($stat["count"]),
        "after" => intval($end["count"]),
        "change" => intval($end["count"])-intval($stat["count"]),
    ];;
    if($setting["change"])
    {
        $changed[$stat["slug"]] = $setting;

    }
    if(substr( $stat["slug"], 0, 7 ) === "browser"){
        $browsers[$stat["slug"]] = $setting;

    }
    if(substr( $stat["slug"], 0, 2 ) === "os"){
        $os[$stat["slug"]] = $setting;

    }
    $settings[$stat["slug"]] = $setting;

}
unset( $settings["active"] );

//loop through before and after and merge
/**
Report page Hero
 **/
ob_start();
?>
<h1>Your Report</h1>
<p>
    <a class="button" href="/home"> Home </a>
</p>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "short", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);


/**
Welcome reports Banner
 **/
ob_start(); ?>
<h1>Welcome to your report</h1><p>Below is your report details</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-calendar", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);



/**
Report sections
 **/
?>
<div class="container block-margin">
    <div  class="grid">

        <?php foreach ($settings as $setting):
            ?>
            <div class="col-1-2">
                <div class="textbox" style="display: flex;justify-content: space-between;align-content: center">
                    <div style='float:left;font-size:30px;'> <i class="<?php echo $setting["icon"]; ?>"></i><?php echo $setting["title"]; ?>: </div>

                    <span><?php echo $setting["before"]; ?> to <?php echo $setting["after"]; ?>  </span>
                    <div style='float:right;font-size:30px;'><?php echo $setting["change"] == "0" ? "<span style='color:lightblue;'> 0</span>" : ($setting["change"] > 0 ? " <span style='color:lightgreen;'> +{$setting["change"]}</span>" : " <span style='color:indianred;'> -$setting[change]</span>"); ?></div>

                </div>
            </div>

        <?php endforeach; ?>

    </div>
   <div class="grid">
       <div class="col-1-3">

            <div class="block chart"> <h3>Changes</h3><canvas id="stats_bar" width="400" height="400"></canvas></div>
       </div>
       <div class="col-1-3">

           <div class="block chart"> <h3>Browser</h3> <canvas id="browser_bar" width="400" height="400"></canvas></div>
       </div>
       <div class="col-1-3">

           <div class="block chart"> <h3>OS</h3> <canvas id="os_bar" width="400" height="400"></canvas></div>
       </div>

   </div>

</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" />
<script  src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script defer>

    <?php

    $stats_bar_labels = [];
    $browser_labels = [];
    $os_labels = [];
    $stats_bar_values = [];
    $browser_values = [];
    $os_values = [];
    $stats_bar_colours = [];
    $browser_colours = [];
    $os_colours = [];
    $index = 0;
    foreach ($changed as $change){
        $stats_bar_labels[] = "'".$change["title"]."'";
        $stats_bar_values[] = $change["change"];
        $stats_bar_colours[] =  "'".$GLOBALS["colors"][$index]."'";
        $index++;if($index > 3)$index = 0;
    }
    foreach ($browsers as $stat){
        $browser_labels[] = "'".$stat["title"]."'";
        $browser_values[] = $stat["change"];
        $browser_colours[] =  "'".$GLOBALS["colors"][$index]."'";
        $index++;if($index > 3)$index = 0;
    }
    foreach ($os as $stat){
        $os_labels[] = "'".$stat["title"]."'";
        $os_values[] = $stat["change"];
        $os_colours[] =  "'".$GLOBALS["colors"][$index]."'";
        $index++;if($index > 3)$index = 0;
    }

    $stats_bar_labels = implode(",", $stats_bar_labels);
    $stats_bar_values = implode(",", $stats_bar_values);
    $stats_bar_colours = implode(",", $stats_bar_colours);

    $browser_labels = implode(",", $browser_labels);
    $browser_values = implode(",", $browser_values);
    $browser_colours = implode(",", $browser_colours);

    $os_labels = implode(",", $os_labels);
    $os_values = implode(",", $os_values);
    $os_colours = implode(",", $os_colours);

    ?>
    var ctx_stats_bar = document.getElementById('stats_bar').getContext('2d');
    var ctx_browsers = document.getElementById('browser_bar').getContext('2d');
    var ctx_os = document.getElementById('os_bar').getContext('2d');

    var myBarChart = new Chart(ctx_stats_bar, {
        type: 'bar',
        data:  {
            labels: [<?php echo $stats_bar_labels;?>],
            datasets: [{
                label: '# of changes',
                data: [<?php echo $stats_bar_values;?>],
                backgroundColor: [<?php echo $stats_bar_colours;?>],
            }],

        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });


    var browsers = new Chart(ctx_browsers, {
        type: 'pie',
        data:  {
            labels: [<?php echo $browser_labels;?>],
            datasets: [{
                label: '# browsers',
                data: [<?php echo $browser_values;?>],
                backgroundColor: [<?php echo $browser_colours;?>],
            }],

        },
        options: {
        }
    });
    var oss = new Chart(ctx_os, {
        type: 'doughnut',
        data:  {
            labels: [<?php echo $os_labels;?>],
            datasets: [{
                label: '# Operating Systems',
                data: [<?php echo $os_values;?>],
                backgroundColor: [<?php echo $os_colours;?>],
            }],

        },
        options: {
        }
    });
</script>
