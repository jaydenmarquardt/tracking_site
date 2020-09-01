<?php

/**
 * This is simply a debug for help  *
 */
function debug($debug, $die = 0)
{
    echo "<pre>";
    var_dump($debug);
    echo "</pre>";
    $die ? die : 0 ;
}

/**
 * This is simply a redirect function  *
 */
function redirect_to($page)
{
    header("Location: /$page");
    exit();
}

/**
 * This makes a string safe for url and db keys  *
 */
function slugify($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

/**
 * This returns if the string contains a substring  *
 */
function str_contains($string, $contains)
{
    return strpos($string, $contains) !== false;

}

/**
 * This is simple no swear filter  *
 */
function is_rude($message_board)
{
    $message_board = strtolower($message_board);
    $badWords = array('shit', 'ass', 'fuck', 'bitch', 'cunt', 'boob', 'penis', 'vagina', 'slut');
    foreach ($badWords as $badword) {
        $split = str_split($badword, 1);
        $iterations = [
            $badword,
            implode("-", $split),
            implode(" ", $split),
            implode("  ", $split),
            implode("_", $split),
            implode("#", $split),
            implode("*", $split),
        ];
        foreach ($iterations as $badwordsmart)
        {
            if(strpos($message_board, $badwordsmart) !== false)
            {
                return true;
            }

        }

    }

    return false;
}

/**
 * This returns the users system info  *
 */
function system_info()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform    = "os_other";
    $os_array       = array(
        '/windows phone 8/i'    =>  'os_other',
        '/windows phone os 7/i' =>  'os_other',
        '/windows nt 6.3/i'     =>  'os_windows',
        '/windows nt 6.2/i'     =>  'os_windows',
        '/windows nt 6.1/i'     =>  'os_windows',
        '/windows nt 6.0/i'     =>  'os_windows',
        '/windows nt 5.2/i'     =>  'os_windows',
        '/windows nt 5.1/i'     =>  'os_windows',
        '/windows xp/i'         =>  'os_windows',
        '/windows nt 5.0/i'     =>  'os_windows',
        '/windows me/i'         =>  'os_windows',
        '/win98/i'              =>  'os_windows',
        '/win95/i'              =>  'os_windows',
        '/win16/i'              =>  'os_windows',
        '/macintosh|mac os x/i' =>  'os_mac',
        '/mac_powerpc/i'        =>  'os_mac',
        '/linux/i'              =>  'os_linux',
        '/ubuntu/i'             =>  'os_linux',
        '/iphone/i'             =>  'os_ios',
        '/ipod/i'               =>  'os_ios',
        '/ipad/i'               =>  'os_ios',
        '/android/i'            =>  'os_android',
        '/blackberry/i'         =>  'os_other',
        '/webos/i'              =>  'os_other');

    $browser        =   "browser_other";

    $browser_array  = array(
        '/msie/i'       =>  'browser_other',
        '/firefox/i'    =>  'browser_firefox',
        '/safari/i'     =>  'browser_safari',
        '/chrome/i'     =>  'browser_chrome',
        '/opera/i'      =>  'browser_other',
        '/netscape/i'   =>  'browser_other',
        '/maxthon/i'    =>  'browser_other',
        '/konqueror/i'  =>  'browser_other',
        '/mobile/i'     =>  'browser_other');
    $found = false;

    foreach ($browser_array as $regex => $value)
    {
        if($found)
            break;
        else if (preg_match($regex, $user_agent,$result))
        {
            $browser    =   $value;
        }
    }
    $device = '';
    foreach ($os_array as $regex => $value)
    {
        if($found)
            break;
        else if (preg_match($regex, $user_agent))
        {
            $os_platform    =   $value;
            $device = !preg_match('/(windows|mac|linux|ubuntu)/i',$os_platform)
                ?'device_mobile':(preg_match('/phone/i', $os_platform)?'device_mobile':'device_computer');
        }
    }
    $device = !$device? 'device_computer':$device;
    return array('os'=>$os_platform,'device'=>$device, "browser" => $browser);
}

/**
 * This returns the page by id  *
 */
function get_page($id = 0)
{
    return Database::select("*", "pages", "ID = $id")[0];
}

