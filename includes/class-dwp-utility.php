<?php
/**
 * Utilities class for Advanced Dewplayer
 * @since 1.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}   

/*
 * Utilities class - singleton class *
 * @since 1.5
 */ 
class DWP_Utility {
   /**
	 * Private ctor so nobody else can instance it
	 *
	 */
	private function __construct() {

	}

	/**
	 * Call this method to get singleton
	 * @return singleton instance of OW_Utility
	 */
	public static function instance() {
		static $instance = NULL;
		if ( is_null( $instance ) ) {
			$instance = new DWP_Utility();
		}
		return $instance;
	}
   
   /**
    * logging utility function
    * prints log statements in debug.log is logging is turned on in wp-config.php
    * @since 1.5
    */
   public function logger( $message ) {
      if ( WP_DEBUG === true ) {
         if ( is_array( $message ) || is_object( $message ) ) {
            error_log( print_r( $message, true ) );
         } else {
            error_log( $message );
         }
      } 
   }
}
?>