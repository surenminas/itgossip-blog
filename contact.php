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
], ['contacts']);

$errMsg = '';


?>


<div class="container">
    <div class="row content_without_sidebar">
        <div class="col-lg-7">
            <div class="comment_form">
                <div class="error_txt_contact">
                    <p >Something went wrong, Try later</p>
                </div>
                <div class="comment_form__title">
                    <p>Send Us Message</p>
                </div>
                <div class="comment_form__form">
                    <form action="#" method="post" id="comment_form" class="contact_form">
                        <div id="contact_form_name">
                            <input type="text" name="name" id="contact_name" placeholder="Your Name">
                        </div>
                        <div id="contact_form_email">
                            <input type="email" name="email" id="contact_email" placeholder="Email">
                        </div>
                        <div id="contact_form_text">
                            <textarea name="text" id="contact_text" placeholder="Leave a comment here" id="text" rows="5"></textarea>
                        </div>
                        <button type="submit" name="contact">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>