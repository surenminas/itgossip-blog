<?php
    $driver = 'mysql';
    $host = 'localhost';
    $db_name = 'blog';
    $db_user = 'root';
    $db_pass =  '';
    $charset = 'utf8';
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
    
    try{
        $db = new PDO(
            "$driver:host=$host;dbname=$db_name;charset=$charset",
            $db_user, $db_pass, $options
        );
        
        if (!$db) {
            echo "\nPDO::errorInfo():\n";
            print_r($db->errorInfo());
            exit;
        }
    }catch (PDOException $i){
        die("ошибка подключение база данных");
    }

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

