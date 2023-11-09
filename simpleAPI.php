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
], ['api']);



$limit = 6;
$type = 'post';
if (isset($_POST['set_limit'])) {
    $limit = $_POST['set_limit'];
}
if (isset($_POST['post_type'])) {
    $type = $_POST['post_type'];
}

?>

<div class="api_content">
    <!-- content -->

    <h2>This API returns the latest posts/photos</h2> <!-- ????? -->

    <table class="table">
        <tr>
            <td>
                <strong>Request GET parameters:</strong>
            </td>
        </tr>
        <tr>
            <td>
                <p class="base">limit</p>
            </td>
            <td>
                <p>[optional] The count of posts, default 6, max. 30.
                    <strong>example: limit=6</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="base">Posts</p>
            </td>
            <td>
                <p>[optional] The count of posts, default "posts".
                    <strong>example: type=post</strong>
                </p>
            </td>
        </tr>
        <form method="post" action="simpleAPI" id="API_form">

            <tr>
                <td>
                    <label for="type">Posts count</label>
                </td>
                <td>
                    <input type="number"  name="set_limit" id="set_limit" value="<?php if (isset($_POST['set_limit'])) echo $_POST['set_limit'];
                                                                                else echo 6 ?>" placeholder="Posts Count">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="type">Posts type</label>
                </td>
                <td>
                    <select name="post_type" id="type">
                        <option <?php if ($type == 'post') {
                                    echo 'selected';
                                }  ?> value="post">Post</option>
                        <option <?php if ($type == 'photo') {
                                    echo 'selected';
                                }  ?> value="photo">Photo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="btn_td_decoration">
                    <input type="submit" name="send_api" id="send_api" value="Get Posts from API">
                </td>
            </tr>
        </form>
    </table>
</div>


<div class="row api_show">
    <p class="api_show__not_post_title">Here you can see the posts/photos taken with AIP</p>
</div>

<?php
// echo getLastPostsWithAPI($limit,$type);
?>