 <?php

add_action('admin_init', 'admin_init_media');

function admin_init_media()
{
    $id = 47;
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

    //
    // echo "<pre>";
    // print_r($images);
    exit;
}
    // $query_images_args = array(
    //     'post_type' => 'attachment',
    //     'post_mime_type' => 'image',
    //     'post_status' => 'inherit',
    //     'posts_per_page' => - 1,
    // );
    // $query_images = new WP_Query($query_images_args);
    // $images = array();
    // foreach ($query_images->posts as $image) {
    //     $images[] = $image->guid;
    // }
    // $array = fileName($images);
    // echo "<pre>";
    // print_r($array);


function fileName($images)
{
    $arra = [];
    foreach ($images as $key => $value) {
        $url = $value;
        //$url = 'http://localhost/wordpress/wp-content/uploads/2017/09/file0002063905655.jpg';

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
