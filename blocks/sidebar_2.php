<div class="col-lg-4 sidebar">
    <div class="col-md-12">
        <div class="api_sidebar bg_side">
            <h5>API</h5>
            <ul class="api_sidebar_list">
                <li>
                    <a href='simple-api?type=post'>Simple API</a>
                </li>
                <li>
                    <a href='simpleAPI'>CURL to simple API</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-12">
        <div class="lastests_posts_with_photos bg_side">
            <h5>Latest Posts With Thumbnail</h5>
            <?php
            $last_4_posts = fetchAll([
                'order_by' => 'id DESC',
                'limit' => 4,
                'select' => 'id, title, img',
                'table' => 'blog_posts',
                'where' => array(
                    'condition' => 'AND',
                    'fields' => array(
                        array(
                            'key' => 'type',
                            'value' => 'post'
                        ),
                        array(
                            'key' => 'publish_status',
                            'value' => '1'
                        )
                    )
                )
            ]);
            ?>
            <?php foreach ($last_4_posts as $key => $posts_img) : ?>
                <div class="lastests_posts_with_photos__block">
                    <div class="lastests_posts_with_photos__img">
                        <a href="single?page=<?php echo $posts_img['id']; ?>"><img src="uploads/posts_img/<?php echo $posts_img['img']; ?>"></a>
                    </div>
                    <div class="lastests_posts_with_photos__text">
                        <p><a href="single?page=<?php echo $posts_img['id']; ?>"><?php echo $posts_img['title'] ?></a></p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>


    <div class="col-md-12">
        <div class="lastests_posts_with_photos bg_side">
            <h5>Exchange rates</h5>

            <?php $getExchangeRates = getExchangeRatesAmd(); ?>
            <div class="table_rate pb-3">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <div class="text-center">
                                    <span><?php echo date("d.m.Y"); ?></span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <span>Currency</span>
                            </td>
                            <td>
                                <span>AMD</span>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($getExchangeRates as $key => $value) : ?>
                            <tr>
                                <td><?php echo $key; ?></td>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!--  Subscribe >>> -->
    <?php
    if (isset($_SESSION['user']['id'])) {
        $subscribeEmailCheck = $_SESSION['user']['id'];
        $checkEmail = fetch([
            'select' => 'user_id',
            'table' => 'subscribers',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'user_id',
                        'value' => '?'
                    )
                )
            )
        ], [$subscribeEmailCheck]);
    }


    if (empty($checkEmail)) { ?>
        <div class="col-md-12">
            <div class="subscribe bg_side pb-5">
                <h5>Subscribe</h5>
                <form name="SR_form" method="post" action="." class="sub_form">
                    <div class="form-group">
                        <input type="text" class="form-control mb-3" name="subscribe_name" id="subscribe_name" placeholder="Your name" maxlength=50>
                    </div>
                    <div class="form-group">
                        <input type=text class="form-control mb-3" name="subscribe_email" id="subscribe_email" placeholder="Your email address" maxlength=50>
                    </div>
                    <input type="submit" name="subscribe" id="subscribe_btn" value="Subscribe">
                </form>
                <p class="error_txt_sub d-none">Subscribed</p>
            </div>
        </div>
    <?php } ?>


    <!--  Subscribe <<< -->
</div>