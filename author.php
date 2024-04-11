<?php

//view author posts >>>
if (isset($_GET['author_id'])) {
    $authorId = $_GET['author_id'];

    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `blog_posts` WHERE type != 'photo'AND author_id = ? AND publish_status = 1", [$authorId]);
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['p']) ? $_GET['p'] : 1;
    $perPage = 6;
    $offset = ($page - 1) * $perPage;




    $authorPostLists = executeQuery(" SELECT blog_posts.id, blog_posts.title, blog_posts.img, blog_posts.date, blog_posts.publish_status, blog_posts.description, blog_posts.comment_count, users.username as author_name, users.id as user_id 
        from blog_posts 
        LEFT JOIN users ON blog_posts.author_id = users.id
        where type = 'post' AND publish_status = 1 AND blog_posts.author_id = ?
        order by id DESC 
        limit $perPage OFFSET $offset
    ", [$authorId]);

    $totalPagesCount = ceil($totalRowsCount / $perPage);
    $paginationRange = 2;


?>

    <?php if (!empty($authorPostLists)) : ?>
        <div class="row">
            <?php foreach ($authorPostLists as $key => $post) : ?>
                <div class="col-md-6 post_card">
                    <div class="inner">
                        <div class="thumbnail">
                            <a href="single?page=<?php echo $post['id']; ?>"><img src="uploads/posts_img/<?php echo $post['img']; ?>" class="rounded" alt="posts"></a>
                        </div>
                        <div class="card_block_body">
                            <ul class="rainbow_meta_list">
                                <li><span>By: </span><a href=""><?php echo $post['author_name']; ?></a></li>
                                <li>, <?php echo date("d M, Y", $post['date']) ?></li>
                            </ul>
                            <h3 class="card_block__title"><a href="single?page=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h3>
                            <p class="card_block__text"><?php echo $post['description']; ?></p>
                            <ul class="card_block_comment">
                                <li>In: <span>Reviews </span></li>
                                <li>, <?php echo $post['comment_count']; ?> Comments</li>
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
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=1">First</a></li>
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=<?php echo $page - 1 ?>">Previous</a></li>
                        <?php endif; ?>


                        <?php for ($i = ($page - $paginationRange); $i < $page; $i++) {  ?>
                            <?php if ($i < 1) continue; ?>
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>

                        <!-- page 1 > -->
                        <li class="page-item active" aria-current="page">
                            <span class="page-link bs-gray-100"><?php echo $page; ?></span>
                        </li>
                        <!-- page 1 < -->

                        <?php for ($i = $page; $i < ($page + $paginationRange); $i++) {  ?>
                            <?php if (($i + 1)  > $totalPagesCount) break; ?>
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a></li>
                        <?php } ?>


                        <?php if ($page != $totalPagesCount) : ?>
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=<?php echo $page + 1 ?>">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="author?author_id=<?php echo $authorId; ?>&p=<?php echo $totalPagesCount; ?>">Last</a></li>
                        <?php endif; // LAST 
                        ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <!-- Pagination <<< -->
    <?php else : ?>
        <h1>Not posts yet</h1>
    <?php endif; ?>

<?php
} else {
    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `users` WHERE 1");
    $totalRowsCount = $countPostRows[0]["totalRowsCount"];

    $page = isset($_GET['a']) ? $_GET['a'] : 1;
    $perPage = 15;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);

    $paginationRange = 2;

    $authorsName = fetchAll([
        'select' => 'id, username, img, bio, author_posts_count',
        'table' => 'users',
        'order_by' => 'username ASC',
        'limit' => "$perPage",
        'offset' => "$offset",
        'where' => array(
            'condition' => 'AND',
            'fields' => array(
                array(
                    'key' => '1',
                    'value' => '?',

                ),
            )
        )
    ], ['1']);

?>
    <div class=" author_page d-flex bg-white">Authros Name</div>
    <ul class="list-group">
        <?php foreach ($authorsName as $key => $authorName) : ?>
            <a class="list-group-item list-group-item-action" href="author?author_id=<?php echo $authorName['id']; ?>">
                <li class="d-flex justify-content-between align-items-center">
                    <?php echo $authorName['username']; ?>
                    <span class="badge bg-primary rounded-pill"><?php echo $authorName['author_posts_count']; ?></span>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>

    <!-- Pagination >>> -->
    <?php if ($totalPagesCount > 1) : ?>
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