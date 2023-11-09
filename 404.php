<?php

if (isset($_POST['logout'])) {
	logOut();
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
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<!-- awesome -->
	<script src="https://kit.fontawesome.com/74946a2e9f.js" crossorigin="anonymous"></script>
	<title><?php if (isset($selectPagesInformation['title'])) echo $selectPagesInformation['title']; ?></title>
</head>

<body>
	<header>
		<div class="container">
			<nav class="navbar navbar-expand-lg flex-wrap justify-content-between navbar-light bg-light">
				<a class="navbar-brand logo" href="."><span>IT</span>Gossip</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div>


					<div class="navbar collapse navbar-collapse nav_menu" id="navbarSupportedContent">
						<ul class="navbar-nav mr-1 mb-2 mb-lg-0 active">

							<li class="nav-item menu_list">
								<a class="nav-link active" aria-current="page" href=".">Home</a>
							</li>

							<li class="nav-item dropdown menu_list">
								<a class="nav-link dropdown-toggle" href="author" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Authors
								</a>
								<ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
									<li><a class="dropdown-item" href="author">All Authors</a></li>
									<li>
										<hr class="dropdown-divider dropdown_line_divider">
									</li>
									<?php
									$selectAuthorsName = fetchAll([
										'order_by' => 'author_posts_count desc',
										'limit' => 10,
										'select' => 'id, username, author_posts_count',
										'table' => 'users'
									]);
									?>
									<?php foreach ($selectAuthorsName as $name) : ?>
										<li><a class="dropdown-item" href="author?author_id=<?php echo $name['id']; ?>"><?php echo $name['username']; ?> (<?php echo $name['author_posts_count']; ?>)</a></li>
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
										<li><a class="dropdown-item" href="categories?page=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>

							<li class="nav-item menu_list">
								<a class="nav-link" aria-current="page" href="gallery">Gallery</a>
							</li>

							<li class="nav-item dropdown menu_list">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Other Links
								</a>
								<ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
									<li><a class="dropdown-item" href="about">About Us</a></li>
									<li><a class="dropdown-item" href="contact">Contact Us</a></li>
								</ul>
							</li>

							<li class="nav-item search menu_list">
								<img src="img/search.svg" alt="Search for materials">
							</li>

							<!-- <form class="form-inline my-2 my-lg-0">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Signup</button>
                            </form> -->



							<?php if (isUserLoggedIn()) { ?>
								<li class="nav-item dropdown menu_list">
									<a class="nav-link dropdown-toggle active" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<?php echo $_SESSION['user']['username']; ?> </a>
									<ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
										<li><a class="dropdown-item" href="user">Profile</a></li>
										<div class="logout">
											<form action="" method="post">
												<!-- <img src="img/sign_out.png" /> -->
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
									<button><a class="nav-link signup" aria-current="page" href="registration">Signup</a></button>
								</li>

							<?php } ?>

						</ul>
					</div>

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
								<a href="forgot">Forgot password?</a>
							</div>

							<button type="submit" class="btn" name="enter">Enter</button>
							<button type="button" class="btn cancel" id="close_form">close</button>
						</form>

					</div>
				</div>
			</nav>
		</div>

	</header>

	<!-- Content >>> -->
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="error_404">
					<div>
						<a href=".">Go Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Content <<< -->



	<!-- Footer >>> -->
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4 footer_about">
					<h4>IT Gossip</h4>
					<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
						totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
						dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
						fugit, sed quia consequuntur magni dolores eos qui ratione</p>
				</div>
				<div class="col-md-4 footer_last_posts">
					<h4>Lastest posts</h4>
					<ul>
						<li><a href="#">What Bronze Medalist Michael Woods Eats to Fuel His Rides</a></li>
						<li><a href="#">3 Things You Can Do to Get a Better Spin Class Workout</a></li>
						<li><a href="#">Save Big on These 10 Great Indoor Trainers</a></li>
					</ul>
				</div>
				<div class="col-md-4">
					<h4>Contact</h4>
					<div class="socials">
						<p>Armenia, Yerevan</p>
						<p>Yerevan, 0022</p>
						<p>+(374)99-000-000</p>
						</ul>
						<ul>
							<li class="footer_first">
								<a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
							</li>
							<li>
								<a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
							</li>
							<li>
								<a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
							</li>
							<li>
								<a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>


	</div>

	<div class="footer_bottom">
		<p>Copyright 2023 by <span>IT Gossip</span>, All Right Reserved</p>
	</div>

	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
	<?php

	getActionStyleAndScript();

	// debug($GLOBALS['allStylesheetAndScript']);

	foreach ($GLOBALS['allStylesheetAndScript']['script'] as $key => $value) : ?>
		<script type="text/javascript" src="<?php echo $value; ?>"></script>
	<?php endforeach; ?>
	</div>

	<?php foreach ($GLOBALS['allStylesheetAndScript']['stylesheet'] as $key => $value) {
		echo '<link href="' . $value . '" rel="stylesheet" type="text/css" />';
	}       ?>
	<!-- Footer <<< -->

</body>

</html>