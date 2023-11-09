<?php
$errorMsgPhoto = [
    "error" => [],
    "success" => []
];


if (isset($_POST['addPhoto']) && isset($_FILES['image']['name'])) {

    $title = $_POST['title'];
    $date = strtotime($_POST['date']);
    $img = $_FILES['image']['name'];
    $fileType = $_FILES['image']['type'];
    $fileSize = $_FILES['image']['size'];

    if ($date === false) {
        $errorMsgPhoto['error'][] = "Choose date";
    }

    if ($title === '') {
        $errorMsgPhoto['error'][] = "Choose Name";
    }

    if ($img === '') {
        $errorMsgPhoto['error'][] = "Choose photo";
    }

    if (!empty($date) && !empty($img) && !empty($title)) {
        if (strpos($fileType, 'image') === false) {
            $errorMsgPhoto['error'][] = "File is not photo";
        }

        if ($fileSize > 1024 * 2 * 1024) {
            $errorMsgPhoto['error'][] = "Photo more 2mb";
        }
    }


    if (empty($errorMsgPhoto['error'])) {
        $uploadFile = move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/photos/" . $img);
        if ($uploadFile === false) {
            $errorMsgPhoto['error'][] = "Photo not uploaded";
        } else {
            $addPhoto = executeQuery(
                "INSERT INTO blog_posts (title, img, date, author_id, type) 
                VALUES (:title, :img, :date, :userId, 'photo')",
                [
                    ':title' => $title,
                    ':img' => $img,
                    ':date' => $date,
                    ':userId' => $_SESSION['user']['id']
                ]
            );
            if ($addPhoto === false) {
                $errorMsgPhoto['error'][] = "Photo not uploaded";
            } else {
                // header('Location: new-photo');
                $errorMsgPhoto['success'][] = "Photo added";
            }
        }
    }

    header('Cache-Control: no-cache');
} else {
    $title = '';
}
?>
<div class="button">
    <a href="photos" class="col-2 btn btn-secondary">Back</a>
</div>

<div class="mt-3 mb-3 text-center">
    <h3>Add new photo</h3>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <form class="form-group" name="form" method="post" action="new-photo" enctype="multipart/form-data">
            <div class="err_massage">
                <?php if (!empty($errorMsgPhoto['success'])) {
                    foreach ($errorMsgPhoto['success'] as $key => $error) : ?>
                        <div class="text-center p-2 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                            unset($error); ?>
                        </div>
                <?php endforeach;
                } ?>
                <?php if (!empty($errorMsgPhoto['error'])) {
                    foreach ($errorMsgPhoto['error'] as $key => $error) : ?>
                        <div class="p-2 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
                <?php endforeach;
                } ?>
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" name="title" value="<?php if (isset($title) && !empty($errorMsgPhoto['error'])) echo $title;
                                                                            unset($title); ?>" id="exampleFormControlInput2" placeholder="Name">
            </div>
            <div class="mb-3 date_picture_logo">
                <input type="text" readonly class="form-control" name="date" id="datepicker" placeholder="yyyy-mm-dd">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Choose a picture</label>
                <input class="form-control" name="image" id="image" type="file" id="formFile">
            </div>
            <div class="mt-4 ">
                <input type="submit" class="btn btn-success w-100" name="addPhoto" id="addPhoto" value="Save">
            </div>
        </form>
    </div>
</div>

<?php
//datepicker 
addCustemStylesheetAndScript('css', 'app\datepicker\jquery-ui.min.css');
addCustemStylesheetAndScript('js', 'app\datepicker\jquery-ui.min.js');
addCustemStylesheetAndScript('js', 'js\admin\datepickerScript.js');

?>