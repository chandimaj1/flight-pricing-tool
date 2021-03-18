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
$plugin_url = $site_host."/wp-content/plugins/flight_booking/";

global $wpdb;
    $table_name = $wpdb->prefix."flightbook_aircrafts";
    $sql = "SELECT DISTINCT * FROM $table_name WHERE ac_status=1";
    $result = $wpdb->get_results( $sql );
    
    $msg = "fail";
    if($result){ 
        $msg= "success";
    }

//Get aircraft results

?>
<script type="text/javascript">
        var plugin_url = "<?= $plugin_url ?>";
    </script>
<div class="container-fluid" >
    <div style="margin-top:15px; margin-bottom:20px; background-color:#555; padding:5px;">
        
            <img src="<?= $plugin_url ?>/assets/images/velox-jets-logo.png" class="velox-logo">
       
            <h2 style="padding:20px; display:inline-block" id="admin-title">Velox Jets Pricing Tool</h2>
    </div>
    
    <h4>Aircraft Categories</h4>
    <hr>
        <input id="ac_file_upload" type="file" style="display:none;"/>
        <table id="ac_table" class="table-responsive table-sm table-striped">
            <thead class="thead-dark">
                <tr class="">
                    <th style="width:12%">Category</th>
                    <th style="">Aircraft Description</th>
                    <th style="width:6%">Min. Pax</th>
                    <th style="width:6%">Max. Pax</th>
                    <th style="width:8%">Range (NM)</th>
                    <th style="width:8%">Speed (KTS)</th>
                    <th style="width:8%">Hourly Rate (USD)</th>
                    <th style="width:6%">Comm(%)</th>
                    <th style="width:6%">Fees (USD)</th>
                    <th style="width:6%">Block Time (min)</th>
                    <th style="width:50px">Int Image</th>
                    <th style="width:50px">Ext Image</th>
                    <th style="width:60px">Edit Row</th>
                </tr>
            </thead>
            <tbody>
<?php
    if ($msg=='success'){
        foreach ($result as $row){
            
?>
                <tr class="aircraft_row" id="aircraft_row<?= $row->id ?>">
                    <td><input type="text" class="ac_name  form-control" value="<?= $row->ac_name ?>"/></td>
                    <td><input type="text" class="ac_desc  form-control" value="<?= $row->ac_desc ?>"/></td>
                    <td><input type="text" class="ac_pax_min  form-control" value="<?= $row->ac_pax_min ?>"/></td>
                    <td><input type="text" class="ac_pax_max  form-control" value="<?= $row->ac_pax_max ?>"/></td>
                    <td><input type="text" class="ac_range form-control" value="<?= $row->ac_range ?>"/></td>
                    <td><input type="text" class="ac_speed form-control" value="<?= $row->ac_speed ?>"/></td>
                    <td><input type="text" class="ac_per_hr_fee form-control" value="<?= $row->ac_per_hr_fee ?>"/></td>
                    <td><input type="text" class="ac_additions form-control" value="<?= $row->ac_additions ?>"/></td>
                    <td><input type="text" class="ac_landing_fee form-control" value="<?= $row->ac_per_landing_fee ?>"/></td>
                    <td><input type="text" class="ac_ground_mins form-control"value="<?= $row->ac_ground_mins ?>" /></td>
                    <td class="ac_img_int" img_name='<?= $row->ac_interior_img ?>'>
                        <button class='btn btn-primary ac_img_view'><i class="fa fa-eye"></i></button>
                        <button class='btn btn-primary ac_img_change'><i class="fa fa-plus"></i></button>
                    </td>
                    <td class="ac_img_ext" img_name='<?= $row->ac_exterior_img ?>'>
                        <button class='btn btn-primary ac_img_view'><i class="fa fa-eye"></i></button>
                        <button class='btn btn-primary ac_img_change'><i class="fa fa-plus"></i></button>
                    </td>
                    <td class="ac_actions">
                        <button class='btn btn-success ac_img_save'><i class="fa fa-check"></i></button> 
                        <button class='btn btn-danger ac_img_cancel'><i class="fa fa-times"></i></button>
                        <button class='btn btn-warning ac_img_edit'><i class="fa fa-edit"></i></button>
                    </td>
                </tr>
<?php
        }
    }else{
        echo ('<h3> Could not fetch results from the database !</h3>');
    }
?>
            </tbody>
        </table>
</div>

<!--
    Plugin Settings
-->

