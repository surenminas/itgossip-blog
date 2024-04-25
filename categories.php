<?php

// $selectPagesInformation = fetch([
//     'select' => 'id, title, meta_d, meta_k,text',
//     'table' => 'settings',
//     'where' => array(
//         'fields' => array(
//             array(
//                 'key' => 'page',
//                 'value' => '?'
//             )
//         )
//     )
// ], ['categories']);

if (isset($_GET['page'])) {
    $categoryId = $_GET['page'];

    // pagination set >>>
    $countPostRows = executeQuery(
        "SELECT COUNT(*) as totalRowsCount FROM `blog_posts` WHERE type != 'photo' AND publish_status = 1 AND category_id = ? ",
        [$categoryId]
    );
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['p']) ? $_GET['p'] : 1;
    $perPage = 6;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);

    $paginationRange = 2;

    // pagination set <<<


    // post cache >>>
    $allPostsWithCategory = Cache::get('id' . $_GET['page'] . 'page' . $page);
    if (!$allPostsWithCategory) {
        $allPostsWithCategory = executeQuery(
            " SELECT blog_posts.*, 
        users.author_posts_count, users.username as author_name, users.id as user_id, 
        blog_categories.name as category_name, blog_categories.posts_count as posts_count
        from blog_posts 
        LEFT JOIN users ON blog_posts.author_id = users.id
        LEFT JOIN blog_categories ON blog_posts.category_id = blog_categories.id
        WHERE blog_posts.category_id = ? and blog_posts.type = 'post' and publish_status = 1 
        order by id DESC 
        limit $perPage OFFSET $offset",
            [$categoryId]
        );

        Cache::set('id' . $_GET['page'] . 'page' . $page, $allPostsWithCategory);
    }
    // post cache <<<




?>

    <div class="row">
        <?php foreach ($allPostsWithCategory as $key => $allPost) : ?>
            <div class="col-md-6 post_card">
                <div class="inner">
                    <div class="thumbnail">
                        <a href="single?page=<?php echo $allPost['id']; ?>"><img src="uploads/posts_img/<?php echo $allPost['img']; ?>" class="rounded" alt="posts"></a>
                    </div>
                    <div class="card_block_body">
                        <ul class="rainbow_meta_list">
                            <li><span>By: </span><a href="author?author_id=<?php echo $allPost['user_id']; ?>"><?php echo $allPost['author_name']; ?></a></li>
                            <li>, <?php echo date("d M, Y", $allPost['date']) ?></li>
                        </ul>
                        <h3 class="card_block__title"><a href="single?page=<?php echo $allPost['id']; ?>"><?php echo $allPost['title']; ?></a></h3>
                        <p class="card_block__text"><?php echo $allPost['description']; ?></p>
                        <ul class="card_block_comment">
                            <li>In: <span><a href="categories?page=<?php echo $allPost['category_id']; ?> "><?php echo $allPost['category_name']; ?></a> </span></li>
                            <li>, <?php echo $allPost['comment_count']; ?> Comments</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination >>> -->
    <div class="pagination_posts">
        <nav aria-label="Page navigation example pagination_posts">
            <ul class="pagination">

                <?php if ($totalPagesCount > 1) : ?>
                    <?php if ($page != 1) : ?>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=1">First</a></li>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=<?php echo $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>

                    <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                        <?php if ($i < 1) continue; ?>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>

                    <!-- page 1 > -->
                    <li class="page-item active" aria-current="page">
                        <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                    </li>
                    <!-- page 1 < -->

                    <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                        <?php if (($i + 1)  > $totalPagesCount) break; ?>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                    <?php } ?>


                    <?php if ($page != $totalPagesCount) : ?>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=<?php echo $page + 1 ?>">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="categories?page=<?php echo $categoryId; ?>&p=<?php echo $totalPagesCount; ?>">Last</a></li>
                    <?php endif; // LAST 
                    ?>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- Pagination < -->
    </div>
    <!-- Pagination <<< -->

<?php } else { ?>

    <?php
    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `blog_categories` WHERE 1");
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['a']) ? $_GET['a'] : 1;
    $perPage = 15;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);

    $paginationRange = 2;

    $caregories = fetchAll([
        'select' => 'id, name, posts_count',
        'table' => 'blog_categories',
        'limit' => "$perPage",
        'offset' => "$offset",
        'where' => array(
            'fields' => array(
                array(
                    'key' => 'id',
                    'value' => '5',
                    'operator' => '!='
                )
            )
        )
    ]);


    ?>
    <div class=" author_page d-flex bg-white">Categories</div>
    <ul class="list-group">
        <?php foreach ($caregories as $key => $category) : ?>
            <a class="list-group-item list-group-item-action" href="categories?page=<?php echo $category['id']; ?>">
                <li class="d-flex justify-content-between align-items-center">
                    <?php echo $category['name']; ?>
                    <span class="badge bg-primary rounded-pill"><?php echo $category['posts_count']; ?></span>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>
    <?php if ($totalPagesCount > 1) : ?>
        <!-- Pagination >>> -->
        <div class="pagination_posts">
            <nav aria-label="Page navigation example pagination_posts">
                <ul class="pagination">

                    <?php if ($page != 1) : ?>
                        <li class="page-item"><a class="page-link" href="?a=1">First</a></li>
                        <li class="page-item"><a class="page-link" href="?a=<?php echo $page - 1 ?>">Previous</a></li>
                    <?php endif; ?>


                    <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                        <?php if ($i < 1) continue; ?>
                        <li class="page-item"><a class="page-link" href="?a=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>

                    <!-- page 1 > -->
                    <li class="page-item active" aria-current="page">
                        <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                    </li>
                    <!-- page 1 < -->

                    <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                        <?php if (($i + 1)  > $totalPagesCount) break; ?>
                        <li class="page-item"><a class="page-link" href="?a=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                    <?php } ?>


                    <?php if ($page != $totalPagesCount) : ?>
                        <li class="page-item"><a class="page-link" href="?a=<?php echo $page + 1 ?>">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="?a=<?php echo $totalPagesCount; ?>">Last</a></li>
                    <?php endif; // LAST 
                    ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
    <!-- Pagination <<< -->


<?php } ?>