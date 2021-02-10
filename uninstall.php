<?php
/**
 * Trigger these files on headphone_ranker uninstall
 * 
 * @package headphone_ranker
 */

 if (! defined ( 'WP_UNINSTALL_PLUGIN')){
     die;
 }

/**
 * Powerful Delete
 * Access the database via SQL and delete everything related to the plugin
 * Line by line
*/
global $wpdb; //Wordpress  query

$table_name = $wpdb->prefix."flightbook_aircrafts";
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
delete_option("flightbook_aircrafts_db");

$table_name = $wpdb->prefix."flightbook_settings";
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
delete_option("flightbook_settings_db");

$table_name = $wpdb->prefix."flightbook_languages";
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
delete_option("flightbook_languages_db");