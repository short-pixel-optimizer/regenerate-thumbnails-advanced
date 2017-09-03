 <?php
//
// add_action('admin_init', 'admin_init_media');
//
// function admin_init_media()
// {
//     $query_images_args = array(
//         'post_type' => 'attachment',
//         'post_mime_type' => 'image',
//         'post_status' => 'inherit',
//         'posts_per_page' => - 1,
//     );
//     $query_images = new WP_Query($query_images_args);
//     $images = array();
//     foreach ($query_images->posts as $image) {
//         $images[] = $image->guid;
//     }
//     $array = fileName($images);
//     echo "<pre>";
//     print_r($array);
//
// }
//
// function fileName($images)
// {
//     $arra = [];
//     foreach ($images as $key => $value) {
//         $url = $value;
//         //$url = 'http://localhost/wordpress/wp-content/uploads/2017/09/file0002063905655.jpg';
//
//         $arr = strrpos($url, '/');
//         $arr2 = $arr + 1;
//         $file = substr($url, $arr2);
//         $arra[] = $file;
//     }
//     return $arra;
// }
// function selectFiles()
// {
// } -->