<div class="container-fluid stripe" style="margin-top:50px">
    <h4>Plugin Settings</h4>
    <hr>
    <div id="settings_row">
        <div class="row" id="settings_buttons_row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn btn-warning" id="reset_settings">Reset</button>
                <button class="btn btn-success" id="update_settings">Update</button>
            </div>
        </div>
    <?php
$table_name = $wpdb->prefix."flightbook_settings";
$sql = "SELECT DISTINCT * FROM $table_name";
$result = $wpdb->get_results( $sql );
    
$msg = "fail";

if($result){
    $tabs = '';
    foreach ($result[0] as $key=>$row){

        if ($key != 'id'){
?>
        <div class="row settings_set_row">
            <div class="col-sm-4"><?= $key ?></div>
            <div class="col-sm-8">
                <input type="text" class="settings_row_set form-control" id="settings_<?= $key ?>"  value="<?= $row ?>" />
            </div>
        </div>

<?php
        }
    }
}else{
    echo ('Error getting settings from database.');
}
?>
    </div> 
</div>



<!--
    Theme Settings
-->

<div class="container-fluid stripe" style="margin-top:50px">
    <h4>Theme Settings</h4>
    <hr>
    <div id="themes_row">
        <div class="row" id="settings_buttons_row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn btn-warning" id="reset_theme_settings">Reset</button>
                <button class="btn btn-success" id="update_theme_settings">Update</button>
            </div>
        </div>
    <?php
$table_name = $wpdb->prefix."flightbook_theme";
$sql = "SELECT DISTINCT * FROM $table_name";
$result = $wpdb->get_results( $sql );
    
$msg = "fail";

if($result){
    $tabs = '';
    foreach ($result[0] as $key=>$row){

        if ($key != 'id' && $key != 'google_font'){
?>
        <div class="row themes_set_row">
            <div class="col-sm-4"><?= $key ?></div>
            <div class="col-sm-8">
                <input type="text" class="themes_row_set form-control" id="themes_<?= $key ?>"  value="<?= $row ?>" />
            </div>
        </div>

<?php
        }else if($key == 'google_font'){
?>
        <div class="row themes_set_row">
            <div class="col-sm-4"><?= $key ?></div>
            <div class="col-sm-4">
                <select class="themes_row_set form-control" id="settings_google_font"  selected_font="<?= $row ?>">

                <select>
            </div>
            <div class="col-sm-4">
                <div id="sample_text">The quick brown fox jumps over the lazy dog.</div>
            </div>
        </div>

<?php
        }
    }
}else{
    echo ('Error getting settings from database.');
}
?>
    </div> 
</div>


<!--
    Language Settings
-->

<div class="container-fluid" style="margin-top:50px">
    <h4>Language Translations</h4>
    <hr>
    <div class="row" id="language_select_row">

<?php
$table_name = $wpdb->prefix."flightbook_languages";
$sql = "SELECT DISTINCT * FROM $table_name";
$result = $wpdb->get_results( $sql );
    
$msg = "fail";

if($result){
    $tabs = '';
    foreach ($result as $row){

        $lg_class = "btn-secondary";
        if ($row->id == "1"){
            $lg_class = "btn-primary";
        }

        $tabs .= ("<button class='lang_tab_link btn $lg_class' lang_id='$row->id' > $row->language </button>");
    }
?>

        <div class="col-md-12">
            <div>Select language file to modify <br><br></div>
        </div>
        <div class="col-md-9" id="language_tabs">
            <?= $tabs ?>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" id="update_language">Update Language</button>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-12" id="language_settings">

        <?php
            foreach ($result as $rows){
                $lg_active = "";
                if ($rows->id == "1"){
                    $lg_active = "lg_active";
                }
        ?>
            
             <div class='lang_tab <?= $lg_active ?>' lang_name="<?= $rows->language ?>" id='lang_tab<?= $rows->id ?>'>
             <?php
             foreach ($rows as $key=>$row){
                 if( $key != 'id' && $key != 'language'){
             ?>
                <div class="row">
                    <div class="col-sm-4"><?= $key ?></div>
                    <div class="col-sm-8">
                        <input type="text" class="lang_row_set form-control" row_key="<?= $key ?>"   value="<?= $row ?>" />
                    </div>
                </div>
             <?php
                }
             }
             ?>
            </div>
        <?php
            }
        ?>

        </div>
<?php
    

}else{
    echo ('<h3> Could not fetch results from the languages database !</h3>');
}
?>

    </div>
</div>






<div class="modal fade" id="ac_img_viewer" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" style="width:100%">
      </div>
      <div class="modal-footer">
        <a href="" target="_blank"><button type="button" class="btn btn-primary">Full View</button></a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





