<?php

if (getUserRole() != 'administrator') {
    header("location: .");
}


$errorMsgCategories = [
    'error' => [],
    'success' => []
];

/////////////////////////
// Pagination settings/// 
////////////////////////

$countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `subscribers` WHERE 1");
$totalRowsCount = $countPostRows[0]["totalRowsCount"];

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$totalPagesCount = ceil($totalRowsCount / $perPage);

$paginationRange = 2;
$subscriberLists = executeQuery("SELECT * FROM subscribers ORDER BY id DESC limit $perPage OFFSET $offset");

/////////////////////
////// End //////////
////////////////////

// Delete >>>
if (isset($_GET['delete'])) {
    $deletePhoto = executeQuery("DELETE FROM subscribers WHERE id = ?", [$_GET['delete']]);
    if ($deletePhoto === false) {
        $errorMsgCategories["error"][] = "Subscriber cannot delete";
    } else {

        $errorMsgCategories["success"][] = "Deleted";
        redirect();
    }
}

if (isset($_POST['id']) && isset($_POST['delete'])) {
    $delID = $_POST['id'];
    $ids = implode(",", $delID);
    $delForomDB = executeQuery("DELETE FROM subscribers WHERE id IN (" . $ids . ")");
    if ($delForomDB === false) {
        $errorMsgCategories["error"][] = "Subscriber cannot delete";
    } else {
        $errorMsgCategories["success"][] = "Deleted";
        redirect();
    }
}
// Delete <<<

else { ?>

    <?php if (!empty($errorMsgCategories['success'])) {
        foreach ($errorMsgCategories['success'] as $key => $error) : ?>
            <div class="text-center p-2 mt-5 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                    unset($error); ?>
            </div>
    <?php endforeach;
    } ?>
    <div class="row title-table">
        <div class="id col-1">N</div>
        <div class=" col-3">Email</div>
        <div class=" col-3">Name</div>
        <div class=" col-5">Settings</div>
    </div>

    <?php
    // $subscriberLists = fetchAll([
    //     'select' => '*',
    //     'table' => 'subscribers',
    //     'order_by' => 'id ASC',
    //     'where' => array(
    //         'fields' => array(
    //             array(
    //                 'key' => '1',
    //                 'value' => '1'
    //             )
    //         )
    //     )
    // ]);
    ?>

    <form method="post" action="subscribe" id="delete_category">
        <?php foreach ($subscriberLists as $key => $subscriber) : ?>

            <div class="row post_manage">

                <div class="id col-lg-1">
                    <p class='listName'>
                        <input type='checkbox' name='id[]' value='<?php echo $subscriber['id']; ?>'>
                    </p>
                </div>

                <div class=" col-3">
                    <a href="subscribe?id=<?php echo $subscriber['id']; ?>"><?php echo $subscriber['email']; ?></a>
                </div>
                <div class=" col-3">
                    <?php echo $subscriber['full_name']; ?>
                </div>

                <div class="col-lg-5 cat_setting">
                    <ul class="p-0">
                        <li><a class="btn btn-danger w-100" href="subscribe?delete=<?php echo $subscriber['id']; ?>">Delete</a></li>
                    </ul>
                </div>

            </div>
        <?php endforeach; ?>
        <div class="delete_photo">
            <input type="submit" name="delete" class="btn btn-danger w-25" value="Delete">
        </div>
    </form>

<?php } ?>

<!-- Pagination >>> -->
<?php if ($totalPagesCount > 1) : ?>
    <div class="pagination_posts mt-4 justify-content-center d-flex">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($page != 1) : ?>
                    <li class="page-item"><a class="page-link" href="subscribe?p=1">First</a></li>
                    <li class="page-item"><a class="page-link" href="subscribe?p=<?php echo $page - 1 ?>">Previous</a></li>
                <?php endif; ?>


                <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                    <?php if ($i < 1) continue; ?>
                    <li class="page-item"><a class="page-link" href="subscribe?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>

                <!-- page 1 > -->
                <li class="page-item active" aria-current="page">
                    <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                </li>
                <!-- page 1 < -->

                <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                    <?php if (($i + 1)  > $totalPagesCount) break; ?>
                    <li class="page-item"><a class="page-link" href="subscribe?p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                <?php } ?>


                <?php if ($page != $totalPagesCount) : ?>
                    <li class="page-item"><a class="page-link" href="subscribe?p=<?php echo $page + 1 ?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="subscribe?p=<?php echo $totalPagesCount; ?>">Last</a></li>
                <?php endif; // LAST 
                ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
<?php endif; ?>
<!-- Pagination <<< -->