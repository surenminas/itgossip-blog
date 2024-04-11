<?php
// debug($_FILES, 1);


if (isset($_FILES['file']['name'])) {
    if (!$_FILES['file']['error']) {
        $name = md5(rand(100, 200));
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $filename = $name .
            '.' . $ext;
        $destination = '../uploads/posts_img/' . $filename; //change this directory
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        echo BASE_URL . $filename;
        // echo ROOT . '/uploads/posts_img/' . $filename; //change this URL
    } else {
        $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
        return $message;
    }
}
