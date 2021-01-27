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
            <a class="icon-change swap" href="#"><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector leg_departure_date" placeholder="When?" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passengers"> 
                            <option selected value="passenger">Passenger </option>
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
            <div class="col-md-3">
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
                    <input type="text" class="form-control autocomplete one leg_from" placeholder="From" value="From" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /></span>
                </div> 
            </div> 
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocomplete two leg_to" placeholder="Where to?" value="Where to?" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /></span>
                </div> 
            </div>
        </div>
        <div class="retweet">
            <a class="icon-change swap" href="#"><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector2 leg_departure_date" placeholder="Departure date" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector2 leg_return_date" placeholder="Return date" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passengers"> 
                            <option selected="">Passenger </option>
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
            <div class="col-md-3">
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
                    <input type="text" class="form-control autocomplete one2 leg_from" placeholder="From" value="From" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/takeoff-the-plane.svg" alt="" /></span>
                </div> 
            </div> 
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="field">
                    <input type="text" class="form-control autocomplete two2 leg_to" placeholder="Where to?" value="Where to?" />
                    <span class="icon-span"><img class="image1" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /><img class="image2" src="'.$plugin_url.'assets/images/plane-landing.svg" alt="" /></span>
                </div> 
            </div>
        </div>
        <div class="retweet">
            <a class="icon-change swap2" href="#"><img src="'.$plugin_url.'assets/images/retweet-arrows.svg" alt="" /></a>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="flight-book-right">
        <div class="row no-gutters">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="field">
                        <input type="text" class="form-control selector leg_departure_date" placeholder="When?" />
                        <span class="icon-span"><img src="'.$plugin_url.'assets/images/calendar-icon.svg" alt="" /></span>
                    </div> 
                </div> 
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="field">
                        <select class="templatingSelect2 leg_no_of_passangers"> 
                            <option selected="">Passenger </option>
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
            <div class="col-md-3">
                <div class="form-group border-none">
                    <ul class="add-close-btn">
                        <li><a class="btn brn-search addrow_btn" href="#"><img src="'.$plugin_url.'assets/images/pluse-icon.svg" alt="" /> </a></li>
                        <li><a class="btn brn-search removerow_btn" href="#"><img src="'.$plugin_url.'assets/images/close-icon.svg" alt="" /></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>';

?>

<div id="hr_settings" style="display:none">
    <input id="hr_pluginurl" value="<?= $plugin_url ?>" disabled/>
    <input id="isadminpage" value="true" disabled/>
</div>

