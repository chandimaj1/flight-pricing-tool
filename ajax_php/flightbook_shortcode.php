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

    //Get Aircrafts settings from database
    global $wpdb;
    $table_name = $wpdb->prefix."flightbook_aircrafts";
    $sql = "SELECT DISTINCT * FROM $table_name WHERE ac_status=1";
    $result = $wpdb->get_results( $sql );
    
    if($result){ 
        $msg= "success";
        $aircrafts = json_encode($result);
    }else{
        $msg="failed";
        $aircrafts = json_encode("{message:'error'}");
    }

    //Get theme settings from the database
    $table_name = $wpdb->prefix."flightbook_theme";
    $sql = "SELECT DISTINCT * FROM $table_name";
    $result = $wpdb->get_results( $sql );
    
    if($result){ 
        $msg= "success";
        $theme = json_encode($result[0]);
    }else{
        $msg="failed";
        $theme = json_encode("{message:'error'}");
    }


    //GET Language settings from the database
    $table_name = $wpdb->prefix."flightbook_languages";
    $sql = "SELECT DISTINCT * FROM $table_name";
    $result = $wpdb->get_results( $sql );
    
    if($result){ 
        $msg= "success";
        $languages = array();
        foreach ($result as $row){
            $languages[$row->language] = $row;
        }
        $languages = json_encode($languages);
    }else{
        $msg="failed";
        $languages = json_encode("{message:'error'}");
    }


//Normal Leg row template
$leg_row_normal = '
<div class="row no-gutters leg_row">
<div class="col-md-5">
    <div class="row no-gutters complete-trip">
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex one leg_from" placeholder="From" value="" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /></span>
                </div> 
            </div> 
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex two leg_to" placeholder="Where to?" value="" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /></span>
                </div> 
            </div>
        </div>
        <div class="retweet">
            <a class="icon-change swap" ><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-sm-4 col-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector leg_departure_date" placeholder="When?" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                        <div class="leg_departure_dateformat">Departure Date</div>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-5 col-6">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passengers"> 
                            <option selected value="0" class="default_passenger">Passengers</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20-50">20-50</option>
                            <option value="51-100">51-100</option>
                            <option value="51-100">51-100</option>
                            <option value="200+">200+</option>
                        </select> 
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/passenger-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-3 col-12">
                <div class="form-group border-none">
                    <a class="btn brn-search search_btn">Search</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
';

//Round Trip Leg row template
$leg_row_round_trip = '<div class="row no-gutters leg_row" >
<div class="col-md-4">
    <div class="row no-gutters complete-trip">
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex one leg_from" placeholder="From" value="" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /></span>
                </div> 
            </div> 
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex two leg_to" placeholder="Where to?" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /></span>
                </div> 
            </div>
        </div>
        <div class="retweet">
            <a class="icon-change swap" ><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector2 leg_departure_date" placeholder="Departure date" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                        <div class="leg_departure_dateformat">Departure Date</div>
                    </div> 
                </div> 
            </div>
            <div class="col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector2 leg_return_date" placeholder="Return date" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                        <div class="leg_departure_dateformat">Return Date</div>
                    </div> 
                </div> 
            </div>
            <div class="col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passengers"> 
                            <option selected value="0" disabled style="display:none" class="default_passenger">Passengers</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20-50">20-50</option>
                            <option value="51-100">51-100</option>
                            <option value="51-100">51-100</option>
                            <option value="200+">200+</option>
                        </select> 
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/passenger-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-md-3 col-sm-6 col-6">
                <div class="form-group border-none">
                    <a class="btn brn-search search_btn">Search</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>';

$leg_row_multi_row = '<div class="row no-gutters leg_row leg_row_multi">
<div class="col-md-5">
    <div class="row no-gutters complete-trip">
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex one2 leg_from" placeholder="From" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /></span>
                </div> 
            </div> 
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocompletex two2 leg_to" placeholder="Where to?" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /></span>
                </div> 
            </div>
        </div>
        <div class="retweet">
            <a class="icon-change swap" ><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-sm-4 col-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector leg_departure_date" placeholder="When?" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                        <div class="leg_departure_dateformat">Departure Date</div>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-5 col-6">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passengers multi_select2"> 
                            <option selected value="0" disabled class="default_passenger">Passengers</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20-50">20-50</option>
                            <option value="51-100">51-100</option>
                            <option value="51-100">51-100</option>
                            <option value="200+">200+</option>
                        </select> 
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/passenger-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-3 col-12">
                <div class="form-group border-none">
                    <ul class="add-close-btn">
                        <li><a class="btn brn-search addrow_btn" ><img src="'.$plugin_url.'assets/images/pluse-icon.svg" alt="" /> </a></li>
                        <li><a class="btn brn-search removerow_btn" ><img src="'.$plugin_url.'assets/images/close-icon.svg" alt="" /></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>';

