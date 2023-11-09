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
], ['registration']);

// Content -->
if (isUserLoggedIn()) {
    header("location: main");
} ?>

    <div class="container content">
        <div class="row content_without_sidebar">
            <div class="col-lg-7">
                <div class="reg_form">
                    <div class="reg_form__title">
                        <p>Registration</p>
                    </div>
                    <div class="reg_form__form">
                        <form action="registration" method="post" id="reg_form" class="reg-form" style="<?php if (isset($registration) && $registration != false) echo 'display:none'; ?>">
                            <div>
                                <input type="text" name="name_reg" id="name_rgn" placeholder="Your Name*">
                            </div>
                            <div>
                                <input type="email" name="email_reg" id="email_rgn" placeholder="Your Email*">
                            </div>
                            <p class="error_txt_reg">Email busy</p>

                            <div>
                                <input type="password" name="pswd" id="psw1_rgn" placeholder="Password*">
                            </div>
                            <div>
                                <input type="password" name="pswd2" id="psw2_rgn" placeholder="Re-enter Password*">
                            </div>
                            <button type="submit" name="register" id="reg_btn">Register</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

