(function($) {
//--- jQuery No Conflict

//Global Variables
var ajax_url = '/wp-content/plugins/flight_booking/ajax_php/';
var delayTimer;
var use_ajax = false;
var selected_language = 'english';

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
  console.log('Flighbook Shortcode JavaScripts Loaded');
  //console.log(window.ac_lang);
  //console.log(window.airports);

  var results = [];

  //App Settings
  onload_settings();

  //UI functions
  ui_functions();
  language_select(); // Load default language text

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
  
  //Currency Set -- To be tested
  function setCurrency(currency) {
    if (!currency.id) {
        return currency.text;
    }
    var $currency = $('<span class="glyphicon glyphicon-' + currency.element.value + '">' + currency.text + '</span>');
    return $currency;
  };

  $(".templatingSelect2").select2({
      //placeholder: "What currency do you use?", //placeholder
      //templateResult: setCurrency,
      //templateSelection: setCurrency
      templateResult: resultState
  });

  function resultState(data, container) {
    if(data.element) {
        $(container).addClass( $(data.element).attr("class") );
    }
    return data.text;
  }


    //Currency values from google finance api
    $.ajax({     
      url: ajax_url+"get_prices.php",
      method: "GET",
      success: function(data)
      {   
        data = JSON.parse(data);
        console.log('Loading currency conversions from api...');
        console.log(data);
        if (typeof data.rates["GBP"] !== 'undefined' && typeof data.rates["USD"] !== 'undefined'){
          
          for ( var i in data.rates){
            let key = i;
            i = i.toLowerCase();
            if (i == 'eur' || i=="gbp" || i=="usd"){
              $('#currency_'+i).attr('cur_rate', data.rates[key] );
              $('#currency_'+i).val( i );
            }else{
              let cur_option = '<option cur_rate="'+data.rates[key]+'" value="'+i+'">'+i.toUpperCase()+'</option>';
              $('#currency_selector').append(cur_option);
            }
          }

          set_currency_change();

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

  // On currency change
  function set_currency_change(){

    $('#currency_selector').off().on('change', function(){
      console.log('currency changed');
      let n = $('.total_price').length;
      let rate = parseFloat ( $('#currency_selector option:selected').attr('cur_rate') );

      if (n>0){

        $('.total_price').each(function(){

          let total = parseInt( $(this).attr('price_in_eur') );
          let new_total = Math.ceil(total*rate);
          console.log('total:'+total);
          console.log('new total:'+new_total);
          $(this).html( new_total.toLocaleString() );
        });
      }      
    });

    $("#currency_selector").select2("destroy");
    $("#currency_selector").select2();    
  }



/**
 * 
 * 
 * 
 * 
 * UI Related Functions
 */
function ui_functions(){

  //Format date on departure data
  $('.leg_departure_date').on('change', function(){
    let formatted_date = moment( $(this).val() ).format('MM/DD/YYYY HH:mm');
    $(this).parent().find('.leg_departure_dateformat').html( formatted_date );
  });
  // Return date
  $('.leg_return_date').on('change', function(){
    let formatted_date = moment( $(this).val() ).format('MM/DD/YYYY HH:mm');
    $(this).parent().find('.leg_departure_dateformat').html( formatted_date );
  });

  // From and To swapping
  $('.icon-change.swap').off().on('click', function(){
    //Animate icon
    $(this).toggleClass('rotate');

    //Swap values
    let current_row = $(this.parentElement.parentElement);
      //Getting values
      let current_row_from_iata = current_row.find('.leg_from').val();
      let current_row_from_icao = current_row.find('.leg_from').attr('selected_icao');
      let current_row_from_gmt = current_row.find('.leg_from').attr('selected_gmt');
      let current_row_from_rowid = current_row.find('.leg_from').attr('selected_row_id');

      let current_row_to_iata = current_row.find('.leg_to').val();
      let current_row_to_icao = current_row.find('.leg_to').attr('selected_icao');
      let current_row_to_gmt = current_row.find('.leg_to').attr('selected_gmt');
      let current_row_to_rowid = current_row.find('.leg_to').attr('selected_row_id');
      //Setting Values
      current_row.find('.leg_from').val(current_row_to_iata);
      current_row.find('.leg_from').attr('selected_icao',current_row_to_icao);
      current_row.find('.leg_from').attr('selected_gmt',current_row_to_gmt);
      current_row.find('.leg_from').attr('selected_row_id',current_row_to_rowid);
      
      current_row.find('.leg_to').val(current_row_from_iata);
      current_row.find('.leg_to').attr('selected_icao',current_row_from_icao);
      current_row.find('.leg_to').attr('selected_gmt',current_row_from_gmt);
      current_row.find('.leg_to').attr('selected_row_id',current_row_from_rowid);
  });

  //Tooltip Settings
  $('i').tooltip();

  //Flatpicker Settings
  $(".selector, .selector2").flatpickr({
    //mode: "range",
    minDate: "today",
    dateFormat: "Y-m-d H:i",
    minuteIncrement: 10,
    enableTime: true, 
    disableMobile: "true",
    time_24hr: true,
  });

  $('.multi_select2').select2({
    templateResult: resultState
  });
  function resultState(data, container) {
    if(data.element) {
        $(container).addClass( $(data.element).attr("class") );
    }
    return data.text;
  }

  //Add new row on multi leg
  $('#pills-multi-leg .addrow_btn').off().on('click',function(){
    $(this).closest('form').find('.leg_row_multi').last().after(window.multi_row_html);
    ui_functions();
    autocomplete_js();

  });

  //Remove row on multi leg
  $('#pills-multi-leg .removerow_btn').off().on('click',function(){
    let noof_multirows = $(this).closest('form').find('.leg_row_multi').length;
    //console.log(noof_multirows);
    if ( noof_multirows > 1 ){
      $(this).closest('.leg_row_multi').remove();
    }else{
      $(this).closest('.leg_row_multi').find('input').val('');
      $(this).closest('.leg_row_multi').find('select').val(0);
    }
  });


  //Language Select
  $('#language_select li a').on('click', function(){
    let clicked_lang = $(this).attr('ac_lang');
    language_select(clicked_lang);
  });

}



/**
 * 
 * 
 * Language Select
 */

 function language_select(lang){
   console.log('Language Select')

  if (lang != '' && typeof lang !== 'undefined'){
    selected_language = lang;
  }else if( selected_language == ''){
    selected_language = 'english'
  }
  let lg = window.ac_lang[selected_language];

  //Setting Language

  //Pill Titles
  $('#pills-one-way-tab').html(lg.pill_title_oneway);
  $('#pills-round-trip-tab').html(lg.pill_title_roundtrip);
  $('#pills-multi-leg-tab').html(lg.pill_title_multileg);
  $('#pills-empty-leg-tab').html(lg.pill_title_emptyleg);

  //Search Section
  $('.leg_from').attr('placeholder',lg.search_field_from);
  $('.leg_to').attr('placeholder',lg.search_field_whereto);
  $('.leg_departure_date').attr('placeholder',lg.search_field_departuredate);
  $('.leg_return_date').attr('placeholder',lg.search_field_returndate);
  $('.default_passenger').html(lg.search_field_passenger);
  $('.search_btn').html(lg.search_button_search);

  //Resutls Card
  //--Airplanes
  $('#ac_0 .ac_category_name').html(lg.results_card_turboprop);
  $('#ac_1 .ac_category_name').html(lg.results_card_lightjets);
  $('#ac_2 .ac_category_name').html(lg.results_card_midsize);
  $('#ac_3 .ac_category_name').html(lg.results_card_supermid);
  $('#ac_4 .ac_category_name').html(lg.results_card_heavyprivate);
  $('#ac_5 .ac_category_name').html(lg.results_card_ultralong);
  $('#ac_6 .ac_category_name').html(lg.results_card_vipairliners);

  //--Inquiry
  $('.ac_lang_inquiry').html(lg.results_card_inquiry);
  $('.ac_lang_pricefooter').html(lg.results_card_pricefooter);

  //--Contact Form

  $('.contact_form_title').html(lg.contact_form_title);
  $('.contact_name').attr('placeholder',lg.contact_form_name);
  $('.contact_email').attr('placeholder',lg.contact_form_email);
  $('.contact_phone').attr('placeholder',lg.contact_form_phone);
  $('.contact_requirements').attr('placeholder',lg.contact_form_requirements);
  $('.send_inquiry').html(lg.contact_form_button_send);

  //Messages
  $('.searching_notice').html(lg.searching_notice);
  $('#status_update').attr('default_note',lg.search_error_note);

 }




/**
 * 
 * 
 * 
 * 
 * Autocomplete Airports
 */
function autocomplete_js(){

  var airports = window.airports_new;
  console.log(airports);

  var options = {
      shouldSort: true,
      threshold: 0.4,
      maxPatternLength: 20,
      keys: [
        {
          name: 'airport_iata',
          weight: 0.2
        },
        {
          name: 'airport_icao',
          weight: 0.1
        }, 
        {
          name: 'airport_name',
          weight: 0.3
        },
        {
          name: 'airport_city',
          weight: 0.2
        }, 
        {
          name: 'country_name',
          weight: 0.2
        }
      ]
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
    

    $(document).on('DOMSubtreeModified', ac, function(){
     
      $(this).find('.autocomplete-result').off().on('mouseover', function(e) {
        //console.log(this);
        $(this).closest('.autocomplete-results').find('.autocomplete-result').removeClass('autocomplete-result-hovered');
        $(this).addClass('autocomplete-result-hovered');
  
        var index = parseInt( $(this).data('index'), 10);
        
        if (!isNaN(index)) {
          list.attr('data-highlight', index);
        }
      });

      $(document).on('click', 'body', function(){
        clearResults();
      });

    });

    
      
    function clearResults() {
      results = [];
      numResults = 0;
      list.empty();
    }
      
    // On option select
    function selectIndex(index, dontClearResults) {
      if ( (results.length >= index + 1) && use_ajax ) { //For ajax
        ac.val(results[index].codeIataAirport);
        //console.log(results[index].codeIataAirport);
        clearResults();
      }else if ( (results.length >= index + 1) && !use_ajax ) { //For fuse
        ac.val(results[index].item.airport_iata+'('+results[index].item.airport_icao+')');
        ac.attr('selected_icao', results[index].item.airport_icao);
        ac.attr('selected_gmt', results[index].item.gmt);
        ac.attr('selected_row_id', results[index].item.id);
        
      }

      if (typeof dontClearResults === 'undefined' || dontClearResults==false){
          clearResults();
      }

    }
      
    results = [];
    var numResults = 0;
    var selectedIndex = -1;
      
    function search(e) {
      
      if (e.which === 38 || e.which === 13 || e.which === 40 || e.which === 9 || e.which==27) {
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

      $('.autocomplete-result').removeClass('autocomplete-result-hovered');

      selectedIndex = parseInt( list.attr('data-highlight') );

      if (e.which==27){
        clearResults();
        return;
      }

      if (selectedIndex<0){
        selectedIndex = 0;
      }
      numResults = list.find('.autocomplete-result').length;
      if (numResults<0){
        numResults = 0;
        list.attr('data-highlight', selectedIndex);
      }

      console.log( list.attr('data-highlight') );
      switch(e.which) {
        case 38: // up
        console.log("up");
          selectedIndex--;
          if (selectedIndex <= -1) {
            selectedIndex = 0;
          }
          list.attr('data-highlight', selectedIndex);
          selectIndex(selectedIndex, true);
          break;
        case 13: // enter
        console.log("enter");
          selectIndex(selectedIndex);
          break;
        case 9: // enter or tab
        console.log("enter or tab");
          //selectIndex(selectedIndex);
          selectedIndex=0;
          list.attr('data-highlight', selectedIndex);
          selectIndex(selectedIndex);
          //e.stopPropagation();
          //e.preventDefault();
          return;
        case 40: // down
        console.log("down");
          selectedIndex++;
          console.log(numResults);
          if (selectedIndex >= numResults) {
            selectedIndex = numResults-1;
          }
          
          if(selectedIndex<0){
            selectedIndex=0;
          }
          list.attr('data-highlight', selectedIndex);
          selectIndex(selectedIndex, true);
          break;
    
        default: return; // exit this handler for other keys
      }
      console.log(selectedIndex);
      list.find('.autocomplete-result').eq(selectIndex).addClass('autocomplete-result-hovered');
      e.stopPropagation();
      //e.preventDefault(); // prevent the default action (scroll / move caret)
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
          if ( 
            (typeof r.item.latitude !== 'undefined' && r.item.latitude != '') ||
            (typeof r.item.longitude !== 'undefined' && r.item.longitude != '') 
          ){ // show only results with latitudes and longitudes
            return '<div class="autocomplete-result" data-index="'+ i +'" >'
              + '<div><b>'+ r.item.airport_name +'</b></div>'
              + '<div><span class="iatacode">'+ r.item.airport_iata +'</span>, <span class="icaocode">'+ r.item.airport_icao+'</span></div>'
              + '<div><b>'+r.item.airport_city+'</b></div>'
              + '<div class="autocomplete-location">'+ r.item.country_name +' ('+r.item.country_code+')'+'</div>'
              + '</div>';
            } 
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
    $('#status_update').html("");
    //$('#search_modal').modal('hide');





    //Modal Placement and styling

      let initselect_scroll_top = $("#initial_selection").offset().top -20;
      let initselect_height = $('#initial_selection').height()+25; // Init select height + 50px margin + 5px Margin
      let searchresults_height = $(window).height() - initselect_height - 10; //Remaining height to show results + 15px margin bottom

      // If mobile
      let is_mobile = false;
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
          is_mobile = true;
          console.log('is mobile: Yes!');
       }else{
          console.log('is mobile: No!');
       }



      if( searchresults_height < 400 || is_mobile || $(window).width() < 576 ){ // If search results height too small or if mobile

        console.log('is mobile:'+is_mobile+" Extended height Mode");
        
        searchresults_height = $(window).height() - 25;
        initselect_height = 20;
        $('#search_modal').css('z-index',99999);
        $('.search_loader').css('margin-top', '50px');
        searchresults_height += 50;

        initselect_scroll_top = $("#initial_selection").offset().top-20; //Keep only 15px margin

      }else{
        console.log('is mobile:'+is_mobile+" General Height Mode");
        $('#search_modal').css('z-index',1);
        $('.search_loader').css('margin-top', 'inherit');
      }


      $('#search_modal').css('margin-top',initselect_height+'px');
      $('#search_modal').css('height',searchresults_height+'px');






    if ( validate_search() ){ // Check for validation
      $('.search-results').removeClass('show');
      $('.search_loader').removeClass('show');
  
      let active_tab = $('#initial_selection .tab-content .tab-pane.active').attr('id');
      console.log('active tab: '+active_tab);
  
      let legs = [];

      let i = 0;
      $('#'+active_tab+' .leg_row').each(function(index,item){

        let departure_date = $(this).find('.leg_departure_date').val();
            departure_date = moment(departure_date).format("YYYY/MM/DD HH:mm");

        let return_date = $(this).find('.leg_return_date').val();
        if ( typeof return_date !== 'undefined' && return_date!= ''){
          return_date = moment(return_date).format("YYYY/MM/DD HH:mm");
        }
        
        let leg = {
          from_iata : $(this).find('.leg_from').val(),
          from_icao : $(this).find('.leg_from').attr('selected_icao'),
          from_id : $(this).find('.leg_from').attr('selected_row_id'),
          from_gmt : $(this).find('.leg_from').attr('selected_gmt'),
          to_iata : $(this).find('.leg_to').val(),
          to_icao : $(this).find('.leg_to').attr('selected_icao'),
          to_id : $(this).find('.leg_to').attr('selected_row_id'),
          to_gmt : $(this).find('.leg_to').attr('selected_gmt'),
          departure_date : departure_date,
          return_date : return_date,
          pax : $(this).find('.leg_no_of_passengers').val()
        };

        //Additional leg row if injected before for multi leg select
        /*
        if (active_tab=="pills-multi-leg" && i>0){
          let previous_index = i - 1;
          let intermediate_leg = {
            from_iata : legs[previous_index].to_iata,
            from_icao : legs[previous_index].to_icao,
            from_id : legs[previous_index].from_id,
            from_gmt : legs[previous_index].to_gmt,
            to_iata : $(this).find('.leg_from').val(),
            to_icao : $(this).find('.leg_from').attr('selected_icao'),
            to_id : $(this).find('.leg_from').attr('selected_row_id'),
            to_gmt : $(this).find('.leg_from').attr('selected_gmt'),
            departure_date : legs[previous_index].return_date,
            return_date : return_date,
            pax : legs[previous_index].pax
          };
          legs[i]=intermediate_leg;
          legs[i+1]=leg;
          i = i+2;
        }else{
          legs[i]=leg;
          i = i+1;
        }
        */
        legs[i]=leg;
        i = i+1;
      });
      
  
      let search_selection = {
        active_tab : active_tab,
        legs :legs,
      };
  
      console.log(search_selection);
  


      //Scro Page top
      $('html, body').stop().animate({
        scrollTop: initselect_scroll_top
      }, 300, function(){
        $('#search_modal').modal('show');
        $('.search_loader').addClass('show');
      });
  
      get_search_results(search_selection);
        
    }else{

      $('.search-results').removeClass('show');
      let default_note = $('#status_update').attr('default_note');
      $('#status_update').html("<strong>Sorry :(</strong><br>"+default_note);
      $('.search_loader').addClass('show');
      // Animate page scroll to selection top



      //Scro Page top
      $('html, body').stop().animate({
        scrollTop: initselect_scroll_top
      }, 300, function(){
        $('#search_modal').modal('show');
      });
    }

  });
}

 // Validate Search Parameters before search
 function validate_search(){
   console.log('validating...');
   let active_tab = $('#initial_selection .tab-content .tab-pane.active').attr('id');
   let validation = true;
  
   $('#'+active_tab+' input').each(function(index, item){
     let closest_field = $(this).closest('.field');
     if ( $(this).val() == '' || typeof $(this).val() === 'undefined' ){
        closest_field.addClass('redfont');
        validation = false;
     }else{
        closest_field.removeClass('redfont');
     }
   });

   $('#'+active_tab+' select').each(function(index, item){
    if ( $(this).val()<=0 || $(this).val() == '' || typeof $(this).val() === 'undefined' ){
       $(this).closest('.field').addClass('redfont');
       validation = false;
    }else{
      $(this).closest('.field').removeClass('redfont');
    }
  });

   console.log('validation: '+validation);
   return validation;
 }



  // Get Search Results
  function get_search_results( search_selection ){
    console.log('fetching search results...');

      let delay_results;

      delay_results = setTimeout(function() {

        if (typeof search_selection.legs[0].from_icao == 'undefined' || search_selection.legs[0].from_icao == '' ||
          typeof search_selection.legs[0].to_icao == 'undefined' || search_selection.legs[0].to_icao == ''){
            //clearTimeout(delay_results);
            $('.search-results').removeClass('show');
            $('#status_update').html("<strong>Sorry :(</strong><br>  We can't find any result for the current selection. Please make sure that required data is entered.");
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

          try {
            data = JSON.parse(data);
          }
          catch(err) {
            $('#status_update').html("Results not found for current selection");
          }
          
          console.log(data);

          if (typeof data.totals === 'undefined'){
            $('#status_update').html("Results not found for current selection");
          }else{
            $('.search_loader').removeClass('show');

            //Sorting section
            $('#search-results1').append(window.category_sorting_html);
            check_sorting();
            $('.order_category').select2();

            //Populate results for each aircraft
            $(aircrafts).each(function(index, item){
              let flight_card_html = window.flight_info_card;

              //Aircraft Category ID
              flight_card_html = flight_card_html.replace('{{ac_id}}',index);
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


               /**
                * 
                * Each leg specific calculations
                * 
                */
               let legs_html = '';

               //One way
              if (search_selection.active_tab=='pills-one-way' || search_selection.active_tab=='pills-empty-leg' ){
                  let leg_index = 0;
                  legs_html = get_leg_html(search_selection.legs, item, data, leg_index);
              
              //Round Trip
              }else if (search_selection.active_tab=='pills-round-trip'){
                
                let leg_index = 0;
                // Initial Leg
                legs_html = get_leg_html(search_selection.legs, item, data, leg_index);
                //Return Leg
                let init_from_iata = search_selection.legs[0].from_iata;
                let init_from_gmt = search_selection.legs[0].from_gmt;
                let init_to_iata = search_selection.legs[0].to_iata;
                let init_to_gmt = search_selection.legs[0].to_gmt;
                //Switching Values
                search_selection.legs[0]["from_iata"] = init_to_iata;
                search_selection.legs[0]["from_gmt"] = init_to_gmt;
                search_selection.legs[0]["to_iata"] = init_from_iata;
                search_selection.legs[0]["to_gmt"] = init_from_gmt;
                search_selection.legs[0]["departure_date"] = search_selection.legs[0].return_date;


                legs_html += get_leg_html(search_selection.legs, item, data, leg_index);
                
              //Multi Leg
              }else if (search_selection.active_tab=='pills-multi-leg'){
                
                let no_of_legs = data.legs.length;

                let i = 0;
                $('#pills-multi-leg .leg_row').each(function(){
                  legs_html += get_leg_html(search_selection.legs, item, data, i);
                  i++;
                });
                /*
                //leg rows in input tab (pill)
                let input_rows = [];
                $('#pills-multi-leg .leg_row').each(function(){
                  let from_icao = $(this).find('.leg_from').attr('selected_icao');
                  let to_icao = $(this).find('.leg_to').attr('selected_icao');
                  input_rows.push(from_icao+to_icao);
                });

                console.log("input_rows");
                console.log(input_rows);

                let no_of_legs = data.legs.length;

                for (i=0; i<no_of_legs; i++){
                  let from_to_icao = search_selection.legs[i].from_icao+search_selection.legs[i].to_icao;
                  console.log("from to icao:"+from_to_icao);
                  console.log('found in array at'+input_rows.indexOf(from_to_icao));

                  if( input_rows.indexOf(from_to_icao)>=0 ){
                    legs_html += get_leg_html(search_selection.legs, item, data, i);
                  }else{
                    continue;
                  }
                }
                */
              
              }



              //Final HTML append to placeholder
              let timestamp =  Date.now();
              flight_card_html = flight_card_html.replace('{{legs_time_placeholder}}', legs_html);
              flight_card_html = flight_card_html.replace(/__carousel_id__/g, timestamp);
              $('#search-results1').append(flight_card_html);


              //PRICING
              let price = 0; // In USD
                $('#ac_'+index+' .leg_card').each(function(index,item){
                  let leg_price = $(this).attr('leg_total_price');
                  leg_price = parseInt(leg_price);
                  price += leg_price;
                });

                let usd_exchange_rate = parseFloat( $('#currency_usd').attr('cur_rate') );
                let price_in_eur = price * (1/usd_exchange_rate);
                $('#ac_'+index+' .total_price').attr( 'price_in_eur', price_in_eur );
                //Selected Currency
                let selected_exchange_rate = parseFloat ( $('#currency_selector option:selected').attr('cur_rate') );;

                let price_in_selected_currency = (price_in_eur * selected_exchange_rate);
                    price_in_selected_currency = Math.ceil(price_in_selected_currency);
                $('#ac_'+index+' .total_price').html( price_in_selected_currency.toLocaleString() );

                
                console.log('price:'+price);
                console.log('usd exchange rate:'+usd_exchange_rate);
                console.log('price in eur:'+price_in_eur);
                console.log('selected exchange rate:'+selected_exchange_rate);
                console.log('price_in_selected_currency'+price_in_selected_currency);
               /* */
            });

            //Carousel stuff
            
            $('#search-results1 .carousel-inner').each(function(){  

              if (  $(this).children('.carousel-item').length == 1 ){
                $(this).parent().find('.carousel-control-prev').remove();
                $(this).parent().find('.carousel-control-next').remove();
              }else{
                $(this).parent().find('.carousel-control-prev').hide();
              }

              $(this).children('.carousel-item').eq(0).addClass('active');


              $(this).parent().on('slide.bs.carousel', function (e) {
                var slidingItemsAsIndex = $(this).find('.carousel-item').length - 1;
                // If last item hide next arrow
                if($(e.relatedTarget).index() == slidingItemsAsIndex ){
                    $(this).find('.carousel-control-next').hide();
                }
                else{
                  $(this).find('.carousel-control-next').show();
                }
                // If first item hide prev arrow
                if($(e.relatedTarget).index() == 0){
                  $(this).find('.carousel-control-prev').hide();
                }
                else{
                  $(this).find('.carousel-control-prev').show();
                }
              });

            });

            

            $('.search-results').addClass('show');
            
            refresh_search_results_ui();
          }
        },

        error: function(e)
        {
            console.log('error');
            console.log(e);
        }
      });
    }

    //Refresh results UI
    function refresh_search_results_ui(){
      $('i').tooltip();
      set_currency_change();
      language_select();
      send_inquiry();
      viewfullimage_onclick();
    }


    /**
     * 
     * 
     *  Check sorting
     */
    function check_sorting(){
      $('#search-results1 .order_category').off().on('change', function(){
        let selected_sort = $(this).attr('id');
        let selected_sort_value = $(this).val();
        console.log('selected_sort:'+selected_sort+" by "+selected_sort_value);


        let issort = false;
        let sort_arr = [];

        //Reset sort
        $('.order_category').each(function(){
          $(this).val("noselect");
          $('#select2-'+$(this).attr('id')+'-container').attr("title", $(this).children('option').eq(0).text() );
          $('#select2-'+$(this).attr('id')+'-container').text( $(this).children('option').eq(0).text() );
        });

        // If Order by Price
        if ( selected_sort=="orderby_price" ){
          console.log('order by price called.');

          $('#search-results1 .aircraft_card').each(function(index){
            let price = parseInt( $(this).find('.total_price').attr('price_in_eur') );
            sort_arr.push([index, price]);
          });
          issort = true;

        // If order by time
        }else if ( selected_sort=="orderby_time" ){
          console.log('order by time called.');

          $('#search-results1 .aircraft_card').each(function(index){
            let total_time = 0;
            
            $(this).find('.time-est').each(function(){
              let leg_time = $(this).text();
              leg_time = leg_time.replace(':','');
              leg_time = parseInt(leg_time);
              total_time += leg_time;
            });
            sort_arr.push([index, total_time]);
          });

          issort = true;
        
      // If order by pax
        }else if ( selected_sort=="orderby_pax" ){
          console.log('order by pax called.');

          $('#search-results1 .aircraft_card').each(function(index){
          let pax = $(this).find('.badge-passanger').text();
          pax = pax.split("-");
          let avg_pax = ( parseInt( pax[0] ) + parseInt( pax[1] ) )/2;
          sort_arr.push([index, avg_pax]);
        });

        issort = true;
      }


        //Sort successful
        if (issort){
          console.log('order array element and values');
          console.log(sort_arr);
          //Sorting by asc
          sort_arr.sort(function(a, b) {
            return a[1] - b[1];
          });
          //sorting by desc
          if (selected_sort_value=="desc"){
            sort_arr.reverse();
          }

          console.log('sorted_array');
          console.log(sort_arr);
        
          //Dom Change
          let temp_div = $('<div></div>');
          $.each(sort_arr, function(index, arr){
              //console.log(arr[0]);
              let this_element = $('#search-results1 .aircraft_card').eq(arr[0]);
              let this_parent = $('<div class="card-result aircraft_card" id="'+this_element.attr('id')+'"></div>');
              this_parent.append( this_element.html() );
              temp_div.append(this_parent);
          });

          $('#search-results1 .aircraft_card').remove();
          $('#search-results1').append( temp_div );
          refresh_search_results_ui();

          $("#"+selected_sort).val(selected_sort_value);
        }

      });
    }


      /**
       * 
       * Get Leg HTML
       */

      var previous_leg_arrival_date;
      var previous_leg_to_gmt;

      function get_leg_html(legs, item, data, leg_index){

        let leg = legs[leg_index];

            let distance = parseFloat(data.legs[leg_index].distance_nm);
            let total_legs = legs.length;
            let total_time = 0;
            let total_cost = 0;
            let origin_airpot = 'N/A';
            let destination_airpot = 'N/A';
            let origin_time = 'N/A';
            let destination_time = 'N/A';


            //If no date for intermediate legs
            if ( typeof leg.to_gmt == 'undefined' || !leg.to_gmt ){
              leg['to_gmt'] = previous_leg_to_gmt;
              console.log('Requested previous leg to gmt:'+previous_leg_to_gmt);
            }else{
              previous_leg_to_gmt = leg.to_gmt;
            }


            if ( typeof leg.departure_date == 'undefined' || !leg.departure_date ){
              leg["departure_date"] = previous_leg_arrival_date;
              console.log('Requested previous arrival date:'+previous_leg_arrival_date);
            }


        //Derives
        origin_airpot = leg.from_iata;
        destination_airpot = leg.to_iata;
        


        //UTC Timing...
        let departure_dateandtime = new Date(leg.departure_date);
        console.log('departure date passed:'+leg.departure_date);

        let t_departure_time = leg.departure_date+' '+ format_gmt_to_string(leg.from_gmt);
        console.log('Departure date and time string:'+ t_departure_time);
        //let departure_dateandtime_utc = new Date(t_departure_time).getTime();
        let departure_dateandtime_utc = moment(t_departure_time).format('x'); // Timestamp
        let departure_date = departure_dateandtime.toLocaleString().split(',')[0];

        console.log('departure datetime object:'+departure_dateandtime);
        console.log('departure_datetime_utc:'+departure_dateandtime_utc);
        
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

        let total_duration_utc = Math.ceil(total_duration)*60*1000; //Miliseconds
        console.log('total duration miliseconds:'+total_duration_utc);
        let destination_time_utc = parseInt(departure_dateandtime_utc) + parseInt(total_duration_utc) + format_gmt_to_utc(leg.to_gmt);
        console.log('Destination time utc: '+destination_time_utc);
        let destination_dateandtime = parseTimestamp(destination_time_utc);
        console.log('Destination time: '+destination_dateandtime);
          previous_leg_arrival_date = destination_dateandtime;
          previous_leg_arrival_datetime = destination_dateandtime;
        console.log('arrival time:'+destination_dateandtime);
        let destination_time_hr = destination_dateandtime.getHours();
        let destination_date = destination_dateandtime.toLocaleString().split(',')[0];
        let destination_time_min = destination_dateandtime.getMinutes();

            


        //Price
        let price = ( item.ac_per_hr_fee*(distance/ac_speed) )  + ( item.ac_per_landing_fee * stops_needed );
            price = price + price * ( item.ac_additions/100 ); // with adjustable commissions
            price = Math.ceil(price);


        //Setting values on template
        let legs_timing_html =  window.legs_time_template;
        //Price
        legs_timing_html = legs_timing_html.replace('{{leg_total}}',price);

        //Airport info
        let origin_time_hr = departure_dateandtime.getHours();
        if (origin_time_hr<10){origin_time_hr=''+0+origin_time_hr};
      
        let origin_time_min = departure_dateandtime.getMinutes();
        if (origin_time_min<10){origin_time_min=''+0+origin_time_min};
      
        legs_timing_html = legs_timing_html.replace('{{departure_time}}',origin_time_hr+":"+origin_time_min);
        legs_timing_html = legs_timing_html.replace('{{departure_date}}',moment(departure_dateandtime).format('MM/DD/YYYY'));
        legs_timing_html = legs_timing_html.replace('{{origin_iata}}',origin_airpot);
      
        if (destination_time_min<10){destination_time_min=''+0+destination_time_min};
        if (destination_time_hr<10){destination_time_hr=''+0+destination_time_hr};

        legs_timing_html = legs_timing_html.replace('{{arrival_time}}',destination_time_hr+":"+destination_time_min);
        legs_timing_html = legs_timing_html.replace('{{arrival_date}}',moment(destination_dateandtime).format('MM/DD/YYYY'));
        legs_timing_html = legs_timing_html.replace('{{destination_iata}}',destination_airpot);
      
        //Total Flight Time
        if(total_duration_hours<10){total_duration_hours=''+0+total_duration_hours};
        if(total_duration_minutes<10){total_duration_minutes=''+0+total_duration_minutes};

        legs_timing_html = 
        legs_timing_html.replace('{{total_duration}}',total_duration_hours+':'+total_duration_minutes);

        
          console.log("origin: "+origin_airpot);
          console.log("Destination: "+destination_airpot);
          
          if (leg.from_iata != leg.to_iata){
            return legs_timing_html;
          }else{
            return '';
          }
      }

        //Format GMT
        function format_gmt_to_string(gmt){
          let x = gmt.split('.');
          let gmt_h = parseInt(x[0]);
          let gmt_m = x[1];

          let gmt_sign = '+';
          if (gmt_h<0){gmt_sign='-'; gmt_h = Math.abs(gmt_h);}
          if (gmt_h<10){gmt_h='0'+gmt_h}
          if (gmt_m && gmt_m.length<2){
            gmt_m='0'+gmt_m;
          }else if(!gmt_m){
            gmt_m='00';
          }

          return (gmt_sign+gmt_h+':'+gmt_m);
        }

        function format_gmt_to_utc(gmt){
          let x = parseFloat(gmt);
          let gmt_h = Math.floor(gmt);
          let gmt_m = x - gmt_h;

          let gmt_utc = (gmt_h*60 + gmt_m)*60*1000;
          console.log('destination gmt to utc:'+gmt_utc);

          gmt_utc = parseInt(gmt_utc);
          return (gmt_utc);
        }

        //Gives time without user gmt affecting
        function parseTimestamp(timestampStr) {
          return new Date(new Date(timestampStr).getTime() + (new Date(timestampStr).getTimezoneOffset() * 60 * 1000));
        };

  function viewfullimage_onclick(){
    $('.img-enlargeable').off().on('click', function(){
      let src = $(this).css('background-image');
      src = src.match(/\((.*?)\)/)[1].replace(/('|")/g,'');
      var modal;
      function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
      modal = $('<div>').css({
          background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
          backgroundSize: 'contain',
          width:'100%', height:'100%',
          position:'fixed',
          zIndex:'1000000',
          top:'0', left:'0',
          cursor: 'zoom-out'
      }).click(function(){
          removeModal();
      }).appendTo('body');
      //handling ESC
      $('body').on('keyup.modal-close', function(e){
        if(e.key==='Escape'){ removeModal(); } 
      });
    });
  }

  function send_inquiry(){
    $('.send_inquiry').off().on('click', function(){
      //Inquired Card
      let card = $(this).closest('.aircraft_card');
      let contact = {};
      contact['contact_name'] = card.find('.contact_name').val();
      contact['contact_email'] = card.find('.contact_email').val();
      contact['contact_phone']  = card.find('.contact_phone').val();
      contact['contact_requirements']  = card.find('.contact_requirements').val();
       
      let quoted_total = card.find('.total_price').text();
          quoted_total = quoted_total.replace(/,/g,'');
          quoted_total = parseInt(quoted_total);
          quoted_total = Math.ceil(quoted_total) + $('#select2-currency_selector-container').text();
      let selected_aircraft = card.find('.ac_category_name').html();

      //Search Selection 
      let active_tab = $('#initial_selection .tab-content .tab-pane.active').attr('id');
  
      let legs = [];

      let i = 0;
      $('#'+active_tab+' .leg_row').each(function(index,item){

        let departure_date = $(this).find('.leg_departure_dateformat').eq(0).text();

        let return_date = $(this).find('.leg_departure_dateformat').eq(1).text();
        if ( typeof return_date == 'undefined' || return_date== ''){
          return_date = 'No Return Date';
        }
        
        let leg = {
          from_iata : $(this).find('.leg_from').val(),
          from_icao : $(this).find('.leg_from').attr('selected_icao'),
          from_gmt : $(this).find('.leg_from').attr('selected_gmt'),
          to_iata : $(this).find('.leg_to').val(),
          to_icao : $(this).find('.leg_to').attr('selected_icao'),
          to_gmt : $(this).find('.leg_to').attr('selected_gmt'),
          departure_date : departure_date,
          return_date : return_date,
          pax : $(this).find('.leg_no_of_passengers').val()
        };

        //Additional leg row if injected before for multi leg select
        /*
        if (active_tab=="pills-multi-leg" && i>0){
          let previous_index = i - 1;
          let intermediate_leg = {
            from_iata : legs[previous_index].to_iata,
            from_icao : legs[previous_index].to_icao,
            from_gmt : legs[previous_index].to_gmt,
            to_iata : $(this).find('.leg_from').val(),
            to_icao : $(this).find('.leg_from').attr('selected_icao'),
            to_gmt : $(this).find('.leg_from').attr('selected_gmt'),
            departure_date : legs[previous_index].departure_date,
            //return_date : return_date,
            pax : legs[previous_index].pax
          };
          legs[i]=intermediate_leg;
          legs[i+1]=leg;
          i = i+2;
        }else{
          */
          legs[i]=leg;
          i = i+1;
          /*
        }
        */
      });
      
  
      let inquiry_selection = {
        active_tab : active_tab,
        legs :legs,
        contact: contact,
        aircraft: selected_aircraft,
        total: quoted_total,
      };

      console.log(inquiry_selection);

      const emailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
      if (inquiry_selection.contact.contact_email.match( emailformat )){
        card.find('.contact_email').removeClass('redback');
        card.find('.inquirymsg_fail').removeClass('show_inquirymsg');

        $.ajax({     
          url: ajax_url + "send_email.php",
          method: "POST",
          data: inquiry_selection,
          success: function(data)
          {   
            console.log(data);
            card.find('.inquirymsg_success').addClass('show_inquirymsg');
          },
          fail:function(e){
              console.log('Error connecting to send email...');
              card.find('.inquirymsg_fail').addClass('show_inquirymsg');
          }
        });


      }else{
        card.find('.contact_email').addClass('redback');
        card.find('.inquirymsg_fail').addClass('show_inquirymsg');

        
      }
    });
  }


//--- jQuery No Conflict
})(jQuery);