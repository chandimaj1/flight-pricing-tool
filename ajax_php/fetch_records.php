<?php
//error_reporting(0);

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);


function fetch_paginated_result_ids(){
    global $wpdb;
    $msg = 'Error! Unknown';

    if ( isset($_POST['page_size']) && ((int)$_POST['page_size']>=25) ){
        $array_chunk_size = (int)$_POST['page_size'];
    }else{
        $array_chunk_size = 25;
        $_POST['page_size'] = 25;
    }

    if( isset($_POST['table']) ){
        $table_name = $wpdb->prefix."hranker_".$_POST['table'];

        $sort = 'ORDER BY rank ASC';

        if ( isset($_POST["sort_by"])  &&  isset($_POST["sort_type"]) && $_POST["sort_by"]!="no_sort" &&  $_POST["sort_type"]!="no_sort" ){
            $sort_by = str_replace("hrt_","",$_POST['sort_by']);

            if( $sort_by == 'price' ){
                $sort = "ORDER BY ".$sort_by." * 1 ".$_POST['sort_type'];
            }else{
                $sort = "ORDER BY ".$sort_by." ".$_POST['sort_type'];
            }
            

            //Reset Pagination
            $_POST['pagination']=1;
        }

        $sql = "SELECT DISTINCT id FROM $table_name $sort";

        //Filtrations
        $filter_brand = true;
        $filter_principle = true;
        $filter_genre = true;
        $filter_price = true;
        $filter_sql = '';

        //-- Brand
        if(  isset($_POST['filter_brand'])  &&  ($_POST['filter_brand'] != "any")  ){
            $filter_sql = "( brand LIKE BINARY '".$_POST['filter_brand']."%')";
        }else{
            $filter_brand = false;
        }

        //-- Principle
        if ( $filter_brand ){
            $and = " AND"; 
        }else{
            $and = "";
        }

        if(  isset($_POST['filter_principle'])  &&  ($_POST['filter_principle'] != "any")  &&  ($_POST['table']=="headphones")   ){
            $filter_sql .= " $and ( principle LIKE BINARY '%".$_POST['filter_principle']."%')";
        }else{
            $filter_principle = false;
        }

        //-- Genre
        if ( $filter_principle || $filter_brand ){
            $and = " AND";
        }else{
            $and = "";
        }

        if(  isset($_POST['filter_genre'])  &&  ($_POST['filter_genre'] != "any")  ){
            $filter_sql .= " $and ( ganre_focus LIKE BINARY '%".$_POST['filter_genre']."%')";
        }else{
            $filter_genre = false;
        }


        //Price
        if ( $filter_principle || $filter_genre || $filter_brand ){
            $and = " AND";
        }else{
            $and = "";
        }

        if(  isset($_POST['filter_price_from'])  &&  isset($_POST['filter_price_to'])  &&  ( (int)$_POST['filter_price_to'] > (int)$_POST['filter_price_from'])  ){
                $filter_sql .= " $and ( price BETWEEN ".(int)$_POST['filter_price_from']." AND ".(int)$_POST['filter_price_to'].")";
        }else{
            $filter_price = false;
        }


        /**
         * 
         * Select table based on filters
         */
        if (  $filter_brand || $filter_principle || $filter_genre || $filter_price ){
            $select_from = "( SELECT * FROM $table_name WHERE ".$filter_sql." ) AS filtered_table";
        }else{
            $select_from = $table_name;
        }

        /**
         * 
         * Search 
         */
        if( isset($_POST['search']) && $_POST['search']!=""){
            $search_text = sanitize_text_field( $_POST['search'] );

            if( $_POST['table']=="headphones" ){
                $sql = "SELECT DISTINCT id FROM $select_from WHERE (brand like '%$search_text%') OR (device like '%$search_text%') OR (principle like '%$search_text%') OR (ganre_focus like '%$search_text%') ";
            }else if ( $_POST['table']=="iem" || $_POST['table']=="earbuds" ){
                $sql = "SELECT DISTINCT id FROM $select_from WHERE (brand like '%$search_text%') OR (device like '%$search_text%') OR (ganre_focus like '%$search_text%') ";
            }
        }else if (  $filter_brand || $filter_principle || $filter_genre || $filter_price  ){
            $sql = "SELECT DISTINCT id FROM $table_name WHERE $filter_sql";
        }

        //echo ("sql:".$sql);
        $result = $wpdb->get_results( $sql );
        

        if($result){ 
            $msg= "success";
        }else{
            $msg="failed";
        }
    }else{
        $msg = 'Missing data sent to server !';
    }

    $result = array_chunk($result, $array_chunk_size);

    if ( !isset($_POST["filter_principle"]) ){
        $_POST["filter_principle"] = false;
    }

    $return = array("msg"=>$msg, "paginated_result"=>$result, "sort"=>$sort, "sql"=>$sql, "filters"=>array("brand"=>$_POST["filter_brand"],"principle"=>$_POST["filter_principle"], "genre"=>$_POST["filter_genre"], "from"=>(int)$_POST["filter_price_from"], "to"=>(int)$_POST["filter_price_to"]) );
    return ($return);
}
$return = fetch_paginated_result_ids();





