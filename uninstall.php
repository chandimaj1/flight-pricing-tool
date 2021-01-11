<?php
/**
 * Trigger these files on headphone_ranker uninstall
 * 
 * @package headphone_ranker
 */

 if (! defined ( 'WP_UNINSTALL_PLUGIN')){
     die;
 }

 // Clear Databasse Stored Data
 $headphoneRanker_files = get_posts ( array('post_type' => 'SignNow', 'numberposts' => -1) );

 /*
 //Selective delete of posts
foreach ($signNow_files as $signNow_file){
     wp_delete_post ( $signNow_file->ID, true);
}
*/

/**
 * Powerful Delete
 * Access the database via SQL and delete everything related to the plugin
 * Line by line
*/
global $wpdb; //Wordpress  query

//$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'signNow'" );