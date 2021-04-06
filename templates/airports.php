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

/**
 * 
 * Database Retrieval for information
 */
global $wpdb;
    $table_name = $wpdb->prefix."flightbook_airports";
    $limit = ' LIMIT 25 ';
    $sort = ' ORDER BY id ASC ';
    $where = '';


    //For JavaScript Identification
    $limitset = 'no';
    $columnset = 'no';
    $orderset = 'no';
    $searchset = 'no';
    $pageset = 'no';
    
    //Query Parameters
    // Order
    if ( isset($_GET["column"]) && $_GET["column"]!='' ){
        $order = "ASC";
        if( isset($_GET["order"]) && $_GET["order"]!='' ){
            $order = $_GET["order"];
            $orderset = $_GET["order"];
        }
        $sort = " ORDER BY ".$_GET["column"]." $order ";
        $columnset = $_GET["column"];
    }
    // Per Page Results Limit
    if( isset($_GET["limit"]) && $_GET["limit"]!='' ){
        $limit = " LIMIT ".$_GET["limit"]." ";
        $limitset = $_GET["limit"];
    }
    // search
    if( isset($_GET["search"]) && $_GET["search"]!='' ){
        $search_text = sanitize_text_field( $_GET['search'] );
        $where = " WHERE (airport_name like '%$search_text%') OR (airport_city like '%$search_text%') OR (country_name like '%$search_text%') OR (airport_iata like '%$search_text%') OR (airport_icao like '%$search_text%') ";
        $searchset = $search_text;
    }


    //var_dump ($_GET);

    $sql = "SELECT DISTINCT id FROM $table_name ";
    $sql = $sql.$where.$sort;
    //echo $sql.$where.$sort.$limit;
    $result = $wpdb->get_results( $sql,ARRAY_N );
    $count = $wpdb->query( $sql );

    
    $msg = "fail";
    $pages_count = 0;

    if($result){ 
        $msg= "success";

        $limit = 25;
        if ( isset($_GET["limit"]) && (int)$_GET["limit"]>0 ){
            $limit = (int)$_GET["limit"];
        }

        $pages = array_chunk($result,$limit); 
        
        if ( isset($pages) && count($pages)>0 ){
            $pages_count = count($pages);
        }

        $w='';
        $page = 0;
        if ( isset($_GET["results_page"])  &&  (int)$_GET["results_page"]>0 ){
            $page = (int)$_GET["results_page"] - 1;
            $pageset = $_GET["results_page"];
        }


        if ($pages_count>0){
            foreach ($pages[$page] as $id){
                $w .= $id[0].',';
            }
            $w = rtrim($w, ",");
            //$w = array_map( 'intval' , explode(',',$w) );
            //$w = implode(",", $w);

        // var_dump($w);


            $result = $wpdb->get_results("SELECT DISTINCT * FROM $table_name WHERE id IN ($w) $sort");
        }

        $pagination_html = '';
        //Pagination HTML
        if ($count <= $limit){
            $pagination_html = "<option value='1' selected>1</option>";
        }else{
            for ($i=0; $i<$pages_count; $i++){
                $x = $i+1;
                $selected = "";
                if ($i==$page){
                    $selected = "selected";
                }
                $pagination_html .= "<option value='$x' $selected>$x</option>";
            }
        }

    }

    //Getting the latest id
    $sql_for_id = "SELECT DISTINCT id FROM $table_name ORDER BY id DESC LIMIT 1";
    $result_for_id = $wpdb->get_results($sql_for_id, ARRAY_N);
    $last_id = (int)$result_for_id[0][0] + 1;
?>


<script type="text/javascript">
        var plugin_url = "<?= $plugin_url ?>";
        var settings_page_url = "<?php menu_page_url('flightbook_airports_settings'); ?>";
        var limitset = '<?= $limitset ?>';
        var columnset = '<?= $columnset ?>';
        var orderset = '<?= $orderset ?>';
        var searchset = '<?= $searchset ?>';
        var pageset = '<?= $pageset ?>';
        var lastid = <?= $last_id ?>;
    </script>