<!-- Init Selection -->
<div class="search-modal" id="initial_selection">
    <div class=" modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="flight-book-top">
                <div class="table-responsive">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-one-way-tab" data-toggle="pill" href="#pills-one-wayx" role="tab" aria-controls="pills-one-way" aria-selected="true">One Way</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-round-trip-tab" data-toggle="pill" href="#pills-round-tripx" role="tab" aria-controls="pills-round-trip" aria-selected="false">Round Trip</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-multi-leg-tab" data-toggle="pill" href="#pills-multi-legx" role="tab" aria-controls="pills-multi-leg" aria-selected="false">Multi Leg</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-empty-leg-tab" data-toggle="pill" href="#pills-empty-legx" role="tab" aria-controls="pills-empty-leg" aria-selected="false">Empty Legs</a>
                        </li>
                    </ul>
                </div>
                <div class="country-flight">
                    <ul>
                        <li><a href="#"><img src="<?= $plugin_url ?>assets/images/afghanistan-flag.png" alt="" /></a></li>
                        <li><a href="#"><img src="<?= $plugin_url ?>assets/images/albania.png" alt="" /></a></li>
                        <li><a href="#"><img src="<?= $plugin_url ?>assets/images/UAE-flag.png" alt="" /></a></li>
                        <li><a href="#"><img src="<?= $plugin_url ?>assets/images/andorra-flag.png" alt="" /></a></li>
                    </ul>
                    <div class="currency-dropdown">
                        <select class="templatingSelect2"> 
                            <option value="usd">USD</option>
                            <option value="euro">Eur</option>
                            <option value="gbp">Tk</option>
                        </select> 
                    </div>
                </div>
            </div>  
            <div class="tab-content" id="pills-tabContent">

                <!-- Init:: One way Tab-->
                <div class="tab-pane fade show active" id="pills-one-wayx" role="tabpanel" aria-labelledby="pills-one-way-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg row -->
                            <?= $leg_row_normal ?>
                        </form>
                    </div>
                </div> 


                <!-- Init:: Round Trip Tab-->
                <div class="tab-pane fade" id="pills-round-tripx" role="tabpanel" aria-labelledby="pills-round-trip-tab">
                    <div class="flight-book-inner">
                        <form action="#">
                            <!-- Leg row Round Trip-->
                            <?= $leg_row_round_trip ?>
                        </form>
                    </div>
                </div>

                <!-- Init:: Multi Leg Tab-->
                <div class="tab-pane fade" id="pills-multi-legx" role="tabpanel" aria-labelledby="pills-multi-leg-tab">
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
                <div class="tab-pane fade" id="pills-empty-legx" role="tabpanel" aria-labelledby="pills-empty-leg-tab">
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

                <!-- Loader -->
                <div class="search_loader fade show">
                    <img src="<?= $plugin_url ?>assets/images/loader_grey.png" />
                    <p>Please wait while we source the available aircraft</p>
                </div>

                <!-- Results Set -->
                <div class="search-results fade" id="search-results1">
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane1.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner1.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Turbo Prop  <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    5-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c1" aria-expanded="false">
                                                    <strong>7,050 USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c1">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- RESULT 2   -->
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane2.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner2.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Light Jet <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    4-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c2" aria-expanded="false">
                                                    <strong>8100  USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c2">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- RESULT 3   -->
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane1.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner1.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Midsize Jet <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    5-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c3" aria-expanded="false">
                                                    <strong>7,050 USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c3">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- RESULT 4   -->
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane2.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner2.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Heavy Jet  <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    4-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c4" aria-expanded="false">
                                                    <strong>8100  USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c4">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div> 
                    <!-- RESULT 5   -->
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane1.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner1.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Ultra Long
                                                    Range  <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    5-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c5" aria-expanded="false">
                                                    <strong>7,050 USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c5">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- RESULT 6   -->
                    <div class="card-result">
                        <div class="top-result">
                            <div class="row no-gutters">
                                <div class="col-lg-7">
                                    <div class="title-images">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/aeroplane2.png" alt="">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="img-holder">
                                                    <img src="<?= $plugin_url ?>assets/images/inner2.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="detail-card">
                                        <div class="media">
                                            <div class="media-body">
                                                <h3>Airliner  <i data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="<?= $plugin_url ?>assets/images/question-circle.svg" alt=""></i></h3>
                                                <span class="badge-passanger">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-white.svg" alt=""></i>
                                                    4-9
                                                </span>
                                            </div>
                                            <div class="inquiry-info">

                                                <a class="btn cta-primary cta-inquiry" data-toggle="collapse" href="#c6" aria-expanded="false">
                                                    <strong>8100  USD*</strong>
                                                    <span>Inquiry</span>
                                                </a>

                                                <p>*Estimated price before taxes & fees.</p>
                                            </div>
                                        </div>

                                        <div class="detail-card-footer row no-gutters">
                                            <div class="col">
                                                <div class="time">09:00</div>
                                                <div class="loc">SAN</div>
                                            </div>
                                            <div class="img-flight">
                                                <img src="<?= $plugin_url ?>assets/images/flight-icon.svg" alt="" class="img-fluid">
                                                <div class="time-est">1h:50m</div>
                                            </div>
                                            <div class="col">
                                                <div class="time">10:50</div>
                                                <div class="loc">OAK</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="c6">
                                <form action="" class="contact-detail">
                                    <p>Please provide your contact details here.</p>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Name">
                                                    <i><img src="<?= $plugin_url ?>assets/images/user-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="email" class="form-control" placeholder="Email" required>
                                                    <i><img src="<?= $plugin_url ?>assets/images/envelope-grey.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <i><img src="<?= $plugin_url ?>assets/images/phne.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Any special requests or requirements">
                                                    <i><img src="<?= $plugin_url ?>assets/images/cmment.svg" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn cta-primary">Send Inquiry</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- / Modal 2 -->
        </div> 
    </div> <!-- / Modal Dialog -->
</div>