<?php

if (getUserRole() != 'administrator') {
    header("location: home");
}

$errorMsgSettings = [
    "error" => [],
    "success" => []
];

if (isset($_POST['edit_page_data'])) {
    $title = $_POST['title'];
    $meta_d = $_POST['meta_d'];
    $meta_k = $_POST['meta_k'];
    $text = $_POST['text'];
    $dataId = $_POST['id'];

    if (empty($title) || empty($meta_d) || empty($meta_k) || empty($text)) {
        $errorMsgSettings['error'][] = "You have not completed all fields";
    } else {
        $pageDataUpdate = executeQuery(
            "UPDATE settings SET title = :title, meta_d = :meta_d, meta_k = :meta_k, text = :text WHERE id = :dataId",
            [
                ':title' => $title,
                ':meta_d' => $meta_d,
                ':meta_k' => $meta_k,
                ':text' => $text,
                ':dataId' => $dataId
            ]
        );
        if ($pageDataUpdate === false) {
            $errorMsgSettings['error'][] = "Error, try later";
        } else {
            $errorMsgSettings['success'][] = "Updated";
            // header("location: settings");
        }
    }
}

if (isset($_GET['id'])) {
    $pageId = $_GET['id'];
    $pageData = fetch(
        [
            'select' => '*',
            'table' => 'settings',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'id',
                        'value' => ':set_id'
                    )
                )
            )
        ],
        ['set_id' => $pageId]
    );

?>

    <div class="button">
        <a href="settings" class="col-2 btn btn-secondary">Back</a>
    </div>

    <div class="mt-3 mb-3 text-center">
        <h3>Edit settings</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-7 ">

            <?php if (!empty($errorMsgSettings['success'])) {
                foreach ($errorMsgSettings['success'] as $key => $error) : ?>
                    <div class="text-center p-2 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                        unset($error); ?>
                    </div>
            <?php endforeach;
            } ?>
            <?php if (!empty($errorMsgSettings['error'])) {
                foreach ($errorMsgSettings['error'] as $key => $error) : ?>
                    <div class="p-2 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
            <?php endforeach;
            } ?>

            <form name="form" method="post" action="settings?id=<?php echo $pageId; ?>">
                <div class="mb-3">
                    <label class="form-label">Edit settings title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo $pageData['title'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Edit meta description</label>
                    <input type="text" name="meta_d" id="meta_d" class="form-control" value="<?php echo $pageData['meta_d'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Edit meta keywords</label>
                    <input type="text" name="meta_k" id="meta_k" class="form-control" value="<?php echo $pageData['meta_k'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Edit description about page</label>
                    <textarea name="text" id="text" class="form-control" rows="5"><?php echo $pageData['text'] ?></textarea>
                </div>
                <input type="hidden" name="id" id="id" value="<?php echo $pageData['id'] ?>">
                <div class="mt-4 ">
                    <input type="submit" name="edit_page_data" id="edit_page_data" class="btn btn-success w-100" value="Save">
                </div>
            </form>
        </div>
    </div>
<?php

} else {
    $allPagesData = fetchAll([
        'select' => 'id, title, meta_d, meta_k',
        'table' => 'settings',
        'order_by' => 'id DESC',
        'where' => array(
            'fields' => array(
                array(
                    'key' => '1',
                    'value' => '1'
                )
            )
        )
    ]);
?>
    <div class="mt-3 mb-3 text-center">
        <h3>Edit settings</h3>
    </div>

    <div class="row title-table">
        <div class="id col-1">N</div>
        <div class="img  col-2">Title</div>
        <div class="title col-4">Meta description</div>
        <div class="author col-3">Meta keyword</div>
        <div class="red col-2">Settings</div>
    </div>

    <?php foreach ($allPagesData as $key => $pageData) : ?>

        <div class="row post_manage">
            <div class="id col-lg-1"><?php echo $key + 1; ?></div>
            <div class="img col-lg-2">
                <a href="settings?id=<?php echo $pageData['id']; ?>"><?php echo $pageData['title']; ?></a>
            </div>
            <div class=" col-lg-4"><?php echo $pageData['meta_d']; ?></div>
            <div class=" col-lg-3"><?php echo $pageData['meta_k']; ?></div>
            <div class="col-lg-2 setting_page">
                <ul class="p-0">
                    <li><a class="btn btn-success" href="settings?id=<?php echo $pageData['id']; ?>">Edit</a></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php } ?>

<?php
//Summernote editor
addCustemStylesheetAndScript('js', 'app\summernote\summernote-lite.js');
addCustemStylesheetAndScript('js', 'js\admin\post_edit.js');
?>