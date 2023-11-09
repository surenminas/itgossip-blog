<?php

if (isset($_POST['id'])) {
    $delID = $_POST['id'];
}
$errMsg = '';

if (isset($delID) && isset($_POST['delete'])) {
    $ids = implode(",", $delID);
    $delArticlesForomDB = executeQuery("DELETE FROM blog_posts WHERE id IN (" . $ids . ")");
    if ($delArticlesForomDB === false) {
        $errMsg = "Статью невозможно удалить";
    } else {
        $errMsg = "Статья удальена, выборитье другую статью для удаления";
    }
} elseif (!isset($delID) && isset($_POST['delete'])) {
    $errMsg = "Удаление невозможно, выберите статью для удаления";
}

$postsDeleteViews = fetchAll([
    'order_by' => 'id DESC',
    'select' => 'id, title',
    'table' => 'blog_posts',
    'where' => array(
        'fields' => array(
            array(
                'key' => 'type',
                'value' => 'post'
            )
        )
    )
]);
?>
<p>Выберите статью для удаления</p>
<form method="post" action="delete-posts">
    <span class="err_massage"><?php echo $errMsg; ?></span>
    <?php foreach ($postsDeleteViews as $key => $postsDeleteView) : ?>
        <div class='lesson_title'>
            <p class='listName'>
                <input type='checkbox' name='id[]' value='<?php echo $postsDeleteView['id']; ?>'> <?php echo $postsDeleteView['title']; ?></a>
            </p>
        </div>
    <?php endforeach; ?>
    <p><input type="submit" name="delete" value="Удалить стастю"></p>
</form>