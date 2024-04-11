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
            <h1>About Us</h1>
            <ul>
                <li><a href="<?php echo BASE_URL ?>">Home</a></li>
                <li class="active_page">> About Us</li>
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