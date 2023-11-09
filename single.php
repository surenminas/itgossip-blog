    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_comment'])) {
        $commentText = $_POST['text'];
        $postID = $_GET['page'];
        $userID = $_SESSION['user']['id'];
        $userName = $_SESSION['user']['username'];

        if (!isset($_POST['parent_id'])) {
            if (empty($commentText)) {
                $_SESSION['error'] = "Fields must be filled";
            }
        }

        if (!isset($_SESSION['error']) || $_SESSION['error'] == '') {
            if (!isset($_POST['parent_id'])) $parentID = 0;
            else $parentID = $_POST['parent_id'];
            $addComment = executeQuery(
                "INSERT INTO comments (name, text, post_id, user_id, parent_id) 
            VALUES (?, ?, ?, ?, ?)",
                [$userName, $commentText, $postID, $userID, $parentID]
            );
            if ($addComment === false) {
                $_SESSION['error'] = "Ops, please try again later";
            } else {
                $_SESSION['success'] = "Your comment has been added";
            }
        }
    }

    if (isset($_GET['page'])) {

        executeQuery("UPDATE blog_posts SET  blog_posts.view = blog_posts.view + 1  WHERE id = ? ", [$_GET['page']]); // query for plus view

        // cache post >>> 
        $postsViewOnSinglePage = Cache::get('single_post' . $_GET['page']);
        if (!$postsViewOnSinglePage) {
            echo "caching";
            $postsViewOnSinglePage = executeQuery(" SELECT 
            blog_posts.*, blog_categories.name as category_name, 
            users.username as author_name, users.bio as author_bio, users.author_posts_count, users.img as user_img, users.id as user_id from blog_posts 
            LEFT JOIN blog_categories ON blog_posts.category_id = blog_categories.id
            LEFT JOIN users ON blog_posts.author_id = users.id
            WHERE publish_status = 1 AND blog_posts.id = ?", [$_GET['page']]);

            $postsViewOnSinglePage = Cache::set('single_post' . $_GET['page'], $postsViewOnSinglePage);
            $postsViewOnSinglePage = Cache::get('single_post' . $_GET['page']);
        }
        $selectPagesInformation = ["meta_d" => $postsViewOnSinglePage[0]['meta_d'], "meta_k" => $postsViewOnSinglePage[0]['meta_k'], "title" => $postsViewOnSinglePage[0]['title']];
        // cache post <<<
    ?>
        <?php foreach ($postsViewOnSinglePage as $key => $postViewOnSinglePage) : ?>
            <div class="single_page">
                <div class="single_page__img">
                    <img src="uploads/posts_img/<?php echo $postViewOnSinglePage['img']; ?>">
                </div>

                <div class="single_page__content">
                    <div class="single_page__title">
                        <h1><?php echo $postViewOnSinglePage['title']; ?></h1>
                        <ul>
                            <li>By:<a href="author?author_id=<?php echo $postViewOnSinglePage['user_id']; ?>"> <?php echo $postViewOnSinglePage['author_name']; ?></a></li>
                            <li>, <?php echo date("d M, Y", $postViewOnSinglePage['date']) ?>, </li>
                            <li>In: <span><a href="categories?page=<?php echo $postViewOnSinglePage['category_id']; ?> "><?php echo $postViewOnSinglePage['category_name']; ?></a> </span>, <?php echo $postViewOnSinglePage['comment_count'];
                                                                                                                                                                                            ?> comment</li>
                        </ul>
                    </div>

                    <div class="single_page__text">
                        <p><?php echo $postViewOnSinglePage['text']; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


        <!-- Related articles >>> -->

        <?php $getLastPostsByCategories = fetchAll([
            'select' => 'id, title, img',
            'table' => 'blog_posts',
            'order_by' => 'id DESC',
            'limit' => 3,
            'where' => array(
                'condition' => 'AND',
                'fields' => array(
                    array(
                        'key' => 'category_id',
                        'value' => '?'
                    )
                )
            )
        ], [$postsViewOnSinglePage[0]['category_id']]);

        ?>
        <div class="related_article">
            <div class="related_article__p">
                <p>Related Articles</p>
            </div>
            <div class="container inner_container">
                <div class="row">
                    <?php foreach ($getLastPostsByCategories as $key => $posts) : ?>
                        <div class="col-lg-4 col-md-6 inner_container__topic">
                            <div class="inner_related">
                                <div class="related_article__img">
                                    <a href="single?page=<?php echo $posts['id']; ?>"><img src="uploads/posts_img/<?php echo $posts['img']; ?>"></a>
                                </div>
                                <div class="related_article__text">
                                    <p><a href="single?page=<?php echo $posts['id']; ?>"><?php echo $posts['title']; ?></a></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Related articles <<< -->


        <!-- User bio >>> -->
        <div class="single_page_author_info">
            <div class="single_page_author_info__avatar">
                <img src="uploads/users_img/<?php echo $postsViewOnSinglePage[0]['user_img']; ?>" alt="avatar">
            </div>
            <div class="single_page_author_info__text">
                <p class="single_page_author_info__name"><?php echo $postsViewOnSinglePage[0]['author_name']; ?></p>
                <p><?php echo $postsViewOnSinglePage[0]['author_bio']; ?></p>
            </div>
        </div>
        <!-- User bio <<< -->


        <!-- Comment form >>> -->
        <div class="comment_form">
            <div class="comment_form__title">
                <p>Leave a reply</p>
            </div>
            <div class="comment_form__form">
                <form action="single?page=<?php echo $_GET['page']; ?> " method="post" id="comment_form">
                    <div>
                        <textarea name="text" id="comment_text" placeholder="Leave a comment here" rows="5"></textarea>
                    </div>
                    <div>
                        <input type="hidden" name="post_id" class="post_id" value="<?php echo $_GET['page'] ?>">
                    </div>
                    <button type="submit" name="send_comment">Post Comment</button>
                </form>
            </div>
        </div>
        <!-- Comment form <<< -->


        <?php
        $checkCommentAvailable = fetch([
            'select' => 'text, name',
            'table' => 'comments',
            'where' => array(
                'fields' => array(
                    array(
                        'key' => 'post_id',
                        'value' => '?'
                    )
                )
            )
        ], [$_GET['page']]);


        // Select comments 
        $queryComment = executeQuery("SELECT comments.*, users.img as user_img FROM comments 
    LEFT JOIN users ON users.id = comments.user_id WHERE post_id = ? ORDER BY id DESC", [$_GET['page']]);


        foreach ($queryComment as $key => $value) {
            $data[$value['id']] = $value;
        }
        // debug($data);

        if (!empty($data)) {
            $tree = getTree($data);
        }
        ?>

        <!-- Show comment >>> -->
        <?php if ($checkCommentAvailable) : ?>
            <div class="comment">
                <div class="comment_form__title">
                    <p>Comment</p>
                </div>

                <div class="comment_block">
                    <?php buildCascadCommHtml($tree); ?>
                </div>

                <!-- <div class="wrap_comment_block d-none">
                    <div class="comment_content">
                        <div class="comment_content__avatar">
                            <img src="uploads/users_img/<?php //echo $item['user_img'] 
                                                        ?>" alt="avatar">
                        </div>
                        <div class="comment_content__text">
                            <div class="comment_content__name"><?php //echo $item['name'] 
                                                                ?>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div> 
                <div class="reply"><span>Reply</span></div> -->

                    <!-- <div class="reply_block d-none">
                        <form action="#" method="post">
                            <input type="text" name="text" class="reply_block__text">
                            <input type="hidden" name="parent_id" value="<?php //echo $data['id']  
                                                                            ?>">
                            <input type="hidden" name="post_id" class="post_id" value="<?php //echo $_GET['page'] ?>">
                            <button name="send_comment" class="reply_block__btn">Reply</button>
                        </form>
                    </div>
                </div> -->


            </div>
        <?php else : ?>
            <div class="comment">
                <div class="comment_form__title">
                    <p>Comment</p>
                </div>
                <div class="comment_block">
                    <p class="no_comment">No Comment</p>
                </div>
            </div>

        <?php endif; ?>

        <!-- Show comment <<< -->
    <?php } ?>