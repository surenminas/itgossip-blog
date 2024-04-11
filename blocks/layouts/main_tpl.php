<?php include 'blocks/header.php'; ?>

<?php $mostViewPost = executeQuery("SELECT id, title, img FROM blog_posts WHERE publish_status = 1 ORDER BY view DESC LIMIT 1"); ?>
<div class="container-fluid hero  text-center" style="background-image: url('uploads/posts_img/<?php echo $mostViewPost[0]['img']; ?>')">
    <div class="row">
        <p><?php echo $mostViewPost[0]['title']; ?></p>
        <div class="text-center">
            <a href="<?php echo BASE_URL ?>single?page=<?php echo $mostViewPost[0]['id']; ?>">
                <button class="btn hero__button">Read more</button>
            </a>
        </div>
    </div>
</div>

<!-- main page decor >>> -->
<div class="container">
    <div class="row post_line">
        <div class="col-lg-8">
            <div class="last_posts_decor">
                <span>Lastests Blog</span>
            </div>
        </div>
    </div>
</div>
<!-- main page decor <<< -->

<div class="container">
    <div class="row content">
        <div class="col-lg-8">
            <!-- Content >>> -->
            <?php echo $content ?>
            <!-- Content <<< -->
        </div>
        <!-- Sidbar >>> -->
        <?php include 'blocks/sidebar_2.php'; ?>
        <!-- Sidbar <<< -->

    </div>
</div>

<?php include 'blocks/footer.php'; ?>