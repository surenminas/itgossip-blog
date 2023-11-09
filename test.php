<?php


$text = 'text';

$func = function() use ($text){
    echo $text;
};

$func();

// $queryComments = fetchAll([
//     'select' => 'id, post_id, parent_id, text, name',
//     'table' => 'comments',
//     'order_by' => 'id DESC',
//     'where' => array(
//         'fields' => array(
//             array(
//                 'key' => 'post_id',
//                 'value' => ':post_id'
//             )
//         )
//     )
// ], ['post_id' => 95]);

// foreach ($queryComments as $key => $value) {
//     $data[$value['id']] = $value;
// }


// $tree = getTree($data);


?>

<?php
// $categoriesArray = [
//     1 => [
//         'title' => 'cat1', 
//         'parent' => 0, 
//         'childs'=> [
//             2 => [
//                 'title' => 'cat2', 
//                 'parent' => 1,
//                 'childs'=> [
//                     6 => [
//                         'title' => 'cat2', 
//                         'parent' => 1
//                     ],
//                     7 => [
//                         'title' => 'cat3', 
//                         'parent' => 1
//                     ]
//                 ]
//             ],
//             3 => [
//                 'title' => 'cat3', 
//                 'parent' => 1
//             ]
//         ]
//     ],
//     4 => [
//         'title' => 'cat4', 
//         'parent' => 0
//     ],

// ];

// $aa = [
//     1 => ['title' => 'cat1', 'parent' => 0],
//     2 => ['title' => 'cat2', 'parent' => 1],
//     3 => ['title' => 'cat3', 'parent' => 0],
//     4 => ['title' => 'cat4', 'parent' => 1],
//     5 => ['title' => 'cat5', 'parent' => 7],
//     6 => ['title' => 'cat6', 'parent' => 5],
//     7 => ['title' => 'cat7', 'parent' => 5],

// ];


echo '<pre>';

function getMenuHtml($categories)
{
    $str = '';
    foreach ($categories as $category => $value) {
        var_dump($value['parent']);
        exit;
?>
        <li>
            <?php echo $value['title'];
            var_dump($value['parent']); ?></a>
            <?php if (isset($value['parent'])) : ?>
                <ul>
                    <?php //echo var_dump(getMenuHtml($value)); 
                    ?>
                </ul>
            <?php endif; ?>
        </li>
<?php
    }
    return $str;
}
// echo getMenuHtml($aa);
// echo '<pre>';
// var_dump($aa);
// echo "<br>";
