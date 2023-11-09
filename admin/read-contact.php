<?php
   
    if(getUserRole() != 'administrator'){
        header("location: main");
    } 

    if(isset($_GET['id'])){
        $contactText = fetch([   
                                'select' => '*',
                                'table' => 'contacts',
                                'where' => array(
                                    'fields' => array(
                                        array(
                                            'key' => 'id',
                                            'value' => ':contact_id'
                                        )
                                    )
                                ) 
                            ], ['contact_id' =>  $_GET['id']]
                        );      
?>  
            <div class="lesson">
                <p class="listNameP">Отправлено от: <?php echo $contactText['email'];?></p>
            
                <label><br />
                    <p readonly name="text" id="text" cols="40" rows="8" class="listNameP"><?php echo $contactText['text']?></p>
                </label>
            </div>    

        <?php 
        
    }
    else{
        $titleViews = fetchAll([   
                                    'select' => 'id, text',
                                    'table' => 'contacts',
                                    'order_by' => 'id DESC',
                                    'where' => array(
                                        'fields' => array(
                                            array(
                                                'key' => '1',
                                                'value' => '1'
                                            )
                                        )
                                    ) 
                                ]);
    ?>
        <?php foreach($titleViews as $key => $titleView): ?>
            <div class='lesson_title'>
                <p class='listName'><a href='read-contact?id=<?php echo $titleView['id'] ?>'><?php echo str_size_header($titleView['text'], $symbol = 50, '...') ?></a></p>                                               
            </div>
        <?php endforeach; ?>
  

    <?php } ?>       
            
