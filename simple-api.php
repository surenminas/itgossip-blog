<?php
    
    if(!empty($_GET) && $_GET['type']){
        $postTypes = ['photo' => 1, 'post' => 1];
        $limit = 6;
        $type = 'post';


        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            if( is_numeric($_GET['limit']) ) $limit = $_GET['limit'];
            else exit('wrong post count');
        }  
        if( $limit > 30 ) $limit = 30;
        
        if(isset($_GET['type'])){
            if( isset($postTypes[$_GET['type']]) ) $type = $_GET['type'];
            else exit('wrong post type');
        }

        $lastAuthorPostsAndCategory = executeQuery("SELECT blog_posts.*, users.username as username from blog_posts
        LEFT JOIN users ON blog_posts.author_id = users.id
        WHERE type = :types  AND publish_status = 1 ORDER BY id desc LIMIT :limit ", ['types' => $type, 'limit' => $limit]);

        // debug($lastAuthorPostsAndCategory);exit;
        $arrayAPI = [];  
        foreach($lastAuthorPostsAndCategory as $key => $value){
                $arrayAPI [] = $value;
        }   
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($arrayAPI);
    }

    

    
    // $limit = 5;
    // $type = 'post';
    // if(isset($_GET['limit'])){
    //     $limit = $_GET['limit'];
    // }     
    // if(isset($_GET['type'])){
    //     $type = $_GET['type'];
    // }
    // //type, vor@ by default post a, kara lini photo.


    // $lastAuthorPostsAndCategory = executeQuery("SELECT blog_posts.id, blog_posts.title, blog_posts.img, blog_posts.type, blog_categories.name as category_name from blog_posts
    // LEFT JOIN blog_categories ON blog_posts.category_id = blog_categories.id
    // WHERE type = :types LIMIT :limit ", ['types' => $type, 'limit' => $limit]);

    // // debug($lastAuthorPostsAndCategory);exit;
    // $arrayAPI = [];  
    // foreach($lastAuthorPostsAndCategory as $key => $value){
    //         $value['img'] = baseUrl() . "uploads/photos/" . $value['img'];
    //         $arrayAPI [] = $value;
    // }   

    // echo json_encode($arrayAPI);


?>