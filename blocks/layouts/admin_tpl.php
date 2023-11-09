<?php

if (isset($_POST['logout'])) {
    logOut(1);
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Summernote CSS -->
    <link href="../app/summernote/summernote.min.css" rel='stylesheet' type='text/css' />
    <!-- //Summernote CSS -->
    <link href="../css/admin/admin.css" rel="stylesheet">
    <!----webfonts---->
    <link href='http://fonts.googleapis.com/css?family=Oswald:100,400,300,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,300italic' rel='stylesheet' type='text/css'>
    <!----//webfonts---->
    <title>Admin Block IT Gossip</title>
</head>



<body>

    <!-- hedaer >>> -->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg flex-wrap justify-content-between navbar-light bg-light">
            <a class="navbar-brand logo" href="."><span>IT</span>Gossip</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div>


                <div class="navbar collapse navbar-collapse nav_menu" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-1 mb-2 mb-lg-0 active">

                        <li class="nav-item menu_list">
                            <a class="nav-link" aria-current="page" href="../">Client Page</a>
                        </li>

                        <?php if (isUserLoggedIn()) { ?>
                            <li class="nav-item dropdown menu_list">
                                <a class="nav-link dropdown-toggle active" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $_SESSION['user']['username']; ?> </a>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
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
                                <a class="nav-link signup" aria-current="page" href="registration">Signup</a>
                            </li>

                        <?php } ?>

                    </ul>
                </div>


            </div>
        </nav>
    </div>
    <!-- header <<< -->


    <div class="container-fluid">
        <div class="row">
            <!-- sidbar >>> -->
            <div class="sidebar col-lg-3">
                <ul>
                    <li>
                        <a href="posts">Articles</a>
                    </li>
                    <li>
                        <a href="category">Categories</a>
                    </li>
                    <li>
                        <a href="photos">Gallery</a>
                    </li>
                    <?php if (getUserRole() == 'administrator') : ?>

                        <li>
                            <a href="settings">Menu settings</a>
                        </li>
                        <li>
                            <a href="edit-users">User Role Change</a>
                        </li>
                    <?php endif;  ?>


            </div>
            <!-- sidbar <<< -->

            <!-- content >>> -->
            <div class="posts col-lg-9">
                <?php echo $content; ?>
            </div>
            <!-- <<< content -->

        </div>
    </div>



    <!-- Block Footer Start -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/74946a2e9f.js" crossorigin="anonymous"></script>


    <?php
    foreach ($GLOBALS['allStylesheetAndScript']['script'] as $key => $value) : ?>
        <script type="text/javascript" src="<?php echo $value; ?>"></script>
    <?php endforeach; ?>
    </div>

    <?php foreach ($GLOBALS['allStylesheetAndScript']['stylesheet'] as $key => $value) {
        echo '<link href="' . $value . '" rel="stylesheet" type="text/css" />';
    }       ?>

    <script type="text/javascript" src="../js/bootstrap.min.js"></script>



</body>

</html>