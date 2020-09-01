<?php

global $page;

/**
Manages form login error messages
 **/
if(array_key_exists("login_failed", $_GET))
{
    $error = "An error has occurred.";
    switch ($_GET["login_failed"]){

        case 0:
            $error = "Username/Email and/or password was incorrect.";
            break;
        case 1:
            $error = "You must be logged in to access that page.";
            break;
        case 2:
            $error = "You fill-out both username and password.";
            break;
    }
}

/**
Manages register form error messages
 **/
if(array_key_exists("register_failed", $_GET))
{
    $errorRegister = "An error has occurred.";
    switch ($_GET["register_failed"]){
        case 0:
            $errorRegister = "An error has occurred.";
            break;
        case 1:
            $errorRegister = "Email is not valid";
            break;
        case 2:
            $errorRegister = "You must fill-out all the fields to create account.";
            break;
        case 3:
            $errorRegister = "Username already exists, please try a new account or login";
            break;
        case 4:
            $errorRegister = "Email already exists, please try a new account or login";
            break;
        case 5:
            $errorRegister = "The provided passwords do not match.";
            break;
        case 6:
            $errorRegister = "The provided emails do not match.";
            break;
        case 7:
            $errorRegister = "You must be over the age of 13 to register to this site.";
            break;
    }
}


/**
Login page Hero
 **/
ob_start(); ?>
<h1>Login</h1>
<?php
$hero_content = ob_get_clean();
do_block("hero", ["class" => "short", "content" => $hero_content, "image" => "/assets/images/home.jpg"]);

/**
User Login Banner
 **/
ob_start(); ?>
<h1>Welcome back</h1><p>Please enter your login details</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-shield", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
User Login Form
 **/
?>
<div class="container block-margin">
    <div class="forms">
        <?php if($error):?><div class="error"><?php echo $error;?></div><?php endif;?>
        <h1 class="title">Login</h1>

        <form method="post" action="/login">
            <input type="hidden" name="secret" value="<?php ;?>"/>

            <div class="textbox">
                <i class="far fa-user-shield"></i>
                <input type="text" placeholder="Enter your Username/Email" name="username" />
            </div>
            <div class="textbox">
                <i class="far fa-shield"></i>
                <input type="password" placeholder="Enter your password" name="password" />
            </div>

            <input type="submit" name="login" value="Login"/>
            <a class="button button-secondary" href="#register"> Dont have an account? </a>
        </form>
    </div>
</div>
<?php
/**
User Register Banner
 **/
ob_start(); ?>
<h1>Dont have an account yet?</h1><p>Join the crew</p>
<?php $banner_content_left = ob_get_clean(); ob_start(); ?>
<h1 class="tracker"><?php echo get_tracking("users");?></h1>
<?php $banner_content_right = ob_get_clean();
do_block("banner", ["class" => "block-margin-bottom", "icon" => "far fa-users", "content_left" => $banner_content_left, "content_right" => $banner_content_right]);

/**
User Register Form
 **/
?>
<div id="register" class="container block-margin">
    <div class="forms">
        <h1 class="title">Register</h1>
        <div class="msg">*You must be over the age of 13 to register to this site.</div>

        <?php if($errorRegister):?><div class="error"><?php echo $errorRegister;?></div><?php endif;?>
        <form method="post" action="/login#register">
            <input type="hidden" name="secret" value="<?php ;?>"/>

            <div class="textbox">
                <i class="far fa-user-shield"></i>
                <input type="text" placeholder="Enter a Username" name="username" value="<?php echo $_POST["username"];?>"/>
            </div>
            <div class="textbox">
                <i class="far fa-envelope"></i>
                <input type="email" placeholder="Enter your Email" name="email" value="<?php echo $_POST["email"];?>"/>
            </div>
            <div class="textbox">
                <i class="fas fa-check-square"></i>
                <input type="email" placeholder="Confirm your Email" name="email_confirm" value="<?php echo $_POST["email_confirm"];?>"/>
            </div>
            <hr>
            <div class="textbox">
                <i class="far fa-shield"></i>
                <input type="password" placeholder="Enter your password" name="password" />
            </div>
            <div class="textbox">
                <i class="far fa-shield-alt"></i>
                <input type="password" placeholder="Confirm your password" name="password_confirm" />
            </div>
            <input type="submit" name="register" value="Register"/>

        </form>
    </div>
</div>
