<?php

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
], ['gallery']);

?>


<!-- content -->

<?php
// $selectAllPhotosAddedAuthors = executeQuery("SELECT users.id as users_id, users.username,blog_posts.id as blog_post_id, blog_posts.img, blog_posts.publish_status FROM users 
//             LEFT JOIN blog_posts ON blog_posts.author_id = users.id 
//             WHERE blog_posts.type = 'photo'");

// $photosByAuthorsGroupped = [];
// foreach ($selectAllPhotosAddedAuthors as $key => $selectAllPhotosAddedAuthor) :
//     $photosByAuthorsGroupped[$selectAllPhotosAddedAuthor['username']][] = $selectAllPhotosAddedAuthor;
// endforeach;

$countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `blog_posts` WHERE type = 'photo' AND publish_status = 1");
$totalRowsCount = $countPostRows[0]["totalRowsCount"];

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$totalPagesCount = ceil($totalRowsCount / $perPage);

$paginationRange = 2;

$selectAllPhotos = fetchall([
    'select' => 'id, img, title',
    'table' => 'blog_posts',
    'order_by' => 'id DESC',
    'limit' => $perPage,
    'offset' => $offset,
    'where' => array(
        'condition' => 'AND',
        'fields' => array(
            array(
                'key' => 'type',
                'value' => '?'
            )
            ),
            array(
                'key' => 'publish_status',
                'value' => '?'
            )
    )
], ['photo'],['1']);

?>

<div class="row a">
    <?php foreach ($selectAllPhotos as $key => $selectAllPhoto) : ?>
        <a class="col-lg-4 col-md-6  post_card_photo" data-lightbox="example-set" href="uploads/photos/<?php echo $selectAllPhoto['img']; ?>">
            <div class="rainbow-gallery icon-center">
                <img class="radius-small img-thumbnail" src="uploads/photos/<?php echo $selectAllPhoto['img'] ?>">
            </div>
            <div class="tags-info">
                <p class="text-center"><?php echo $selectAllPhoto['title']; ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>



<?php if ($totalPagesCount > 1) : ?>

    <!-- Pagination >>> -->
    <div class="pagination_posts">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($page != 1) : ?>
                    <li class="page-item"><a class="page-link" href="?p=1">First</a></li>
                    <li class="page-item"><a class="page-link" href="?p=<?php echo $page - 1 ?>">Previous</a></li>
                <?php endif; ?>


                <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                    <?php if ($i < 1) continue; ?>
                    <li class="page-item"><a class="page-link" href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>

                <!-- page 1 > -->
                <li class="page-item active" aria-current="page">
                    <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                </li>
                <!-- page 1 < -->

                <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                    <?php if (($i + 1)  > $totalPagesCount) break; ?>
                    <li class="page-item"><a class="page-link" href="?p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                <?php } ?>


                <?php if ($page != $totalPagesCount) : ?>
                    <li class="page-item"><a class="page-link" href="?p=<?php echo $page + 1 ?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="?p=<?php echo $totalPagesCount; ?>">Last</a></li>
                <?php endif; // LAST 
                ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
    <!-- Pagination <<< -->

<?php endif; ?>




<!-- footer -->
<?php addCustemStylesheetAndScript('js', "js/lightbox-plus-jquery.min.js"); ?>
<?php addCustemStylesheetAndScript('css', "css/lightbox.min.css"); ?>