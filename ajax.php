<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    throw new \Exception("Page not found", 404);
}

/////////////////////
///// COMMENT ///////
/////////////////////

if (isset($_POST['action']) && $_POST['action'] == 'add_comment') {


    $commentName = $_SESSION['user']['username'];
    $userID = $_SESSION['user']['id'];
    $commentText = $_POST['text'];
    $postID = $_POST['post_id'];
    // if empty, if user is logged in .... echo  error message; exit;

    if (!isset($_POST['parent_id'])) {
        $parentID = 0;
    } else {
        $parentID = $_POST['parent_id'];
    }

    $addComment = executeQuery(
        "INSERT INTO comments (name, text, post_id, user_id, parent_id) 
        VALUES (:commentName, :commentText, :postId, :userId, :parentID)",
        [':commentName' => $commentName, ':commentText' => $commentText, ':postId' => $postID, ':userId' => $userID, ':parentID' => $parentID]
    );
}

///////////////////
///// LOGIN ///////
///////////////////

if (isset($_POST['email']) && isset($_POST['psw'])) {
    $response = ["error" => []];

    $email = $_POST['email'];
    $pswd = $_POST['psw'];

    $selectUserInfo = fetch([
        'select' => '*',
        'table' => 'users',
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'email',
                    'value' => '?'
                )

            )
        )
    ], [$email]);

    if (!$selectUserInfo) {
        $response["error"][] = "Login Password Not Valid";
    }

    if ($email === '') {
        $response["error"][] = "eamil empty";
    }
    if ($pswd === '') {
        $response["error"][] = "password empty";
    }
    if (mb_strlen($email) <= 3) {
        $response["error"][] = "Логин должен быть больше 3 букв";
    }
    if (mb_strlen($pswd) <= 3) {
        $response["error"][] = "Пароль должен быть больше 3 букв";
    }
    if (mb_strlen($pswd) <= 3) {
        $response["error"][] = "Пароль должен быть больше 3 букв";
    }

    if (empty($response["error"])) {

        if (password_verify($pswd, $selectUserInfo['password'])) {

            $selectUserInfo = fetch([
                'select' => '*',
                'table' => 'users',
                'where' => array(
                    // 'condition' => 'AND',
                    'fields' => array(
                        array(
                            'key' => 'email',
                            'value' => ':email'
                        )
                        // array(
                        //     'key' => 'password',
                        //     'value' => ':password'
                        // ),

                    )
                )
            ], ['email' => $email]);


            foreach ($selectUserInfo as $k => $v) {
                if ($k != 'password') {
                    $_SESSION['user'][$k] = $v;
                }
            }
            $_SESSION['last_activity'] = time(); // update last activity time stamp
        } else {
            $response["error"][] = "Login Password Not Valid";
        }
    }
    echo json_encode($response['error']);
}

////////////////////
///// CONTACT //////
///////////////////


if (isset($_POST['name']) && isset($_POST['email'])) {

    require_once(basePath() . "/app/phpmailer/PHPMailerAutoload.php");
    $mail = new PHPMailer;
    $mail->CharSet = 'utf-8';

    $response = ["error" => []];

    $name = $_POST['name'];
    $email = $_POST['email'];
    $text = $_POST['text'];

    $email = (filter_var($email, FILTER_VALIDATE_EMAIL));

    if (empty($name) && empty($email) && empty($text)) {
        $response["error"][] = "fields cannot be empty";
    }

    if (empty($name)) {
        $response["error"][] = "Name field cannot be empty";
    }

    if (empty($email)) {
        $response["error"][] = "Eamil field cannot be empty";
    }

    if (empty($text)) {
        $response["error"][] = "Text field cannot be empty";
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $response["error"][] = "Wrong email";
    }

    if (empty($response["error"])) {

        $userSessionID = null;

        if (isset($_SESSION['user']['id'])) {
            $userSessionID = "User ID: " . $_SESSION['user']['id'];
        }else{
            $userSessionID = "User not login or not registration ";

        }


        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hometvyerevan@gmail.com';                     //SMTP username
        $mail->Password   = 'ijntuarcrwovpmou';                               //SMTP password
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';

        //Recipients
        // $mail->setFrom($mail);                               // who to send the mail
        $mail->addAddress('hometvyerevan@gmail.com');     // to whom the mail will sent 


        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Web page It Gossip, Email: $email";
        $mail->Body    = "$userSessionID, <br> Email: $email <br><br>'Message: <br> $text";

        if ($mail->send()) {
            $response["error"][] = "Mail send";
        } else {
            $response["error"][] = "Mail not send";
        }
    } else {
        $response["error"][] = "Mail not send";
    }
    echo json_encode($response);
}

//////////////////////////
///// REGISTRATION ///////
//////////////////////////

