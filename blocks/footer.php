<!-- Footer >>> -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 footer_about">
                <h4>IT Gossip</h4>
                <div class="footer_about__text">
                    <p>
                        <?php
                        $string = "Welcome to ITGossip, a blog dedicated to providing valuable and informative content about web development technologies such as 
                        HTML, CSS, JavaScript, and PHP. As a self-taught web developer from Armenia, I understand the importance of having access to quality 
                        resources to improve your coding skills. Here on ITGossip";

                        echo str_size_header($string, 350, "... "); ?><a href="<?php echo BASE_URL ?>about">Read more</a>
                    </p>
                </div>
            </div>
            <div class="col-md-4 footer_last_posts">
                <h4>Lastest posts</h4>
                <ul>
                    <li><a href="#">What Bronze Medalist Michael Woods Eats to Fuel His Rides</a></li>
                    <li><a href="#">3 Things You Can Do to Get a Better Spin Class Workout</a></li>
                    <li><a href="#">Save Big on These 10 Great Indoor Trainers</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4>Contact</h4>
                <div class="socials">
                    <ul class="socials_contact">
                        <li>Armenia, Yerevan</li>
                        <li>mail@mail.ru</li>
                        <li>+(374)99-000-000</li>
                    </ul>
                    <ul>
                        <li class="footer_first">
                            <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="footer_bottom">
    <p>Copyright 2023 by <span>IT Gossip</span>, All Right Reserved</p>
</div>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<?php

getActionStyleAndScript();

// debug($GLOBALS['allStylesheetAndScript']);

foreach ($GLOBALS['allStylesheetAndScript']['script'] as $key => $value) : ?>
    <script type="text/javascript" src="<?php echo $value; ?>"></script>
<?php endforeach; ?>
</div>

<?php foreach ($GLOBALS['allStylesheetAndScript']['stylesheet'] as $key => $value) {
    echo '<link href="' . $value . '" rel="stylesheet" type="text/css" />';
}       ?>
<!-- Footer <<< -->

</body>

</html>