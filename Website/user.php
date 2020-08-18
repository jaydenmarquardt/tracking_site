<?php

/**
 * This returns if a user is logged in  *
 */
function is_logged_in()
{

    return get_username() != null;
}

/**
 * This returns the user data  *
 */
function get_user()
{

    return $_SESSION['user'];
}

/**
 * This returns the users name  *
 */
function get_username()
{

    return $_SESSION['username'];
}

/**
 * This returns if a username exists  *
 */
function username_exists($username)
{
    return Database::select("*", "logins", "(lower(username) = '$username')") != 0;
}

/**
 * This returns if a email exists  *
 */
function email_exists($email)
{
    return Database::select("*", "logins", "(lower(email) = '$email')") != 0;
}

/**
 * This updates the user profile  *
 */
function update_user()
{
    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        $_GET["admin_failed"] = 3;
        return false;

    }
    $password =  filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $password_confirm =  filter_var($_POST["password_confirm"], FILTER_SANITIZE_STRING);
    $email =  filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $first_name =  filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);
    $last_name =  filter_var($_POST["last_name"], FILTER_SANITIZE_STRING);
    $dob =  filter_var($_POST["dob"], FILTER_SANITIZE_STRING);
    $ID = get_user()["ID"];
    if(empty($email) || empty($first_name) || empty($last_name) || empty($dob))
    {
        $_GET["admin_failed"] = 4;
        return false;
    }




    $date = new DateTime(($dob));
    $now = new DateTime();
    $age = $now->diff($date)->y;
    if($age < 13)
    {
        $_GET["admin_failed"] = 7;
        return false;
    }

    $email = mysqli_real_escape_string(Database::get(), $email);
    $first_name = mysqli_real_escape_string(Database::get(), $first_name);
    $last_name = mysqli_real_escape_string(Database::get(),$last_name);
    $dob = mysqli_real_escape_string(Database::get(), $dob);


    if(email_exists($email) && get_user()["email"] != $email)
    {
        $_GET["admin_failed"] = 8;
        return false;
    }
    $_GET["updated"] = 3;

    if(!empty($password) && !empty($password_confirm))
    {
        if($password_confirm != $password)
        {
            $_GET["admin_failed"] = 5;
            unset( $_GET["updated"]);
            return false;

        }

        $password = mysqli_real_escape_string(Database::get(), $password);
        $passwordEncrypted = md5($password);
        Database::update("logins", "password", "'$passwordEncrypted'", "ID = $ID");
        $_GET["updated"] = 4;

    }

    Database::update("logins", "email", "'$email'", "ID = $ID");
    Database::update("logins", "first_name", "'$first_name'", "ID = $ID");
    Database::update("logins", "last_name", "'$last_name'", "ID = $ID");
    Database::update("logins", "dob", "'$dob'", "ID = $ID");
    return true;


}

/**
 * This returns login attempts  *
 */
function get_login_attempts()
{
    $ip = get_ip();

    $profile = Database::select("*", "login_attempts", "ip = '$ip'");
    return $profile ? $profile[0]["attempts"] : 0;
}

/**
 * This registers the user  *
 */
function register()
{
    $page = get_page_by_slug("login");
    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        return 1;//Email not valid
    }
    $username =  filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password =  filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $password_confirm =  filter_var($_POST["password_confirm"], FILTER_SANITIZE_STRING);
    $email =  filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $email_confirm =  filter_var($_POST["email_confirm"], FILTER_SANITIZE_EMAIL);
    $first_name =  filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);
    $last_name =  filter_var($_POST["last_name"], FILTER_SANITIZE_STRING);
    $dob =  filter_var($_POST["dob"], FILTER_SANITIZE_STRING);

    if(empty($username) || empty($password) || empty($email) || empty($first_name) || empty($last_name) || empty($dob))
    {
        $_GET["register_failed"] = 2;
        return $page;
    }
    if($password_confirm != $password)
    {
        $_GET["register_failed"] = 5;
        return $page;
    }
    if($email_confirm != $email)
    {
        $_GET["register_failed"] = 6;
        return $page;
    }
    $date = new DateTime(($dob));
    $now = new DateTime();
    $age = $now->diff($date)->y;
    if($age < 13)
    {
        $_GET["register_failed"] = 7;
        return $page;
    }

    if($email_confirm != $email)
    {
        $_GET["register_failed"] = 6;
        return $page;
    }

    $username = mysqli_real_escape_string(Database::get(), $username);
    $email = mysqli_real_escape_string(Database::get(), $email);
    $password = mysqli_real_escape_string(Database::get(), $password);
    $first_name = mysqli_real_escape_string(Database::get(), $first_name);
    $last_name = mysqli_real_escape_string(Database::get(),$last_name);
    $dob = mysqli_real_escape_string(Database::get(), $dob);

    if(username_exists($username))
    {
        $_GET["register_failed"] = 3;
        return $page;
    }
    if(email_exists($email))
    {
        $_GET["register_failed"] = 4;
        return $page;
    }
    $id = count(Database::select("*", "logins"));
    $passwordEncrypted = md5($password);

    Database::insert("logins", "ID, username, email, password, first_name, last_name, dob", "$id, '$username', '$email', '$passwordEncrypted', '$first_name', '$last_name', '$dob'");


    return login($username, $password);

}

/**
 * This logs the user out  *
 */
function logout(){
    session_destroy();
    //change db login status
}

/**
 * This logs the user in  *
 */
function login($username = 0, $password = 0)
{
    $page = get_page_by_slug("login");

    $username = mysqli_real_escape_string(Database::get(), $username ? $username : $_POST["username"]);
    $password = mysqli_real_escape_string(Database::get(), $password ? $password : $_POST["password"]);
    $password = md5($password);

    if(empty($username) || empty($password))
    {
        $_GET["login_failed"] = 2;
        return $page;
    }

    $email = filter_var($username, FILTER_VALIDATE_EMAIL);

    $user = authorise($username, $password, $email);
    if(is_int($user))
    {
        $_GET["login_failed"] = $user;
        return $page;
    }
    unset($user["password"]);
    $_SESSION['user_id'] = $user["ID"];
    $_SESSION['username'] = $user["username"];
    $_SESSION['user'] = $user;


    return get_page_by_slug("admin");
}

/**
 * This handles the login auth  *
 */
function authorise($username, $password, $email)
{
    $username = strtolower($username);
    if($email)
    {
        $user = Database::select("*", "logins", "(lower(email) = '$username') AND password = '$password'");
    }else{
        $user = Database::select("*", "logins", "(lower(username) = '$username') AND password = '$password'");
    }
    if(is_array($user) && count($user)==1)
    {

        clear_login_attempts();
        return $user[0];

    }else{
        $ip = get_ip();
        $attempts = get_login_attempts();

        if(!$attempts){
            $attempts = 1;
            Database::insert("login_attempts", "ip, attempts", "'$ip', $attempts");

        }else{
            $attempts++;
            Database::update("login_attempts", "attempts", "$attempts", "ip = '$ip'");
        }

        return 0;//Username or password is incorrect or doesnt exist;
    }
}

/**
 * This clears the users login attempts  *
 */
function clear_login_attempts()
{
    $ip = get_ip();
    Database::delete("login_attempts", "ip = '$ip'");
}

/**
 * This returns the users list *
 */
function get_users(){
    $users = Database::select("*", "logins");
    update_tracking("users", count($users));
    return $users;
}

/**
 * This returns the users ip  *
 */
function get_ip()
{
    return $_SERVER['REMOTE_ADDR'];
}