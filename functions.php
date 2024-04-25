<?php

session_start();

include "blocks/db.php";
include 'classes/errorHandler.php';


function debug($test, $args = false)
{
    if ($args) {
        echo "<pre>";
        var_dump($test);
        exit;
        echo "</pre>";
    } else {
        echo "<pre>";
        var_dump($test);
        echo "</pre>";
    }
}



//FRONT Block///



function logOut($refresh = false)
{
    if ($refresh) {
        session_destroy();
        unset($_SESSION);
        redirect($_SERVER['HTTP_REFERER'] = ".");
    } else {
        session_destroy();
        unset($_SESSION);
    }
}

function lastUserActivity()
{
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 86400) {
        // last request was more than 1 day = 86400 seconds ago 
        session_unset($_SESSION); // unset $_SESSION variable for the run-time
        session_destroy(); // destroy session data in storage
        header("location: main");    // redirect to login page
    }
}

function isUserLoggedIn()
{

    return isset($_SESSION['user']);
}

function getUserRole()
{
    if (!isset($_SESSION['user']['status'])) return false;

    if ($_SESSION['user']['status'] == 0) {
        return "administrator";
    }

    if ($_SESSION['user']['status'] == 1) {
        return "content_manager";
    }
    if ($_SESSION['user']['status'] == 2) {
        return "user";
    }
}

function checkUserForgot($email)
{
    global $db;
    $query = fetch([
        'select' => 'email',
        'table' => 'users',
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'email',
                    'value' => ':email'
                )
            )
        )
    ], ['email' => $email]);
    if ($query) {
        return TRUE;
    } else {
        return FALSE;
    }
}


function changePswd($email, $pswd)
{
    global $db;
    $result = executeQuery("UPDATE `users` SET `password` = :pswd WHERE email = :email", [':pswd' => $pswd, 'email' => $email]);
    if ($result === FALSE) {
        return FALSE;
    }
    return TRUE;
}

function captcha()
{
    $captcha = random_int(10000, 99999); // nayel araj rand er
    return $captcha;
}

function addContactIntodatabase($name, $email, $text)
{
    global $db;

    $result = executeQuery("INSERT INTO `contacts` (`name`, `email`, `text`) VALUES (?, ?, ?)", [$name, $email, $text]);
    if ($result === FALSE) {
        return FALSE;
    }
    return TRUE;
}

function ifUserSubscribed($userId)
{
    global $db;
    $result = fetch([
        'select' => 'user_id',
        'table' => 'subscribers',
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'user_id',
                    'value' => ':user_id'
                )
            )
        )
    ], ['user_id' => $userId]);
    if ($result) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function str_size_header($str, $symbol = 10, $end = '')
{
    $str = iconv('UTF-8', 'windows-1251', $str);
    $str = substr($str, 0, $symbol);
    $str = iconv('windows-1251', 'UTF-8', $str);
    return $str . $end;
}


// select All 

function fetchAll($args, $bindParams = [])
{
    global $db;
    $sqlString = '';
    if (!empty($args)) {
        if (isset($args['select'])) {
            $sqlString = "SELECT " . $args['select'];
        }
        if (isset($args['table'])) {
            $sqlString = $sqlString . " from " . $args['table'];
        }
        if (isset($args['where'])) {
            $loopNumber = 0;
            $totalItems = count($args['where']['fields']);
            $sqlString = $sqlString . " WHERE ";
            $operator = '=';
            foreach ($args['where']['fields'] as $value) {
                if (isset($value['operator'])) {
                    $operator = $value['operator'];
                }
                $loopNumber++;
                if ($loopNumber < $totalItems) {
                    if (empty($bindParams)) {
                        $sqlString = $sqlString . $value['key'] . $operator . " '" . $value['value'] . "' " . $args['where']['condition'] . " ";
                    } else {
                        $sqlString = $sqlString . $value['key'] . $operator . $value['value'] . " " . $args['where']['condition'] . " ";
                    }
                } else {
                    if (empty($bindParams))
                        $sqlString = $sqlString . $value['key'] . $operator . " '" . $value['value'] . "'";
                    else
                        $sqlString = $sqlString . $value['key'] . $operator . $value['value'] . "";
                }
            }
        }

        if (isset($args["group_by"])) {
            $sqlString = $sqlString . " GROUP BY " . $args['group_by'];
        }
        if (isset($args["order_by"])) {
            $sqlString = $sqlString . " ORDER BY " . $args['order_by'];
        }
        if (isset($args["limit"])) {
            $sqlString = $sqlString . " LIMIT " . (int)($args['limit']);
        }
        if (isset($args["offset"])) {
            $sqlString = $sqlString . " OFFSET " . (int)$args['offset'];
        }
    }

    // var_dump($sqlString); 
    // var_dump($bindParams);

    $query = $db->prepare($sqlString); // Prepare for sql query
    $query->execute($bindParams);
    return $query->fetchAll();
}



