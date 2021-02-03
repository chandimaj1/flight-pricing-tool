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
    <h2 style="padding:20px;" id="admin-title">FlightBook Plugin Settings <br>
    <span>-FlightBook Pluggin by ChandimaJ</span></h2>
    
    <h4>Aircraft Categories</h4>
        <table id="ac_table" class="table-responsive table-sm table-striped">
            <thead class="thead-dark">
                <tr class="bg-info">
                    <th style="width:12%">Name</th>
                    <th style="">Description</th>
                    <th style="width:6%">Min. Pax</th>
                    <th style="width:6%">Max. Pax</th>
                    <th style="width:8%">Range (NM)</th>
                    <th style="width:8%">Speed (KTS)</th>
                    <th style="width:8%">Price/Hr (USD)</th>
                    <th style="width:6%">Adds.(%)</th>
                    <th style="width:6%">PerLand (USD)</th>
                    <th style="width:6%">PerLand (min)</th>
                    <th style="width:50px">In. Img</th>
                    <th style="width:50px">Out. Img</th>
                    <th style="width:60px">Actions</th>
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
                    <td class="ac_img_ext" img_name='<?= $row->ac_interior_img ?>'>
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



