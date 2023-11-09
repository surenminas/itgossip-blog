<?php

if (getUserRole() != 'administrator') {
    header("location: .");
}


$errorMsgCategories = [
    'error' => [],
    'success' => []
];

// Delete >>>
if (isset($_GET['delete'])) {
    $deletePhoto = executeQuery("DELETE FROM blog_categories WHERE id = ?", [$_GET['delete']]);
    if ($deletePhoto === false) {
        $errorMsgCategories["error"][] = "Category cannot delete";
    } else {

        $errorMsgCategories["success"][] = "Deleted";
        // redirect();
    }
}

if (isset($_POST['id']) && isset($_POST['delete'])) {
    $delID = $_POST['id'];
    $ids = implode(",", $delID);
    $delForomDB = executeQuery("DELETE FROM blog_categories WHERE id IN (" . $ids . ")");
    if ($delForomDB === false) {
        $errorMsgCategories["error"][] = "Category cannot delete";
    } else {
        $errorMsgCategories["success"][] = "Deleted";
    }
}
// Delete <<<

// edite 
if (isset($_POST['editCategory'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $editID = (int)$_GET['id'];

    if (empty($name)) {
        $errorMsgCategories['error'][] = "Name empty";
    }

    if (empty($description)) {
        $errorMsgCategories['error'][] = "Description empty";
    }

    if (empty($errorMsgCategories['error'])) {
        $updateCategory = executeQuery(
            "UPDATE blog_categories SET name = :name, categories_description = :description WHERE id = :editId",
            [':name' => $name, ':description' => $description, ':editId' => $editID]
        );
        if ($updateCategory === false) {
            $errorMsgCategories['error'][] = "Wrong, try later";
        } else {
            $errorMsgCategories['success'][] = "Saved!";
            // header("location: category"); 
        }
    }
}


// Edit 
if (isset($_GET['id'])) {
    $editCategory = fetch([
        'select' => '*',
        'table' => 'blog_categories',
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'id',
                    'value' => ':category_id'
                )
            )
        )
    ], [
        'category_id' => $_GET['id']
    ]);
?>
    <div class="button">
        <a href="category" class="col-2 btn btn-secondary">Back</a>
    </div>
    <div class="mt-3 mb-3 text-center">
        <h3>Edit category</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- error >>> -->
            <div class="err_massage">
                <?php if (!empty($errorMsgCategories['success'])) {
                    foreach ($errorMsgCategories['success'] as $key => $error) : ?>
                        <div class="p-2 mb-2 alert alert-success text-center"><?php echo $error;
                                                                                unset($error) ?> </div>
                <?php endforeach;
                } ?>
                <?php if (!empty($errorMsgCategories['error'])) {
                    foreach ($errorMsgCategories['error'] as $key => $error) : ?>
                        <div class="p-2 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
                <?php endforeach;
                } ?>
            </div>
            <!-- error <<< -->
            <form name="form" method="post" action="category?id=<?php echo $_GET['id']; ?>">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $editCategory['name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4"><?php echo $editCategory['categories_description'] ?></textarea>
                </div>

                <div class="mt-4 ">
                    <input type="submit" name="editCategory" class="btn btn-success w-100" id="editCategory" value="Save">
                </div>
            </form>
        </div>
    </div>
<?php



} else { ?>

    <div class="button">
        <a href="new-category" class="col-lg-2 btn btn-success">Create Category</a>
    </div>
    <?php if (!empty($errorMsgCategories['success'])) {
        foreach ($errorMsgCategories['success'] as $key => $error) : ?>
            <div class="text-center p-2 mt-5 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                    unset($error); ?>
            </div>
    <?php endforeach;
    } ?>
    <div class="row title-table">
        <div class="id col-1">N</div>
        <div class=" col-3">Name</div>
        <div class=" col-3">Description</div>
        <div class=" col-5">Settings</div>
    </div>

    <?php
    $categoriesLists = fetchAll([
        'select' => '*',
        'table' => 'blog_categories',
        'order_by' => 'name ASC',
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
    <form method="post" action="category" id="delete_category">
        <?php foreach ($categoriesLists as $key => $category) : ?>

            <div class="row post_manage">

                <div class="id col-lg-1">
                    <p class='listName'>
                        <input type='checkbox' name='id[]' value='<?php echo $category['id']; ?>'>
                    </p>
                </div>

                <div class=" col-3">
                    <a href="category?id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
                </div>
                <div class=" col-3">
                    <?php echo $category['categories_description']; ?>
                </div>

                <div class="col-lg-5 cat_setting">
                    <ul class="p-0">
                        <li> <a class="btn  btn-success w-100" href="category?id=<?php echo $category['id']; ?>">Edit</a></li>
                        <li><a class="btn btn-danger w-100" href="category?delete=<?php echo $category['id']; ?>">Delete</a></li>
                    </ul>
                </div>

            </div>
        <?php endforeach; ?>
        <div class="delete_photo">
            <input type="submit" name="delete" class="btn btn-danger w-25" value="Delete">
        </div>
    </form>

<?php } ?>