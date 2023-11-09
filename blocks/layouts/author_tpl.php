<?php
include 'blocks/header.php';

$selectPagesInformation = fetch([
    'select' => 'id, title, meta_d, meta_k,text',
    'table' => 'settings',
    'where' => array(
        'fields' => array(
            array(
                'key' => 'page',
                'value' => '?'
            )
        )
    )
], ['author']);


?>


<!-- Breadcrumb >>> -->
<div class="breadcrumb">
    <div class="container text-center">
        <div class="row">
            <?php if (isset($_GET['author_id'])) : ?>
                <?php $authorName = executeQuery("SELECT username FROM users WHERE id = ?", [$_GET['author_id']]); ?>
                <h1><?php echo $authorName[0]['username'] ?></h1>
            <?php else : ?>
                <h1>Categories</h1>
            <?php endif; ?>
            <ul>
                <li><a href=".">Home</a></li>
                <?php if (isset($_GET['author_id'])) : ?>
                    <li><a href="author">> Authors</a></li>
                    <li class="active_page">> <?php echo $authorName[0]['username'] ?></li>
                <?php else : ?>
                    <li class="active_page">> Authors</li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb <<< -->

<div class="container">
    <div class="row content">
        <div class="col-lg-8">
            <!-- Content >>> -->
            <?php echo $content ?>
            <!-- Content <<< -->
        </div>
        <!-- Sidbar -->
        <?php include 'blocks/sidebar_2.php'; ?>
        <!-- Sidbar <<< -->

    </div>
</div>

<!-- Footer >>> -->
<?php include 'blocks/footer.php'; ?>