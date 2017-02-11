<?php
$mpRTA = new mediaPageRTA();

class mediaPageRTA{
  public function __construct(){
    //get the capability of the user for managing images
    $this->capability = apply_filters( 'regenerate_thumbs_cap', 'manage_options' );

    //hook to the rows of the media page
    add_filter( 'media_row_actions', array( $this, 'addToMediaRow' ), 10, 2 );
  }
  //
  //add an extra element to the media rows
  //

  //if the media type is not an image, don't add the element
  public function addToMediaRow( $actions, $post ) {
    if ( 'image/' != substr( $post->post_mime_type, 0, 6 ) || ! current_user_can( $this->capability ) ){
      return $actions;
    }
    $actions['regenerate_thumbnails'] = '<button type="button" imgID="'.$post->ID.'">'.__( 'Regenerate Thumbnails', 'rta' ).'</button>';
    return $actions;
  }

}
