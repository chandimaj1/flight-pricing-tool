<?php
/**
 * @package flight_booking
 */

 /* 
 Plugin Name: Flight Booking
 Plugin URI: https://github.com/axawebs/flightbooking
 Description: Custom created FlightBooking plugin for VeloxAirCharter on Wordpress
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
        $this->create_table();
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

        // aircrafts
        $table_name = $wpdb->prefix."flightbook_aircrafts";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name."(
            id INTEGER NOT NULL AUTO_INCREMENT,
            ac_name VARCHAR(50),
            ac_desc TEXT(1000),
            ac_pax_min INT(3),
            ac_pax_max INT(3),
            ac_range float(20),
            ac_speed float(20),
            ac_per_hr_fee float(20),
            ac_per_landing_fee float(20), 
            ac_additions float(20),
            ac_ground_mins float(20),
            ac_interior_img VARCHAR(100),
            ac_exterior_img VARCHAR(100),
            ac_status INT(1),
            PRIMARY KEY  (id))";

            $sql_insert = "INSERT INTO wp_flightbook_aircrafts (id, ac_name, ac_desc, ac_pax_min, ac_pax_max, ac_range, ac_speed, ac_per_hr_fee, ac_per_landing_fee, ac_additions, ac_ground_mins, ac_interior_img, ac_exterior_img, ac_status) VALUES
            (1, 'Turboprop', 'Tooltip desctiption goes here', 5, 9, 2338, 288, 2225, 150, 10, 15, 'turbo-prop-category-iinterior.jpg', 'turbo-prop-category-iinterior.jpg', 1),
            (2, 'Light Jets', 'Light Jets desctiption goes here', 5, 8, 1529, 355, 2850, 150, 10, 15, 'light-jet-category-interior.jpg', 'light-jet-category-interior.jpg', 1),
            (3, 'Midsize Jets', 'Midsize Jets Description goes here', 8, 9, 2269, 422, 3750, 150, 10, 15, '', '', 1),
            (4, 'Super MidsizeJets', 'Super MidsizeJets Description', 8, 10, 3063, 453, 5150, 150, 10, 15, '', '', 1),
            (5, 'Heavy Private Jets', 'Heavy Private Jets Description', 10, 16, 4062, 499, 6800, 150, 10, 15, '', '', 1),
            (6, 'Ultra Long Range Jets', 'Ultra Long Range Jets Description', 12, 16, 6226, 442, 9500, 150, 10, 15, '', '', 1),
            (7, 'VIP Airliners', 'VIP Airliners Description', 16, 50, 4349, 477, 19500, 150, 10, 15, '', '', 1);";

            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($sql_insert);
            add_option("flightbook_aircrafts_db", "1.0");
        }

        // settings
        $table_name = $wpdb->prefix."flightbook_settings";
        if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
            $sql = 'CREATE TABLE '.$table_name.'(
            id INTEGER NOT NULL AUTO_INCREMENT,
            color_theme VARCHAR(10),
            PRIMARY KEY  (id))';
            $sql_insert = "INSERT INTO $table_name
            VALUES (1,'grey')";
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($sql_insert);
            add_option("flightbook_settings_db", "1.1");
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
        if ( $post_slug=="flightbook" || true ){
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
         $color='gray';
         if ( $color=='gray' ){
            wp_enqueue_style( 'flightbook_custom_styles', plugins_url('/assets/gray.css',__FILE__),100);
        }else if ( $color=='blue' ){
            wp_enqueue_style( 'flightbook_custom_styles', plugins_url('/assets/blue.css',__FILE__),100);
        }

        wp_enqueue_style( 'flightbook_common_styles', plugins_url('/assets/common.css',__FILE__),101);

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