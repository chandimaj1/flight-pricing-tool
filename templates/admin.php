<?php
if (! defined( 'ABSPATH') ){
    die;
}

//setting script variables
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
$site_host = "https://";   
else  
$site_host = "http://";   
// Append the host(domain name, ip) to the URL.   
$site_host.= $_SERVER['HTTP_HOST']; 


// Get SN Api Settings
//include plugin_dir_path(__FILE__)."../ajax_php/get_sn_settings.php"; 
//$sn_settings = get_settings_table_results();

?>
<h2 style="padding:20px;" id="admin-title">FlightBook Plugin Settings <br>
<span>-FlightBook Pluggin by ChandimaJ</span></h2>

<div class="container" id="admin-container">
    <div class="row">
        <div class="col-sm-8" id="sn-api-settings" >
            <h4>Api Testing</h4>
            <div class="signnow_error">Error! All fields must be filled.</div>
            <div>Api URL:</div>
            <div class="input-group">
                <input type="text" id="sn_data_url" value="https://greatcirclemapper.p.rapidapi.com/airports/read" class="form-control" placeholder="Api Host">
            </div>
            <div>Api Host:</div>
            <div class="input-group">
                <input type="text" id="sn_data_host" value="greatcirclemapper.p.rapidapi.com" class="form-control" placeholder="Api Host">
            </div>
            <div>Api Key:</div>
            <div class="input-group">
                <input type="text" id="sn_data_key" value="0dade7188emsh9333ccd18ebfa18p1df4a7jsn3e15a17341bb" class="form-control" placeholder="Api Key">
            </div>
            <div>Query String:</div>
            <div class="input-group">
                <input type="text" id="sn_data_query" value="KSFO"   class="form-control" placeholder="Api Query (if applicable)">
            </div>
            <div id="sn_save_settings">
                <button type="button" class="btn btn-default sn_save">Test API</button>
            </div>

            <hr>

            <div>Result:</div>
            <div class="input-group">
                <textarea id="sn_data_result" value="" style="height:200px"  class="form-control" placeholder="-Results-"></textarea>
            </div>
 
            <div class="button-group">
                
            </div>
            
        </div>
        <div class="col-sm-4">
            
        </div>
    </div>
 
    <div class="row" style="margin-top:50px;">
        <div class="col-sm-6">
            <h4>FrontEnd Implementation</h4>
            <p> Use shortcode [flightbook] and slug '/flightbook' on any page / post </p>
        </div>
    </div>
</div>



