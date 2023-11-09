<?php

class Cache {

    public static function set($key, $data, $seconds = 3600*24) {
        $content['data'] = $data;
        $content['end_time'] = time() + $seconds;
        if(file_put_contents(BASE_DIR . '/cache//' . md5($key) . '.txt', json_encode($content))) {
            if(file_exists(md5($key).'txt')) {
                return true;
            } 
            else return false;
        }
        return false;
    }

    public static function get($key) {
        $file = BASE_DIR . '/cache//' . md5($key) . '.txt';
        if(file_exists($file)) {
            $content = json_decode(file_get_contents($file), true);
            if(time() <= $content['end_time']){
                return $content['data'];
            }
            else{
                return false;  
                unlink($file);
            }
        }
        return false;       
    }
}