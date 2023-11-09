<?php

// change name later
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
], ['search']);

$errMsg = '';

// Content -->

if (isset($_POST['search'])) {

    if (mb_strlen($_POST['search_text']) < 2) {
        echo $searchErr;
        echo "error";
        // echo $errMsg;
        // $referer = $_SERVER['HTTP_REFERER'];
        // header("location: $referer"); 
    } else {

        $searchText = $_POST['search_text'] . '*';
        $allSearchResults = executeQuery(
            "SELECT blog_posts.*, users.username, blog_categories.name as category_name,
            MATCH(blog_posts.title, blog_posts.description) AGAINST(:search IN BOOLEAN MODE) as relevanceByScour 
            FROM `blog_posts` 
            LEFT JOIN `users` ON blog_posts.author_id = users.id
            LEFT JOIN `blog_categories` ON blog_posts.category_id = blog_categories.id
            WHERE publish_status = 1 AND type = 'post' AND MATCH(title, description) AGAINST(:search2 IN BOOLEAN MODE)
            order by relevanceByScour DESC",
            ['search' => $searchText, 'search2' => $searchText]
        );

        // echo "<pre>";
        // var_dump($allSearchResults);

        if (!empty($allSearchResults)) { ?>
            <!-- search >>> -->
            <div class="search_in_page">
                <form action="search" method="post" class="search_form_page">
                    <input type="text" name="search_text" class="search_from_page__text" placeholder="Search..." value="<?php if(!empty($_POST['search_text'])) echo$_POST['search_text'];  ?>"/>
                    <button type="submit" name="search" id="search_bt"><img src="img/search.svg" alt="Search for materials"></button>
                </form>
                <p class="error_txt_page"></p>
            </div>
            <div class="row">

                <!-- search <<< -->

                <?php foreach ($allSearchResults as $key => $allSearchResult): ?>
                    <div class="col-md-6 post_card">
                        <div class="inner">
                            <div class="thumbnail">
                                <a href="single?page=<?php echo $allSearchResult['id']; ?>"><img src="uploads/posts_img/<?php echo $allSearchResult['img']; ?>" class="rounded" alt="posts"></a>
                            </div>
                            <div class="card_block_body">
                                <ul class="rainbow_meta_list">
                                    <li><span>By:<a href="author?author_id=<?php echo $allSearchResult['author_id'] ?> "><?php echo $allSearchResult['username'] ?> </span><a href="author?author_id=<?php echo $allSearchResult['author_id']; ?>"><?php //echo $allSearchResult['author_name']; 
                                                                                                                                                                                                                                                    ?></a></li>
                                    <li>, <?php echo date("d M, Y", $allSearchResult['date']) ?></li>
                                </ul>
                                <h3 class="card_block__title"><a href="single?page=<?php echo $allSearchResult['id']; ?>"><?php echo $allSearchResult['title']; ?></a></h3>
                                <p class="card_block__text"><?php echo $allSearchResult['description']; ?></p>
                                <ul class="card_block_comment">
                                    <li>In: <span><a href="categories?page=<?php echo $allSearchResult['category_id']; ?> "><?php echo $allSearchResult['category_name']; ?></a> </span></li>
                                    <li>, <?php echo $allSearchResult['comment_count']; ?> Comments</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php
        } else {
        ?>
            <div class="row">
                <div class="search_in_page">
                    <form action="search" method="post" class="search_form_page">
                        <input type="text" name="search_text" class="search_from_page__text" placeholder="Search..." />
                        <button type="submit" name="search" id="search_bt"><img src="img/search.svg" alt="Search for materials"></button>
                    </form>
                    <p class="error_txt_page"></p>
                </div>
                <div class="search_in_page">
                    <div class="pt-4 pb-4">Nothing found for your request</div>
                </div>
            </div>

    <?php }
    }
} else { ?>

    <div class="row">
        <div class="search_in_page">
            <form action="search" method="post" class="search_form_page">
                <input type="text" name="search_text" class="search_from_page__text" placeholder="Search..." />
                <button type="submit" name="search" id="search_bt"><img src="img/search.svg" alt="Search for materials"></button>
            </form>
            <p class="error_txt_page"></p>
        </div>
    </div>

<?php } ?>