//Check if Pagination requested

if ( isset($_POST['pagination']) &&  (int)$_POST['pagination']>1 ){
    $request_page = (int)$_POST['pagination'];
}else{
    $request_page = 1;
}
//paged_Ids_array Index for requested page 
$page = $request_page - 1;
$total_pages = count($return["paginated_result"]);



//Fetch record info for the requested page
function fetch_results_from_ids($page_ids,$table,$sort){
    global $wpdb;
    $msg = 'Error! Unknown';

    if( isset($page_ids) &&  isset($table)){
        $table_name = $wpdb->prefix."hranker_".$table;
        $where = 'WHERE (';

        foreach ($page_ids as $id){
            $where .= 'id='.$id->id.' || ';
        }

        $where .= ' false )';

        $result = $wpdb->get_results( 
                    "SELECT * FROM $table_name $where $sort"
                );

        if($result){ 
            $msg= "success";
        }else{
            $msg="failed";
        }
    }else{
        $msg = 'Missing data sent to server !';
        $result = 'No Result';
    }

    $return = array("msg"=>$msg, "page_data"=>$result);
    return ($return);
}
$return_page = fetch_results_from_ids($return["paginated_result"][$page], $_POST["table"], $return["sort"]);







function create_paginationHTML($total_pages, $request_page){

    //Filling middle pages
    $paginationHtml_pages = '';
        for($i=1; $i<($total_pages+1); $i++){
            //Check if page or current page
            $class="page";
            if ($i==$request_page){$class="current";}
            $paginationHtml_pages .= "<span class='$class' >$i</span>";
        }

    $paginationHtml_previous = '<span class="page pagination_prev"><i class="fa fa-angle-left"></i></span>';
    $paginationHtml_next = '<span class="page pagination_next"><i class="fa fa-angle-right"></i></span>';
    
    if ($request_page == 1 && $total_pages==1){        // Only one page for results
        //HTML Components
        $paginationHtml_previous = '';
        $paginationHtml_pages = '<span class="current">1</span>';
        $paginationHtml_next = '';

    } else if ($request_page == 1){        // First Page requested
        //HTML Components
        $paginationHtml_previous = '';

    }else if(  $request_page < $total_pages  ){         // A Middle Page requested
        //HTML Components
        
    }else if(  $request_page == $total_pages  ){  // Last page requested
        //HTML Components
        $paginationHtml_next = '';
    }

    $paginationHtml_info = "Page $request_page of $total_pages";

    $arr = [25,50,100,1000];
    $page_select_html = "<select id='results_per_page'>";
    foreach ($arr as $i){
        if ($i == (int)$_POST['page_size']){
            $ifpgselected = "selected";
        }else{
            $ifpgselected = "";
        }
        $itxt = $i;
        if($i==1000){$itxt="All";}
        $page_select_html .= "<option value='$i' $ifpgselected >$itxt</option>"; 
    }
    $page_select_html .= "</select>";

    $paginationHtml = 
        "<div class='page-nav td-pb-padding-side'>"
            .$paginationHtml_previous
            .$paginationHtml_pages
            .$paginationHtml_next
            ."<span class='pages' id='pagination_info'>Showing "
            .$page_select_html
            ." results per page on $paginationHtml_info </span> "
            //.'<span class="pages" id="pagination_info">'.$paginationHtml_info.'</span>'
           // .'<div class="clearfix"></div>'
        ."</div>";

    return ($paginationHtml);
}
$paginationHtml = create_paginationHTML($total_pages, $request_page);



