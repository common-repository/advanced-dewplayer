<?php
/*
  Plugin Name: Advanced Dewplayer
  Plugin URI: http://www.westerndeal.com
  Description: Upload MP3 files to any folder on your server. Add the shortcode to your page/post with path of your MP3 folder from which you want to fetch all MP3 files and you have a beautiful playable list of MP3's with much more options.
  Version: 1.6
  Author: WesternDeal
  Author URI: http://www.westerndeal.com
 * Text Domain: dewplayer
 */

define( 'DWP_VERSION', '1.6' );
define( 'DWP_DB_VERSION', '1.6' );
define( 'DWP_PATH', plugin_dir_path( __FILE__ ) ); //use for include files to other files
define( 'DWP_ROOT', dirname( __FILE__ ) );
define( 'DWP_FILE_PATH', DWP_ROOT . '/' . basename( __FILE__ ) );
define( 'DWP_URL', plugins_url( '/', __FILE__ ) );
define( 'DWP_SITE_PATH', get_bloginfo('url') . "/");
define( 'DWP_SETTINGS_PAGE', esc_url( add_query_arg( 'page', 'ef-settings', get_admin_url( null, 'admin.php' ) ) ) );
define( 'DWP_STORE_URL', 'http://www.westerndeal.com' );
define( 'DWP_PRODUCT_NAME', 'Advanced Dewplayer' );
load_plugin_textdomain( 'dewplayer', false, basename( dirname( __FILE__ ) ) . '/languages' );



/*
 * include utility classes
 */

if ( ! class_exists( 'DWP_Utility' ) ) {
	include( DWP_PATH . 'includes/class-dwp-utility.php' );
}

/**
 * DWP_Plugin_Init Class
 * This class will set the plugin
 * @since 1.5
 */
class DWP_Plugin_Init {
   /*
	 * Set things up.
	 * @since 1.5
	 */
	public function __construct() {
      //run on activation of plugin
		register_activation_hook( __FILE__, array( $this, 'dewplayer_activate' ) );
      
      //run on deactivation of plugin
		register_deactivation_hook( __FILE__, array( $this, 'dewplayer_deactivate' ) );
      
      //run on uninstall
		register_uninstall_hook( __FILE__, array( 'DWP_Plugin_Init', 'dewplayer_uninstall' ) );
      
      add_action( 'admin_menu', array( $this, 'register_menu_pages' ) );
      
      // load the classes
		add_action( 'init', array( $this, 'load_all_classes' ) );
      
      add_action('admin_enqueue_scripts', array( $this, 'dew_options_enqueue_scripts' ) );
   }
   
