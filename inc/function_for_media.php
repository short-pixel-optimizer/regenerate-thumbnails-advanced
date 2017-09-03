 <?php

function fileName($images)
{
    $arra = [];
    foreach ($images as $key => $value) {
        $url = $value;

        $arr = strrpos($url, '/');
        $arr2 = $arr + 1;
        $file = substr($url, $arr2);
        $arra[] = $file;
    }
    return $arra;
}



function take_name($url)
{
    $arr = strrpos($url, '/');
    $arr2 = $arr + 1;
    $file = substr($url, $arr2);
    return $file;
}

function getPath($path)
{
    $partial_path = strrpos($path, '/');
    $partial_path = substr($path, 0, $partial_path);
    return $partial_path;
}

function delete_thumbnails($id)
{
    $get_path = get_attached_file($id);
    // echo $get_path;
    $file_name = take_name($get_path);
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