//Format filters
    function format_filters($filter_array){

        if ($filter_array){
            $filter_formatted = array();
            //echo ("filter array:"); 
             //var_dump($filter_array);
            foreach ($filter_array as $term){
                $term = $term[0];
                //echo ("Term:");
                //var_dump($term);

                if ( isset($term) && $term!="" && strpos($term, ',') !== false) {
                    $term_split = explode( ',', $term );
                //  echo('term split:');
                // var_dump($term_split);

                    foreach ($term_split as $t){
                        $t = ltrim($t); //Remove Spaces at begining
                        $t = rtrim($t); //Remove Spaces at end

                        if ($t != ""){ //Remove Empty $t values
                            array_push($filter_formatted,$t);
                        }
                    }
                }else if( $term!="" ){
                    array_push($filter_formatted,$term);
                }
            }
            //var_dump($filter_formatted);

            $filter_formatted = array_unique($filter_formatted); //Remove duplicates
            //var_dump($filter_formatted);
            sort($filter_formatted); //Sort Ascending
            //var_dump($filter_formatted); 
        }else{
            $filter_formatted = false;
        }
        

        return ($filter_formatted);
    }


function get_filters($table){
    global $wpdb;
    $principle = false;
    $genre = false;
    $msg = "Failed. No table found";
    
    if ( isset($_POST['table']) ){
        $table_name = $wpdb->prefix."hranker_".$_POST['table'];

        if ( $table == "headphones" ){
            $principle = $wpdb->get_results( "SELECT DISTINCT principle FROM $table_name ORDER BY principle ASC", ARRAY_N);
            $genre = $wpdb->get_results( "SELECT DISTINCT ganre_focus FROM $table_name ORDER BY ganre_focus ASC", ARRAY_N);
            $brand = $wpdb->get_results( "SELECT DISTINCT brand FROM $table_name ORDER BY device ASC", ARRAY_N);
        }else if( $table == "iem" || $table == "earbuds" ){
            $genre = $wpdb->get_results( "SELECT DISTINCT ganre_focus FROM $table_name ORDER BY ganre_focus ASC", ARRAY_N);
            $brand = $wpdb->get_results( "SELECT DISTINCT brand FROM $table_name ORDER BY device ASC", ARRAY_N);
        }

        if( $principle ){ $principle_filters = format_filters($principle);} else { $principle_filters = false;}
        if( $genre ){ $genre_filters = format_filters($genre);} else { $genre_filters = false;}
        if( $brand ){ $brand_filters = format_filters($brand);} else { $brand_filters = false;}
        $msg = "success";
    }

    $filters = array("msg"=>$msg,"principle"=>$principle_filters, "genre"=>$genre_filters, "brand"=>$brand_filters);
    return ($filters);
}
$filters = get_filters($_POST['table']);



$msg = array($return, $return_page, "paginationHtml"=>"$paginationHtml", $filters);

$msg = json_encode($msg);
echo($msg);

?>