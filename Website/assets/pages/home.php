<?php

global $page;
global $browser_detail;
global $location;
global $user_ip;
$user_ip = slugify($user_ip);
$username = get_username();

$other = [
    ["Active now", "This is how many users are currently browsing the site.", "fas fa-plug", "active", get_tracking("active")],
    ["Locations", "This is how many unique locations users have visited from.", "fas fa-globe", "locations", count(get_locations())],
    ["This is the average daily visit count.", "sss", "fas fa-calendar-check", "daily", get_tracking("daily")],
    ["Registered Users", "This is how many users are registered on the site.", "fas fa-users", "users", count(get_users())],

];
$devices = [
    ["Apple Devices", "This is how many users are currently browsing the site.", "fab fa-apple", "os_ios", get_tracking("os_ios")],
    ["Computers", "This is how many unique locations users have visited from.", "fas fa-desktop", "device_computer", get_tracking("device_computer")],
    ["Android / Other", "This is the average daily visit count.", "fab fa-android", "os_other", get_tracking("os_other")],
];
$browsers = [
    ["Safari", "sss", "fab fa-safari", "browser_safari", get_tracking("browser_safari")],
    ["Google Chrome", "sss", "fab fa-chrome", "browser_chrome", get_tracking("browser_chrome")],
    ["Firefox", "sss", "fab fa-firefox", "browser_firefox", get_tracking("browser_firefox")],
    ["Other", "sss", "fab fa-internet-explorer", "browser_other", get_tracking("browser_other")],

];
$os = [
    ["Mac Os", "sss", "fab fa-apple", "os_mac", get_tracking("os_mac")],
    ["Windows", "sss", "fab fa-windows", "os_windows", get_tracking("os_windows")],
    ["Linux", "sss", "fab fa-linux", "os_linux", get_tracking("os_linux")],
    ["iOS", "sss", "fab fa-app-store-ios", "os_ios", get_tracking("os_ios")],
    ["Android", "sss", "fab fa-android", "os_android", get_tracking("os_android")],
    ["Other OS", "sss", "fab fa-ubuntu", "os_other", get_tracking("os_other")],

];



/**
Home page Hero
 **/
ob_start();
?>
<h1>University Of Canberra</h1>
<h2>Website Tracker - by Jayden Marquardt</h2>
<h2>Location: <?php echo $location; ?></h2>
<?php if (is_logged_in()): ?>
<h2>Username: <?php echo $username; ?></h2>

<p>
    <a class="button" href="/admin"> Admin </a>
    <a class="button button-secondary" href="/logout"> Logout </a>
</p>

<?php else: ?>
<p>
    <a class="button" href="/login"> Login </a>
    <a class="button button-secondary" href="/login#register"> Register </a>
</p>
<?php endif; ?>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);


/**
User message board Banner
 **/
ob_start(); ?>
<h1>User message board</h1> <p data-tracker="dashboard"><?php echo get_meta("dashboard"); ?></p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "fas fa-chalkboard", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
Other Tracking Grid
 **/
do_block("tracking-grid", ["class" => "block-margin", "title" => "", "columns" => 2, "list" => $other]);

/**
Total Visits Banner
 **/
ob_start(); ?>
<h1>Site Visits</h1><p>This is how many times the site has been visited.</p>
<?php $banner_content_left = ob_get_clean();
ob_start(); ?>
<h1 class="tracker" data-tracker="total"><?php echo get_tracking("total"); ?></h1>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin", "icon" => "far fa-eye", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
Device Tracking Grid
 **/
do_block("tracking-grid", ["class" => "block-margin", "title" => "Devices", "columns" => 3, "list" => $devices]);

/**
Your Visits Banner
 **/
ob_start(); ?>
<h1>Your Visits</h1><p>This is how many times you have visited the site.</p>
<?php $banner_content_left = ob_get_clean();
ob_start(); ?>
<h1 class="tracker"
    data-tracker="<?php echo "user_ip_" . $user_ip; ?>"><?php echo get_tracking(slugify("user_ip_" . $user_ip)); ?></h1>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin", "icon" => "fas fa-child", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
Browser Tracking Grid
 **/
do_block("tracking-grid", ["class" => "block-margin", "title" => "Browsers", "columns" => 4, "list" => $browsers]);

/**
Unique Visits Banner
 **/
ob_start(); ?>
<h1>Unique Visits</h1><p>This is how many Different devices have visited.</p>
<?php $banner_content_left = ob_get_clean();
ob_start(); ?>
<h1 class="tracker" data-tracker="unique_users"><?php echo count(get_ips()); ?></h1>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin", "icon" => "far fa-eye", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
OS Tracking Grid
 **/
do_block("tracking-grid", ["class" => "block-margin", "title" => "Operating Systems", "columns" => 3, "list" => $os]);



?>



