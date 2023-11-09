<!-- sideBar >>> -->
<div id="sideBar">

    <?php

    $errMsg = '';

    // cache >>>
    $menuSidebarList = Cache::get('menu_sidebar_list');
    if (!$menuSidebarList) {
        $menuSidebarList = fetchAll([
            'select' => 'page, title',
            'table' => 'settings',
            'order_by' => 'id ASC',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'show_in_sidebar',
                        'value' => '1'
                    )
                )
            )
        ]);
        Cache::set('menu_sidebar_list', $menuSidebarList);
    }
    // cache <<<

    ?>

    <!-- link frst pagei >>> -->
    <p align="center" class="title_default_page"><a href=".">Главная</a></p>
    <!-- link frst pagei <<< -->

    <!-- search >>>  -->
    <p align="center" class="title">Поиск</p>
    <div id="coolmenu">
        <form method="post" action="search" id="search_form">
            <input type="text" name="search_text" id="search_text" placeholder="Search" />
            <div align="center" id="search_err" class="error"></div>

            <input type="submit" name="search_btn" id="search_btn">
        </form>
    </div>
    <!-- search <<<  -->

    <!-- navigator menu >>> -->
    <p align="center" class="title">Навигация</p>
    <div id="coolmenu">



        <?php foreach ($menuSidebarList as $key => $list) : ?>
            <a href='<?php echo $list['page']; ?>'><?php echo $list['title']; ?></a>
        <?php endforeach; ?>

    </div>
    <!-- navigation menu <<< -->

    <!--  categories menu >>> -->
    <p align="center" class="title">Категории</p>
    <div id="coolmenu">
        <?php
        $categories = fetchAll([
            'order_by' => 'id DESC',
            'select' => 'id, name, posts_count',
            'table' => 'blog_categories',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'id',
                        'value' => '5',
                        'operator' => '!='
                    )
                )
            )
        ]);
        ?>
        <?php foreach ($categories as $key => $category) :  ?>
            <a href='categories?id=<?php echo $category['id']; ?>'><?php echo $category['name']; ?> (<?php echo $category['posts_count']; ?>)</a>
        <?php endforeach; ?>
    </div>
    <!--  categories menu <<< -->

    <!--  last posts >>> -->
    <p align="center" class="title">Последние добавленые</p>
    <div id="coolmenu">
        <?php
        $lastAuthorPosts = fetchAll([
            'order_by' => 'id DESC',
            'limit' => 5,
            'select' => 'id, title, category_id',
            'table' => 'blog_posts',
            'where' => array(
                'condition' => 'AND',
                'fields' => array(
                    array(
                        'key' => 'type',
                        'value' => 'post'
                    ),
                    array(
                        'key' => 'publish_status',
                        'value' => '1'
                    )
                )
            )
        ]);
        ?>
        <?php foreach ($lastAuthorPosts as $key => $lastAuthorPost) :  ?>
            <a href='single?id=<?php echo $lastAuthorPost['id']; ?>'><?php echo str_size_header($lastAuthorPost['title'], $symbol = 25, '...'); ?></a>
        <?php endforeach; ?>
    </div>
    <!--  last posts <<< -->

    <!--  API >>> -->
    <p align="center" class="title">API</p>
    <div id="coolmenu">
        <a href='simple-api?type=post'>Simple API</a>
        <a href='simpleAPI'>CURL to simple API</a>
    </div>
    <!--  API <<< -->

    <!--  Subscribe >>> -->
    <?php
    $userExists = false;
    if (isset($_SESSION['user']['id'])) {
        $sessionID = $_SESSION['user']['id'];
        $userExists = ifUserSubscribed($sessionID);
    }
    if ($userExists == false) {
        if (isset($_POST['submit_button'])) {
            $subscribeUserName = $_POST['subscribe_name'];
            $subscribeEmail = $_POST['subscribe_email'];
            $subscribeEmail = (filter_var($subscribeEmail, FILTER_VALIDATE_EMAIL));

            if (empty($subscribeEmail)) {
                $errMsg = "Поле Email обызателно";
            } elseif (filter_var($subscribeEmail, FILTER_VALIDATE_EMAIL) == false) {
                $errMsg = "Поле Email должна быть 'email'";
            } else {
                if (isset($sessionID)) {
                    try {
                        $result = executeQuery(
                            "INSERT INTO `subscribers` (`email`, `full_name`, `user_id`) 
                VALUES (:subscribeEmail, :subscribeUserName, :userId)",
                            [
                                ':subscribeEmail' => $subscribeEmail,
                                ':subscribeUserName' => $subscribeUserName,
                                ':userId' => $sessionID
                            ]
                        );
                        $errMsg = "Successfully subscribed...";
                    } catch (Exception $e) {
                        if ($e->getCode() == 1062) $info = "Duplicate email...";
                        else $errMsg = "Unknown error, please try again...";
                    }
                }

                try {
                    $result = executeQuery(
                        "INSERT INTO `subscribers` ( `email`, `full_name` ) 
            VALUES ( :subscribeEmail,:subscribeUserName )",
                        [
                            ':subscribeEmail' => $subscribeEmail,
                            ':subscribeUserName' => $subscribeUserName
                        ]
                    );
                    $errMsg = "Successfully subscribed...";
                } catch (Exception $e) {
                    if ($e->getCode() == 1062) $errMsg = "Duplicate email...";
                    else $errMsg = "Unknown error, please try again...";
                }
            }
        }
    ?>
        <p><?php echo $errMsg; ?></p>
        <p align="center" class="title2">Рассылка</p>
        <div class="formm" width="80%">
            <p class='form3'> Подписывайтесь на нашу рассылку и получайте свежие уроки, статьи и новости, прямо в свой почтовый ящик!</p>
            <form name="SR_form" method="post" action="." onsubmit="">

                <p class='form1'> Имя на русском:
                    <input type="text" name="subscribe_name" size=23 value='' maxlength=50 style='border: 1px #c1c1c1 solid; font-family: Verdana; font-size: 11px; width:120px; color:#424242;'>
                </p>
                <p class='form1'> Email адрес *:
                    <input type=text name="subscribe_email" size=23 value='' maxlength=50 style='margin:0px; padding:0px; border: 1px #c1c1c1 solid; font-family: Verdana; font-size: 11px; width:120px; color:#424242;'>
                </p>
                <p style='margin:5px;margin-top:10px; padding:0px;'>
                    <input type="submit" name="submit_button" value='Подписаться' style=' font-family: Verdana, sans-serif; border:1px gray solid; font-size: 11px; width:120px; height:19px;   background-Color:#f6f6f6; color:#424242; font-weight:bold; margin-left:10px;'>
                </p>
            </form>
        </div>


    <?php } ?>
    <!--  Subscribe <<< -->

</div>
<!-- sidebar <<< -->