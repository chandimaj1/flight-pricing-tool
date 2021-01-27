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


  //Disable Hyperlinks within the Plugin
  $("#initial_selection a[href='#'], #search_modal a[href='#']").click(function(e) {
    e.preventDefault();
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
        weight: 0.5
      }, {
        name: 'nameAirport',
        weight: 0.3
      }, {
        name: 'nameCountry',
        weight: 0.2
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
      
    function selectIndex(index) {
      if ( (results.length >= index + 1) && use_ajax ) {
        ac.val(results[index].codeIataAirport);
        console.log(results[index].codeIataAirport);
        clearResults();
      }else if ( (results.length >= index + 1) && !use_ajax ) {
        ac.val(results[index].item.codeIcaoAirport);
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
  $('.search_btn').on('click',function(){

    //Copying InnterHtml to modal
    $('#initial_selection .tab-pane').each(function(index,item){
       let tab_html = $(item).html();
       let current_tab = $(item).attr('id');
          current_tab = current_tab.slice(0,-1);

        $('#'+current_tab).html(tab_html);
    });

    let active_tab = $('#initial_selection .tab-content .tab-pane.active').attr('id');
    active_tab = active_tab.slice(0,-1);
    console.log('active tab: '+active_tab);

    $('#search_modal .tab-content .tab-pane').removeClass('.show');
    $('#search_modal .tab-content .tab-pane').removeClass('.active');


    $('#search_modal #'+active_tab).addClass('.show');
    $('#search_modal #'+active_tab).addClass('.active');



    let legs = [];
    $('#'+active_tab+' .flight-book-inner>form>.row').each(function(index,item){
        console.log(item);
    });
    

    let search_selection = {
      active_tab : active_tab,
      legs :legs,
    };

    console.log(search_selection);

    let initselect_scroll_top = $("#initial_selection").offset().top;
    initselect_scroll_top = initselect_scroll_top - 50;

    $('html, body').animate({
      scrollTop: initselect_scroll_top
    }, 300, function(){
      let initselect_height = $('#initial_selection').height()+55;
      let searchresults_height = $(window).height() - initselect_height - 15;
      
      $('#search_modal').css('margin-top',initselect_height+'px');
      $('#search_modal').css('height',searchresults_height+'px');

      $('#search_modal').modal('show');
    });
    
  });
}


//--- jQuery No Conflict
})(jQuery);