/**
 * This returns the page by slug  *
 */
function get_page_by_slug($slug)
{
    return Database::select("*", "pages", "slug = '$slug'")[0];
}

/**
 * This returns the page by url  *
 * also handles login/register/update/delete account functions
 */
function get_page_from_url()
{
    $path = $_SERVER["REQUEST_URI"];
    $path = parse_url($path);
    $path = $path["path"];
    $path = $path == "/" ? "/home" : $path;
    $path = substr($path, 1);
    unset($_GET["updated"]);

    switch ($path){

        case "login":
            if(is_logged_in())
            {
                redirect_to("admin");

            }
            else
                if(array_key_exists("login", $_POST) && $_POST["login"]=="Login")
                {
                    return login();
                }
                else
                    if(array_key_exists("register", $_POST) && $_POST["register"]=="Register")
                    {
                        return register();
                    }
            break;
        case "logout":
            logout();
            break;
        case "admin":

            error_log(print_r($_POST, 1));
            if(!is_logged_in())
            {
                redirect_to("login/?login_failed=1");
            }else if(array_key_exists("remove_account", $_GET) && $_GET["remove_account"]=="true")
            {
               remove_account();

            }else if(array_key_exists("report", $_POST) && $_POST["report"]=="Start Report")
            {
                if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                {
                    $_GET["admin_failed"] = 9;

                }
                else if(empty($_POST["email"]) || empty($_POST["date_end"]))
                {
                    $_GET["admin_failed"] = 10;
                }
                else if(date("Ymd", strtotime($_POST["date_end"]) < date("Ymd")))
                {
                    $_GET["admin_failed"] = 11;
                }else{
                    $_GET["updated"] = 5;

                    create_report($_POST["email"], date("Ymd", strtotime($_POST["date_end"])));

                }

            }else if(array_key_exists("update", $_POST) && $_POST["update"]=="Update")
            {
                $_GET["updated"] = 1;
                unset( $_POST["update"]);
                foreach ($_POST as $key => $value)
                {
                    update_tracking($key, $value);
                }

            }else if(array_key_exists("update_user", $_POST) && $_POST["update_user"]=="Update")
            {

                update_user();

            }else if(array_key_exists("message_board", $_POST) && $_POST["message_board"]=="Update")
            {
                $message_board =  filter_var($_POST["message_board_message"], FILTER_SANITIZE_STRING);
                $message_board = mysqli_real_escape_string(Database::get(), $message_board);
                //check swearing
                if(!is_rude($message_board))
                {
                    update_meta("dashboard", $message_board);
                    $_GET["updated"] = 2;
                }else{
                    $_GET["admin_failed"] = 1;
                }


            }
            break;

    }
    $page = get_page_by_slug($path);
    if(!$page)return get_page_by_slug("404");

    return $page;
}

/**
 * This renders a box for the tracking  *
 */
function render_blob($title, $desc, $icon, $key, $stat)
{
    $color = $GLOBALS["colors"][$GLOBALS["color_index"]];
    ?>
    <div class="block" style="background-color: <?php echo $color;?>">
        <i class="big-icon <?php echo $icon;?>"></i>
        <h1 class="tracker " data-tracker="<?php echo $key;?>"><?php echo $stat;?></h1>
        <h1><?php echo $title;?></h1>

        <p><?php echo $desc;?></p>
    </div>
<?php
    $GLOBALS["color_index"] += 1;
    if($GLOBALS["color_index"] > count($GLOBALS["colors"])-1)$GLOBALS["color_index"] = 0;
}

/**
 * This renders a block layout  *
 */
function do_block($block, $atts){
    $file = __DIR__."/assets/blocks/$block.php";
    if(!file_exists($file)){
        echo "Block not found [$file]";
        return;
    }
    ob_start();

    include $file;

    echo ob_get_clean();
}

/**
 * This renders a pages content  *
 */
function do_content($content)
{
    global $page;
    $page_name = $page["slug"];
    $path = __DIR__."/assets/pages/$page_name.php";

    ob_start();

    if(file_exists($path)){
        include ($path);
    }
    else {
        $content = trim($content);
        return $content;
    }
    return ob_get_clean();


}