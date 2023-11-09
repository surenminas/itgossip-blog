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
                                        ],['posts']);

    $errMsg = '';

    // Content 

    // debug($_GET);

    $countPostRows = executeQuery("SELECT COUNT(*) as totalRowsCount FROM `blog_posts` WHERE type != 'photo'");
    $totalRowsCount = $countPostRows[0]["totalRowsCount"]; 

    $page = isset($_GET['page']) ? $_GET['page']: 1;
    $perPage = 3;
    $offset = ($page - 1) * $perPage;

    $totalPagesCount = ceil($totalRowsCount / $perPage);
    

    $paginationRange = 2;


    $allPosts = executeQuery("
        SELECT id, title, img, date, description 
        from blog_posts 
        where type = 'post' 
        order by id DESC 
        limit $perPage OFFSET $offset
    ");
    
    foreach($allPosts as $key => $allPost): ?>
        <div class='lesson'>
            <div class='lesson_title'>
                <p class='listName'><a href='single?id=<?php echo $allPost['id']?>' ><?php echo $allPost['title']?></a></p>
                <p><img src='uploads/posts_img/<?php echo (!empty($allPost['img']))? $allPost['img'] : '../nophoto.png' ; ?>' width='150'></p>
                <!-- <p class='listNameP'>Автор урока։ <a href="author.php?author_id=<?php //echo $allSearchResult['author_id']; ?>"></p>   -->
                <p class='listNameP'>Дата добавления: <?php echo date("Y-m-d", $allPost['date'])?></p>
            </div>
            <div class='post_description'><?php echo $allPost['description']?></div>            
        </div>
    <?php endforeach; ?>

    <!-- Pagination > -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center ">
            <?php if( $page != 1 ): ?>
                <li class="page-item"><a class="page-link" href="posts?page=1">First</a></li>
                <li class="page-item"><a class="page-link" href="posts?page=<?php echo $page - 1 ?>">Previous</a></li>
            <?php endif; ?>


            <?php for($i = ($page - $paginationRange); $i < $page; $i++){  ?>
                <?php if( $i < 1 ) continue; ?>
                <li class="page-item"><a class="page-link" href="posts?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php } ?>

            <!-- page 1 > -->
            <li class="page-item active" aria-current="page">
                <span class="page-link bs-gray-100"><?php echo $page;?></span>
            </li>
            <!-- page 1 < -->

            <?php for($i = $page; $i < ($page+$paginationRange); $i++){  ?>
                <?php if( ($i+1)  > $totalPagesCount ) break; ?>
                <li class="page-item"><a class="page-link" href="posts?page=<?php echo $i+1; ?>"><?php echo $i+1; ?></a></li>
            <?php } ?>


            <?php if( $page != $totalPagesCount ): ?>
                <li class="page-item"><a class="page-link" href="posts?page=<?php echo $page + 1 ?>">Previous</a></li>
                <li class="page-item"><a class="page-link" href="posts?page=<?php echo $totalPagesCount; ?>">Last</a></li>
            <?php endif;// LAST ?>
        </ul>
    </nav>
    <!-- Pagination < -->

    