$category_sorting_html =
'<div id="category_sort">
    <div class="row">
        <div class="col-sm-2 text-center mb-3">
            Order results by:
        </div>
         <div class="col-sm-3">
            <div class="input-group">
                <select class="order_category" id="orderby_time" title="Flight Time">
                    <option selected class="hidden" value="noselect">Flight Time</option>
                    <option value="asc">Lowest to highest</option>
                    <option value="desc">Highest to lowest</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group">
                <select class="order_category" id="orderby_price" Placeholder="Price">
                    <option selected style="display:none" value="noselect">Price</option>
                    <option value="asc">Lowest to highest</option>
                    <option value="desc">Highest to lowest</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group">
                <select class="order_category" id="orderby_pax">
                    <option selected style="display:none" value="noselect">Passenger Capacity</option>
                    <option value="asc">Lowest to highest</option>
                    <option value="desc">Highest to lowest</option>
                </select>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>';

$flight_info_card = '<div class="card-result aircraft_card" id="ac_{{ac_id}}">
<div class="top-result">
    <div class="row no-gutters">
        <div class="col-lg-7">
            <div class="title-images">
                <div class="row no-gutters">
                    <div class="col-6">
                        <div class="img-holder img-enlargeable" style="background-image:url('."'$plugin_url"."assets/images/aircrafts/{{aircraft_image}}'".')">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="img-holder img-enlargeable" style="background-image:url('."'$plugin_url"."assets/images/aircrafts/{{aircraft_inner_image}}'".')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="detail-card">
                <div class="media">
                    <div class="media-body">
                        <h3> <span class="ac_category_name">{{aircraft_type}}</span>  <i data-toggle="tooltip" data-placement="top" title="{{aircraft_desc}}"><img src="'.$plugin_url.'assets/images/question-circle.svg" alt=""></i></h3>
                        <span class="badge-passanger">
                            <i><img src="'.$plugin_url.'assets/images/user-white.svg" alt=""></i>
                            {{pax_capacity}}
                        </span>
                    </div>
                    <div class="inquiry-info">

                        <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#{{contact_form_link}}" aria-expanded="false">
                            <strong><span class="total_price" price_in_eur="0">0</span>*</strong>
                            <span class="ac_lang_inquiry">Inquiry</span>
                        </a>

                        <p>*<span class="ac_lang_pricefooter">Estimated price before taxes & fees.</span></p>
                    </div>
                </div>


                <div id="carousel__carousel_id__" class="carousel slide" data-ride="carousel" data-interval="false" data-wrap="false">
                    <div class="carousel-inner">
                        {{legs_time_placeholder}}
                    </div>

                    <a class="carousel-control-prev" href="#carousel__carousel_id__" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel__carousel_id__" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                    </a>
                </div>




                

            </div>
        </div>
    </div>
    <div class="collapse" id="{{contact_form_id}}">
        <form action="" class="contact-detail">
            <p class="contact_form_title">Please provide your contact details here.</p>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-field">
                            <input type="text" class="form-control contact_name" placeholder="Name">
                            <i><img src="'.$plugin_url.'assets/images/user-grey.svg" alt=""></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-field">
                            <input type="email" class="form-control contact_email" placeholder="Email" required>
                            <i><img src="'.$plugin_url.'assets/images/envelope-grey.svg" alt=""></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-field">
                            <input type="text" class="form-control contact_phone" placeholder="Phone Number">
                            <i><img src="'.$plugin_url.'assets/images/phne.svg" alt=""></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="input-field">
                            <input type="text" class="form-control contact_requirements" placeholder="Any special requests or requirements">
                            <i><img src="'.$plugin_url.'assets/images/cmment.svg" alt=""></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="btn brn_search send_inquiry">Send Inquiry</div>
                <div class="inquirymsg_success inquiry_msg">Thank you. We will get in touch with you soon.</div>
                <div class="inquirymsg_fail inquiry_msg">Message not sent.</div>
            </div>
        </form>
    </div>

</div>
</div>';

