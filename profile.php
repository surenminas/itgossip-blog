<?php if (!isset($_SESSION['user']['id'])) {
    throw new \Exception("Page not found", 404);
} else { ?>
    <div class="profile">
        <div class="container">
            <div class="row">
                <div class="profile_title">
                    <h5>Personal Information</h5>
                </div>

                <div class="profile_info">
                    <div class="col-lg-5">
                        <?php
                        if (isset($_POST['update'])) {

                            $error = ['error' => []];

                            $userName = $_POST['name'];
                            $profileText = $_POST['profile_text'];


                            //photos
                            if (!empty($_FILES['user_img']['name'])) {
                                $img = $_FILES['user_img']['name'];
                                $fileType = $_FILES['user_img']['type'];
                                $fileSize = $_FILES['user_img']['size'];
                            }

                            // if (empty($userName) && empty($psw1) && empty($psw2)) {
                            //     $error['error'][] = "All Fields Empty";
                            // }

                            if (empty($error['error']['All Fields Empty'])) {
                            }

                            if (empty($userName)) {
                                $error['error'][] = "Name Empty";
                            }


                            if (isset($img) && !empty($img)) {
                                if (strpos($fileType, 'image') === false) {
                                    $error['error'] = "Not Image";
                                }

                                if ($fileSize > 1024 * 2 * 1024) {
                                    $errMsg = "More Than 2mb";
                                }
                            }

                            if (empty($error['error'])) {
                                if (isset($img)) {
                                    $uploadFile = move_uploaded_file($_FILES["user_img"]["tmp_name"], "uploads/users_img/" . $img);
                                    if ($uploadFile === false) {
                                        $error['error'][] = "Images not uploads";
                                    } else {
                                        // $psw = password_hash($psw1, PASSWORD_DEFAULT);
                                        $userInfoUpdate = executeQuery(
                                            "UPDATE users SET
                                        username = ?,
                                        bio = ?,
                                        img = ?
                                        WHERE id = ?",
                                            [
                                                $userName,
                                                $profileText,
                                                $img,
                                                $_SESSION['user']['id'],
                                            ]

                                        );
                                        echo 'changed';
                                    }
                                } else {
                                    $userInfoUpdate = executeQuery(
                                        "UPDATE users SET
                                    username = ?,
                                    bio = ?
                                    WHERE id = ?",
                                        [
                                            $userName,
                                            $profileText,
                                            $_SESSION['user']['id'],
                                        ]

                                    );
                                    echo 'changed without img';
                                }
                            }

                            foreach ($error['error'] as $err) { ?>

                                <div class="profile_error">
                                    <?php echo $err; ?>
                                </div>

                            <?php } ?>


                        <?php } ?>

                        <?php
                        $userInfo = fetch([
                            'limit' => 1,
                            'select' => '*',
                            'table' => 'users',
                            'where' => array(
                                'fields' => array(
                                    array(
                                        'key' => 'id',
                                        'value' => '?',
                                    )
                                )
                            )
                        ], [$_SESSION['user']['id']]);
                        // debug($userInfo, 1);
                        ?>

                        <div class="profile_">
                            <div class="profile_user_name">
                                <p><?php echo $userInfo['username']; ?></p>
                            </div>
                            <div class="password_change"><a href="#" data-bs-toggle="modal" data-bs-target="#modal_change">Change Password</a></div>
                        </div>
                        <div class="info_form">
                            <form action="profile" method="post" enctype="multipart/form-data">
                                <div class="form_box">
                                    <input type="text" name="name" value="<?php echo $userInfo['username']; ?>" placeholder="Name">
                                </div>
                                <div class="form_box email">
                                    <p><?php echo $userInfo['email']; ?></p>
                                </div>
                                <div class="form_textarea">
                                    <textarea name="profile_text" placeholder="Your information"><?php echo $userInfo['bio']; ?></textarea>
                                </div class="form_box">

                                <div class="upload">
                                    <input type="file" name="user_img" multiple>
                                </div>

                                <div class="form_box">
                                    <input type="submit" name="update" value="Save">
                                </div>
                            </form>
                        </div>

                        <!-- modal for change password >>> -->
                        <div class="modal fade" id="modal_change" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="#" method="post" id="pass_change">
                                            <div class="form_pass pass1">
                                                <input type="password" name="psw1" id="psw1" placeholder="Password">
                                            </div class="form_pass">
                                            <div class="form_pass">
                                                <input type="password" name="psw2" id="psw2" placeholder="Re-Enter-Password">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <div class="form_box">
                                            <input type="submit" name="pass_change" id="pass_btn" value="Save">
                                        </div>
                                        <!-- <button type="button" class="btn btn-primary">Save</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- modal for change password <<< -->

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>