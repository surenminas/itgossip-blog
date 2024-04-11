<?php

if (isset($_POST['logout'])) {
    logOut(1);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php if (isset($selectPagesInformation['meta_d'])) echo $selectPagesInformation['meta_d']; ?>">
    <meta name="keywords" content="<?php if (isset($selectPagesInformation['meta_k'])) echo $selectPagesInformation['meta_k']; ?>">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/main.css">

    <!-- logo -->
    <link rel="shortcut icon" href="<?php echo BASE_URL ?>uploads/blog_logo_2.png" sizes="180x180">
    <!-- awesome -->
    <script src="https://kit.fontawesome.com/74946a2e9f.js" crossorigin="anonymous"></script>
    <title><?php if (isset($selectPagesInformation['title'])) echo $selectPagesInformation['title']; ?></title>
</head>

<body>

    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg flex-wrap justify-content-between navbar-light bg-light">
                <a class="navbar-brand logo" href="<?php echo BASE_URL ?>"><span>IT</span>Gossip</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div>


                    <div class="navbar collapse navbar-collapse nav_menu" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-1 mb-2 mb-lg-0 active">

                            <li class="nav-item menu_list">
                                <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL ?>">Home</a>
                            </li>

                            <li class="nav-item dropdown menu_list">
                                <a class="nav-link dropdown-toggle" href="<?php echo BASE_URL ?>author" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Authors
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL ?>author">All Authors</a></li>
                                    <li>
                                        <hr class="dropdown-divider dropdown_line_divider">
                                    </li>
                                    <?php
                                    $selectAuthorsName = fetchAll([
                                        'select' => 'id, username, author_posts_count',
                                        'table' => 'users',
                                        'order_by' => 'author_posts_count desc',
                                        'limit' => 10,
                                        'where' => array(
                                            'condition' => 'OR',
                                            'fields' => array(
                                                array(
                                                    'key' => 'status',
                                                    'value' => 0,
                                                ),
                                                array(
                                                    'key' => 'status',
                                                    'value' => 1,
                                                )
                                            )
                                        )
                                    ]);
                                    ?>
                                    <?php foreach ($selectAuthorsName as $key => $name) :  ?>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>author?author_id=<?php echo $name['id']; ?>"><?php echo $name['username']; ?> (<?php echo $name['author_posts_count']; ?>)</a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                            <li class="nav-item dropdown menu_list">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Categories
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <?php
                                    $categories = fetchAll([
                                        'order_by' => 'name ASC',
                                        'limit' => 10,
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
                                    <?php foreach ($categories as $category) : ?>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>categories?page=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                            <li class="nav-item menu_list">
                                <a class="nav-link" aria-current="page" href="<?php echo BASE_URL ?>gallery">Gallery</a>
                            </li>

                            <li class="nav-item dropdown menu_list">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Other Links
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL ?>about">About Us</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL ?>contact">Contact Us</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL ?>simple-api?type=post">Simple API</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL ?>simpleAPI">CURL to simple API</a></li>
                                </ul>
                            </li>

                            <li class="nav-item menu_list" id="search">
                                <img src="<?php echo BASE_URL ?>img/search.svg" alt="Search for materials">
                            </li>


                            <?php if (isUserLoggedIn()) { ?>
                                <li class="nav-item dropdown menu_list">
                                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo $_SESSION['user']['username']; ?> </a>
                                    <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
                                        <?php if (getUserRole() === "administrator" || getUserRole() === "content_manager") : ?>
                                            <li><a class="dropdown-item" href="admin">Admin Panel</a></li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>profile">Profile</a></li>
                                        <div class="logout">
                                            <form action="" method="post">
                                                <input type="submit" name="logout" value="Logout" class="dropdown-item" />
                                            </form>
                                        </div>
                                    </ul>
                                </li>
                            <?php } else { ?>

                                <li class="nav-item menu_list">
                                    <button class="nav-link login" aria-current="page" id="login_open">Login</button>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link signup" aria-current="page" href="<?php echo BASE_URL ?>registration">Signup</a>
                                </li>

                            <?php } ?>

                        </ul>
                    </div>

                    <!-- search form >>> -->
                    <div class="d-none search_form">
                        <form action="search" method="post" id="search_form_header">
                            <input type="text" name="search_text" class="search_input" placeholder="Search..." />
                            <button type="submit" name="search"><img src="img/search.svg" alt="Search for materials"></button>
                        </form>
                        <!-- <p class="error_txt" ></p> -->

                    </div>
                    <!-- search form <<< -->

                    <!-- login form >>> -->
                    <div class="form-popup" id="my_form">

                        <form action="login" method="post" class="form_container" name="login_form_name" id=login_form>

                            <div class="email">
                                <input type="email" name="email" placeholder="Your Email" id="email">
                            </div>
                            <p class="error_txt" id="error_login_email">Enter email</p>

                            <div class="pass">
                                <input type="password" name="psw" placeholder="Your password" id="psw"> <!--required for empty input form-->
                            </div>
                            <p class="error_txt" id="error_login_psw">Enter password</p>
                            <div id="error_login_psw_form"></div>

                            <div class="forgot">
                                <a href="<?php echo BASE_URL ?>forgot">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn" name="enter">Enter</button>
                        </form>

                    </div>
                    <!-- login form <<< -->

                </div>
            </nav>
        </div>

    </header>