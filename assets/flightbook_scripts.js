(function($) {
//--- jQuery No Conflict

//Global Variables
var ajax_url = '/wp-content/plugins/flight_booking/ajax_php/';
var delayTimer;
var use_ajax = false;

/**
 * 
 * 
 * 
 * 
 * 
 * Execute functions on DOM ready
 * 
 */

$(document).ready(function() {

  var results = [];

  //App Settings
  onload_settings();

  //UI functions
  ui_functions();

  //Functions
  autocomplete_js();  //-- Autocomplete airports (Search)
  search_for_aircrafts(); //-- Search for aircrafts


});




/**
 * 
 * 
 *  App Settings
 */   
function onload_settings(){
  $("input[type='number'].spinner").inputSpinner();

  //Flatpicker Settings
  $(".selector, .selector2").flatpickr({
    //mode: "range",
    minDate: "today",
    dateFormat: "d-m-Y H:i",
    enableTime: true, 
    disableMobile: "true"
  });

  //Tooltip Settings
  $('i').tooltip();
  
  //Currency Set -- To be tested
  function setCurrency(currency) {
    if (!currency.id) {
        return currency.text;
    }
    var $currency = $('<span class="glyphicon glyphicon-' + currency.element.value + '">' + currency.text + '</span>');
    return $currency;
  };

  $(".templatingSelect2").select2({
      placeholder: "What currency do you use?", //placeholder
      templateResult: setCurrency,
      templateSelection: setCurrency
  });


    //Currency values from google finance api
    $.ajax({     
      url: "http://data.fixer.io/api/latest?access_key=1495fc83ad76e1ecfdd2e8773e9af9a2&symbols=GBP,USD",
      method: "GET",
      success: function(data)
      {   
        console.log(data);
        if (typeof data.rates["GBP"] !== 'undefined' && typeof data.rates["USD"] !== 'undefined'){
          let usd_to_eur = 1/data.rates["USD"];
          let usd_to_gbp = usd_to_eur*data.rates["GBP"];
          $('#currency_eur').attr('cur_rate',usd_to_eur);
          $('#currency_gbp').attr('cur_rate',usd_to_gbp);
        }else{
          alert ('currency converter api error!');
        }
      },

      error: function(e)
      {
          console.log('error');
          console.log(e);
      }
    });

    //Close Modal
    $('.modal_close').on('click', function(){
      $('#search_modal').modal('hide');
    });
}






/**
 * 
 * 
 * 
 * 
 * UI Related Functions
 */
function ui_functions(){

  // From and To swapping
  $('.icon-change.swap').off().on('click', function(){
    //Animate icon
    $(this).toggleClass('rotate');

    //Swap values
    let current_row = $(this.parentElement.parentElement);
      //Getting values
      let current_row_from = current_row.find('.leg_from').val();
      let current_row_to = current_row.find('.leg_to').val();
      //Setting Values
      current_row.find('.leg_from').val(current_row_to);
      current_row.find('.leg_to').val(current_row_from);
  });



  //Add new row on multi leg
  $('#pills-multi-legx .addrow_btn').off().on('click',function(){
    $(this).closest('form').append(window.multi_row_html);
    $('.multi_select2').select2();
    ui_functions();
  });

  //Remove row on multi leg
  $('#pills-multi-legx .removerow_btn').off().on('click',function(){
    let noof_multirows = $(this).closest('form').find('.leg_row').length;
    console.log(noof_multirows);
    if ( noof_multirows > 2 ){
      $(this).closest('.leg_row').remove();
    }else{
      $(this).closest('.leg_row').find('input').val('');
      $(this).closest('.leg_row').find('select').val(0);
    }
  });

}





/**
 * 
 * 
 * 
 * 
 * Autocomplete Airports
 */
function autocomplete_js(){

  var airports = window.airports.airportsByCities;

  var options = {
      shouldSort: true,
      threshold: 0.4,
      maxPatternLength: 32,
      keys: [{
        name: 'codeIataAirport',
        weight: 0.2
      }, {
        name: 'nameAirport',
        weight: 0.4
      }, {
        name: 'nameCountry',
        weight: 0.4
      }]
    };
    
  var fuse = new Fuse(airports, options);
  
  // Run separate searches for each autocompletex
  $('.autocompletex').each(function(){
    let ac = $(this)
      .on('click', function(e) {
        e.stopPropagation();
      })
      .on('focus keyup', search)
      .on('keydown', onKeyDown);
      
    var wrap = $('<div>')
      .addClass('autocomplete-wrapper')
      .insertBefore(ac)
      .append(ac);
      
    var list = $('<div>')
      .addClass('autocomplete-results')
      .on('click', '.autocomplete-result', function(e) {
        e.preventDefault();
        e.stopPropagation();
        selectIndex($(this).data('index'));
      })
      .appendTo(wrap);
    
    $(document)
      .on('mouseover', '.autocomplete-result', function(e) {
        var index = parseInt($(this).data('index'), 10);
        if (!isNaN(index)) {
          list.attr('data-highlight', index);
        }
      })
      .on('click', clearResults());
      
    function clearResults() {
      results = [];
      numResults = 0;
      list.empty();
    }
      
    // On option select
    function selectIndex(index) {
      if ( (results.length >= index + 1) && use_ajax ) { //For ajax
        ac.val(results[index].codeIataAirport);
        console.log(results[index].codeIataAirport);
        clearResults();
      }else if ( (results.length >= index + 1) && !use_ajax ) { //For fuse
        ac.val(results[index].item.codeIataAirport);
        ac.attr('selected_icao', results[index].item.codeIcaoAirport);
        ac.attr('selected_gmt', results[index].item["GMT"]);
        clearResults();
      }
    }
      
    results = [];
    var numResults = 0;
    var selectedIndex = -1;
      
    function search(e) {
      if (e.which === 38 || e.which === 13 || e.which === 40) {
        return;
      }
      
      if (ac.val().length > 0) { //----- Min character length to start search
        console.log( 'search_init_at:'+Date.now() );
        //results = fuse.search(ac.val());
        results = ajax_search( ac.val(), results, list, fuse );
    
      } else {
        numResults = 0;
        list.empty();
      }
    }
      
    function onKeyDown(e) {
      switch(e.which) {
        case 38: // up
          selectedIndex--;
          if (selectedIndex <= -1) {
            selectedIndex = -1;
          }
          list.attr('data-highlight', selectedIndex);
          break;
        case 13: // enter
          selectIndex(selectedIndex);
          break;
        case 9: // enter
          selectIndex(selectedIndex);
          e.stopPropagation();
          return;
        case 40: // down
          selectedIndex++;
          if (selectedIndex >= numResults) {
            selectedIndex = numResults-1;
          }
          list.attr('data-highlight', selectedIndex);
          break;
    
        default: return; // exit this handler for other keys
      }
      e.stopPropagation();
      e.preventDefault(); // prevent the default action (scroll / move caret)
    }

  }); 
}

  // AJAX Search for Autocomplete
  //---- executes within autocomplete_js()
  function ajax_search(search_param, results, list, fuse){
    console.log('ajax search...');

    clearTimeout(delayTimer);

    delayTimer = setTimeout(function() {
        // Do the ajax stuff

      if (use_ajax==true){ // Use ajax autocomplete for airports
        $.ajax({     
          url: ajax_url + "fb_autocomplete.php",
          method: "GET",
          data: {
            query: search_param
          },
          success: function(data)
          {   
            //console.log(data);
            console.log('ajax search result:');
            console.log( 'search_recieved_at:'+ Date.now() );
            data = JSON.parse(data);
            console.log(data);

            //$.extend(results, data.response.airports, data.response.airports_by_cities, data.response.airports_by_countries);
            results = [ ...data.response.airports, ...data.response.airports_by_cities, , ...data.response.airports_by_countries ];
            
            console.log(results);
            numResults = results.length;
            if(numResults>100){
              results.length = 100;
              numResults = 100;
            }

            var divs = results.map(function(r, i) {
                console.log(r);
                return '<div class="autocomplete-result" data-index="'+ i +'">'
                    + '<div><b>'+ r.code +'</b> - '+ r.name +'</div>'
                    + '<div class="autocomplete-location">'+ r.city_name +', '+ r.country_name +'</div>'
                    + '</div>';
            });

            selectedIndex = -1;
            list.html(divs.join(''))
              .attr('data-highlight', selectedIndex);

              console.log( 'search_print_at:'+Date.now() );

            return window.results=results;
          },

          error: function(e)
          {
              console.log('error');
              console.log(e);
          }
        });

      }else{ // use airports.js list
        results = fuse.search(search_param);
        //console.log(results);

        numResults = results.length;

        if(numResults>100){
          results.length = 100;
          numResults = 100;
        }

        var divs = results.map(function(r, i) {
          console.log(r);
          return '<div class="autocomplete-result" data-index="'+ i +'">'
              + '<div><b>'+ r.item.codeIataAirport +', <span class="icaocode">'+ r.item.codeIcaoAirport+'</span></b> - '+ r.item.nameAirport +'</div>'
              + '<div class="autocomplete-location">'+ r.item.codeIso2Country +', '+ r.item.nameCountry +'</div>'
              + '</div>';
        });

        selectedIndex = -1;
        list.html(divs.join(''))
          .attr('data-highlight', selectedIndex);

          console.log( 'search_print_at:'+Date.now() );

        window.results=results;
      }
    }, 10); // Will do the ajax stuff after 10 ms
  }







/**
 * 
 * 
 * 
 * 
 *  Search For Aircrafts
 */
function search_for_aircrafts(){
  $('.search_btn').off().on('click',function(){

    $('.search-results').removeClass('show');
    $('.search_loader').removeClass('show');

    let active_tab = $('#initial_selection .tab-content .tab-pane.active').attr('id');
    console.log('active tab: '+active_tab);

    let legs = [];
    $('#'+active_tab+' .leg_row').each(function(index,item){
        let leg = {
            from_iata : $(this).find('.leg_from').val(),
            from_icao : $(this).find('.leg_from').attr('selected_icao'),
            from_gmt : $(this).find('.leg_from').attr('selected_gmt'),
            to_iata : $(this).find('.leg_to').val(),
            to_icao : $(this).find('.leg_to').attr('selected_icao'),
            to_gmt : $(this).find('.leg_to').attr('selected_gmt'),
            departure_date : $(this).find('.leg_departure_date').val(),
            return_date : $(this).find('.leg_return_date').val(),
            pax : $(this).find('.leg_no_of_passengers').val()
        };

        legs.push(leg);
    });
    

    let search_selection = {
      active_tab : active_tab,
      legs :legs,
    };

    console.log(search_selection);

    // Animate page scroll to selection top
    let initselect_scroll_top = $("#initial_selection").offset().top;
    initselect_scroll_top = initselect_scroll_top - 50;

    // Animate search loader
    $('html, body').stop().animate({
      scrollTop: initselect_scroll_top
    }, 300, function(){
      $('#search_modal').modal('show');
      $('.search_loader').addClass('show');
    });

    get_search_results(search_selection);
    
  });
}

  // Get Search Results
  function get_search_results( search_selection ){
    console.log('fetching search results...');

      let initselect_height = $('#initial_selection').height()+55;
      let searchresults_height = $(window).height() - initselect_height - 15;
        if( searchresults_height < 300){
          searchresults_height = $(window).height() - 30;
          initselect_height = 20;
          $('#search_modal').css('z-index',99999);
        }else{
          $('#search_modal').css('z-index',1);
        }
      
      $('#search_modal').css('margin-top',initselect_height+'px');
      $('#search_modal').css('height',searchresults_height+'px');

      let delay_results;

      delay_results = setTimeout(function() {

        if (typeof search_selection.legs[0].from_icao == 'undefined' || search_selection.legs[0].from_icao == '' ||
          typeof search_selection.legs[0].to_icao == 'undefined' || search_selection.legs[0].to_icao == ''){
            //clearTimeout(delay_results);
            $('.search-results').removeClass('show');
            $('#status_update').html("<strong>Insufficient search parameters.</strong><br>  We can't find any result for the current selection. Please make sure that atleast the origin and destination airports are selected.");
            $('.search_loader').addClass('show');
        }else{
            console.log('Required fields are found');
            $('#status_update').html("");
            ajax_search_results(search_selection);
        }
      
        return true;

      }, 1000);
  }

    //AJAX Search Results
    function ajax_search_results(search_selection){
      $.ajax({     
        url: ajax_url + "fetch_flight_info.php",
        method: "POST",
        data: search_selection,
        success: function(data)
        {   
          //console.log(data);
          $('#search-results1').html('');

          data = JSON.parse(data);
          console.log(data);

          if (typeof data.totals === 'undefined'){
            $('#status_update').html("Results not found for current selection");
          }else{
            $('.search_loader').removeClass('show');
             
            //Value Settings & Totals
            let distance = parseFloat(data.totals.distance_nm);
            let total_legs = search_selection.legs.length;
            let total_time = 0;
            let total_cost = 0;
            let origin_airpot = 'N/A';
            let destination_airpot = 'N/A';
            let origin_time = 'N/A';
            let destination_time = 'N/A';
            let selected_currency = $('#currency_selector').val();
            console.log('selected_curency:'+selected_currency);
            let exchange_rate = parseFloat ( $('#currency_'+selected_currency).attr('cur_rate') );
            if (!exchange_rate || exchange_rate<0 ){
              exchange_rate = 1;
            }

            //Derives
            origin_airpot = search_selection.legs[0].from_iata;
            let destination_airport_index = total_legs-1;
            destination_airpot = search_selection.legs[destination_airport_index].to_iata;


            //UTC Timing...
            let departure_dateandtime = new Date(search_selection.legs[0].departure_date);
            let departure_dateandtime_utc = departure_dateandtime.getTime();
            if ( isNaN(departure_dateandtime_utc) ){
              departure_dateandtime = new Date();
              departure_dateandtime.setHours(0,0,0,0);
              departure_dateandtime_utc = departure_dateandtime.getTime();
            }

            console.log(departure_dateandtime_utc);
            console.log(departure_dateandtime);

            let origin_gmt = parseFloat(search_selection.legs[0].from_gmt);
            let origin_gmt_utc = 
                Math.trunc(origin_gmt)*60*60*1000 // Hours in UTC
                + ( origin_gmt - Math.trunc(origin_gmt) )*60*1000; //Minutes in UTC
            
            let destination_gmt = parseFloat( search_selection.legs[destination_airport_index].to_gmt );
            let destination_gmt_utc = 
                Math.trunc(destination_gmt)*60*60*1000 // Hours in UTC
                + ( destination_gmt - Math.trunc(destination_gmt) )*60*1000; //Minutes in UTC

            
            let destination_dateandtime_utc = departure_dateandtime_utc + origin_gmt_utc + destination_gmt_utc;
            

            //Populate results for each aircraft
            $(aircrafts).each(function(index, item){

               //Aircraft Images
               flight_card_html = flight_card_html.replace('{{aircraft_image}}',item.ac_exterior_img);
               flight_card_html = flight_card_html.replace('{{aircraft_inner_image}}',item.ac_interior_img);
               //Type
               flight_card_html = flight_card_html.replace('{{aircraft_type}}',item.ac_name);
               //Pax
               flight_card_html = flight_card_html.replace('{{pax_capacity}}',item.ac_pax_min+'-'+item.ac_pax_max);
               //Description
               flight_card_html = flight_card_html.replace('{{aircraft_desc}}',item.ac_desc);
               //Contact Form Link & ID
               flight_card_html = flight_card_html.replace('{{contact_form_link}}','ac_contact_form'+item.id);
               flight_card_html = flight_card_html.replace('{{contact_form_id}}','ac_contact_form'+item.id);




              //Calculations
              let ac_range = parseFloat(item.ac_range);
              let ac_speed = parseFloat(item.ac_speed);
              let stops_needed = distance/ac_range;
                  stops_needed = Math.ceil(stops_needed);
                  if(stops_needed <= 0){
                    stops_needed = 1;
                  }
              let delay_at_each_stop = stops_needed * item.ac_ground_mins;
              let total_duration = (distance/ac_speed)*60+(delay_at_each_stop); // In minutes
                let total_duration_hours = Math.floor(total_duration / 60);          
                let total_duration_minutes = total_duration % 60;
                    total_duration_minutes = Math.ceil(total_duration_minutes);

              destination_dateandtime_utc = destination_dateandtime_utc + (total_duration*60*1000);
              let destination_dateandtime = new Date(destination_dateandtime_utc);
              let destination_time_hr = destination_dateandtime.getHours();
              let destination_time_min = destination_dateandtime.getMinutes();

              //Price
              let price = ( item.ac_per_hr_fee*(distance/ac_speed) )  + ( item.ac_per_landing_fee * stops_needed );
                  price = price + price * ( item.ac_additions/100 ); // with adjustable commissions
                  price = price * exchange_rate; // Currency changed
                  price = Math.ceil(price);

              let flight_card_html = window.flight_info_card;

              //Price
              flight_card_html = flight_card_html.replace( '{{price}}', price.toLocaleString() );



              /**
               * Per leg calculations
               */

              let legs_html = '';
              if (search_selection.active_tab=='pills-one-way'){
                let legs_timing_html =  window.legs_time_template;
                //Airport info
                let origin_time_hr = departure_dateandtime.getHours();
                if (origin_time_hr<10){origin_time_hr=''+0+origin_time_hr};
              
                let origin_time_min = departure_dateandtime.getMinutes();
                if (origin_time_min<10){origin_time_min=''+0+origin_time_min};
              
                legs_timing_html = legs_timing_html.replace('{{departure_time}}',origin_time_hr+":"+origin_time_min);
                legs_timing_html = legs_timing_html.replace('{{origin_iata}}',origin_airpot);
              
                if (destination_time_min<10){destination_time_min=''+0+destination_time_min};
                if (destination_time_hr<10){destination_time_hr=''+0+destination_time_hr};

                legs_timing_html = legs_timing_html.replace('{{arrival_time}}',destination_time_hr+":"+destination_time_min);
                legs_timing_html = legs_timing_html.replace('{{destination_iata}}',destination_airpot);
              
                //Total Flight Time
                if(total_duration_hours<10){total_duration_hours=''+0+total_duration_hours};
                if(total_duration_minutes<10){total_duration_minutes=''+0+total_duration_minutes};

                legs_timing_html = 
                legs_timing_html.replace('{{total_duration}}',total_duration_hours+':'+total_duration_minutes);

                legs_html = legs_timing_html;
              }



              //Final HTML append to placeholder
              flight_card_html = flight_card_html.replace('{{legs_time_placeholder}}', legs_html);
              $('#search-results1').append(flight_card_html);
            });

            $('.search-results').addClass('show');
            //Refresh
            $('i').tooltip(); 
          }
        },

        error: function(e)
        {
            console.log('error');
            console.log(e);
        }
      });
    }


//--- jQuery No Conflict
})(jQuery);