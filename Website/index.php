<?php

$page_id = null;
$page = null;
$user_ip = getenv('REMOTE_ADDR');

/**
 * This Is when any page on the domain gets called, it will funnel through here *
 */
function init()
{
    //This makes sure that it is'nt double loading the page due to the .htaccess reroute
    if(!$_GET["page"]){
        echo "Ignore this load";
        header("/?index.php?page=0");
        die;
    }

    /**
     * This Includes the needed php assets *
     */
    include_once ("database.php");
    include_once ("functions.php");
    include_once ("user.php");
    include_once ("tracking.php");

    /**
     * This Connects to the db using the credentials *
     */
    Database::connect("localhost", "jaydenun_db", "jaydenun_uni-site", "PHb$1Oy7APVm");

    /**
     * This checks the ip address for login attempts *
     */
    if(get_login_attempts() > 10)
    {
        global $page_id;
        global $page;
        $page = get_page_by_slug("blocked");
        $page_id = $page["ID"];
        include_once ("layout.php");
        return;
    }

    /**
     * This is a set of globals for tracking grids *
     */
    $GLOBALS["colors"] = [
        "rgb(90,185,135)",
        "rgb(65,134,157)",
        "rgb(57,65,145)",
        "rgb(121,63,161)",
    ];
    $GLOBALS["color_index"] = 0;

    /**
     * This starts the tracking  *
     */
    tracking();


    session_start();


    if($_GET["page"] == "cron"){
        check_reports();
        die;
    }


    global $page_id;
    global $page;
    $page = get_page_from_url();
    $page_id = $page["ID"];

    /**
     * This starts the layout template  *
     */
    include_once ("layout.php");


}

init();