$legs_time_template = '
<div class="carousel-item">
<div class="detail-card-footer row no-gutters leg_card" leg_total_price="{{leg_total}}">
<div class="col">
    <div class="time">{{departure_time}}</div>
    <div class="ac_date">{{departure_date}}</div>
    <div class="loc">{{origin_iata}}</div>
</div>
<div class="img-flight">
    <img src="'.$plugin_url.'assets/images/flight-icon.svg" alt="" class="img-fluid">
    <div class="time-est">{{total_duration}}</div>
</div>
<div class="col">
    <div class="time">{{arrival_time}}</div>
    <div class="ac_date">{{arrival_date}}</div>
    <div class="loc">{{destination_iata}}</div>
</div>
</div>
</div>';

?>

<div id="hr_settings" style="display:none">
    <input id="hr_pluginurl" value="<?= $plugin_url ?>" disabled/>
    <input id="isadminpage" value="true" disabled/>

    <script type="text/javascript">
        var plugin_url = "<?= $plugin_url ?>";
        var multi_row_html = `<?= $leg_row_multi_row ?>`;
        var flight_info_card = `<?= $flight_info_card ?>`;
        var legs_time_template = `<?= $legs_time_template ?>`;
        var category_sorting_html = `<?= $category_sorting_html ?>`;
        var aircrafts = <?= $aircrafts ?>;
        var ac_lang = <?= $languages ?>;
        var theme = <?= $theme ?>;
        console.log(theme);
        /**
         * Setting theme values on elements
         */
        let googlefont_link_tag = "<link href='https://fonts.googleapis.com/css?family=" + theme.google_font + ":100,200,300,400,500,600,700,800' rel='stylesheet' type='text/css'>";
        let style_tag = `
        <style>
            #initial_selection *:not(i),
            #search_modal *:not(i),
            .search-modal .flatpickr-calendar *,
            .search-modal .select2-results__option{
                font-family:'${theme.google_font}' !important;
            }

            .search-modal .flight-book-top .nav-pills .nav-item .nav-link{
                font-size: ${theme.tabs_font_size}px !important;
                color: ${theme.tabs_font_color} !important;
            }

            .search-modal .flight-book-inner .form-group .field .form-control,
            .search-modal .flight-book-inner .form-group .field .form-control::placeholder,
            .search-modal .flight-book-inner .form-group .field .select2-selection__rendered,
            .search-modal .leg_departure_dateformat,
            .search-modal .leg_return_dateformat{
                font-size: ${theme.input_fields_font_size}px !important;
                color: ${theme.input_fields_font_color} !important;
            }

            .search-modal .flight-book-inner .form-group .field span.icon-span{
                background-color: ${theme.input_fields_icon_backgroundcolor} !important;
            }

            .search-modal .btn{
                font-size: ${theme.buttons_font_size}px !important;
                color: ${theme.buttons_font_color} !important;
                background-color: ${theme.buttons_backgroundcolor} !important;
            }

            .search-modal .search_btn:hover,
            .search-modal .btn:hover
            {
                background-color: ${theme.buttons_hovercolor} !important;
            }

            .card-result .top-result .detail-card .media .media-body h3{
                font-size: ${theme.aircraft_category_font_size}px !important;
                color: ${theme.aircraft_category_font_color} !important;
            }

            .card-result .top-result .detail-card .media .media-body .badge-passanger,
            .flatpickr-day.selected,
            .flatpickr-time,
            .select2-container--default .select2-results__option--highlighted[aria-selected]
            {
                background-color: ${theme.accents_background_color} !important;
            }
        </style>
        `;
        document.head.insertAdjacentHTML( 'beforeend', googlefont_link_tag );
        document.head.insertAdjacentHTML( 'beforeend', style_tag );
    </script>
</div>

<!-- Init Selection -->

