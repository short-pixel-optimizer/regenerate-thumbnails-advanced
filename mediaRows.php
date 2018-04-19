<?php
$mpRTA = new mediaPageRTA();

class mediaPageRTA
{
    public function __construct()
    {
        //get the capability of the user for managing images
    $this->capability = apply_filters('regenerate_thumbs_cap', 'manage_options');

    //hook to the rows of the media page
        add_action('admin_footer', array($this, 'admin_footer'), 10, 2);
    }
  //
  //add an extra element to the media rows
  //
    public function admin_footer()
    {
        ?>
    <div id="rta">
      <div class="rtaPopup hidden">
        <div class="child">
          <img src="<?php echo plugin_dir_url( __FILE__ );?>images/ajax-loader.gif" alt="">
          Regenerating Thumbnails
        </div>

      </div>
  </div>

    <?php

    }
}
