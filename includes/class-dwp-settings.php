<?php
/*
 * Settings class for Advanced DewPlayer
 * @since 1.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
   exit();
}

/*
 * dwp_Settings Class
 * @since 1.5
 */

class dwp_Settings {

   /**
    * Set things up.
    * @since 1.5
    */
   public function __construct() {
      add_action( 'admin_init', array( $this, 'init_settings' ) );
      add_action( 'admin_init', array( $this, 'dew_options_setup' ) );
   }

   public function init_settings() {

      add_settings_section(
              'dew_settings_section', '', array( $this, 'dew_options_callback' ), 'dew_display_options'
      );

      add_settings_field(
              'max_rows', __( 'Maximum Rows', 'dewplayer' ), array( $this, 'max_rows_callbacks'), 'dew_display_options', 'dew_settings_section', array(
         __( 'define max. number of rows in table', 'dewplayer' ),
              )
      );

      add_settings_field(
              'table_width', __( 'Table Width', 'dewplayer' ), array( $this, 'table_width_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'define width for table in % or px (include % or px with value)', 'dewplayer' ),
              )
      );

      add_settings_field(
              'header_height', __( 'Header Height', 'dewplayer' ), array( $this, 'header_height_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'define height for table header in % or px (include % or px with value)', 'dewplayer' ),
              )
      );

      add_settings_field(
              'row_height', __( 'Row Height', 'dewplayer' ), array( $this, 'row_height_callback') , 'dew_display_options', 'dew_settings_section', array(
         __( 'define height for table rows in % or px (include % or px with value)', 'dewplayer' ),
              )
      );

      add_settings_field(
              'table_header_color', __( 'Table Header Color', 'dewplayer' ), array( $this, 'table_header_color_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'define color for table header', 'dewplayer' ),
              )
      );

      add_settings_field(
              'primary_row_color', __( 'Primary Row Color', 'dewplayer' ), array( $this, 'primary_row_color_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'define color for alternate table rows', 'dewplayer' ),
              )
      );


      add_settings_field(
              'alt_row_color', __( 'Alt Row Color', 'dewplayer' ), array( $this, 'alt_row_color_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'define color for alternate table rows', 'dewplayer' ),
              )
      );


      add_settings_field(
              'show_no_column', __( 'Show Number Column', 'dewplayer' ), array( $this, 'show_no_column_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'check if to show number column in table', 'dewplayer' ),
              )
      );

      add_settings_field(
              'show_size_column', __( 'Show Size Column', 'dewplayer' ), array( $this, 'show_size_column_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'check if to show size column in table', 'dewplayer' ),
              )
      );

      add_settings_field(
              'show_duration_column', __( 'Show Duration Column', 'dewplayer' ), array( $this, 'show_duration_column_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'check if to show duration column in table', 'dewplayer' ),
              )
      );

      add_settings_field(
              'header_name_for_no', __( 'Number Column Header', 'dewplayer' ), array( $this, 'header_name_for_no_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for number column header', 'dewplayer' ),
              )
      );