<div class="search-modal" id="initial_selection">
    <div class=" modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="flight-book-top row">
                <div class="table-responsive col-lg-8 col-md-7 col-sm-12">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-one-way-tab" data-toggle="pill" href="#pills-one-way" role="tab" aria-controls="pills-one-way" aria-selected="true">One Way</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-round-trip-tab" data-toggle="pill" href="#pills-round-trip" role="tab" aria-controls="pills-round-trip" aria-selected="false">Round Trip</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-multi-leg-tab" data-toggle="pill" href="#pills-multi-leg" role="tab" aria-controls="pills-multi-leg" aria-selected="false">Multi Leg</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-empty-leg-tab" data-toggle="pill" href="#pills-empty-leg" role="tab" aria-controls="pills-empty-leg" aria-selected="false">Empty Legs</a>
                        </li>
                    </ul>
                </div>
                <div class="country-flight col-lg-4 col-md-5 col-sm-12">
                    <ul id="language_select" class="col-sm-7 col-xs-7" selected_language="en">
                        <li><a class='en' ac_lang="english" > <i data-toggle="tooltip" data-placement="top" title="English"> <img src="<?= $plugin_url ?>assets/images/uk.png" alt="" /> </i> </a></li>
                        <!--
                        <li><a class='afganistan' ac_lang="afganistan" > <i data-toggle="tooltip" data-placement="top" title="Afganistan"> <img src="<?= $plugin_url ?>assets/images/afghanistan-flag.png" alt="" /> </i> </a></li>
                        <li><a class='albania' ac_lang="albania" ><i data-toggle="tooltip" data-placement="top" title="Albania"> <img src="<?= $plugin_url ?>assets/images/albania.png" alt="" /> </i> </a></li>
                        <li><a class='uae' ac_lang="uae" ><i data-toggle="tooltip" data-placement="top" title="UAE"> <img src="<?= $plugin_url ?>assets/images/UAE-flag.png" alt="" /> </i> </a></li>
                        <li><a class='andorra'ac_lang="andorra" ><i data-toggle="tooltip" data-placement="top" title="Andorra"> <img src="<?= $plugin_url ?>assets/images/andorra-flag.png" alt="" /> </i> </a></li>
                        -->
                        <li><a class='portugese'ac_lang="portugese" ><i data-toggle="tooltip" data-placement="top" title="Portugese"> <img src="<?= $plugin_url ?>assets/images/portugese.png" alt="" /> </i> </a></li>
                        <li><a class='spannish'ac_lang="spannish" ><i data-toggle="tooltip" data-placement="top" title="Spannish"> <img src="<?= $plugin_url ?>assets/images/spannish.png" alt="" /> </i> </a></li>
                    </ul>
                    <div class="currency-dropdown col-sm-5 col-xs-5">
                        <select class="templatingSelect2" id="currency_selector"> 
                            <option id="currency_usd" selected cur_rate='1'> USD (&dollar;)</option>
                            <option id="currency_eur" cur_rate='1'>EUR (&euro;)</option>
                            <option id="currency_gbp" >GBP (&pound;)</option>
                        </select> 
                    </div>
                </div>
            </div>  
            <div class="tab-content" id="pills-tabContent">

                <!-- Init:: One way Tab-->
                <div class="tab-pane fade show active" id="pills-one-way" role="tabpanel" aria-labelledby="pills-one-way-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg row -->
                            <?= $leg_row_normal ?>
                        </form>
                    </div>
                </div> 


                <!-- Init:: Round Trip Tab-->
                <div class="tab-pane fade" id="pills-round-trip" role="tabpanel" aria-labelledby="pills-round-trip-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg row Round Trip-->
                            <?= $leg_row_round_trip ?>
                        </form>
                    </div>
                </div>

                <!-- Init:: Multi Leg Tab-->
                <div class="tab-pane fade" id="pills-multi-leg" role="tabpanel" aria-labelledby="pills-multi-leg-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg Row Normal-->
                            <?= $leg_row_normal ?>
                            <!-- Leg Row Multi-->
                            <?= $leg_row_multi_row ?>
                            
                        </form>
                    </div>
                </div>

                <!-- Init:: Empty Leg Tab-->
                <div class="tab-pane fade" id="pills-empty-leg" role="tabpanel" aria-labelledby="pills-empty-leg-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg Row-->
                            <?= $leg_row_normal ?>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        
    </div> <!-- / Modal Dialog -->
</div>




<!-- Modal Window -->
<div class="modal fade  search-modal" id="search_modal">
    <div class="modal modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            
            <div class=" search-modal" id="results_modal">
                <div class="modal_close"><i class="fa fa-times"></i></div>

                <!-- Loader -->
                <div class="search_loader fade show">
                    <span id="status_update" default_note=" We can&#39;t find any result for the current selection. Please make sure that required data is entered."></span><br>
                    <img src="<?= $plugin_url ?>assets/images/loader_grey.png" />
                    <p class="searching_notice">Please wait while we source the available aircraft</p>
                </div>



                <!-- Results Set -->
                <div class="search-results fade" id="search-results1">
                </div>

            </div><!-- / Modal 2 -->
        </div> 
    </div> <!-- / Modal Dialog -->
</div>