(function($) {
    //--- jQuery No Conflict
    var ajax_url = '/wp-content/plugins/flight_booking/ajax_php/';
    
    /**
     * 
     * 
     * UI notifications
     */
    
     function hr_status(type,message){
            if (type=='success' || type=='danger'){
                $('#hranker_loader').hide();
            }
            $('#hr_message').css('opacity','0');
    
    
            $('#hr_message').css('opacity','0');
            $('#hr_message').html(message);
            $('#hr_message').removeClass('text-success');
            $('#hr_message').removeClass('text-danger');
            $('#hr_message').removeClass('text-secondary');
            $('#hr_message').addClass('text-'+type);
            $('#hr_message').css('opacity','1');
    
            if(type=="secondary"){
                $('#hranker_loader').stop().show();
            }else{
                $('#hranker_loader').stop().hide();
            }
     }
    
   
function onload_functions(){
    $("input[type='number'].spinner").inputSpinner();

    $(".selector").flatpickr({
    
    //    mode: "range",
        minDate: "today",
        dateFormat: "d-m-Y H:i",
        enableTime: true, 
        disableMobile: "true"
    
    });

    $(".selector2").flatpickr({
    
    //    mode: "range",
        minDate: "today",
        dateFormat: "d-m-Y H:i",
        enableTime: true,
        disableMobile: "true"
    
    });
    
    //$(".timePicker").flatpickr({
    //
    //    enableTime: true,
    //    noCalendar: true,
    //    dateFormat: "H:i",
    //    time_24hr: true
    //
    //
    //});
    
    
    
    $(".cta-inquiry").click(function () {
        $(this).parents(".top-result").toggleClass("show")
    })
    $(".searchbtn1").click(function () {
    
        $("#search-results1").slideDown()
        e.preventDefault();
    })
    $(".searchbtn2").click(function () {
    
        $("#search-results2").slideDown()
        e.preventDefault();
    })
    $(".searchbtn3").click(function () {
    
        $("#search-results3").slideDown()
        e.preventDefault();
    })
    $(".searchbtn4").click(function () {
    
        $("#search-results4").slideDown()
        e.preventDefault();
    })
    $('i').tooltip();
    
    
    
    
    
    // AUTOCOMPLETE COUNTRY
    var countries = {
        "AD": "Andorra",
        "A2": "Andorra Test",
        "AE": "United Arab Emirates",
        "AF": "Afghanistan",
        "AG": "Antigua and Barbuda",
        "AI": "Anguilla",
        "AL": "Albania",
        "AM": "Armenia",
        "AN": "Netherlands Antilles",
        "AO": "Angola",
        "AQ": "Antarctica",
        "AR": "Argentina",
        "AS": "American Samoa",
        "AT": "Austria",
        "AU": "Australia",
        "AW": "Aruba",
        "AX": "\u00c5land Islands",
        "AZ": "Azerbaijan",
        "BA": "Bosnia and Herzegovina",
        "BB": "Barbados",
        "BD": "Bangladesh",
        "BE": "Belgium",
        "BF": "Burkina Faso",
        "BG": "Bulgaria",
        "BH": "Bahrain",
        "BI": "Burundi",
        "BJ": "Benin",
        "BL": "Saint Barth\u00e9lemy",
        "BM": "Bermuda",
        "BN": "Brunei",
        "BO": "Bolivia",
        "BQ": "British Antarctic Territory",
        "BR": "Brazil",
        "BS": "Bahamas",
        "BT": "Bhutan",
        "BV": "Bouvet Island",
        "BW": "Botswana",
        "BY": "Belarus",
        "BZ": "Belize",
        "CA": "Canada",
        "CC": "Cocos [Keeling] Islands",
        "CD": "Congo - Kinshasa",
        "CF": "Central African Republic",
        "CG": "Congo - Brazzaville",
        "CH": "Switzerland",
        "CI": "C\u00f4te d\u2019Ivoire",
        "CK": "Cook Islands",
        "CL": "Chile",
        "CM": "Cameroon",
        "CN": "China",
        "CO": "Colombia",
        "CR": "Costa Rica",
        "CS": "Serbia and Montenegro",
        "CT": "Canton and Enderbury Islands",
        "CU": "Cuba",
        "CV": "Cape Verde",
        "CX": "Christmas Island",
        "CY": "Cyprus",
        "CZ": "Czech Republic",
        "DD": "East Germany",
        "DE": "Germany",
        "DJ": "Djibouti",
        "DK": "Denmark",
        "DM": "Dominica",
        "DO": "Dominican Republic",
        "DZ": "Algeria",
        "EC": "Ecuador",
        "EE": "Estonia",
        "EG": "Egypt",
        "EH": "Western Sahara",
        "ER": "Eritrea",
        "ES": "Spain",
        "ET": "Ethiopia",
        "FI": "Finland",
        "FJ": "Fiji",
        "FK": "Falkland Islands",
        "FM": "Micronesia",
        "FO": "Faroe Islands",
        "FQ": "French Southern and Antarctic Territories",
        "FR": "France",
        "FX": "Metropolitan France",
        "GA": "Gabon",
        "GB": "United Kingdom",
        "GD": "Grenada",
        "GE": "Georgia",
        "GF": "French Guiana",
        "GG": "Guernsey",
        "GH": "Ghana",
        "GI": "Gibraltar",
        "GL": "Greenland",
        "GM": "Gambia",
        "GN": "Guinea",
        "GP": "Guadeloupe",
        "GQ": "Equatorial Guinea",
        "GR": "Greece",
        "GS": "South Georgia and the South Sandwich Islands",
        "GT": "Guatemala",
        "GU": "Guam",
        "GW": "Guinea-Bissau",
        "GY": "Guyana",
        "HK": "Hong Kong SAR China",
        "HM": "Heard Island and McDonald Islands",
        "HN": "Honduras",
        "HR": "Croatia",
        "HT": "Haiti",
        "HU": "Hungary",
        "ID": "Indonesia",
        "IE": "Ireland",
        "IL": "Israel",
        "IM": "Isle of Man",
        "IN": "India",
        "IO": "British Indian Ocean Territory",
        "IQ": "Iraq",
        "IR": "Iran",
        "IS": "Iceland",
        "IT": "Italy",
        "JE": "Jersey",
        "JM": "Jamaica",
        "JO": "Jordan",
        "JP": "Japan",
        "JT": "Johnston Island",
        "KE": "Kenya",
        "KG": "Kyrgyzstan",
        "KH": "Cambodia",
        "KI": "Kiribati",
        "KM": "Comoros",
        "KN": "Saint Kitts and Nevis",
        "KP": "North Korea",
        "KR": "South Korea",
        "KW": "Kuwait",
        "KY": "Cayman Islands",
        "KZ": "Kazakhstan",
        "LA": "Laos",
        "LB": "Lebanon",
        "LC": "Saint Lucia",
        "LI": "Liechtenstein",
        "LK": "Sri Lanka",
        "LR": "Liberia",
        "LS": "Lesotho",
        "LT": "Lithuania",
        "LU": "Luxembourg",
        "LV": "Latvia",
        "LY": "Libya",
        "MA": "Morocco",
        "MC": "Monaco",
        "MD": "Moldova",
        "ME": "Montenegro",
        "MF": "Saint Martin",
        "MG": "Madagascar",
        "MH": "Marshall Islands",
        "MI": "Midway Islands",
        "MK": "Macedonia",
        "ML": "Mali",
        "MM": "Myanmar [Burma]",
        "MN": "Mongolia",
        "MO": "Macau SAR China",
        "MP": "Northern Mariana Islands",
        "MQ": "Martinique",
        "MR": "Mauritania",
        "MS": "Montserrat",
        "MT": "Malta",
        "MU": "Mauritius",
        "MV": "Maldives",
        "MW": "Malawi",
        "MX": "Mexico",
        "MY": "Malaysia",
        "MZ": "Mozambique",
        "NA": "Namibia",
        "NC": "New Caledonia",
        "NE": "Niger",
        "NF": "Norfolk Island",
        "NG": "Nigeria",
        "NI": "Nicaragua",
        "NL": "Netherlands",
        "NO": "Norway",
        "NP": "Nepal",
        "NQ": "Dronning Maud Land",
        "NR": "Nauru",
        "NT": "Neutral Zone",
        "NU": "Niue",
        "NZ": "New Zealand",
        "OM": "Oman",
        "PA": "Panama",
        "PC": "Pacific Islands Trust Territory",
        "PE": "Peru",
        "PF": "French Polynesia",
        "PG": "Papua New Guinea",
        "PH": "Philippines",
        "PK": "Pakistan",
        "PL": "Poland",
        "PM": "Saint Pierre and Miquelon",
        "PN": "Pitcairn Islands",
        "PR": "Puerto Rico",
        "PS": "Palestinian Territories",
        "PT": "Portugal",
        "PU": "U.S. Miscellaneous Pacific Islands",
        "PW": "Palau",
        "PY": "Paraguay",
        "PZ": "Panama Canal Zone",
        "QA": "Qatar",
        "RE": "R\u00e9union",
        "RO": "Romania",
        "RS": "Serbia",
        "RU": "Russia",
        "RW": "Rwanda",
        "SA": "Saudi Arabia",
        "SB": "Solomon Islands",
        "SC": "Seychelles",
        "SD": "Sudan",
        "SE": "Sweden",
        "SG": "Singapore",
        "SH": "Saint Helena",
        "SI": "Slovenia",
        "SJ": "Svalbard and Jan Mayen",
        "SK": "Slovakia",
        "SL": "Sierra Leone",
        "SM": "San Marino",
        "SN": "Senegal",
        "SO": "Somalia",
        "SR": "Suriname",
        "ST": "S\u00e3o Tom\u00e9 and Pr\u00edncipe",
        "SU": "Union of Soviet Socialist Republics",
        "SV": "El Salvador",
        "SY": "Syria",
        "SZ": "Swaziland",
        "TC": "Turks and Caicos Islands",
        "TD": "Chad",
        "TF": "French Southern Territories",
        "TG": "Togo",
        "TH": "Thailand",
        "TJ": "Tajikistan",
        "TK": "Tokelau",
        "TL": "Timor-Leste",
        "TM": "Turkmenistan",
        "TN": "Tunisia",
        "TO": "Tonga",
        "TR": "Turkey",
        "TT": "Trinidad and Tobago",
        "TV": "Tuvalu",
        "TW": "Taiwan",
        "TZ": "Tanzania",
        "UA": "Ukraine",
        "UG": "Uganda",
        "UM": "U.S. Minor Outlying Islands",
        "US": "United States",
        "UY": "Uruguay",
        "UZ": "Uzbekistan",
        "VA": "Vatican City",
        "VC": "Saint Vincent and the Grenadines",
        "VD": "North Vietnam",
        "VE": "Venezuela",
        "VG": "British Virgin Islands",
        "VI": "U.S. Virgin Islands",
        "VN": "Vietnam",
        "VU": "Vanuatu",
        "WF": "Wallis and Futuna",
        "WK": "Wake Island",
        "WS": "Samoa",
        "YD": "People's Democratic Republic of Yemen",
        "YE": "Yemen",
        "YT": "Mayotte",
        "ZA": "South Africa",
        "ZM": "Zambia",
        "ZW": "Zimbabwe",
        "ZZ": "Unknown or Invalid Region"
    }
    var countriesString = [
        "Andorra",
        "Andorra Test",
        "United Arab Emirates"
    ];
    var countriesArray = $.map(countries, function (value, key) {
        return {value: value, data: key};
    });
    
    
    $(".swap").click(function (e) {
        $(this).toggleClass("rotate");
        e.preventDefault();
        $(".complete-trip").toggleClass("active")
    
        var fromVal = $(".one").val();
        var toVal = $(".two").val();
    
        $(".one").val(toVal);
        $(".two").val(fromVal);
    });
    $(".swap2").click(function (e) {
        $(this).toggleClass("rotate");
        e.preventDefault();
        $(".complete-trip").toggleClass("active")
    
        var fromVal = $(".one2").val();
        var toVal = $(".two2").val();
    
        $(".one2").val(toVal);
        $(".two2").val(fromVal);
    });
}

    

/**
 * 
 *  Auto Complete
 *  */    
var delayTimer;

var use_ajax = false;

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
    

  }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s

  
}

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
      
      var fuse = new Fuse(airports, options)
      
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
        
        if (ac.val().length > 2) {
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
      })
      
}


    /**
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * Execute functions on DOM ready
     * 
     */

    $(document).ready(function() {

        function setCurrency(currency) {
            if (!currency.id) {
                return currency.text;
            }
            var $currency = $('<span class="glyphicon glyphicon-' + currency.element.value + '">' + currency.text + '</span>');
            return $currency;
        }
        ;
        $(".templatingSelect2").select2({
            placeholder: "What currency do you use?", //placeholder
            templateResult: setCurrency,
            templateSelection: setCurrency
        });

        onload_functions();

        autocomplete_js();

        var results = [];
       
        //console.log(window.airports.airportsByCities);
    });

    $(window).on('load', function () {
       // $('#search_modal').modal('show');
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

          $('#search_modal').modal('show');
        });
    });
    
    //--- jQuery No Conflict
    })(jQuery);