<div class="container-fluid" >
    <div style="margin-top:15px; margin-bottom:20px; background-color:#555; padding:5px;">
        
            <img src="<?= $plugin_url ?>/assets/images/velox-jets-logo.png" class="velox-logo">
       
            <h2 style="padding:20px; display:inline-block" id="admin-title">Velox Jets Pricing Tool</h2>
    </div>
    
    <h4>Airports Table</h4>
    <hr>
    <div class="records_actions row ">
        <div class="col-sm-4">
            <label class="text-left" style="width:100%">Sort table by column</label>
            <select id="ra_sort" sort_column="0" class="form-control" style="width:45%; float:left; height:38px">
                <option value="" selected>Select Column</option>
                <option value="id">Id(#)</option>
                <option value="airport_name">Airport Name</option>
                <option value="airport_city">Airport City</option>
                <option value="country_name">Country Name</option>
                <option value="airport_iata">IATA Code</option>
                <option value="airport_icao">ICAO Code</option>
                <option value="gmt">GMT</option>
                <option value="country_code">Country Code</option>
                <option value="latitude">Latitude</option>
                <option value="longitude">Longitude</option>
                <option value="status">Status</option>
            </select>

            <select id="ra_sortby" sort_order="asc" class="form-control" style="width:40%; float:left; height:38px;">
                <option value="" selected>Select Order</option>
                <option value="ASC"> Ascending </option>
                <option value="DESC">Descending</option>
            </select>

            <button id="sort_btn" class="btn btn-primary" style="width:15%; float:left; margin-top:0"><i class="fa fa-arrow-right"></i> </button>
        </div>


        <div class="col-sm-3">
            <label class="text-left" style="width:100%">Search table</label>
            <input type="text" id="ra_search" class="form-control" placeholder="Search..." style="width:80%; float:left;">
            <button id="search_btn" class="btn btn-primary" style="width:20%; float:left;"><i class="fa fa-search"></i></button>
        </div>

        <div class="col-sm-5">
            <label class="text-left" style="width:100%">Table actions</label>
            <button id="addnew_btn" class="btn btn-primary" style="width:29%; float:left; margin-right:.5%" onclick=""><i class="fa fa-plus"></i> Add Airport</button>
            <button id="update_btn" class="btn btn-secondary" style="width:30%; float:left; margin-right:.5%"><i class="fa fa-check"></i> Save All Changes</button>
            <button id="reset_btn" class="btn btn-primary" style="width:30%; float:left;" onclick="window.location.href='<?php menu_page_url('flightbook_airports_settings'); ?>'"><i class="fa fa-refresh"></i> Reload Table</button>
        </div>

    </div>

    <div class="row" style="margin-bottom:20px">
        <div class="col-sm-12" style="margin:0px 0px; background-color:#ddd; padding:10px 15px;">
            Showing 
            <select id="ra_perpage" sort_perpage="25">
                <option value="25" selected>25</option>
                <option value="50"> 50 </option>
                <option value="100"> 100 </option>
                <option value="250"> 250 </option>
                <option value="500"> 500 </option>
            </select>
            Results per page on page
            <?php 
                if ( $pages_count>1 && ($page+1)>1 ){
            ?>
            <button id="prev_page" class="btn btn-default"><i class="fa fa-chevron-left"></i></button>
            <?php
                }
            ?>
            <select id="ra_page" sort_page="1">
                <?= $pagination_html ?>
            </select>
            <?php 
                if ( $pages_count>1 && $pages_count>($page+1) ){
            ?>
            <button id="next_page" class="btn btn-default"><i class="fa fa-chevron-right"></i></button>
            <?php
                }
            ?>
            of <?= $pages_count ?> for <?= $count ?> results.
        </div>
    </div>

        <table id="ap_table" class="table-responsive table-sm table-striped">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th style="width:5%">#</th>
                    <th style="">Airport Name</th>
                    <th style="width:15%">Airport City</th>
                    <th style="width:13%">Country Name</th>
                    <th style="width:6%">IATA Code</th>
                    <th style="width:8%">ICAO Code</th>
                    <th style="width:5%">GMT</th>
                    <th style="width:3%">Country Code</th>
                    <th style="width:8%">Latitude</th>
                    <th style="width:8%">Longitude</th>
                    <th style="width:5%">Status</th>
                    <th style="">Edit</th>
                </tr>
            </thead>
            <tbody>
<?php
    if ($msg=='success'){
        foreach ($result as $row){
            
?>
                <tr class="airports_row" id="airports_row<?= $row->id ?>">
                    <td><div class="ap_id  form-control"><?= $row->id ?></div> </td>
                    <td><input type="text" class="ap_name  form-control" value="<?= $row->airport_name ?>"/></td>
                    <td><input type="text" class="ap_city form-control" value="<?= $row->airport_city ?>"/></td>
                    <td><input type="text" class="ap_country  form-control" value="<?= $row->country_name ?>"/></td>
                    <td><input type="text" class="ap_iata  form-control" value="<?= $row->airport_iata ?>"/></td>
                    <td><input type="text" class="ap_icao form-control" value="<?= $row->airport_icao ?>"/></td>
                    <td><input type="text" class="ap_gmt form-control" value="<?= $row->gmt ?>"/></td>
                    <td><input type="text" class="ap_country_code form-control" value="<?= $row->country_code ?>"/></td>
                    <td><input type="text" class="ap_lat form-control" value="<?= $row->latitude ?>"/></td>
                    <td><input type="text" class="ap_long form-control" value="<?= $row->longitude ?>"/></td>
                    <td class="ap_status status_at_<?= $row->status ?>" original_content="<?= $row->status ?>">
                        <button class='btn btn-success'><i class="fa fa-eye"></i></button>
                        <button class='btn btn-danger'><i class="fa fa-eye-slash"></i></button>
                    </td>
                    <td class="ap_actions">
                        <button class='btn btn-warning ap_img_edit'><i class="fa fa-edit"></i></button>
                        <button class='btn btn-success ap_img_save'><i class="fa fa-check"></i></button>
                        <button class='btn btn-secondary ap_img_cancel'><i class="fa fa-refresh"></i></button>
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