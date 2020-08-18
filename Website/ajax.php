<?php
/**
 * This only loads when called through js *
 *
 * This file returns all current tracking info and updates the active user
 */


$user_ip = getenv('REMOTE_ADDR');

include_once ("database.php");
include_once ("functions.php");
include_once ("user.php");
include_once ("tracking.php");
Database::connect("localhost", "jaydenun_db", "jaydenun_uni-site", "PHb$1Oy7APVm");

$now = date("Ymdhis");
$user_ip = slugify($user_ip);
$key = ("last_active_".$user_ip);
$old = get_meta($key);
update_meta($key, $now);

$actives = get_active();

$response =[];
$response["key"] = $key;
$response["now"] = $now;
$response["old"] = $old;
$response["actives"] = $actives;
$response["tracking"] = [
    "total" => get_tracking("total"),
    "active" => get_tracking("active"),
    "daily" => get_tracking("daily"),
    "users" => get_tracking("users"),
    "unique_users" => get_tracking("unique_users"),
    "locations" => get_tracking("locations"),
    "user_ip_".$user_ip => get_tracking(slugify("user_ip_".$user_ip)),
    "device_computer" => get_tracking("device_computer"),
    "browser_safari" => get_tracking("browser_safari"),
    "browser_chrome" => get_tracking("browser_chrome"),
    "browser_firefox" => get_tracking("browser_firefox"),
    "browser_other" => get_tracking("browser_other"),
    "os_mac" => get_tracking("os_mac"),
    "os_windows" => get_tracking("os_windows"),
    "os_linux" => get_tracking("os_linux"),
    "os_ios" => get_tracking("os_ios"),
    "os_android" => get_tracking("os_android"),
    "os_other" => get_tracking("os_other"),
    "dashboard" => get_meta("dashboard"),
];;


echo json_encode($response);
exit();