   /**
	 * Activate the plugin
	 * @since 1.5
	 */
	public function dewplayer_activate( $network_wide ) {
		global $wpdb;
		$this->run_on_activation();
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $network_wide ) {
				// Get all blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->base_prefix}blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
               $this->run_for_site();
					restore_current_blog();
				}
				return;
			}
		}
      $this->run_for_site();
	}
   
   /**
	 * deactivate the plugin
	 * @since 1.5
	 */
	public function dewplayer_deactivate( $network_wide ) {
		global $wpdb;
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $network_wide ) {
				// Get all blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->base_prefix}blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->run_on_deactivation();
					restore_current_blog();
				}
				return;
			}
		}

		// for non-network sites only
		$this->run_on_deactivation();
	}
   
   /**
	 *  Runs on plugin uninstall.
	 *  a static class method or function can be used in an uninstall hook
	 *  @since 1.5
	 */
	public static function dewplayer_uninstall() {
		global $wpdb;
		DWP_Plugin_Init::run_on_uninstall();
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			//Get all blog ids; foreach them and call the uninstall procedure on each of them
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->base_prefix}blogs" );

			//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
            DWP_Plugin_Init::delete_for_site();
            restore_current_blog();
         }
			return;			
		}
		DWP_Plugin_Init::delete_for_site();
	}
   
   /**
	 * Called on activation.
	 * Creates the site_options (required for all the sites in a multi-site setup)
	 * If the current version doesn't match the new version, runs the upgrade
	 * @since 1.5
	 */
	private function run_on_activation() {
		$plugin_options = get_site_option( 'dwp_info' );
		if ( false === $plugin_options ) {
			$dwp_info = array(
					'version' => DWP_VERSION,
					'db_version' => DWP_DB_VERSION
			);
         
         update_site_option( 'dwp_info', $dwp_info );
      } elseif ( DWP_VERSION != $plugin_options['version'] ) {
			$this->run_on_upgrade();
		}
   } 
   
   /**
	 * Run on deactivation
	 * @since 1.5
	 */
	private function run_on_deactivation() {
		
	}
   
   /**
	 * Called on activation.
	 * Creates the options and DB (required by per site)
	 *  @since 1.5
	 */
	private function run_for_site() {
      
      $default_option = array(
         'max_rows' => '',
         'table_width' => '',
         'header_height' => '',
         'row_height' => '',
         'table_header_color' => '',
         'primary_row_color' => '',
         'alt_row_color' => '',
         'show_no_column' => '1',
         'show_size_column' => '1',
         'show_duration_column' => '1',
         'header_name_for_no' => 'No',
         'header_name_for_name' => 'Name',
         'header_name_for_player' => 'Play',
         'header_name_for_size' => 'Size',
         'header_name_for_duration' => 'Length',
         'header_name_for_download' => 'Download',
         'download_img' => DWP_URL . 'img/download.png',
         'img_preview' => '',
      );
      if ( has_filter( 'dew_default_display_options') ) {
         $default_option =  apply_filters( 'dew_default_display_options', $default_option );
      }
      
      if ( ! get_option( 'dew_display_options' ) ) {
			update_option( "dew_display_options", $default_option );
		}
   }
   
   /**
	 * Called on uninstall - deletes site_options
	 * @since 1.5
	 */
	private static function run_on_uninstall() {
		if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) )
			exit();
		delete_site_option( 'dwp_info' );
   }
   
   /**
	 * Called on uninstall - deletes site specific options
	 * @since 1.5
	 */
	private static function delete_for_site() {
      delete_option( 'dew_display_options' );
   }
   
   /**
	 * called on upgrade. checks the current version and applies the necessary upgrades from that version onwards
	 * @since 1.5
	 */
	public function run_on_upgrade() {
		$plugin_options = get_site_option( 'dwp_info' );
      
      $dwp_info = array(
         'version' => DWP_VERSION,
         'db_version' => DWP_DB_VERSION
      );

      update_site_option( 'dwp_info', $dwp_info );
   }  
   
   /**
	 * Create/Register menu items for the plugin.
	 * @since 1.5
	 */
	public function register_menu_pages() {
      
      // top level menu for Workflows
		add_menu_page(
				__( 'Advanced Dewplayer', 'oasisworkflow' ),
				__( 'Advanced Dewplayer', 'oasisworkflow' ), 'manage_options',
				'dew_player_options',
				array( $this, 'dwp_setting_page' ) 
      );
   }
   
   /*
	 * Dewplayer Settings page
	 * @since 1.5
	 */
	public function dwp_setting_page() {
      $dwp_settings = new dwp_Settings();
      $dwp_settings->dew_admin_settings();
   }
   
   /**
	 * Load all the classes - as part of init action hook
	 * @since 1.5
	 */
	public function load_all_classes() {
      /*
		 * Settings classes
		 */
		if ( ! class_exists( 'dwp_Settings' ) ) {
			include( DWP_PATH . 'includes/class-dwp-settings.php' );
		}
      
      /*
		 * Service classes
		 */
		if ( ! class_exists( 'DWP_Service' ) ) {
			include( DWP_PATH . 'includes/class-dwp-service.php' );
		}
   }
   
   /**
    * Enqueue css and js files
    * @since 1.5
    */
   public function dew_options_enqueue_scripts( $page ) {
      wp_register_script( 'dew-upload', DWP_URL . 'js/upload.js', array('jquery','media-upload','thickbox'), DWP_VERSION );
		wp_enqueue_script('jquery');	
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');	
		wp_enqueue_script('media-upload');
		wp_enqueue_script('dew-upload');
		 
		if ( function_exists('get_current_screen') ) {
			if ( $this->is_dew_screen() ) {        
				wp_enqueue_style( 'dew-css', DWP_URL . 'css/dew-design.css', false, DWP_VERSION, 'screen' );
			}
		}
		else if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'dew_player_options' ) ) {
			wp_enqueue_style( 'dew-css', DWP_URL . 'css/dew-design.css', false, DWP_VERSION, 'screen' );
		}
		else
		{}
   }
   
   /**
    * @return boolean
    * @since 1.5
    */
   public function is_dew_screen() {
	$screen = get_current_screen();
	if (is_object($screen) && $screen->id == 'toplevel_page_dew_player_options') {
		return true;
	} else {
		return false;
	}
}
   
}

// initialize the plugin
$dwp_plugin_init = new DWP_Plugin_Init();
?>