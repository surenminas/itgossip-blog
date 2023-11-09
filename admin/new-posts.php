<?php

$errorMsgPpost = [
    "error" => [],
    "success" => []
];


if ($_SERVER['REQUEST_METHOD'] == 'POST'  && $_POST["newPosts"]) {
    $title = $_POST['title'];
    $meta_d = $_POST['meta_d'];
    $meta_k = $_POST['meta_k'];
    $date = strtotime($_POST['date']);
    $description = $_POST['description'];
    $text = $_POST['text'];
    $category = $_POST['categories'];
    //photos

    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image']['name'];
        $fileType = $_FILES['image']['type'];
        $fileSize = $_FILES['image']['size'];
    } else {
        $img = 'nophoto.png';
    }

    if (empty($title) || empty($meta_d) || empty($meta_k) || empty($description) || empty($text) || empty($category) || empty($date)) {
        $errorMsgPpost['error'][] = "You have not completed all fields";
    }

    if (!empty($fileType) && strpos($fileType, 'image') === false) {
        $errorMsgPpost['error'][] = "Is not photo";
    }

    if (isset($fileSize) && $fileSize > 1024 * 2 * 1024) {
        $errorMsgPpost['error'][] = "Photo more 2mb";
    }

    if (empty($errorMsgPpost['error'])) {

        if ($img != "nophoto.png") {
            $uploadFile = move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/posts_img/" . $img);
            if ($uploadFile === false) {
                $errorMsgPpost['error'][] = "Photo not uploaded";
            }
        }
        $newPosts = executeQuery(
            "INSERT INTO blog_posts
            (title, meta_d, meta_k, date, img, description, text, author_id, category_id, type) 
            VALUES 
            (:title, :meta_d, :meta_k, :date, :img, :description, :text, :userId, :category, 'post')",
            [
                ':title' => $title,
                ':meta_d' => $meta_d,
                ':meta_k' => $meta_k,
                ':date' => $date,
                ':img' => $img,
                ':description' => $description,
                ':text' => $text,
                ':userId' => $_SESSION['user']['id'],
                ':category' => $category
            ]
        );

        if ($newPosts === false) {
            $errorMsgPpost['error'][] = "Article cannot added";
        } else {
            // header('Location: new-posts');
            $errorMsgPpost['success'][] = "Added!";
        }
    }
}

?>
<div class="button">
    <a href="posts" class="col-2 btn btn-secondary">Back</a>
</div>

<div class="mt-3 mb-3 text-center">
    <h3>Add new post</h3>
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

        <form name="form" method="post" action="new-posts" enctype="multipart/form-data">


            <div class="mb-3">
                <input type="text" class="form-control" value="<?php if (isset($_POST['title']) && !empty($errorMsgPpost['error'])) echo $_POST['title']; ?>" name="title" id="title" placeholder="Article name">
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" value="<?php if (isset($_POST['meta_d']) && !empty($errorMsgPpost['error'])) echo $_POST['meta_d']; ?>" name="meta_d" id="meta_d" placeholder="Meta description">
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" value="<?php if (isset($_POST['meta_k']) && !empty($errorMsgPpost['error'])) echo $_POST['meta_k']; ?>" name="meta_k" id="meta_k" placeholder="Meta keywords">
            </div>

            <div class="mb-3 date_picture_logo">
                <input type="text" readonly class="form-control" name="date" value="<?php if (isset($_POST['date']) && !empty($errorMsgPpost['error'])) echo $_POST['date']; ?>" id="datepicker" placeholder="yyyy-mm-dd">
            </div>

            <div class="mb-3">
                <textarea class="form-control" name="description" id="description" rows="5" placeholder="Short description"><?php if (isset($_POST['description']) && !empty($errorMsgPpost['error'])) echo $_POST['description'];  ?></textarea>
            </div>
            <div class="mb-3" id="editor">
                <label for="text" class="form-label">Text</label>
                <textarea name="text" id="text" rows="10"><?php if (isset($_POST['text']) && !empty($errorMsgPpost['error'])) echo $_POST['text']; ?></textarea>
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
                    <option value="">...</option>
                    <?php foreach ($allCategories as $key => $category) : ?>
                        <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Choose a picture</label>
                <input class="form-control" name="image" id="image" type="file" id="formFile">
            </div>
            <div class="mt-4 ">
                <input type="submit" class="btn btn-success w-100" name="newPosts" id="newPosts" value="Save">
            </div>
        </form>
    </div>
</div>


<?php
//Summernote editor
addCustemStylesheetAndScript('js', 'app\summernote\summernote-lite.js');
addCustemStylesheetAndScript('js', 'js\admin\post_edit.js');


//datepicker 
addCustemStylesheetAndScript('css', 'app\datepicker\jquery-ui.min.css');
addCustemStylesheetAndScript('js', 'app\datepicker\jquery-ui.min.js');
addCustemStylesheetAndScript('js', 'js\admin\datepickerScript.js');

?>