if (isset($_POST['name_reg']) && isset($_POST['email_reg'])) {
	

    $responsRegistration = ['error' => []];


    $name = ucfirst($_POST['name_reg']);
    $email = $_POST['email_reg'];
    $pswd1 = $_POST['pswd'];
    $pswd2 = $_POST['pswd2'];

    // if (empty($name) && empty($email) && empty($pswd1) && empty($pswd2)) {
    //     $responsRegistration['error'][] = "Заполните все поля";
    // }

    if ($name == '') {
        $responsRegistration['error'][] = "Name empty";
    }

    if (mb_strlen($name) < 2) {
        $responsRegistration['error'][] = "Логин должен быть больше 4 букв";
    }

    if ($email == '') {
        $responsRegistration['error'][] = "Email empty";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $responsRegistration['error'][] = "неверный формат email";
    }

    if ($pswd1 == '' || $pswd2 == '') {
        $responsRegistration['error'][] = "password empty";
    }

    if ($pswd1 != $pswd2) {
        $responsRegistration['error'][] = "Пароль не совпадают";
    }

    if (mb_strlen($pswd1) < 5) {
        $responsRegistration['error'][] = "Пароль должен быть больше 6 букв";
    }


    if (empty($responsRegistration['error'])) {

        $checkEmailBusy = fetch([
            'select' => 'username',
            'table' => 'users',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'email',
                        'value' => '?'
                    )
                )
            )
        ], [$email]);

        if (!empty($checkEmailBusy)) {
            $responsRegistration['error'][] = "email busy";
        }

        if (empty($checkLoginBusy) && empty($checkEmailBusy)) {
            $pswd = password_hash($pswd1, PASSWORD_DEFAULT);
            $img = "avatar_2.png";
            $registration = executeQuery(
                "INSERT INTO users (username, email, img, password) VALUES (?, ?, ?, ?)",
                [$name, $email, $img, $pswd]
            );
        }
    }
    echo json_encode($responsRegistration);
}


////////////////////////
///// SUBSCRIBE ///////
///////////////////////

if (isset($_POST['subscribe_name']) && isset($_POST['subscribe_email'])) {

    $subscribeError = ['error' => []];

    $userExists = false;
    if (isset($_SESSION['user']['id'])) {
        $sessionID = $_SESSION['user']['id'];
        $userExists = ifUserSubscribed($sessionID);
    }
    if ($userExists == false) {

        $subscribeUserName = $_POST['subscribe_name'];
        $subscribeEmail = $_POST['subscribe_email'];
        $subscribeEmail = (filter_var($subscribeEmail, FILTER_VALIDATE_EMAIL));

        if (empty($subscribeUserName)) {
            $subscribeUserName['error'] = "Name empty";
        }

        if (empty($subscribeEmail)) {
            $subscribeError['error'] = "Email empty";
        }

        if (filter_var($subscribeEmail, FILTER_VALIDATE_EMAIL) == false) {
            $subscribeError['error'] = "Enter validatr email";
        }

        $checkEmailSub = fetch([
            'select' => 'email',
            'table' => 'subscribers',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'email',
                        'value' => '?'
                    )
                )
            )
        ], [$subscribeEmail]);

        if (!empty($checkEmailSub)) {
            $subscribeError['error'] = "subscribed";
        }



        if (empty($subscribeError['error'])) {

            if (isset($sessionID)) {

                $result = executeQuery(
                    "INSERT INTO `subscribers` (`email`, `full_name`, `user_id`) VALUES (?, ?, ?)",
                    [
                        $subscribeEmail,
                        $subscribeUserName,
                        $sessionID
                    ]
                );
                if ($result == NULL) {
                    $subscribeError['error'] = "successfully";
                }
            } else {

                $result = executeQuery(
                    "INSERT INTO `subscribers` (`email`, `full_name`, `user_id`) VALUES (?, ?, ?)",
                    [
                        $subscribeEmail,
                        $subscribeUserName,
                        0
                    ]
                );
                if ($result == NULL) {
                    $subscribeError['error'] = "successfully";
                }
            }
        }
    }

    echo json_encode($subscribeError);
}

/////////////////////////////
///// CHANGE PASSWORD ///////
/////////////////////////////

if (isset($_POST['action']) && $_POST['action'] == 'change') {

    $passwordChangeError = ['error' => []];

    $psw1 = $_POST['psw1'];
    $psw2 = $_POST['psw2'];

    if (empty($psw1) || empty($psw2)) {
        $passwordChangeError['error'][] = "Passwords Empty";
    }

    if ($psw1 != $psw2) {
        $passwordChangeError['error'][] = "Passwords do not match ";
    }

    if (empty($passwordChangeError['error'])) {
        $psw = password_hash($psw1, PASSWORD_DEFAULT);
        $passInfoUpdate = executeQuery(
            "UPDATE users SET
            password = ?
            WHERE id = ?",
            [
                $psw,
                $_SESSION['user']['id'],
            ]

        );
    }
    echo json_encode($passInfoUpdate);
}


//??????

if (isset($_POST['action']) && $_POST['action'] == 'image') {
    exit('11111111');
    if (!$_FILES['file']['error']) {
        $name = md5(rand(100, 200));
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $filename = $name .
            '.' . $ext;
        $destination = 'uploads/posts_img/' . $filename; //change this directory
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        echo $filename;
        // echo ROOT . '/uploads/posts_img/' . $filename; //change this URL
    } else {
        $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
        echo $message;
    }
}