//SELECT One
function fetch($args, $bindParams = [])
{
    global $db;
    $sqlString = '';
    if (!empty($args)) {
        if (isset($args['select'])) {
            $sqlString = "SELECT " . $args['select'];
        }
        if (isset($args['table'])) {
            $sqlString = $sqlString . " from " . $args['table'];
        }

        if (isset($args['where'])) {
            $loopNumber = 0;
            $totalItems = count($args['where']['fields']);
            $sqlString = $sqlString . " WHERE ";
            $operator = '=';
            foreach ($args['where']['fields'] as $value) {
                $loopNumber++;
                if ($loopNumber < $totalItems) {
                    if (empty($bindParams)) {
                        $sqlString = $sqlString . $value['key'] . $operator . " '" . $value['value'] . "' " . $args['where']['condition'] . " ";
                    } else {
                        $sqlString = $sqlString . $value['key'] . $operator . $value['value'] . " " . $args['where']['condition'] . " ";
                    }
                } else {
                    if (empty($bindParams))
                        $sqlString = $sqlString . $value['key'] . $operator . " '" . $value['value'] . "'";
                    else
                        $sqlString = $sqlString . $value['key'] . $operator . $value['value'] . "";
                }
            }
        }

        if (isset($args["group_by"])) {
            $sqlString = $sqlString . " GROUP BY " . $args['group_by'];
        }
        if (isset($args["order_by"])) {
            $sqlString = $sqlString . " ORDER BY " . $args['order_by'];
        }
        if (isset($args["limit"])) {
            $sqlString = $sqlString . " LIMIT " . (int)($args['limit']);
        }
        if (isset($args["offset"])) {
            $sqlString = $sqlString . " OFFSET " . (int)$args['offset'];
        }
    }
    // var_dump($sqlString);
    //  var_dump($bindParams);

    $query = $db->prepare($sqlString);
    $query->execute($bindParams);
    return $query->fetch();
}
//
function executeQuery($sqlString, $params = [])
{
    // debug($sqlString); 
    // debug($params);

    global $db;
    try {
        $query = $db->prepare($sqlString);
        $query->execute($params);
        // debug($query);exit;
        return $query->fetchAll();
    } catch (PDOException $e) {
        // debug($e);
        echo $e->getMessage();
        return false;
    }
}

function baseUrl($protocol = true, $host = true)
{
    if ($protocol) {
        $protocol = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = 'https://';
        }
    } else $protocol = '';

    if ($host) {
        $host = $_SERVER['HTTP_HOST'];
    } else $host = '';


    $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    return $protocol . $host . $dir;
}


function basePath()
{
    return __DIR__;
}

function basePathAdmin()
{
    return dirname(__DIR__) . "blog";
}




function getLastPostsWithAPI($limitNumber, $type)
{
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "http://localhost/blog/simple-api?limit=" . $limitNumber . "&type=" . $type);
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    //decode json array
    $outputArray = json_decode($output, true);
    // debug($outputArray);exit;


    foreach ($outputArray as $key => $postViewWithAPI) : ?>
        <?php if ($postViewWithAPI['type'] == 'post') : ?>
            <div class='lesson'>
                <a href="single?id=<?php echo $postViewWithAPI['id']; ?>">Title: <?php echo $postViewWithAPI['title']; ?></a>
                <p>Category: <?php echo $postViewWithAPI['category_name']; ?></p>
            </div>
        <?php else : ?>
            <div class='lesson'>
                <a href="<?php echo $postViewWithAPI['img']; ?>" target="_blank">
                    <img src="<?php echo $postViewWithAPI['img']; ?>" width="70">
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach;



    // close curl resource to free up system resources
    curl_close($ch);
}



function getExchangeRates()
{

    // create curl resource
    $ch = curl_init();

    // set url
    // curl_setopt($ch, CURLOPT_URL, "https://api.exchangerate.host/latests?base=AMD&symbols=USD,EUR");
    // curl_setopt($ch, CURLOPT_URL, "https://cb.am/latest.json.php");

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);
    $outputArray = json_decode($output, true);


    if (isset($outputArray['success']) && $outputArray['success'] == true) {
        foreach ($outputArray['rates'] as $currencyShort => $currency) {
            echo '1 ' . $currencyShort . ' = ' . number_format((1 / $currency), 1, '.', '') . ' AMD<br/>';
        }
    } else {
        echo "Rates API error";
    }

    // close curl resource to free up system resources
    curl_close($ch);
}

