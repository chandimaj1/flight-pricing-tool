<?php
/**
 * @package flight_booking
 */

 /* 
 Plugin Name: Flight Booking
 Plugin URI: https://github.com/axawebs/flightbooking
 Description: Custom created plugin for Headphone Ranking on Wordpress
 Version: 1.0
 Author: Chandima Jayasiri
 Author URI: mailto:chandimaj@icloud.com
 License: GPLV2 or later
 Text Domain: flight_booking
 */

 /*
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (! defined( 'ABSPATH') ){
    die;
}

class flightBook
{

    public $plugin_name;
    public $settings = array();
    public $sections = array();
    public $fields = array();

    //Method Access Modifiers
    // public - can be accessed from outside the class
    // protected - can only be accessed within the class ($this->protected_method())
    // protected - can only be accessed from constructor

    function __construct(){
        //add_action ('init', array($this, 'custom_post_type')); // tell wp to execute method on init
        $this->plugin_name = plugin_basename( __FILE__ );
    }

    function register(){
        add_shortcode( 'flightbook', array($this, 'shortcode_frontend') );

        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );

        add_action ( 'admin_menu', array( $this, 'add_admin_pages' ));
        add_filter ("plugin_action_links_$this->plugin_name", array ($this, 'settings_link'));
       // add_filter( 'single_template', array($this, 'load_custom_post_specific_template'));
    }


    function add_admin_pages(){
        add_menu_page( 'Flight Booking Plugin - Settings', 'FlightBook', 'manage_options', 'flightbook_settings', array($this,'admin_index'), 'dashicons-list-view', 100);
    }    

    function admin_index(){
        //require template
        require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
    }


    function settings_link($links){
        //add custom setting link
        $settings_link = '<a href="admin.php?page=flightbook_settings">Settings Page</a>';
        array_push ( $links, $settings_link );
        return $links;
    }


    function activate(){
        // Plugin activated state
        // generate a Custom Post Style
        // $this->custom_post_type();
      //  $this->create_table();
        // Flush rewrite rules 
        flush_rewrite_rules();
    }
 
    function deactivate(){
        // Plugin deactivate state
        //Flush rewrite rules
        flush_rewrite_rules();
    }

    function uninstall(){
        //Plugin deleted
        //delete Custom Post Style
        //delete all plugin data from the DB

    }


    function create_table(){ 
        // create table if not exist
        global $wpdb;

        // Headphones
        $table_name = $wpdb->prefix."flight_bookings";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name.'(
            id INTEGER NOT NULL AUTO_INCREMENT,
            rank VARCHAR(1),
            brand VARCHAR(50),
            device VARCHAR(50),
            price VARCHAR(20),
            value INT(2), 
            principle VARCHAR(100),
            overall_timbre VARCHAR(300),
            summary TEXT,
            ganre_focus VARCHAR(300),
            PRIMARY KEY  (id))';
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            add_option("headphoneranker_db_version", "1.0");
        }

        // IEM
        $table_name = $wpdb->prefix."hranker_iem";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name.'(
            id INTEGER NOT NULL AUTO_INCREMENT,
            rank VARCHAR(1),
            brand VARCHAR(50),
            device VARCHAR(50),
            price VARCHAR(20),
            value INT(2), 
            overall_timbre VARCHAR(300),
            summary TEXT,
            ganre_focus VARCHAR(300),
            PRIMARY KEY  (id))';
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            add_option("headphoneranker_db_version", "1.1");
        }

        // Earbuds
        $table_name = $wpdb->prefix."hranker_earbuds";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name.'(
            id INTEGER NOT NULL AUTO_INCREMENT,
            rank VARCHAR(1),
            brand VARCHAR(50),
            device VARCHAR(50),
            price VARCHAR(20),
            value INT(2),
            overall_timbre VARCHAR(300),
            summary TEXT,
            ganre_focus VARCHAR(300),
            PRIMARY KEY  (id))';
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            add_option("headphoneranker_db_version", "1.2");
        }

        // settings
        $table_name = $wpdb->prefix."hranker_settings";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name.'(
            id INTEGER NOT NULL AUTO_INCREMENT,
            headphones_html TEXT,
            iem_html TEXT,
            earbuds_html TEXT,
            banner_image1 TEXT,
            banner_image2 TEXT,
            banner_image3 TEXT,
            banner_image4 TEXT,
            banner_url1 TEXT,
            banner_url2 TEXT,
            banner_url3 TEXT,
            banner_url4 TEXT,
            PRIMARY KEY  (id))';
            $sql_insert = "INSERT INTO $table_name
            VALUES (1,'- Headphones HTML goes here -','- IEM HTML goes here -','- EarBuds HTML goes here -','small_banner_default.jpg','small_banner_default.jpg','small_banner_default.jpg','small_banner_default.jpg','')";
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($sql_insert);
            add_option("headphoneranker_db_version", "1.3");
        }
    }
    
    //Enqueue on admin pages
    function enqueue_admin($hook_suffix){

        if (strpos($hook_suffix, 'flightbook_settings') !== false) {
            //Bootstrap
            wp_enqueue_style( 'bootstrap4_styles', plugins_url('/assets/bootstrap4/bootstrap_4_5_2_min.css',__FILE__));
            wp_enqueue_script( 'bootstrap4_scripts', plugins_url('/assets/bootstrap4/bootstrap_4_5_2_min.js',__FILE__), array('jquery'));
            //Font-Awesome
            wp_enqueue_style( 'fontawesome_css', plugins_url('/assets/font_awesome/css/font-awesome.css',__FILE__));
            //Admin scripts and styles
            wp_enqueue_style( 'flightbook_admin_styles', plugins_url('/assets/flightbook_admin_style.css',__FILE__));
            wp_enqueue_script( 'flightbook_admin_script', plugins_url('/assets/flightbook_admin_scripts.js',__FILE__), array('jquery'));
            //Select2
            wp_enqueue_style( 'flightbook_select2_styles', plugins_url('/assets/select2/select2.css',__FILE__));
            wp_enqueue_script( 'flightbook_select2_scripts', plugins_url('/assets/select2/select2.full.js',__FILE__), array('jquery'));
        }
    }

    //Enqueue on all other pages
    function enqueue(){ 

        //Load only on page id = 2
        global $post;
        $post_slug = $post->post_name;
        //echo($post_slug);
        if ( $post_slug=="flightbook" ){
        //Bootstrap
        wp_enqueue_style( 'bootstrap4_css', plugins_url('/assets/bootstrap4/bootstrap_4_5_2_min.css',__FILE__),80);
         wp_enqueue_script( 'bootstrap_bundle_scripts', plugins_url('/assets/bootstrap4/bootstrap.bundle.min.js',__FILE__), array('jquery'));
         wp_enqueue_script( 'bootstrap_input_spinner', plugins_url('/assets/bootstrap4/bootstrap-input-spinner.js',__FILE__), array('bootstrap_bundle_scripts'));
         //FLatpicker
         wp_enqueue_style( 'flatpickr_styles', plugins_url('/assets/flatpicker/flatpickr.min.css',__FILE__),81);
         wp_enqueue_script( 'flatpickr_scripts', plugins_url('/assets/flatpicker/flatpickr.js',__FILE__), array('jquery'));
         //jQuery Autocomplete
         wp_enqueue_script( 'jquery_autocomplete', plugins_url('/assets/jquery_autocomplete/jquery.autocomplete.min.js',__FILE__), array('jquery'));
         //Font-Awesome
         wp_enqueue_style( 'fontawesome_css', plugins_url('/assets/font_awesome/css/font-awesome.css',__FILE__),90);
         //Select2
         wp_enqueue_style( 'flightbook_select2_styles', plugins_url('/assets/select2/select2.css',__FILE__),98);
         wp_enqueue_script( 'flightbook_select2_scripts', plugins_url('/assets/select2/select2.full.js',__FILE__), array('jquery'));
         //Fuse
         wp_enqueue_script( 'airports_scripts', plugins_url('/assets/fuse.js/airports.js',__FILE__));
         wp_enqueue_script( 'fuse_scripts', plugins_url('/assets/fuse.js/fuse.js',__FILE__), array('airports_scripts'));
         //Custom
         wp_enqueue_style( 'flightbook_custom_styles', plugins_url('/assets/custom.css',__FILE__),100);
         //FrontEnd scripts and styles
         wp_enqueue_script( 'flightbook_script', plugins_url('/assets/flightbook_scripts.js',__FILE__), array('flightbook_select2_scripts','flatpickr_scripts','fuse_scripts','jquery'));
        }
    }





    //------ Shortcode
    function shortcode_frontend($atts){
        include 'templates/flightbook_shortcode.php';
    }
    
}

if ( class_exists('flightBook') ){
    $flight_book = new flightBook();
    $flight_book -> register();
}

//activate
register_activation_hook (__FILE__, array($flight_book, 'activate'));

//deactivation
register_deactivation_hook (__FILE__, array($flight_book, 'deactivate'));