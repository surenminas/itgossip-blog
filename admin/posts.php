<?php


$errorMsgPpost = [
    "error" => [],
    "success" => []
];

// post status change
if (isset($_GET['publish']) && $_GET['post_id']) {
    $publish = $_GET['publish'];
    $postId = $_GET['post_id'];

    $statusUpdate = executeQuery(
        "UPDATE blog_posts SET publish_status = ?
        WHERE id = ?",
        [
            $publish,
            $postId,
        ]

    );

    empty($statusUpdate) ? redirect() : $errorMsgPpost['error'][] = "Cannot published";
}

// Delete post
if (isset($_GET['delete'])) {

    $deleteArticle = executeQuery("DELETE FROM blog_posts WHERE id = {$_GET["delete"]}");
    if ($deleteArticle === false) {
        $errorMsgPpost['error'][] = "Article cannot be deleted";
    } else {
        $errorMsgPpost['success'][] = "Deleted!";
    }
}

// update posts
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['postsEdit']) {

    isset($_POST['status']) ? $status = $_POST['status'] : $status = 0;



    $title = trim($_POST['title']);
    $meta_d = trim($_POST['meta_d']);
    $meta_k = trim($_POST['meta_k']);
    $date = strtotime($_POST['date']);
    $description = trim($_POST['description']);
    $text = trim($_POST['text']);
    $category = $_POST['categories'];
    $editID = $_POST['id'];

    //photos
    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image']['name'];
        $fileType = $_FILES['image']['type'];
        $fileSize = $_FILES['image']['size'];
    }



    if (empty($title) || empty($meta_d) || empty($meta_k) || empty($description) || empty($text) || empty($category) || empty($date)) {
        $errorMsgPpost['error'][] = "You have not completed all fields";
    }

    if (empty($errorMsgPpost['error'])) {
        if (isset($img) && !empty($img)) {
            if (strpos($fileType, 'image') === false) {
                $errorMsgPpost['error'][] = "The image file should be in JPG, PNG or GIF format";
            }

            if ($fileSize > 1024 * 2 * 1024) {
                $errorMsgPpost['error'][] = "Photo more 2mb";
            }

            if (empty($errorMsgPpost['error'])) {
                $uploadFile = move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/posts_img/" . $img);
                if (!$uploadFile) {
                    $errorMsgPpost['error'][] = "Photo not uploaded";
                } else {
                    $updatePosts = executeQuery(
                        "UPDATE blog_posts SET
                        title = :title,
                        meta_d = :meta_d,
                        meta_k = :meta_k,
                        date = :date,
                        description = :description,
                        text = :text,
                        category_id = :category_id,
                        author_id = :sessionId,
                        img = :img,
                        publish_status = :status
                        WHERE id = :editId",
                        [
                            ':title' => $title,
                            ':meta_d' => $meta_d,
                            ':meta_k' => $meta_k,
                            ':date' => $date,
                            ':description' => $description,
                            ':text' => $text,
                            ':category_id' => $category,
                            ':sessionId' => $_SESSION['user']['id'],
                            ':img' => $img,
                            ':status' => $status,
                            ':editId' => $editID
                        ]
                    );

                    if ($updatePosts === false) {
                        $errorMsgPpost['error'][] = "Article cannot updated";
                    } else {
                        $errorMsgPpost['success'][] = "Updated";
                    }
                }
            }
        } else {
            $updatePosts = executeQuery(
                "UPDATE blog_posts SET
                        title = :title,
                        meta_d = :meta_d,
                        meta_k = :meta_k,
                        date = :date,
                        description = :description,
                        text = :text,
                        category_id = :category_id,
                        author_id = :sessionId,
                        publish_status = :status
                        WHERE id = :editId",
                [
                    ':title' => $title,
                    ':meta_d' => $meta_d,
                    ':meta_k' => $meta_k,
                    ':date' => $date,
                    ':description' => $description,
                    ':text' => $text,
                    ':category_id' => $category,
                    ':sessionId' => $_SESSION['user']['id'],
                    ':status' => $status,
                    ':editId' => $editID
                ]
            );
            if ($updatePosts === false) {
                $errorMsgPpost['error'][] = "Article cannot updated";
            } else {
                $errorMsgPpost['success'][] = "Updated";
            }
        }
    }
}
//edite posts
if (isset($_GET['id'])) {
    $postsId = $_GET['id'];
    $postsEdit = fetch([
        'select' => '*',
        'table' => 'blog_posts',
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'id',
                    'value' => ':postsId'
                )
            )
        )
    ], ['postsId' => $postsId]);