      add_settings_field(
              'header_name_for_name', __( 'Name Column Header', 'dewplayer' ), array( $this, 'header_name_for_name_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for "name" column header', 'dewplayer' ),
              )
      );


      add_settings_field(
              'header_name_for_player', __( 'Player Column Header', 'dewplayer' ), array( $this, 'header_name_for_player_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for player column header', 'dewplayer' ),
              )
      );


      add_settings_field(
              'header_name_for_size', __( 'Size Column Header', 'dewplayer' ), array( $this, 'header_name_for_size_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for size column header', 'dewplayer' ),
              )
      );


      add_settings_field(
              'header_name_for_duration', __( 'Duration Column Header', 'dewplayer' ), array( $this, 'header_name_for_duration_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for duration column header', 'dewplayer' ),
              )
      );

      add_settings_field(
              'header_name_for_download', __( 'Download Column Header', 'dewplayer' ), array( $this, 'header_name_for_download_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'name for duration column header', 'dewplayer' ),
              )
      );

      add_settings_field(
              'download_img', __( 'Download Image', 'dewplayer' ), array( $this, 'download_img_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'upload download link image', 'dewplayer' ),
              )
      );

      add_settings_field(
              'img_preview', __( 'Download Image Preview', 'dewplayer' ), array( $this, 'img_preview_callback' ), 'dew_display_options', 'dew_settings_section', array(
         __( 'uploaded image preview', 'dewplayer' ),
              )
      );

      // Finally, we register the fields with WordPress
      register_setting( 'dew_display_options', 'dew_display_options' );
   }


   public function dew_options_callback() {
      
   }

   public function max_rows_callbacks( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="max_rows" name="dew_display_options[max_rows]" value="' . $options['max_rows'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function table_width_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="table_width" name="dew_display_options[table_width]" value="' . $options['table_width'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_height_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_height" name="dew_display_options[header_height]" value="' . $options['header_height'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function row_height_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="row_height" name="dew_display_options[row_height]" value="' . $options['row_height'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function table_header_color_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      echo '<input type="text" id="table_header_color" name="dew_display_options[table_header_color]" value="' . $options['table_header_color'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function alt_row_color_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      echo '<input type="text" id="alt_row_color" name="dew_display_options[alt_row_color]" value="' . $options['alt_row_color'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function primary_row_color_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      echo '<input type="text" id="primary_row_color" name="dew_display_options[primary_row_color]" value="' . $options['primary_row_color'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function show_no_column_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      $html = '<input type="checkbox" id="show_no_column" name="dew_display_options[show_no_column]" value="1"' . checked( 1, $options['show_no_column'], false ) . '/>';
      echo $html;
      echo '<span class="descr">' . $args[0] . '</span>';
   }

   public function show_size_column_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      $html = '<input type="checkbox" id="show_size_column" name="dew_display_options[show_size_column]" value="1"' . checked( 1, $options['show_size_column'], false ) . '/>';
      echo $html;
      echo '<span class="descr">' . $args[0] . '</span>';
   }

   public function show_duration_column_callback( $args ) {

      $options = get_option( 'dew_display_options' );

      $html = '<input type="checkbox" id="show_duration_column" name="dew_display_options[show_duration_column]" value="1"' . checked( 1, $options['show_duration_column'], false ) . '/>';
      echo $html;
      echo '<span class="descr">' . $args[0] . '</span>';
   }

   public function header_name_for_no_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_no" name="dew_display_options[header_name_for_no]" value="' . $options['header_name_for_no'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_name_for_name_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_name" name="dew_display_options[header_name_for_name]" value="' . $options['header_name_for_name'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_name_for_player_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_player" name="dew_display_options[header_name_for_player]" value="' . $options['header_name_for_player'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_name_for_size_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_size" name="dew_display_options[header_name_for_size]" value="' . $options['header_name_for_size'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_name_for_duration_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_duration" name="dew_display_options[header_name_for_duration]" value="' . $options['header_name_for_duration'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function header_name_for_download_callback( $args ) {

      $options = get_option( 'dew_display_options' );


      echo '<input type="text" id="header_name_for_download" name="dew_display_options[header_name_for_download]" value="' . $options['header_name_for_download'] . '" class="nice" />';
      echo '<div class="descr">' . $args[0] . '</div>';
   }

   public function download_img_callback( $args ) {

      $options = get_option( 'dew_display_options' );
      ?>  
      <input type="text" id="download_img" name="dew_display_options[download_img]" value="<?php echo esc_url( $options['download_img'] ); ?>" class="nice" />
      <input id="upload_logo_button" type="button" class="button" value="<?php echo 'Upload Image'; ?>"/><span class="descr"><?php echo $args[0]; ?></span>
      <?php
   }

   public function img_preview_callback( $args ) {
      $options = get_option( 'dew_display_options' );
      ?>
      <div id="img_preview" style="min-height: 100px;">
         <img style="max-width:100%;" src="<?php echo esc_url( $options['download_img'] ); ?>" />
         <span class="descr"><?php echo $args[0]; ?></span>
      </div>
      <?php
   }

   // uploading...
   public function dew_options_setup() {
      global $pagenow;

      if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
         // Now we'll replace the 'Insert into Post Button' inside Thickbox
         add_filter( 'gettext', array( $this, 'replace_thickbox_text' ), 1, 3 );
      }
   }

   public function replace_thickbox_text( $translated_text, $text, $domain ) {
      if ( 'Insert into Post' == $text ) {
         $referer = strpos( wp_get_referer(), 'dew_player_options' );
         if ( $referer != '' ) {
            return __( 'yep ! use this image', 'dewplayer' );
         }
      }
      return $translated_text;
   }

   public function dew_admin_settings() {
      ?>
      <!-- Create a header in the default WordPress 'wrap' container -->
      <div class="wrap">
         <div id="icon-themes" class="icon32"></div>
         <h2><?php _e( 'Advanced Dewplayer Settings' ); ?></h2>
         <?php settings_errors(); ?>

         <form method="post" action="options.php">
            <?php
            settings_fields( 'dew_display_options' );
            do_settings_sections( 'dew_display_options' );
            submit_button();
            ?>
         </form>
         <div style="float:right; padding-right: 10px; font-family:calibri; color: #0066FF; font-size:14px;">Developed By <a href="" target="_blank" title="visit westerndeal" style="text-decoration:none; ">WesternDeal</a></div>
      </div><!-- /.wrap -->
      <?php
   }

}

$dwp_settings = new dwp_Settings();
?>
