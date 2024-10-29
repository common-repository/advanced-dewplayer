<?php

/*
 * Service class for Advanced Dewplayer
 * @since 1.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
   exit;
}

/**
 * DWP_Service Class
 * @since 1.5
 */
class DWP_Service {

   public function __construct() {
      add_shortcode( 'musicdirectory', array( $this, 'music_procedure' ) );
   }

   public function music_procedure( $atts ) {
      extract( shortcode_atts( array(
         'path' => '',
                      ), $atts ) );

      global $maxrows;
      global $tabwidth;
      global $headheight;
      global $rowheight;
      global $headcolor;
      global $prirow;
      global $altrow;
      global $showno;
      global $showsize;
      global $showlength;
      global $noheader;
      global $nameheader;
      global $playerheader;
      global $sizeheader;
      global $lengthheader;
      global $downloadheader;
      global $downloading;

      include_once( DWP_PATH . "library/getid3.php");
      
      
      $dp = get_option('dew_display_options');

      $maxrows = $dp['max_rows'];
      $tabwidth = $dp['table_width'];
      $headheight = $dp['header_height'];
      $rowheight = $dp['row_height'];
      $headcolor = $dp['table_header_color'];
      $prirow = $dp['primary_row_color'];
      $altrow = $dp['alt_row_color'];
      $showno = $dp['show_no_column'];
      $showsize = $dp['show_size_column'];
      $showlength = $dp['show_duration_column'];
      $noheader = $dp['header_name_for_no'];
      $nameheader = $dp['header_name_for_name'];
      $playerheader = $dp['header_name_for_player'];
      $sizeheader = $dp['header_name_for_size'];
      $lengthheader = $dp['header_name_for_duration'];
      $downloadheader = $dp['header_name_for_download'];
      $downloading = $dp['download_img'];

      if ( $handle = opendir( ABSPATH . $path ) ) {
         $count = 1;
         $getID3 = new getID3;
         $getID3->encoding = 'UTF-8';

         $tabWidth = ( isset( $tabwidth ) && $tabwidth > 0 ) ? $tabwidth : '100%';
         $headHeight = ( isset( $headheight ) && $headheight > 0 ) ? $headheight : '100%';
         $rowHeight = ( isset( $rowheight ) && $rowheight > 0 ) ? $rowheight : '100%';
         $headColor = isset( $headcolor ) ? $headcolor : '#eeeeee';
         $priRow = isset( $prirow ) ? $prirow : '#fff';
         $altRow = isset( $altrow ) ? $altrow : '#fff';



         $html = '<style type="text/css">
				.dewPlay {width:' . $tabWidth . ' !important; border-collapse:collapse;}
				.dewPlay tbody .dewh { height:' . $headHeight . ' !important; vertical-align:middle; background-color:' . $headColor . ' !important;}
				 .dewPlay tr.dewc {height:' . $rowHeight . ' !important; vertical-align:middle;}
				.dewPlay tbody tr td { vertical-align:middle; }
				.dewc:nth-child(even) {background-color:' . $priRow . ' !important;}
				.dewc:nth-child(odd) {background-color:' . $altRow . ' !important;}
				.dewc object { margin: 0 auto !important; width: 100% !important; }
				
				</style>';
         $html .= '<table class="dewPlay" cellpadding="5">';

         $html .= '<tbody>';
         $html .= '<tr class="dewh">';
         if ( '1' == $showno ) {
            $html .= '<td>' . $noheader . '</td>';
         }

         $html .= '<td>' . $nameheader . '</td><td>' . $playerheader . '</td>';

         if ( '1' == $showsize ) {
            $html .= '<td>' . $sizeheader . '</td>';
         }

         if ( '1' == $showlength ) {
            $html .= '<td>' . $lengthheader . '</td>';
         }

         $html .= '<td>' . $downloadheader . '</td></tr>';

         while ( false !== ($file = readdir( $handle )) ) {
            $dirFiles[] = $file;
         }
         closedir( $handle );
      }

      sort( $dirFiles );
      foreach ( $dirFiles as $file ) {
         if ( $file == "." || $file == ".." )
            continue;
         if ( strtolower( substr( strrchr( $file, "." ), 1 ) ) != 'mp3' )
            continue;

         $name = substr( str_replace( '_', ' ', $file ), 0, -4 );
         $ThisFileInfo = $getID3->analyze( ABSPATH . $path . $file );
         $playtime = $ThisFileInfo ['playtime_string'];
         $size = round( (filesize( ABSPATH . $path . $file )) / (1024 * 1024), 2 );

         $html .= '<tr class="dewc">';
         if ( '1' == $showno ) {
            $html .= '<td>' . $count . '</td>';
         }
         $html .= '<td>' . $name . '</td>';
         $html .= '<td style="vertical-align:middle;"> <object type="application/x-shockwave-flash" data="' . DWP_URL . 'dewplayer.swf?mp3=' . DWP_SITE_PATH . $path . $file . '" width="200" height="20" id="dewplayer"><param name="wmode" value="transparent" /><param name="movie" value="' . DWP_URL . 'dewplayer.swf?mp3=' . DWP_SITE_PATH . $path . $file . '" /></object>
</td>';
         if ( '1' == $showsize ) {
            $html .= '<td>' . $size . ' MB </td>';
         }
         if ( '1' == $showlength ) {
            $html .= '<td>' . $playtime . ' </td>';
         }
         
         $html .= '<td><a href="' . DWP_URL . 'includes/download-file.php?dew_file=' . DWP_SITE_PATH . $path . $file . '" class="download"><img src="' . $downloading . '" title="download" style="border:none !important; width: 32px; height: 32px;"/></a></td>';
         $html .= '</tr>';

         if ( isset( $maxrows ) ) {
            if ( $count == $maxrows ) {
               break;
            }
         }
         $count++;
      }

      $html .= '</tbody>';
      $html .= '</table>';

      return $html;
   }

}

// construct an instance so that the actions get loaded
$dwp_service = new DWP_Service();
