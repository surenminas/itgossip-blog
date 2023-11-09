<?php
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
], ['about']);

?>


<div class="container content">
    <div class="row content_without_sidebar">
        <div class="col-lg-10">
            <div class="about_us">
                <div class="about_us__img">
                    <img src="img/it_gossip.jpg">
                </div>
                <div class="about_us__text">
                    <p><?php echo $selectPagesInformation['text']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>