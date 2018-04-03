<?php

function rta_fileName($images)
{
    $arra = array();
    foreach ($images as $key => $value) {
        $url = $value;

        $arr = strrpos($url, '/');
        $arr2 = $arr + 1;
        $file = substr($url, $arr2);
        $arra[] = $file;
    }
    return $arra;
}



function rta_take_name($url)
{
    $arr = strrpos($url, '/');
    $arr2 = $arr + 1;
    $file = substr($url, $arr2);
    return $file;
}

function rta_getPath($path)
{
    $partial_path = strrpos($path, '/');
    $partial_path = substr($path, 0, $partial_path);
    return $partial_path;
}

function rta__delete_thumbnails($id)
{
    $get_path = get_attached_file($id);
    // echo $get_path;
    $file_name = rta_take_name($get_path);
    $file_name = explode(".", $file_name)[0];
    $finalpath = getPath($get_path);
    $images = scandir($finalpath);
    foreach ($images as $key => $value) {
        $a = strpos($value, $file_name.'-');
        echo $a." ";
        $filetodelete='';
        if ($a===0) {
            $filetodelete = $finalpath.'/'.$value;
            // echo "<pre>";
            // print_r($filetodelete);
            unlink($filetodelete);
        }
    }
}
