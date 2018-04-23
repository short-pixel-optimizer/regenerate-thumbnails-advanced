<?php
function rtaCors(){
	 function rta_customize_rest_cors() {
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		add_filter( 'rest_pre_serve_request', function( $value ) {
			header( 'Access-Control-Allow-Origin: '.get_home_url() );
			header( 'Access-Control-Allow-Methods: GET,POST,PUSH' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Access-Control-Expose-Headers: Link', false );
			return $value;
		} );
	 }
	 add_action( 'rest_api_init', 'rta_customize_rest_cors', 15 );
}
rtaCors();
//
$rtaRESTObj = new rtaREST();
class rtaREST
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'routesInit'));
    }
    public function routesInit($generalArr)
    {
        $namespace = 'rta';
        // register_rest_route('rta', '/regenerate', array('methods' => 'POST', 'callback' => array($this, 'rtaProcess'), 'args' => array()));
        register_rest_route( $namespace, '/regenerate', array(
            'methods' => 'POST',
            'callback' => array($this, 'rtaProcess')
        ) );
    }

    public function rtaProcess($data)
    {
        $imageUrl='';
      if (isset($data['type'])) {
            $type = $data['type'];
        }
        $logstatus = '';
        $offset = 0;
      switch ($type) {
          case 'general':
              $args = array(
                  'post_type' => 'attachment',
                  'posts_per_page' => -1,
                  'post_status' => 'any',
                  'offset' => 0,
              );
              if (isset($data['period'])) {
                  $period = $data['period'];

                  switch ($period) {
                      case '0':
                          break;
                      case '1':
                        $date = '-1 day';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 day ago', 'before' => 'tomorrow');
                  break;
                      case '2':
                        $date = '-1 week';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 week ago', 'before' => 'tomorrow');
                        break;
                      case '3':
                        $date = '-1 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 month ago', 'before' => 'tomorrow');
                        break;
                    case '4':
                        $date = '-3 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '3 months ago', 'before' => 'tomorrow');
                    break;
                    case '5':
                        $date = '-6 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '6 months ago', 'before' => 'tomorrow');
                        break;
                    case '6':
                        $date = '-1 year';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 year ago', 'before' => 'tomorrow');
                    break;
                  }
              }
              $the_query = new WP_Query($args);
              $post_count = 0;
              
              if ($the_query->have_posts()) {
                  $post_count = $the_query->post_count;
              }else{
                $logstatus = 'No pictures uploaded';
                $error[] = array('offset' => 0, 'logstatus' => $logstatus, 'imgUrl' => '', 'startTime' => '', 'fromTo' => '', 'type' => $data['type'], 'period' =>'');
                $finalResult = array('offset' => 0, 'error' => 0, 'logstatus' => $logstatus, 'imgUrl' => '', 'startTime' => '', 'fromTo' => '', 'type' => $data['type'], 'period' =>'');
                return $finalResult;
                
              }
              wp_reset_query();
              wp_reset_postdata();
              $logstatus .= '<pre>'.print_r($the_query, true).'</pre>';
              if (isset($data['type'])) {
                  $typeV = $data['type'];
              }
              if (!isset($date) || empty($date)) {
                  $date = '';
              }
              $return_arr = array('pCount' => $post_count, 'fromTo' => $date, 'type' => $typeV, 'period' => $period);
//                return the total number of results

              return $return_arr;
              break;
          case 'submit':

              $logstatus = '';
              $error = array();
              if (isset($data['offset'])) {
                  $offset = $data['offset'];
              }
              if (isset($data['period'])) {
                  $period = $data['period'];

                  $args = array(
                      'post_type' => 'attachment',
                      'post_status' => 'any',
                      'posts_per_page' => 1,
                      'offset' => $offset,
                      'orderby' => 'ID',
                      'order' => 'DESC',
                  );

                  switch ($period) {
                    case '0':
                        break;
                    case '1':
                        $date = '-1 day';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 day ago', 'before' => 'tomorrow');
                     break;
                     case '2':
                        $date = '-1 week';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 week ago', 'before' => 'tomorrow');
                      break;
                     case '3':
                        $date = '-1 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 month ago', 'before' => 'tomorrow');
                      break;
                    case '4':
                        $date = '-3 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '3 months ago', 'before' => 'tomorrow');
                  break;
                  case '5':
                        $date = '-6 month';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '6 months ago', 'before' => 'tomorrow');
                      break;
                  case '6':
                        $date = '-1 year';
                        $startDate = date("d/m/Y",strtotime($date));  
                        $endDate = date("d/m/Y",strtotime('-'.$date));  
                        $args['date_query'] = array('after' => '1 year ago', 'before' => 'tomorrow');
                  break;
                }
              }

              $args = array(
                  'post_type' => 'attachment',
                  'post_status' => 'any',
                  'posts_per_page' => 1,
                  'offset' => $offset,
              );

              if ($period != 0 && isset($date)) {
                  if (!empty($date)) {
                      $fromTo = explode('-', $date);
                      $startDate = date('m/d/Y', strtotime($fromTo[0]));
                      $endDate = date('m/d/Y', strtotime($fromTo[1].' +1 day'));

                      if (!empty($startDate) && empty($endDate)) {
                          $args['date_query'] = array('after' => $startDate);
                      } elseif (!empty($endDate) && empty($startDate)) {
                          $args['date_query'] = array('before' => $endDate);
                      } elseif (!empty($startDate) && !empty($endDate)) {
                          $args['date_query'] = array('after' => $startDate, 'before' => $endDate);
                      }
                  }
//                    $args = array_merge($args, $period_arr);
              }


              $the_query = new WP_Query($args);
      if ($the_query->have_posts()) {

                  while ($the_query->have_posts()) {
                      $the_query->the_post();
                      $image_id = $the_query->post->ID;
                      $is_image = true;if (isset($data['mediaID'])){
                        $image_id = $data['mediaID'];
                      }
                      $fullsizepath = get_attached_file($image_id);

                      //is image:
                      if (!is_array(getimagesize($fullsizepath))) {

                          $is_image = false;
                      }

                      $filename_only = wp_get_attachment_url($image_id);
                      if ($is_image) {
                          if (false === $fullsizepath || !file_exists($fullsizepath)) {
                              $error[] = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $fullsizepath, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                          }
                          @set_time_limit(900);
                          include( ABSPATH . 'wp-admin/includes/image.php' );
                          $metadata = wp_generate_attachment_metadata($image_id, $fullsizepath);
                          //get the attachment name
                          if (is_wp_error($metadata)) {
                              $error[] = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $filename_only, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                          }
                          if (empty($metadata)) {
                          $logstatus = 'File is not an image';
                          $error[] = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $filename_only, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                          } else {
                              wp_update_attachment_metadata($image_id, $metadata);
                          }
                          $imageUrl = $filename_only;
                          $logstatus = 'Processed';

                          //if shortpixel is enabled
                        //   http://sc-api-ai.shortpixel.com/client/w_400,h_215,q_lossy,ret_json/http://www.thesupercarblog.com/wp-content/uploads/2017/09/Audi-R8_V10_RWS-Frankfurt-2017-1-1-1024x768.jpg
			  list($width, $height, $type, $attr) = getimagesize($imageUrl);
			     $size = filesize(get_attached_file($image_id));
			  $spData = file_get_contents('http://sc-api-ai.shortpixel.com/client/w_'.$width.',h_'.$height.',q_lossy,ret_json/'.$imageUrl.'');
			  $spNewArr = json_decode($spData);
			  $spNewArr->percent=round($spNewArr->FinalSize/$size*100);
                      } else {
                        $logstatus = 'Error';
                        $filename_only = basename(get_attached_file($image_id));

                          $logstatus = 'File is not an image';
                          $error[] = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $filename_only, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                      }
                  }

              } else {
                          $logstatus = 'No pictures uploaded';
                          $error[] = array('offset' => 0, 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => '');
                }
              if (!extension_loaded('gd') && !function_exists('gd_info')) {
                  $filename_only = 'No file';
                  $logstatus = 'PHP GD library is not installed on your web server. Please install in order to have the ability to resize and crop images';
                  $error[] = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $filename_only, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                }
                    //increment offset
                    $result = $offset + 1;
                    if(!isset($filename_only)){
                        $filename_only = 'No files';
                    }
                    $finalResult = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'imgUrl' => $filename_only, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);
                    // if sp data
                    if($spNewArr){
                    $finalResult['spData'] = $spNewArr;

                    }

              break;
      }
      /* Restore original Post Data */
        return $finalResult;
    }
}