function getExchangeRatesAmd(): array
{

    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://cb.am/latest.json.php");

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);
    $outputArray = json_decode($output, true);

    $attributs = [
        'USD' => '',
        'EUR' => '',
        'GBP' => '',
        'GEL' => '',
        'RUB' => ''
    ];

    foreach ($outputArray as $key => $value) {

        if (array_key_exists($key, $attributs)) {
            $attributs[$key] = $value;
        }
    }

    return $attributs;
}


$GLOBALS['allStylesheetAndScript'] = [
    'script' => [],
    'stylesheet' => [],
];


function getActionStyleAndScript()
{

    $action = Router::getRoute();
    $filesPathActionScript = 'actions_scripts/' . $action['action'] . '.js';
    $filesPathActonStylesheet = 'actions_stylesheet/' . $action['action'] . '.css';


    if (file_exists(basePath() . '/js/' . $filesPathActionScript)) {
        $GLOBALS['allStylesheetAndScript']['script'][] = 'js/' . $filesPathActionScript;
    }

    if (file_exists(basePath() . '/css/' . $filesPathActonStylesheet)) {
        $GLOBALS['allStylesheetAndScript']['stylesheet'][] = 'css/' . $filesPathActonStylesheet;
    }
}

function addCustemStylesheetAndScript($fileExtension, $scriptPath)
{

    if (file_exists(basePath() . '/' . $scriptPath)) {
        if ($fileExtension == 'js') {
            $GLOBALS['allStylesheetAndScript']['script'][] = '/blog/' . $scriptPath;
        }
        if ($fileExtension == 'css') {
            $GLOBALS['allStylesheetAndScript']['stylesheet'][] = '/blog/' . $scriptPath;
        }
    } else {
        return false;
    }
}

// for redirect
function redirect($host = false)
{
    if ($host) {
        $redirect = $host;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '.';
    }
    header("Location: $redirect");
}



//recursive

function getTree($data)
{

    // $i = $i ?? 0; // analog isset($i) ? $i : 0

    $tree = [];

    foreach ($data as $id => &$node) {
        if (!$node['parent_id']) {
            $tree[$id] = &$node;
        } else {
            $data[$node['parent_id']]['children'][$id] = &$node;
        }
        // $i ++;
        // echo "=============DATA $i ========================";
        // debug($data);
        // echo "=============Tree $i ========================";
        // debug($tree);

    }
    return $tree;
}


function buildCascadCommHtml($tree)
{
    $i = $i ?? 0;
    $html = '';
    foreach ($tree as  $item) {
        $html .= creatHtmlTegForComm($item);
    }
    return $html;

    // echo "===========($i) data==============";
    // debug($html);
    // echo "===========() Tree==============";
    // debug($tree);
    // return $html;
}

function creatHtmlTegForComm($item)
{ ?>
    <div class="wrap_comment_block">
        <div class="comment_content">
            <div class="comment_content__avatar">
                <img src="uploads/users_img/<?php echo $item['user_img'] ?>" alt="avatar">
            </div>
            <div class="comment_content__text">
                <div class="comment_content__name"><?php echo $item['name'] ?></div>
                <p><?php echo $item['text']; ?></p>
            </div>
        </div>

        <!-- Reply btn toggle >>> -->
        <div class="reply"><span>Reply</span></div>
        <!-- Reply btn toggle <<< -->

        <!-- Reply block >>> -->
        <div class="reply_block d-none">
            <form action="#" method="post" class="">
                <input type="text" name="text" class="reply_block__text">
                <input type="hidden" name="parent_id" value="<?php echo $item['id']  ?>">
                <input type="hidden" name="post_id" class="post_id" value="<?php echo $_GET['page'] ?>">
                <button name="" class="reply_block__btn">Reply</button>
            </form>
        </div>
        <!-- Reply block <<< -->
    </div>



    <!-- comment reply >>> -->
    <?php if (isset($item['children'])) : ?>
        <div class='comment_content_reply'>
            <?php
            buildCascadCommHtml($item['children']);
            ?>
        </div>
    <?php endif; ?>
    <!-- comment reply <<< -->


<?php }

function getAdminSidbarMenu($menu)
{
    $categories = [];
    foreach ($menu as $key => $value) {
        $categories[$value['categories']] = $value;
    }
    return $categories;
}
