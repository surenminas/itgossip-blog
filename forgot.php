<?php

$selectPagesInformation = fetch([
    'select' => 'id, title, meta_d, meta_k,text',
    'table' => 'settings',
    'where' => array(
        'fields' => array(
            array(
                'key' => 'page',
                'value' => '?'
            )
        )
    )
], ['forgot']);

//content 
if (isUserLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'GET') {
    redirect('.');
}
if (isset($_POST['forgot'])) {

    $forgotEmail = $_POST['email'];
    $errorForgot = ['error' => ''];
    $successForgot = '';

    if (checkUserForgot($forgotEmail) == false) {
        $errorForgot['error'] = "There is no user with this email";
    }

    if (empty($errorForgot['error'])) {
        $pswd = rand(10000, 99999);
        $pswdHashed = password_hash($pswd, PASSWORD_DEFAULT);
        if (changePswd($forgotEmail, $pswdHashed)) {
            $to = $forgotEmail;
            $subject = "Password recovery on the site: IT gossip";
            $msg = "Your new password: " . $pswd . " change it on your page";
            mail($to, $subject, $msg);
            $successForgot = "Send password to: " . $forgotEmail;
        }
    } ?>

    <div class="container">
        <div class="row content_without_sidebar">
            <div class="col-lg-7">
                <div class="comment_form">
                    <?php if (isset($successForgot) && !empty($successForgot)) { ?>
                        <div class="contact_form__sent">
                            <p><?php echo $successForgot; ?></p>
                        </div>
                        <div class="contact_form__btn">
                            <button type="submit"><a href=".">Go Back</a></button>
                        </div>
                    <?php } else { ?>
                        <div class="comment_form__title">
                            <p>Enter Your Email</p>
                        </div>
                        <?php if (isset($errorForgot['error'])) { ?>
                            <div class="error_forgot"><?php echo $errorForgot['error']; ?></div>

                            <div class="comment_form__form">
                                <form action="forgot" method="post" id="comment_form">
                                    <div>
                                        <input type="text" name="email" id="email_forgot" placeholder="Email">
                                    </div>
                                    <button type="submit" name="forgot" id="forgot">Send Email</button>
                                </form>
                            </div>
                    <?php }
                    } ?>
                    <div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="container">
        <div class="row content_without_sidebar">
            <div class="col-lg-7">
                <div class="comment_form">
                    <div class="comment_form__title">
                        <p>Enter Your Email</p>
                    </div>
                    <div class="comment_form__form">
                        <div class="error"></div>
                        <form action="forgot" method="post" id="comment_form">
                            <div>
                                <input type="text" name="email" id="email_forgot" placeholder="Email">
                            </div>
                            <button type="submit" name="forgot" id="forgot">Send Email</button>
                        </form>
                    </div>
                    <div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>