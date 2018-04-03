<?php
add_action( 'init', 'xmy_customize_rest_cors', 15 );

function xmy_customize_rest_cors() {
	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
		header( 'Access-Control-Allow-Credentials: true' );
		header( 'Access-Control-Expose-Headers: Link', false );
}

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
            'callback' => array($this, 'rtaProcess'),
        ) );
    }

    public function rtaProcess($data)
    {
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
                          break;
                      case '2':
                          $date = '-1 week';
                          break;
                      case '3':
                          $date = '-1 month';
                          break;
                      case '4':
                          $date = $data['fromTo'];
                          break;
                  }
              }
              if (isset($date) && !empty($date)) {
                  $fromTo = explode('-', $date);
                  $startDate = date('m/d/Y', strtotime($fromTo[0].' -1 day'));
                  $endDate = date('m/d/Y', strtotime($fromTo[1].' +1 day'));

                  if (!empty($startDate) && empty($endDate)) {
                      $args['date_query'] = array('after' => $startDate);
                  } elseif (!empty($endDate) && empty($startDate)) {
                      $args['date_query'] = array('before' => $endDate);
                  } elseif (!empty($startDate) && !empty($endDate)) {
                      $args['date_query'] = array('after' => $startDate, 'before' => $endDate);
                  }
              }
              $the_query = new WP_Query($args);
              $post_count = 0;
              if ($the_query->have_posts()) {
                  $post_count = $the_query->post_count;
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

                          break;
                      case '2':
                          $date = '-1 week';
                          break;
                      case '3':
                          $date = '-1 month';
                          break;
                      case '4':

                          $date = $data['fromTo'];
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
              if ($the_query->havedatas()) {

                  while ($the_query->havedatas()) {
                      $the_query->thedata();
                      $image_id = $the_query->post->ID;
                      $is_image = true;if (isset($data['mediaID'])){
                        $image_id = $data['mediaID'];
                      }
                      $fullsizepath = get_attached_file($image_id);

                      //is image:
                      if (!is_array(getimagesize($fullsizepath))) {

                          $is_image = false;
                      }

                      if ($is_image) {
                          if (false === $fullsizepath || !file_exists($fullsizepath)) {
                              $error[] = '<code>'.esc_html($fullsizepath).'</code>';
                          }
                          @set_time_limit(900);
                          include( ABSPATH . 'wp-admin/includes/image.php' );
                          $metadata = wp_generate_attachment_metadata($image_id, $fullsizepath);
                          //get the attachment name
                          $filename_only = basename(get_attached_file($image_id));
                          if (is_wp_error($metadata)) {
                              $error[] = sprintf('%s Image ID:%d', $metadata->get_error_message(), $image_id);
                          }
                          if (empty($metadata)) {
                              //$this->die_json_error_msg($image_id, __('Unknown failure reason.', 'regenerate-thumbnails'));
                              $error[] = sprintf('Unknown failure reason. regenerate-thumbnails %d', $image_id);
                          } else {
                              wp_update_attachment_metadata($image_id, $metadata);
                          }
                          $logstatus = '<br/>'.$filename_only.' - <b>Processed</b>';
                      } else {
                          $filename_only = basename(get_attached_file($image_id));

                          $error[] = sprintf('Attachment (<b>%s</b> - ID:%d) is not an image. Skipping', $filename_only, $image_id);
                      }

                  }

              } else {
                  $error[] = 'No pictures uploaded';
              }
              if (!extension_loaded('gd') && !function_exists('gd_info')) {
                  $error[] = '<b>PHP GD library is not installed</b> on your web server. Please install in order to have the ability to resize and crop images';
              }
              //increment offset
              $result = $offset + 1;
              $finalResult = array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'startTime' => $data['startTime'], 'fromTo' => $data['fromTo'], 'type' => $data['type'], 'period' => $period);

              break;
      }
      /* Restore original Post Data */
        return $finalResult;
    }
}