?>
    <div class="button">
        <a href="posts" class="col-2 btn btn-secondary">Back</a>
    </div>

    <div class="mt-3 mb-3 text-center">
        <h3>Edit post</h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <?php if (!empty($errorMsgPpost['success'])) {
                foreach ($errorMsgPpost['success'] as $key => $error) : ?>
                    <div class="text-center p-2 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                        unset($error); ?>
                    </div>
            <?php endforeach;
            } ?>
            <?php if (!empty($errorMsgPpost['error'])) {
                foreach ($errorMsgPpost['error'] as $key => $error) : ?>
                    <div class="p-2 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
            <?php endforeach;
            } ?>

            <form name="form" method="post" action="posts?id=<?php echo $postsId; ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="text" class="form-control" value="<?php echo $postsEdit['title'] ?>" name="title" id="title" placeholder="Article name">
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" value="<?php echo $postsEdit['meta_d'] ?>" name="meta_d" id="meta_d" placeholder="Meta description">
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" value="<?php echo $postsEdit['meta_k'] ?>" name="meta_k" id="meta_k" placeholder="Meta keywords">
                </div>

                <div class="mb-3 date_picture_logo">
                    <input type="text" readonly class="form-control" name="date" value="<?php echo date("Y-m-d", $postsEdit['date']); ?>" id="datepicker" placeholder="yyyy-mm-dd">
                </div>

                <div class="mb-3">
                    <textarea class="form-control" name="description" id="description" rows="5" placeholder="Short description"><?php echo $postsEdit['description'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="text" class="form-label">Text</label>
                    <textarea name="text" id="summernote" rows="10"><?php echo $postsEdit['text']; ?></textarea>
                </div>

                
                <div class="mb-3">
                    <label for="categories" class="form-label">Select Category</label>
                    <select class="form-select" name="categories" id="categories">
                        <?php $allCategories = fetchAll([
                            'select' => 'id, name',
                            'table' => 'blog_categories',
                            'order_by' => 'name ASC',
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
                        <?php foreach ($allCategories as $key => $category) : ?>
                            <?php if ($postsEdit['category_id'] === $category['id']) : ?>
                                <option selected value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                            <?php else : ?>
                                <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <img src='../uploads/posts_img/<?php echo $postsEdit['img']; ?>' width="150" />
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Replace photo</label>
                    <input class="form-control" name="image" id="image" type="file" id="formFile">
                </div>
                <div class="mb-3">
                    <?php if ($postsEdit['publish_status'] === 0) : ?>
                        <div class="form-check">
                            <input class="form-check-input" name="status" type="checkbox" value="1" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Publish
                            </label>
                        </div>
                    <?php else : ?>
                        <div class="form-check">
                            <input class="form-check-input" name="status" value="1" type="checkbox" id="flexCheckChecked" checked>
                            <label class="form-check-label" for="flexCheckChecked">
                                Publish
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="hidden" name="id" id="id" value="<?php echo $postsEdit['id'] ?>">
                </div>
                <div class="mt-4 ">
                    <input type="submit" class="btn btn-success w-100" name="postsEdit" id="newPosts" value="Save">
                </div>
            </form>
        </div>
    </div>

<?php
} else { ?>

    <div class="button">
        <a href="new-posts" class="col-lg-2 btn btn-success">Create Post</a>
    </div>

    <?php if (!empty($errorMsgPpost['success'])) {
        foreach ($errorMsgPpost['success'] as $key => $error) : ?>
            <div class="text-center p-2 mt-4 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                    unset($error); ?>
            </div>
    <?php endforeach;
    } ?>
    <?php if (!empty($errorMsgPpost['error'])) {
        foreach ($errorMsgPpost['error'] as $key => $error) : ?>
            <div class="p-2 mt-4 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
    <?php endforeach;
    } ?>
    <div class="row title-table">
        <div class="id col-1">N</div>
        <div class="img  col-2">Pictures</div>
        <div class="title col-2">Title</div>
        <div class="author col-2">Author</div>
        <div class="red col-5">Settings</div>
    </div>

    <?php

    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `users` WHERE 1");
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['p']) ? $_GET['p'] : 1;
    $perPage = 15;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);

    $paginationRange = 2;


    // show posts lists        
    $postLists = executeQuery(" SELECT blog_posts.*, blog_categories.name as category_name, users.username as user_name FROM blog_posts 
        LEFT JOIN blog_categories ON blog_posts.category_id = blog_categories.id 
        LEFT JOIN users ON blog_posts.author_id = users.id 
        WHERE   type = 'post' ORDER BY blog_posts.id DESC limit $perPage OFFSET $offset");
    ?>
    <?php foreach ($postLists as $key => $postList) : ?>

        <div class="row post_manage">
            <div class="id col-1"><?php echo $key + 1;
                                    ?></div>
            <div class="img col-2">
                <img src='../uploads/posts_img/<?php echo $postList['img']; ?>' width="60" />
            </div>
            <div class="title col-2"><?php echo mb_substr($postList['title'], 0, 50, 'UTF-8'); ?></div>
            <div class="author col-2"><?php echo $postList['user_name'];  ?></div>

            <div class="col-lg-5 posts_setting">
                <ul class="p-0">
                    <li> <a class="btn  btn-success w-100" href="posts?id=<?php echo $postList['id']; ?>">Edit</a></li>
                    <?php if ($postList['publish_status'] === 0) : ?>
                        <li><a class="btn btn-warning text-dark w-100" href="posts?publish=1&post_id=<?php echo $postList['id']; ?>">Unpublished</a></li>
                    <?php else : ?>
                        <li><a class="btn btn-secondary w-100" href="posts?publish=0&post_id=<?php echo $postList['id']; ?>">Published</a></li>
                    <?php endif; ?>
                    <li><a class="btn btn-danger w-100" href="posts?delete=<?php echo $postList['id']; ?>">Delete</a></li>

                </ul>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination >>> -->
    <div class="pagination_posts mt-5 justify-content-center d-flex">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($page != 1) : ?>
                    <li class="page-item"><a class="page-link" href="posts?p=1">First</a></li>
                    <li class="page-item"><a class="page-link" href="posts?p=<?php echo $page - 1 ?>">Previous</a></li>
                <?php endif; ?>


                <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                    <?php if ($i < 1) continue; ?>
                    <li class="page-item"><a class="page-link" href="posts?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>

                <!-- page 1 > -->
                <li class="page-item active" aria-current="page">
                    <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                </li>
                <!-- page 1 < -->

                <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                    <?php if (($i + 1)  > $totalPagesCount) break; ?>
                    <li class="page-item"><a class="page-link" href="posts?p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                <?php } ?>


                <?php if ($page != $totalPagesCount) : ?>
                    <li class="page-item"><a class="page-link" href="posts?p=<?php echo $page + 1 ?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="posts?p=<?php echo $totalPagesCount; ?>">Last</a></li>
                <?php endif; // LAST 
                ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
    <!-- Pagination <<< -->

<?php
}
?>

<?php

//Summernote editor
addCustemStylesheetAndScript('js', 'app/summernote/summernote.min.js');
addCustemStylesheetAndScript('js', 'js/admin/post_edit.js');


//datepicker 
addCustemStylesheetAndScript('css', 'app/datepicker/jquery-ui.min.css');
addCustemStylesheetAndScript('js', 'app/datepicker/jquery-ui.min.js');
addCustemStylesheetAndScript('js', 'js/admin/datepickerScript.js');

?>