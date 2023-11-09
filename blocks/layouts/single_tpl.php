<?php
include 'blocks/header.php';

$selectPagesInformation = fetch([
    'select' => 'id, title',
    'table' => 'blog_posts',
    'where' => array(
        'fields' => array(
            array(
                'key' => 'id',
                'value' => '?'
            )
        )
    )
], [$_GET['page']]);
?>


<!--  -->
<div class="breadcrumb_single_page">
    <div class="container">
        <div class="row">
            <ul>
                <?php
                $categoryAndPostNames = executeQuery("SELECT blog_posts.id, blog_posts.title,  blog_categories.id as category_id, blog_categories.name as category_name FROM blog_posts 
                    LEFT JOIN blog_categories ON blog_posts.category_id = blog_categories.id WHERE publish_status = 1 AND blog_posts.id = ? ", [$_GET['page']]);
                ?>
                <li><a href=".">Home</a></li>
                <li><a href="categories?page=<?php echo  $categoryAndPostNames[0]['category_id']; ?>">> <?php echo  $categoryAndPostNames[0]['category_name']; ?></a></li>
                <li class=active_post> > <?php echo  $categoryAndPostNames[0]['title']; ?></li>
            </ul>

        </div>
    </div>
</div>
<!-- Content >>> -->
<div class="container">
    <div class="row">

        <div class="col-lg-8">
            <?php echo $content ?>
        </div>
        <!-- Sidbar -->
        <?php include 'blocks/sidebar_2.php'; ?>
        <!-- Sidbar <<< -->
    </div>
</div>
<!-- Content <<< -->

<?php include 'blocks/footer.php'; ?>