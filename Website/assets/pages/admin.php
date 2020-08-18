<?php

global $page;

$message_board = get_meta("dashboard");
$first_name = get_user()["first_name"];
$last_name = get_user()["last_name"];
$email = get_user()["email"];
$dob = get_user()["dob"];

/**
List of tracking settings allowed to modify
 **/
$settings = [
    ["Active now", "active", "fas fa-plug", get_tracking("active")],
    ["Daily Viewers", "daily", "fas fa-calendar-check", get_tracking("daily")],
    ["Locations", "locations", "fas fa-globe", get_tracking("locations")],
    ["Registered Users", "users", "fas fa-users", get_tracking("users")],
    ["Computers", "device_computer", "fas fa-desktop", get_tracking("device_computer")],
    ["Safari", "browser_safari", "fab fa-safari", get_tracking("browser_safari")],
    ["Google Chrome", "browser_chrome", "fab fa-chrome", get_tracking("browser_chrome")],
    ["Firefox", "browser_firefox", "fab fa-firefox", get_tracking("browser_firefox")],
    ["Other", "browser_other", "fab fa-internet-explorer", get_tracking("browser_other")],
    ["Mac Os", "os_mac", "fab fa-apple", get_tracking("os_mac")],
    ["Windows", "os_windows", "fab fa-windows", get_tracking("os_windows")],
    ["Linux", "os_linux", "fab fa-linux", get_tracking("os_linux")],
    ["iOS", "os_ios", "fab fa-app-store-ios", get_tracking("os_ios")],
    ["Android", "os_android", "fab fa-android", get_tracking("os_android")],
    ["Other OS", "os_other", "fab fa-ubuntu", get_tracking("os_other")],
];

/**
Manages form error messages
 **/
if (array_key_exists("admin_failed", $_GET)) {
    switch ($_GET["admin_failed"]) {
        case 0:
            $error = "Tracking update failed.";
            break;
        case 1:
            $error_message_board = "No swear words allowed on the notice board.";
            break;
        case 4:
            $errorUser = "You must fill-out all the required fields to update account.";
            break;
        case 3:
            $errorUser = "Email is not valid";
            break;
        case 8:
            $errorUser = "Email already exists, please try a new account or login";
            break;
        case 5:
            $errorUser = "The provided passwords do not match.";
            break;
        case 7:
            $errorUser = "You must be over the age of 13 to use this site.";
            break;
    }

}

/**
Manages form update messages
 **/
if (array_key_exists("updated", $_GET)) {
    switch ($_GET["updated"]) {

        case 1:
            $message = "The tracking points have been updated.";

            break;
        case 2:
            $message_board_msg = "The message board has been updated.";

            break;
        case 3:
            $message_user = "Your user information has been updated.";

            break;
        case 4:
            $message_user = "Your user information and password has been updated.";

            break;
    }
}


/**
Admin page Hero
 **/
ob_start();
?>
<h1>Admin</h1>
<p>
    <a class="button" href="/home"> Home </a>
    <a class="button button-secondary" href="/logout"> Logout </a>
</p>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "short", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);


/**
Welcome backend Banner
 **/
ob_start(); ?>
<h1>Welcome to the back end</h1><p>From here you can change all stats.</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-cog", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
Tracker settings form
 **/
?>
<div class="container block-margin">
    <div class="forms">
        <h2>Select a tracking field to update.</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($message): ?>
            <div class="msg"><?php echo $message; ?></div><?php endif; ?>
        <form method="post" action="/admin" class="grid">
            <input type="hidden" name="secret" value="<?php ; ?>"/>

            <?php foreach ($settings as $setting):
                ?>
                <div class="col-1-3">
                    <div class="textbox">
                        <i class="<?php echo $setting[2]; ?>"></i> <label
                                for="<?php echo $setting[1]; ?>"><?php echo $setting[0]; ?>: </label>
                        <input type="text" name="<?php echo $setting[1]; ?>" id="<?php echo $setting[1]; ?>"
                               value="<?php echo $setting[3]; ?>"/>
                    </div>
                </div>

            <?php endforeach; ?>

            <input type="submit" name="update" value="Update"/>


        </form>
    </div>
</div>
<?php
/**
Message board Banner
 **/
ob_start(); ?>
<h1>Add to the message board</h1><p>This is a message for everyone to see!</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-chalkboard", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
Message board form
 **/
?>
<div class="container block-margin">
    <div class="forms">
        <h2>Choose a message for the world to see.</h2>
        <div class="msg">*Swear words will not be accepted.</div>
        <?php if ($error_message_board): ?>
            <div class="error"><?php echo $error_message_board; ?></div><?php endif; ?>
        <?php if ($message_board_msg): ?>
            <div class="msg"><?php echo $message_board_msg; ?></div><?php endif; ?>
        <form method="post" action="/admin" class="">
            <input type="hidden" name="secret" value="<?php ; ?>"/>

            <div class="textbox">
                <i class="fas fa-chalkboard"></i> <label for="message_board_message">Message: </label>
                <input type="text" name="message_board_message" id="message_board_message"
                       value="<?php echo $message_board; ?>"/>
            </div>

            <input type="submit" name="message_board" value="Update"/>


        </form>
    </div>
</div>
<?php
/**
User Profile Banner
 **/
ob_start(); ?>
<h1>User Profile</h1><p>Edit your user details here</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<a class="button red" onclick="confirm('Are you sure you want to remove your account?')" href="/admin?remove_account=true">Remove Account</a>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-user", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
User profile form
 **/
?>
<div class="container block-margin">
    <div class="forms">
        <h2>Select a tracking field to update.</h2>
        <div class="msg">*Leave password fields empty to leave the same</div>
        <?php if ($errorUser): ?>
            <div class="error"><?php echo $errorUser; ?></div><?php endif; ?>
        <?php if ($message_user): ?>
            <div class="msg"><?php echo $message_user; ?></div><?php endif; ?>
        <form method="post" action="/admin" class="">
            <input type="hidden" name="secret" value="<?php ; ?>"/>

            <div class="textbox">
                <i class="far fa-user"></i> <label for="first_name">First Name: </label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>"/>
            </div>
            <div class="textbox">
                <i class="far fa-users"></i> <label for="last_name">Last Name: </label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>"/>
            </div>
            <div class="textbox">
                <i class="far fa-envelope"></i> <label for="email">Email: </label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>"/>
            </div>
            <div class="textbox">
                <i class="far fa-calendar-alt"></i>
                <input type="date" name="dob" value="<?php echo $dob; ?>"/>
            </div>
            <div class="textbox">
                <i class="far fa-shield"></i> <label for="password">New Password: </label>
                <input type="password" name="password" id="password" value=""/>
            </div>
            <div class="textbox">
                <i class="far fa-shield-alt"></i> <label for="password_confirm">Confirm New Password: </label>
                <input type="password" name="password_confirm" id="password_confirm" value=""/>
            </div>
            <input type="submit" name="update_user" value="Update"/>


        </form>
    </div>
</div>


