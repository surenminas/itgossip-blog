<?php include 'blocks/header.php'; ?>


<!-- Breadcrumb >>> -->
<div class="breadcrumb">
    <div class="container text-center">
        <div class="row">
            <h1>Category</h1>
            <ul>
                <?php if (isset($_GET['page'])) {
                    $selectCategoryName = fetch([
                        'select' => 'id, name',
                        'table' => 'blog_categories',
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

                    <li><a href="<?php echo BASE_URL ?>">Home ></a></li>
                    <li><a href="<?php echo BASE_URL ?>categories">Categories ></a></li>
                    <li class="active_page"> <?php echo $selectCategoryName['name']; ?></li>

                <?php } else { ?>

                    <li><a href="<?php echo BASE_URL ?>">Home ></a></li>
                    <li class="active_page"> Categories</li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb <<< -->

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Content >>> -->
            <?php echo $content ?>
            <!-- Content <<< -->
        </div>

        <!-- Sidbar >> -->
        <?php include 'blocks/sidebar_2.php'; ?>
        <!-- Sidbar <<< -->

    </div>
</div>

<!-- Footer >>> -->
<?php include 'blocks/footer.php'; ?>