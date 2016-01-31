<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 1.0.3
  Author: turcuciprian
  Author URI: http://turcuciprian.com
  License: GPLv2 or later
  Text Domain: RTA
 */

class cc {

//    create basic page in the admin panel, with menu settings too
    public function start() {
        //create admin menu page and content
        add_action('admin_menu', array($this, 'create_menu'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
        //ajax callback for button click
        add_action('wp_ajax_rta_ajax', array($this, 'ajax_callback'));
        add_action('wp_ajax_rtaOtfAjax', array($this, 'ajaxOtfCallback'));
    }

    public function ajaxOtfCallback() {
        if (isset($_POST['otfVal'])) {
          $tempValue = '';
          if(!empty($_POST['tempValue'])){
            $tempValue = $_POST['tempValue'];
          }

              update_option('rtaOTF', $tempValue);
              echo "updated!";
        }
    }

    public function ajax_callback() {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        $offset = 0;
        switch ($type) {
            case 'general':
                $args = array(
                    'post_type' => 'attachment',
                    'posts_per_page' => -1,
                    'post_status' => 'any',
                    'offset' => 0
                );

                if (isset($_POST['period'])) {
                    $period = $_POST['period'];

                    switch ($period) {
                        case '0':
                            break;
                        case '1':
                            $date = '-1 day';
                            break;
                        case '2':
                            $date = '-1 week';
//                            echo $date;
                            break;
                        case '3':
                            $date = '-1 month';
                            break;
                        case '4':
                            $date = $_POST['fromTo'];
                            break;
                    }
                }
                if (!empty($date)) {
                    $fromTo = explode('-', $date);
                    $startDate = date("m/d/Y", strtotime($fromTo[0] . " -1 day"));
                    $endDate = date("m/d/Y", strtotime($fromTo[1] . " +1 day"));



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
//                $logstatus .= "<pre>" . print_r($the_query, true) . "</pre>";
                $return_arr = array('pCount' => $post_count, 'fromTo' => $date, 'type' => $_POST['type'], 'period' => $period);
//                return the total number of results


                echo json_encode($return_arr);
                break;
            case 'submit':
//                $logstatus = '';
                $error = array();
                if (isset($_POST['offset'])) {
                    $offset = $_POST['offset'];
                }
                if (isset($_POST['period'])) {
                    $period = $_POST['period'];

                    $args = array(
                        'post_type' => 'attachment',
                        'post_status' => 'any',
                        'posts_per_page' => 1,
                        'offset' => $offset,
                        'orderby' => 'ID',
                        'order' => 'DESC'
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

                            $date = $_POST['fromTo'];
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
                        $startDate = date("m/d/Y", strtotime($fromTo[0]));
                        $endDate = date("m/d/Y", strtotime($fromTo[1] . " +1 day"));



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
                        $is_image = true;
                        $fullsizepath = get_attached_file($image_id);
                        //is image:
                        if (!is_array(getimagesize($fullsizepath))) {
                            $is_image = false;
                        }
                        if ($is_image) {
                            if (false === $fullsizepath || !file_exists($fullsizepath))
                                $error[] = '<code>' . esc_html($fullsizepath) . '</code>';

                            @set_time_limit(900);
                            $metadata = wp_generate_attachment_metadata($image_id, $fullsizepath);
                            //get the attachment name
                            $filename_only = basename(get_attached_file($image_id));
                            if (is_wp_error($metadata)) {
                                $error[] = sprint_f("%s Image ID:%d", $metadata->get_error_message(), $image_id);
                            }
                            if (empty($metadata)) {
                                //$this->die_json_error_msg($image_id, __('Unknown failure reason.', 'regenerate-thumbnails'));
                                $error[] = sprint_f('Unknown failure reason. regenerate-thumbnails %d', $image_id);
                            } else {
                                wp_update_attachment_metadata($image_id, $metadata);
                            }
                            $logstatus = "<br/>" . $filename_only . " - <b>Processed</b>";
                        } else {
                            $filename_only = basename(get_attached_file($image_id));

                            $error[] = sprintf('Attachment (<b>%s</b> - ID:%d) is not an image. Skipping', $filename_only, $image_id);
                        }
                    }
                } else {
                    $error[] = "No pictures uploaded";
                }
                if (!extension_loaded('gd') && !function_exists('gd_info')) {
                    $error[] = "<b>PHP GD library is not installed</b> on your web server. Please install in order to have the ability to resize and crop images";
                }
                //increment offset
                $result = $offset + 1;
//                $logstatus .= "<br/>".$the_query->post->ID;
//                $logstatus .= "<br/><pre>" . print_r($args, true) . "</pre>";
                echo json_encode(array('offset' => ($offset + 1), 'error' => $error, 'logstatus' => $logstatus, 'startTime' => $_POST['startTime'], 'fromTo' => $_POST['fromTo'], 'type' => $_POST['type'], 'period' => $period));
                break;
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();


        wp_die();
    }

//    Admin menu calback
    public function create_menu() {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('reGenerate Thumbnails Advanced', 'rta_id'), __('Regenerate Thumbnails', 'rta_id'), 'administrator', 'regenerate_thumbnails_advanced', array($this, 'create_page_callback'));
        return true;
    }

    function enqueue_admin($hook) {
        if (isset($_GET['page']) && isset($hook)) {
            if ($_GET['page'] !== 'regenerate_thumbnails_advanced' && $hook != 'options-general.php ') {
                return;
            }
        }
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('rta-jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.min.css');
        wp_enqueue_style('rta', plugin_dir_url(__FILE__) . 'style.css');
        wp_enqueue_script('rta', plugin_dir_url(__FILE__) . 'script.js');
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        $total = 1;
        $offset = 0;
        ?>
        <!--GTA wrap START -->
        <div id="rta">
            <div id="no-js">
                <h1>Javascript is not enabled or it has a error!</h1>
                <p>If there is a error in the page (most likely caused by another plugin or even the theme, the regenerate thumbnails advanced plugin will not work properly. Please fix this issue and come back here. YOU WILL NOT SEE THIS WARNING IF EVERYTHING IS WORKING FINE</p>
            </div>
            <div id="js-works" class="hidden">

                <?php
                $rotf = get_option( 'rtaOTF');
                ?>
                <div class="otf">
If you like this plugin and would like to show your appreciation, don't hesitate to donate something:
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">

<!-- Identify your business so that you can collect the payments. -->
<input type="hidden" name="business"
value="turcuciprian1@gmail.com">

<!-- Specify a Donate button. -->
<input type="hidden" name="cmd" value="_donations">

<!-- Specify details about the contribution -->
<input type="hidden" name="item_name" value="Plugin Developer">
<input type="hidden" name="item_number" value="Support Development">
<input type="hidden" name="currency_code" value="USD">

<!-- Display the payment button. -->
<input type="image" name="submit" border="0"
src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
alt="PayPal - The safer, easier way to pay online">
<img alt="" border="0" width="1" height="1"
src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >

</form>

                  <h3> When needed</h3>
                  <input type="checkbox" class="rtaOtf" name="rtaOtf" value="" <?php checked('checked',$rotf);?> /> Regenerate on the fly
                  <p>
                    When needed, when user loads a page that does not have the thumbnail generated previously, it is automatically regenerated. WARNING, this may slow down server load
                  </p>

                </div>
            </div>

        </div>
        <!-- Js Works End -->
        <!--GTA wrap END -->
        <?php
    }

    public function add_settings_link($links) {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=regenerate_thumbnails_advanced') . '">Settings</a>',
        );
        return array_merge($links, $mylinks);
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
//regenerate images on the fly (when they don't exist and it's needed)

//add a filter for image_downsize
  // add_filter('image_downsize','test_image_downsize',true, 10,2);

//where all the on the fly magic happens
function test_image_downsize($bool,$imgid,$size){

  // Get the current image metadata
  $meta = wp_get_attachment_metadata($imgid);
  //get the path to the image
  if(isset($meta['file']) && !empty($meta['file'])){
    $fullsizepath = wp_upload_dir().$meta['path'].$meta['file'];
  }
  // replace the main image with the thumb image name
  // $upload_dir = str_replace($meta['file'],$meta['sizes']['rtatest']['file'],$upload_dir);

  // echo "<pre>";
  // print_r($meta);
  // echo($meta['sizes']['rtatest']['file']);
  $file_url = wp_get_attachment_url($imgid);
  // echo "<pre>";
  // print_r($meta['sizes'][$size]);

if(!isset($meta['sizes'][$size]) && !empty($meta['sizes'][$size])){
  if(!file_exists($file_url)){
    exit;
    // print_r($meta);
    @set_time_limit(900);
    // $metadata = wp_generate_attachment_metadata($imgid, $fullsizepath);
    // print_r($metadata);
  }
}
  // if(!file_exists($file_url)){
    // echo "does not exist";
  // }
  // echo $file_url;
  echo "<pre>";
  // print_r(array($file_url,$meta['sizes'][$size['width'],$meta['sizes'][$size]['height']));
  echo "</pre>";
  exit;
  return array($file_url,$meta['sizes'][$size]['width'],$meta['sizes'][$size]['height']);

}

add_action('init','rtaInit');
function rtaInit(){
  $rotf = get_option( 'rtaOTF');
  if ( ! function_exists( 'gambit_otf_regen_thumbs_media_downsize' ) && $rotf=='checked') {
  	add_filter( 'image_downsize', 'gambit_otf_regen_thumbs_media_downsize', 10, 3 );

  	/**
  	 * The downsizer. This only does something if the existing image size doesn't exist yet.
  	 *
  	 * @param	$out boolean false
  	 * @param	$id int Attachment ID
  	 * @param	$size mixed The size name, or an array containing the width & height
  	 * @return	mixed False if the custom downsize failed, or an array of the image if successful
  	 */
  	function gambit_otf_regen_thumbs_media_downsize( $out, $id, $size ) {

  		// Gather all the different image sizes of WP (thumbnail, medium, large) and,
  		// all the theme/plugin-introduced sizes.
  		global $_gambit_otf_regen_thumbs_all_image_sizes;
  		if ( ! isset( $_gambit_otf_regen_thumbs_all_image_sizes ) ) {
  			global $_wp_additional_image_sizes;

  			$_gambit_otf_regen_thumbs_all_image_sizes = array();
  			$interimSizes = get_intermediate_image_sizes();

  			foreach ( $interimSizes as $sizeName ) {
  				if ( in_array( $sizeName, array( 'thumbnail', 'medium', 'large' ) ) ) {

  					$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['width'] = get_option( $sizeName . '_size_w' );
  					$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['height'] = get_option( $sizeName . '_size_h' );
  					$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['crop'] = (bool) get_option( $sizeName . '_crop' );

  				} elseif ( isset( $_wp_additional_image_sizes[ $sizeName ] ) ) {

  					$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ] = $_wp_additional_image_sizes[ $sizeName ];
  				}
  			}
  		}

  		// This now contains all the data that we have for all the image sizes
  		$allSizes = $_gambit_otf_regen_thumbs_all_image_sizes;

  		// If image size exists let WP serve it like normally
  		$imagedata = wp_get_attachment_metadata( $id );

  		// Image attachment doesn't exist
  		if ( ! is_array( $imagedata ) ) {
  			return false;
  		}

  		// If the size given is a string / a name of a size
  		if ( is_string( $size ) ) {

  			// If WP doesn't know about the image size name, then we can't really do any resizing of our own
  			if ( empty( $allSizes[ $size ] ) ) {
  				return false;
  			}

  			// If the size has already been previously created, use it
  			if ( ! empty( $imagedata['sizes'][ $size ] ) && ! empty( $allSizes[ $size ] ) ) {

  				// But only if the size remained the same
  				if ( $allSizes[ $size ]['width'] == $imagedata['sizes'][ $size ]['width']
  				&& $allSizes[ $size ]['height'] == $imagedata['sizes'][ $size ]['height'] ) {
  					return false;
  				}

  				// Or if the size is different and we found out before that the size really was different
  				if ( ! empty( $imagedata['sizes'][ $size ][ 'width_query' ] )
  				&& ! empty( $imagedata['sizes'][ $size ]['height_query'] ) ) {
  					if ( $imagedata['sizes'][ $size ]['width_query'] == $allSizes[ $size ]['width']
  					&& $imagedata['sizes'][ $size ]['height_query'] == $allSizes[ $size ]['height'] ) {
  						return false;
  					}
  				}

  			}

  			// Resize the image
  			$resized = image_make_intermediate_size(
  				get_attached_file( $id ),
  				$allSizes[ $size ]['width'],
  				$allSizes[ $size ]['height'],
  				$allSizes[ $size ]['crop']
  			);

  			// Resize somehow failed
  			if ( ! $resized ) {
  				return false;
  			}

  			// Save the new size in WP
  			$imagedata['sizes'][ $size ] = $resized;

  			// Save some additional info so that we'll know next time whether we've resized this before
  			$imagedata['sizes'][ $size ]['width_query'] = $allSizes[ $size ]['width'];
  			$imagedata['sizes'][ $size ]['height_query'] = $allSizes[ $size ]['height'];

  			wp_update_attachment_metadata( $id, $imagedata );

  			// Serve the resized image
  			$att_url = wp_get_attachment_url( $id );
  			return array( dirname( $att_url ) . '/' . $resized['file'], $resized['width'], $resized['height'], true );


  		// If the size given is a custom array size
  		} else if ( is_array( $size ) ) {
  			$imagePath = get_attached_file( $id );

  			// This would be the path of our resized image if the dimensions existed
  			$imageExt = pathinfo( $imagePath, PATHINFO_EXTENSION );
  			$imagePath = preg_replace( '/^(.*)\.' . $imageExt . '$/', sprintf( '$1-%sx%s.%s', $size[0], $size[1], $imageExt ) , $imagePath );

  			$att_url = wp_get_attachment_url( $id );

  			// If it already exists, serve it
  			if ( file_exists( $imagePath ) ) {
  				return array( dirname( $att_url ) . '/' . basename( $imagePath ), $size[0], $size[1], true );
  			}

  			// If not, resize the image...
  			$resized = image_make_intermediate_size(
  				get_attached_file( $id ),
  				$size[0],
  				$size[1],
  				true
  			);

  			// Get attachment meta so we can add new size
  			$imagedata = wp_get_attachment_metadata( $id );

  			// Save the new size in WP so that it can also perform actions on it
  			$imagedata['sizes'][ $size[0] . 'x' . $size[1] ] = $resized;
  			wp_update_attachment_metadata( $id, $imagedata );

  			// Resize somehow failed
  			if ( ! $resized ) {
  				return false;
  			}

  			// Then serve it
  			return array( dirname( $att_url ) . '/' . $resized['file'], $resized['width'], $resized['height'], true );
  		}

  		return false;
  	}
  }
}
