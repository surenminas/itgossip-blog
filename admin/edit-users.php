<?php

if (getUserRole() != 'administrator') {
    header("location: ../");
}

$errorMsgUser = ['error' => [], 'success' => []];

// Update users status //
if (isset($_POST['save_user_info'])) {

    $userRole = $_POST['role'];
    $editID = $_POST['id'];

    if ($userRole === '') {
        $errorMsgUser['error'][] = "Is not empty";
    } else {
        $result = executeQuery(
            "UPDATE users SET status = ? WHERE id = ?",
            [
                $userRole,
                $editID
            ]
        );
        if ($result === false) {
            $errorMsgUser['error'][] = "Wrong, try later";
        } else {
            $errorMsgUser['success'][] = "Saved!";
            // header("location: edit-users");
        }
    }
}

if (isset($_GET['id'])) {


    $userId = $_GET['id'];

    $editUser = fetch(
        [
            'select' => '*',
            'table' => 'users',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'id',
                        'value' => ':user_id'
                    )
                )
            )
        ],
        ['user_id' => $userId]
    );
    $userRole = fetchAll([
        'select' => 'role, role_name',
        'table' => 'user_roles',
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
    <div class="button">
        <a href="edit-users" class="col-2 btn btn-secondary">Back</a>
    </div>
    <div class="mt-3 mb-3 text-center">
        <h3>Edit Status</h3>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">

            <?php if (!empty($errorMsgUser['success'])) {
                foreach ($errorMsgUser['success'] as $key => $error) : ?>
                    <div class="text-center p-2 mt-5 mb-2 alert alert-success" role="alert"><?php echo $error;
                                                                                            unset($error); ?>
                    </div>
            <?php endforeach; } ?>
            <form class="form-group" name="form" method="post" action="edit-users?id=<?php echo $userId; ?>" enctype="multipart/form-data">
                <div class="err_massage">
                    <?php if (!empty($errorMsgUser['error'])) {
                        foreach ($errorMsgUser['error'] as $key => $error) : ?>
                            <div class="p-2 mb-2 bg-danger text-white text-center"><?php echo $error; ?>
                            </div>
                    <?php endforeach;
                    } ?>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Name</label>
                    <input type="text" readonly="readonly" class="form-control" name="user_name" id="user_name" value="<?php echo $editUser['username']; ?>" placeholder="Name">
                </div>
                <div class="mb-3 date_picture_logo">
                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                    <input type="email" readonly="readonly" class="form-control" name="email" id="email" value="<?php echo $editUser['email'] ?>" placeholder="email">
                </div>

                <label for="exampleFormControlInput1" class="form-label ">Status</label>
                <select class="form-select" name="role" aria-label="...">
                    <?php
                    foreach ($userRole as $userRoles) {
                        if ($userRoles['role'] == $editUser['status']) { ?>
                            <option value="<?php echo $userRoles['role']; ?>" selected><?php echo $userRoles['role_name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $userRoles['role']; ?>"><?php echo $userRoles['role_name']; ?></option>
                    <?php }
                    } ?>
                </select>
                <input type="hidden" name="id" id="id" value="<?php echo $editUser['id'] ?>">

                <div class="mt-4">
                    <input type="submit" class="btn btn-success w-100" name="save_user_info" id="save_ser_info" value="Save">
                </div>
            </form>
        </div>
    </div>

<?php
}

// Show Users lists  //
else {

    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `users` WHERE 1");
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['p']) ? $_GET['p'] : 1;
    $perPage = 15;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);

    $paginationRange = 2;
    $userInfo = executeQuery("SELECT users.id, users.img, users.username, users.status, user_roles.role_name FROM users
    LEFT JOIN user_roles ON users.status = user_roles.role
    WHERE 1 limit $perPage OFFSET $offset");


?>
    <div class="row title-table">
        <div class="id col-1">N</div>
        <div class="img  col-2">Photo</div>
        <div class="title col-3">Name</div>
        <div class="author col-2">Role</div>
        <div class="red col-4">Settings</div>
    </div>

    <?php foreach ($userInfo as $key => $userName) : ?>

        <div class="row post_manage">
            <div class="id col-1"><?php echo $key + 1; ?></div>
            <div class="img col-2">
                <a href="edit-users?id=<?php echo $userName['id']; ?>"><img src='../uploads/users_img/<?php echo $userName['img']; ?>' width="60" /></a>
            </div>
            <div class=" col-3"><a href="edit-users?id=<?php echo $userName['id']; ?>"><?php echo $userName['username']; ?></a></div>
            <div class=" col-2"><?php echo $userName['role_name'];  ?></div>
            <div class="col-lg-4 user_setting">
                <ul class="p-0">
                    <li><a class="btn  btn-success w-100" href="edit-users?id=<?php echo $userName['id']; ?>">Edit</a></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination >>> -->
    <div class="pagination_posts mt-5 justify-content-center d-flex">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($page != 1) : ?>
                    <li class="page-item"><a class="page-link" href="edit-users?p=1">First</a></li>
                    <li class="page-item"><a class="page-link" href="edit-users?p=<?php echo $page - 1 ?>">Previous</a></li>
                <?php endif; ?>


                <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                    <?php if ($i < 1) continue; ?>
                    <li class="page-item"><a class="page-link" href="edit-users?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>

                <!-- page 1 > -->
                <li class="page-item active" aria-current="page">
                    <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                </li>
                <!-- page 1 < -->

                <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                    <?php if (($i + 1)  > $totalPagesCount) break; ?>
                    <li class="page-item"><a class="page-link" href="edit-users?p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                <?php } ?>


                <?php if ($page != $totalPagesCount) : ?>
                    <li class="page-item"><a class="page-link" href="edit-users?p=<?php echo $page + 1 ?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="edit-users?p=<?php echo $totalPagesCount; ?>">Last</a></li>
                <?php endif; // LAST 
                ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
    <!-- Pagination <<< -->


<?php } ?>