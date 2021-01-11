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

    $plugin_url = $site_host."/wp-content/plugins/headphone_ranker/";
?>

<div id="hr_settings" style="display:none">
    <input id="hr_pluginurl" value="<?= $plugin_url ?>" disabled/>
    <input id="isadminpage" value="true" disabled/>
</div>

<div class="container">
    <div class="flight-book-top">
        <div class="table-responsive">
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
        <div class="country-flight">
            <ul>
                <li><a href="#"><img src="images/afghanistan-flag.png" alt="" /></a></li>
                <li><a href="#"><img src="images/albania.png" alt="" /></a></li>
                <li><a href="#"><img src="images/UAE-flag.png" alt="" /></a></li>
                <li><a href="#"><img src="images/andorra-flag.png" alt="" /></a></li>
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
        <div class="tab-pane fade show active" id="pills-one-way" role="tabpanel" aria-labelledby="pills-one-way-tab">
            <div class="flight-book-inner">
                <form action="flight-list.html">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <div class="row no-gutters complete-trip">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete one" placeholder="From" value="From" />
                                            <span class="icon-span"><img class="image1" src="images/takeoff-the-plane.svg" alt="" /><img class="image2" src="images/takeoff-the-plane.svg" alt="" /></span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete two" placeholder="Where to?" value="Where to?" />
                                            <span class="icon-span"><img class="image1" src="images/plane-landing.svg" alt="" /><img class="image2" src="images/plane-landing.svg" alt="" /></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="retweet">
                                    <a class="icon-change swap" href="#"><img src="images/retweet-arrows.svg" alt="" /></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="flight-book-right">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector" placeholder="When?" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="field">
                                                <select class="templatingSelect2"> 
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
                                                <span class="icon-span"><img src="images/passenger-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group border-none">
                                            <button class="btn brn-search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> 
        <div class="tab-pane fade" id="pills-round-trip" role="tabpanel" aria-labelledby="pills-round-trip-tab">
            <div class="flight-book-inner">
                <form action="flight-list.html">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <div class="row no-gutters complete-trip">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete one" placeholder="From" value="From" />
                                            <span class="icon-span"><img class="image1" src="images/takeoff-the-plane.svg" alt="" /><img class="image2" src="images/takeoff-the-plane.svg" alt="" /></span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete two" placeholder="Where to?" value="Where to?" />
                                            <span class="icon-span"><img class="image1" src="images/plane-landing.svg" alt="" /><img class="image2" src="images/plane-landing.svg" alt="" /></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="retweet">
                                    <a class="icon-change swap" href="#"><img src="images/retweet-arrows.svg" alt="" /></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="flight-book-right">
                                <div class="row no-gutters">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector2" placeholder="Departure date" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector2" placeholder="Return date" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="field">
                                                <select class="templatingSelect2"> 
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
                                                <span class="icon-span"><img src="images/passenger-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group border-none">
                                            <button class="btn brn-search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-multi-leg" role="tabpanel" aria-labelledby="pills-multi-leg-tab">
            <div class="flight-book-inner">
                <form action="flight-list.html">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <div class="row no-gutters complete-trip">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete one" placeholder="From" value="From" />
                                            <span class="icon-span"><img class="image1" src="images/takeoff-the-plane.svg" alt="" /><img class="image2" src="images/takeoff-the-plane.svg" alt="" /></span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete two" placeholder="Where to?" value="Where to?" />
                                            <span class="icon-span"><img class="image1" src="images/plane-landing.svg" alt="" /><img class="image2" src="images/plane-landing.svg" alt="" /></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="retweet">
                                    <a class="icon-change swap" href="#"><img src="images/retweet-arrows.svg" alt="" /></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="flight-book-right">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector" placeholder="When?" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="field">
                                                <select class="templatingSelect2"> 
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
                                                <span class="icon-span"><img src="images/passenger-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group border-none">
                                            <button class="btn brn-search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <div class="row no-gutters complete-trip">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete one2" placeholder="From" value="From" />
                                            <span class="icon-span"><img class="image1" src="images/takeoff-the-plane.svg" alt="" /><img class="image2" src="images/takeoff-the-plane.svg" alt="" /></span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete two2" placeholder="Where to?" value="Where to?" />
                                            <span class="icon-span"><img class="image1" src="images/plane-landing.svg" alt="" /><img class="image2" src="images/plane-landing.svg" alt="" /></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="retweet">
                                    <a class="icon-change swap2" href="#"><img src="images/retweet-arrows.svg" alt="" /></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="flight-book-right">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector" placeholder="When?" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="field">
                                                <select class="templatingSelect2"> 
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
                                                <span class="icon-span"><img src="images/passenger-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group border-none">
                                            <ul class="add-close-btn">
                                                <li><a class="btn brn-search" href="#"><img src="images/pluse-icon.svg" alt="" /> </a></li>
                                                <li><a class="btn brn-search" href="#"><img src="images/close-icon.svg" alt="" /></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-empty-leg" role="tabpanel" aria-labelledby="pills-empty-leg-tab">
            <div class="flight-book-inner">
                <form action="flight-list.html">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <div class="row no-gutters complete-trip">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete one" placeholder="From" value="From" />
                                            <span class="icon-span"><img class="image1" src="images/takeoff-the-plane.svg" alt="" /><img class="image2" src="images/takeoff-the-plane.svg" alt="" /></span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="field">
                                            <input type="text" class="form-control autocomplete two" placeholder="Where to?" value="Where to?" />
                                            <span class="icon-span"><img class="image1" src="images/plane-landing.svg" alt="" /><img class="image2" src="images/plane-landing.svg" alt="" /></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="retweet">
                                    <a class="icon-change swap" href="#"><img src="images/retweet-arrows.svg" alt="" /></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="flight-book-right">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="field">
                                                <input type="text" class="form-control selector" placeholder="When?" />
                                                <span class="icon-span"><img src="images/calendar-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="field">
                                                <select class="templatingSelect2"> 
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
                                                <span class="icon-span"><img src="images/passenger-icon.svg" alt="" /></span>
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group border-none">
                                            <button class="btn brn-search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
