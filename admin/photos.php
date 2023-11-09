<?php


$errorMessage = [
    "error" => [],
    "success" => []
];


if (isset($_GET['photos']) && !empty($_GET['id'])) {
    $deletePhoto = executeQuery("DELETE FROM blog_posts WHERE id = ?", [$_GET['id']]);
    if ($deletePhoto === false) {
        $errorMessage["error"][] = "Photo cannot delete";
    } else {

        $errorMessage["success"][] = "Deleted";
        // redirect();
    }
}

// debug($errorMessage["success"]);

if (isset($_POST['id']) && isset($_POST['delete'])) {
    $delID = $_POST['id'];

    $ids = implode(",", $delID);
    $delForomDB = executeQuery("DELETE FROM blog_posts WHERE id IN (" . $ids . ")");
    if ($delForomDB === false) {
        $errorMessage["error"][] = "Photos can not delete";
    } else {
        $errorMessage["success"][] = "Deleted";
    }
}


$countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `blog_posts` WHERE type = 'photo'");
$totalRowsCount = $countPostRows[0]["totalRowsCount"];

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$totalPagesCount = ceil($totalRowsCount / $perPage);

$paginationRange = 2;
$selectAllPhotosAuthors = executeQuery("SELECT blog_posts.id, blog_posts.img,blog_posts.date, blog_posts.publish_status, blog_posts.author_id, users.username as user_name FROM blog_posts
        LEFT JOIN users ON blog_posts.author_id=users.id
        WHERE type = 'photo' ORDER BY id DESC limit $perPage OFFSET $offset");
?>

<div class="button">
    <a href="new-photo" class="col-lg-2 btn btn-success">Add photo</a>
</div>
<div class="mt-3 mb-3 text-center">
    <h3>Select photos to delete</h3>
</div>
<div class="row title-table">
    <div class="id col-lg-1">Select</div>
    <div class="img  col-lg-2">Pictures</div>
    <div class="author col-lg-3">Author</div>
    <div class="date col-lg-2">Date</div>
    <div class="red col-lg-4">Settings</div>
</div>
<form method="post" action="photos" id="delete_photos">
    <?php foreach ($selectAllPhotosAuthors as $key => $delPhoto) : ?>
        <div class="row post_manage">
            <div class="id col-lg-1">
                <p class='listName'>
                    <input type='checkbox' name='id[]' value='<?php echo $delPhoto['id']; ?>'>
                </p>
            </div>
            <div class="img col-lg-2">
                <img src='../uploads/photos/<?php echo $delPhoto['img']; ?>' width="60" />
            </div>
            <div class=" col-lg-3"><?php echo $delPhoto['user_name'];  ?></div>
            <div class=" col-lg-2"><?php echo date("d M, Y", $delPhoto['date']) ?></div>
            <div class="col-lg-4 posts_setting">
                <ul class="p-0">
                    <?php if ($delPhoto['publish_status'] === 0) : ?>
                        <li><a class="btn btn-warning text-dark w-100" href="posts?publish=1&post_id=<?php echo $delPhoto['id']; ?>">Unpublished</a></li>
                    <?php else : ?>
                        <li><a class="btn btn-secondary w-100" href="posts?publish=0&post_id=<?php echo $delPhoto['id']; ?>">Published</a></li>
                    <?php endif; ?>
                    <li><a class="btn btn-danger w-100" href="photos?id=<?php echo $delPhoto['id']; ?>">Delete</a></li>

                </ul>
            </div>

            <!-- <div class="del col-lg-1 btn btn-danger"><a href="photos?id=<?php // echo $delPhoto['id']; ?>">Delete</a></div> -->
        </div>
    <?php endforeach; ?>
    <div class="delete_photo">
        <input type="submit" name="delete" class="btn btn-danger w-25" value="Delete">
    </div>
</form>

<!-- Pagination >>> -->
<?php if ($totalPagesCount > 1) : ?>
    <div class="pagination_posts mt-4 justify-content-center d-flex">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($page != 1) : ?>
                    <li class="page-item"><a class="page-link" href="photos?p=1">First</a></li>
                    <li class="page-item"><a class="page-link" href="photos?p=<?php echo $page - 1 ?>">Previous</a></li>
                <?php endif; ?>


                <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                    <?php if ($i < 1) continue; ?>
                    <li class="page-item"><a class="page-link" href="photos?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>

                <!-- page 1 > -->
                <li class="page-item active" aria-current="page">
                    <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                </li>
                <!-- page 1 < -->

                <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                    <?php if (($i + 1)  > $totalPagesCount) break; ?>
                    <li class="page-item"><a class="page-link" href="photos?p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                <?php } ?>


                <?php if ($page != $totalPagesCount) : ?>
                    <li class="page-item"><a class="page-link" href="photos?p=<?php echo $page + 1 ?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="photos?p=<?php echo $totalPagesCount; ?>">Last</a></li>
                <?php endif; // LAST 
                ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
<?php endif; ?>
<!-- Pagination <<< -->