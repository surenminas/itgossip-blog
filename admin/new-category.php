<?php

$errorMsgNewCategory = [
    'error' => [],
    'success' => []
];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];


    if (empty($name)) {
        $errorMsgNewCategory['error'][] = "Name empty";
    }
    if (empty($description)) {
        $errorMsgNewCategory['error'][] = "Description empty";
    }

    if (empty($errorMsgNewCategory['error'])) {
        $newCategory = executeQuery(
            "INSERT INTO blog_categories (name, categories_description) VALUES (:name, :description)",
            [
                ':name' => $name,
                ':description' => $description
            ]
        );


        if ($newCategory === false) {
            $errorMsgNewCategory['error'][] = "Category can not add";
        } else {
            $errorMsgNewCategory['success'][] = "Added";
            // header('Location: new-category');
        }
    }
}

?>
<div class="button">
    <a href="category" class="col-2 btn btn-secondary">Back</a>
</div>

<div class="mt-3 mb-3 text-center">
    <h3>Add new Category</h3>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <!-- error >>> -->
        <div class="err_massage">
            <?php if (!empty($errorMsgNewCategory['success'])) {
                foreach ($errorMsgNewCategory['success'] as $key => $error) : ?>
                    <div class="p-2 mb-2 alert alert-success text-center"><?php echo $error;
                                                                            unset($error) ?> </div>
            <?php endforeach;
            } ?>
            <?php if (!empty($errorMsgNewCategory['error'])) {
                foreach ($errorMsgNewCategory['error'] as $key => $error) : ?>
                    <div class="p-2 mb-2 alert alert-danger text-center"><?php echo $error; ?> </div>
            <?php endforeach;
            } ?>
        </div>
        <!-- error <<< -->
        <form name="form" method="post" action="new-category">
            <div class="mb-3">
                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?php if (isset($_POST['name']) && !empty($errorMsgNewCategory['error'])) echo $_POST['name']; ?>">
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" id="description" placeholder="Description" rows="4"><?php if (isset($_POST['description']) && !empty($errorMsgNewCategory['error'])) echo $_POST['description']; ?></textarea>
            </div>

            <div class="mt-4 ">
                <input type="submit" name="newCategory" id="newCategory" class="btn btn-success w-100" value="Save">

            </div>
        </form>
    </div>
</div>