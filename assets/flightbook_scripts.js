(function($) {
    //--- jQuery No Conflict
    var ajax_url = '/wp-content/plugins/headphone_ranker/ajax_php/';
    
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
    
   
    /***
     * 
     * Reload table without sort
     */
    function refresh_table(){    
        $('.hr_sort_desc').removeClass('hr_sort_desc');
        $('.hr_sort_asc').removeClass('hr_sort_asc');

        $('#hranker_table tbody').html('');
        $('#hr_search_term').val('');

        $('#filter_brand').val('any');
        $('#filter_principle').val('any');
        $('#filter_genre').val('any');
        $('#hr_price_from').val(0);
        $('#hr_price_to').val(0);

        $('#hr_search_input_group').removeClass('hr_search_active');

        $('#pagination').attr( 'current_page' , '1' );

        get_table_results();
    }
    
    
    
    /**
     * 
     * 
     * 
     * 
     * 
     * Get Table Results
     * 
     * 
     */
    function get_table_results(){
        console.log('Fetching results from table');
        $('#hranker_table thead').addClass('hr_locked');
        $('#hranker_table tbody').addClass('hr_locked');
        $('#hr_edit_selected').addClass('hr_locked');
        $('#hr_delete_selected').addClass('hr_locked');
        $('#hr_search_input_group').addClass('hr_locked');
        $('#pagination').addClass('hr_locked');
    
        hr_status('secondary','Fetching results from database..');
    
        let ajax_data = {
            "table":$('#admin_product_select').val(),
            //Sort status
            "sort_by" : get_sort_status('sort_by'),
            "sort_type" : get_sort_status('sort_type'),
            //Search Status
            "search" :$('#hr_search_term').val(),
            //Pagination Info
            "pagination" : $('#pagination').attr('current_page'),
            "page_size" : $('#results_per_page').val(),
            //Filtration Info
            "filter_brand" : $('#filter_brand').val(),
            "filter_principle" : $('#filter_principle').val(),
            "filter_genre" : $('#filter_genre').val(),
            "filter_price_from" : $('#hr_price_from').val(),
            "filter_price_to" : $('#hr_price_to').val()
        }
        console.log('Request parameters:');
        console.log(ajax_data);
        $.ajax({     
            type: "POST",
            crossDomain: true,
            url:ajax_url+'fetch_records.php',
            data :ajax_data,
    
            success: function(data)
            {   
                //console.log(data);
                
    
                try {
                    data = JSON.parse(data);
                  }
                  catch (e) {
                    console.log("error: "+e);
                  };
    
                if (data[1].msg=="success"){
                    console.log("fetch success...");
                    hr_status('success','Table results fetched..');
                    console.log(data);
    
                    $('#hranker_table thead').removeClass('hr_locked');
                    $('#hranker_table tbody').removeClass('hr_locked');
                    $('#hr_search_input_group').removeClass('hr_locked');
                    $('#hrt_select_all').prop('checked',false);
    
                    //ADD TABLE ROWS
                    add_data_to_table(data[1].page_data);
                    set_pagination(data.paginationHtml);
                    set_filters(data[2], data[0].filters);
                }else{
                    console.log("Error... ");
                    console.log(data);
    
                    if( $('#hr_search_term').val() != '' &&  $('#hr_search_input_group').hasClass('hr_search_active') ){
                        $('#hr_search_input_group').removeClass('hr_locked');
                            hr_status('danger','Results not found for current search...');
                    }else{
                            hr_status('danger','Results not found...');
                            $('#hr_search_input_group').removeClass('hr_locked');
                    }
                }
            },
    
            error: function(e)
            {
                console.log(e);
            }
        });
    }
    
    function add_data_to_table(data){
        $('#hranker_table tbody').html('');
    
        data.forEach(function(item){
    
           
            //Formatting template for each device type
            const device_type = $('#admin_product_select').val();
            let row_principle = '';
            if(device_type=="headphones"){
                row_principle =  `<td class="hrt_principle">`+format_comma_seperated_text(item.principle)+`</td>`;
            }else if( device_type=="iem" || device_type=="earbuds" ){
                row_principle = '';
            }
    
            let row = 
            `<tr id="`+item.id+`">
                <td class="hrt_rank">`+item.rank+`</td>
                <td class="hrt_brand">`+item.brand+`</td>
                <td class="hrt_device">`+item.device+`</td>
                <td class="hrt_price">$`+pretify_price(item.price)+`</td>
                <td class="hrt_value">`+item.value+`</td>`
                +row_principle+
                `<td class="hrt_overall_timbre">`+format_comma_seperated_text(item.overall_timbre)+`</td>
                <td class="hrt_summary">`+item.summary+`</td>
                <td class="hrt_ganre_focus">`+format_comma_seperated_text(item.ganre_focus)+`</td>
            </tr>`;
    
            
            
            $('#hranker_table tbody').append(row);
            $('#hranker_table').removeClass('hr_locked');
        });  
    }

    // Script to pretify price
    function pretify_price(number, decPlaces, decSep, thouSep) {
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSep = typeof decSep === "undefined" ? "." : decSep;
        thouSep = typeof thouSep === "undefined" ? "," : thouSep;
        var sign = number < 0 ? "-" : "";
        var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
        var j = (j = i.length) > 3 ? j % 3 : 0;
        
        return sign +
            (j ? i.substr(0, j) + thouSep : "") +
            i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
            (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
     }
    
    function format_comma_seperated_text(text){
        let items = text.split(',');
        let items_html = '';
        items.forEach(function(itm,index){
            items[index] = '<span>'+itm.trim()+'</span>';
            items_html += items[index];
        });
        //console.log(items_principle);
        return items_html;
    }
    
    
    
    
    
    /**
     * 
     * 
     * 
     * 
     * Pagination
     */
    function set_pagination(paginationHtml){
        console.log('setting pagination...');
        console.log(paginationHtml);
    
        $('#pagination').html('');
        $('#pagination').append(paginationHtml);
    
        $('#pagination .page').unbind('click').click(function(){
            let current_page = parseInt( $('#pagination').attr( 'current_page' ) );
            if ( $(this).hasClass('pagination_prev') ){
                current_page--;
            }else if ( $(this).hasClass('pagination_next') ){
                current_page++;
            }else{
                current_page = $(this).text();
            }
    
            $('#pagination').attr( 'current_page' , current_page );
    
            get_table_results();
        });
    
        $('#results_per_page').unbind('change').change(function(){
                 get_table_results();
         });
    
        $('#pagination').removeClass('hr_locked');
    
        
    }
    
    
    
    
    
    /***
     * 
     * 
     * 
     *  Set Filters
     */
    function set_filters(filters, selected_filters){
    
        $('#filter_principle').html('<option  value="any">Any</option>');
        $('#filter_genre').html('<option  value="any">Any</option>');
        $('#filter_brand').html('<option  value="any">Any</option>');
    
        if(filters.brand && filters.brand.length>0){
            filters.brand.forEach(function(item){
                $('#filter_brand').append('<option value="'+item+'">'+item+'</option>');
            });
            $('#filter_brand').val(selected_filters.brand);
        }
       
        if(filters.principle && filters.principle.length>0){
            filters.principle.forEach(function(item,index){
                $('#filter_principle').append('<option value="'+item+'">'+item+'</option>');
            });
            $('#filter_principle').val(selected_filters.principle);
        }
       
        if(filters.genre && filters.genre.length>0){
            filters.genre.forEach(function(item){
                $('#filter_genre').append('<option value="'+item+'">'+item+'</option>');
            });
            $('#filter_genre').val(selected_filters.genre);
        }
    
        $('#hr_price_from').val( parseInt(selected_filters.from) );
        $('#hr_price_to').val( parseInt(selected_filters.to) );
     
        $('#filter_brand, #filter_principle, #filter_genre').off('change').on('change', function(){
           // if($('#filter_principle').val()!="any" || $('#filter_genre').val()!="any"){
               $('#hr_search_term').val('');
                get_table_results();
           // }
        });
    
        $('#hr_price_from, #hr_price_to').unbind('change').change(function(){
            if( $('#hr_price_from').val() >=0 && $('#hr_price_to').val() >0 && $('#hr_price_to').val()>$('#hr_price_from').val() ){
                get_table_results();
            }
        });
    
    
        //Select 2
        if (window.matchMedia("(min-width: 1024px)").matches) {
        $("#filter_brand").select2("destroy").select2({dropdownPosition: 'below'});
        $("#filter_principle").select2("destroy").select2({dropdownPosition: 'below'});
        $("#filter_genre").select2("destroy").select2({dropdownPosition: 'below'});
        }
    }
    
    
    
    /**
     * 
     * 
     *  Listen for sort
     */
    function hr_listen_sort(){
        $('.hr_sort').click(function(){
            if ($(this).hasClass('hr_sort_desc')){
                $('.hr_sort').removeClass('hr_sort_desc');
                $('.hr_sort').removeClass('hr_sort_asc');
                $(this).addClass('hr_sort_asc');
            }else{
                $('.hr_sort').removeClass('hr_sort_desc');
                $('.hr_sort').removeClass('hr_sort_asc');
                $(this).addClass('hr_sort_desc');
            }
            get_table_results();
        });
    }
    
    /**
     * 
     * Get Sort status
     */
    function get_sort_status(req){
    
        let sort_by = "no_sort";
        let sort_type = "no_sort";
    
        if( $('.hr_sort.hr_sort_desc').length>0 ){
            sort_by = $('.hr_sort.hr_sort_desc').eq(0).parent().attr('class');
            sort_type = "DESC";
    
        }else if( $('.hr_sort.hr_sort_asc').length>0 ){
            sort_by = $('.hr_sort.hr_sort_asc').eq(0).parent().attr('class');
            sort_type = "ASC";
        }
    
        if (req == "sort_by"){
            return sort_by;
        }else{
            return sort_type;
        }
    }
    
    
    
    
    
    
    
    
    /**
     * 
     * 
     *  HR Search
     */
    function hr_search(){
        $('#hr_search').click(function(){
            do_search();
        });
        $('#hr_search_term').on('keypress',function(e) {
            if(e.which == 13) {
                do_search();
            }
        });
    }
    

function do_search(){
    $('#hranker_table thead').addClass('hr_locked');
            $('#hranker_table tbody').addClass('hr_locked');
            $('#hr_edit_selected').addClass('hr_locked');
            $('#hr_delete_selected').addClass('hr_locked');
            $('#hr_search_input_group').addClass('hr_locked');
    
            hr_status('secondary','Searching...');
    
            const search_term = $('#hr_search_term').val();
            let specialChars = "<>@%^*()_+[]{}?;|\"\\,./~`=";
            let check_chars = function(string){
                for(i = 0; i < specialChars.length;i++){
                    if(string.indexOf(specialChars[i]) > -1){
                        return true
                    }
                }
                return false;
            }
    
            if( typeof search_term == 'undefined' || search_term == '' ){
                $('#hr_search_input_group').removeClass('hr_search_active');
                hr_status('danger','Empty Search...');
    
                get_table_results();
    
            }else if( check_chars(search_term) != false ){
                hr_status('danger','Invalid characters found in search term');
                //$('#hr_search_input_group').addClass('hr_search_active');
                $('#hr_search_input_group').removeClass('hr_locked');
    
            }else{
    
                hr_status('secondary','Searching for "'+search_term+'" ...');
                //$('#hr_search_input_group').addClass('hr_search_active');
    
                get_table_results();
            }
}
    
    
    
    /**
     * 
     * 
     * Cancel Search
     */
    function cancel_search(){
        $('#hr_search_cancel').click(function(){
            refresh_table();
        });
    }

/***
 * 
 * 
 * 
 * Fetch Front End HTML
 */
function fetch_frontend_html(){
    const table = $('#admin_product_select').val();
    $.ajax({     
        type: "POST",
        crossDomain: true,
        url:ajax_url+'fetch_frontend_html.php',
        data :{
            table:table,
        },

        success: function(data)
        {   
           
            try {
                data = JSON.parse(data);
              }
              catch (e) {
                console.log("error: "+e);
              };

            if (data.msg=="success"){
                console.log(data);
               console.log("fetch success...");
               $('#frontend_html_placeholder').html(data.result);
            }else{
                console.log("Error... ");
                console.log(data);
            }
        },

        error: function(e)
        {
            console.log(e);
        }
    });
}
    
   


/**
 * 
 * 
 * 
 * SOCIAL SHARE HANDLING
 */
function handle_social_share(){
    console.log('Initializing SocialShare...');
    $('#social_sharer button').each(function(){
        let btn = $(this).attr('id');
        btn = btn.replace('ss_','');

        let title = "Headphone Ranker by Major HiFi";
        let url = window.location.href; 
        
        $(this).attr('data-sharer',btn);
        $(this).attr('data-width','600');
        $(this).attr('data-height','400');

        switch(btn){
            case 'twitter':{
                $(this).attr('data-title',title);
                $(this).attr('data-url',url);
            }
            break;

            case 'facebook':{
                $(this).attr('data-url',url);
            }
            break;

            case 'reddit':{
                $(this).attr('data-url',url);
            }
            break;

            case 'linkedin':{
                $(this).attr('data-url',url);
            }
            break;

            case 'whatsapp':{
                $(this).attr('data-title',title);
                $(this).attr('data-url',url);
            }
            break;

            case 'email':{
                $(this).attr('data-title',title);
                $(this).attr('data-url',url);
                $(this).attr('data-subject','Checkout this awesome ranking tool!');
            }
            break;

            case 'bookmark':{
                $(this).off('click').on('click', function (e) {
                    var bookmarkTitle = title;
                    var bookmarkUrl = url;
                
                    if ('addToHomescreen' in window && addToHomescreen.isCompatible) {
                      // Mobile browsers
                      addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
                    } else if (/CriOS\//.test(navigator.userAgent)) {
                      // Chrome for iOS
                      alert('To add to Home Screen, launch this website in Safari, then tap the Share button and select "Add to Home Screen".');
                    } else if (window.sidebar && window.sidebar.addPanel) {
                      // Firefox <=22
                      window.sidebar.addPanel(bookmarkTitle, bookmarkUrl, '');
                    } else if ((window.sidebar && /Firefox/i.test(navigator.userAgent) && !Object.fromEntries) || (window.opera && window.print)) {
                      // Firefox 23-62 and Opera <=14
                      $(this).attr({
                        href: bookmarkUrl,
                        title: bookmarkTitle,
                        rel: 'sidebar'
                      }).off(e);
                      return true;
                    } else if (window.external && ('AddFavorite' in window.external)) {
                      // IE Favorites
                      window.external.AddFavorite(bookmarkUrl, bookmarkTitle);
                    } else {
                      // Other browsers (Chrome, Safari, Firefox 63+, Opera 15+)
                      alert('Press ' + (/Mac/i.test(navigator.platform) ? 'Cmd' : 'Ctrl') + '+D to bookmark this page.');
                    }
                
                    return false;
                  });
            }
            break;

            case 'copylink':{
                $(this).off('click').on('click', function (e) {
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(url).select();
                    document.execCommand("copy");
                    $temp.remove();
                    hr_status('success','Link Copied...');
                });
            }
            break;
        }
    });

    window.Sharer.init();
    console.log('SocialShare Initialized...');
}

/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 *  Sponsored Banner
 * 
 */
function fetch_banner_info(){

    $.ajax({     
        type: "GET",
        crossDomain: true,
        url:ajax_url+'fetch_banner_info.php',

        success: function(data)
        {   
           
            try {
                data = JSON.parse(data);
              }
              catch (e) {
                console.log("error: "+e);
              };

            if (data.msg=="success"){
                console.log(data);
                console.log("Banner Info fetch success...");
                
                $('#banner1 img').attr('src',ajax_url+'../assets/img/'+data.banner1);
                $('#banner1 a').attr('href',data.url1);
                $('#banner2 img').attr('src',ajax_url+'../assets/img/'+data.banner2);
                $('#banner2 a').attr('href',data.url2);
                $('#banner3 img').attr('src',ajax_url+'../assets/img/'+data.banner3);
                $('#banner3 a').attr('href',data.url3);
                $('#banner4 img').attr('src',ajax_url+'../assets/img/'+data.banner4);
                $('#banner4 a').attr('href',data.url4);

            }else{
                console.log("Error... ");
                console.log(data);
            }
        },

        error: function(e)
        {
            console.log(e);
        }
    });
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
        console.log('HRanker Product Manager - Scripts Ready()');

        if (window.matchMedia("(min-width: 1024px)").matches) {
            //Select 2
              $("#filter_brand").select2({dropdownPosition: 'below'});
              $("#filter_principle").select2({dropdownPosition: 'below'});
              $("#filter_genre").select2({dropdownPosition: 'below'});
          }
        
        $(window).on('resize', function(){

            if (window.matchMedia("(min-width: 1024px)").matches) {
            //Select 2
            $("#filter_brand").select2("destroy").select2({dropdownPosition: 'below'});
            $("#filter_principle").select2("destroy").select2({dropdownPosition: 'below'});
            $("#filter_genre").select2("destroy").select2({dropdownPosition: 'below'});
            }
            
        });
    
     // --- Execute Front end page specific functions   

         hr_listen_sort(); // Sorting

         hr_search(); // Search
         cancel_search()// Cancel Search
     
         //Onload fetch results
         get_table_results();
    
         //Enable Tooltips
         $('[data-toggle="tooltip"]').tooltip();


         //Fetch Frontend HTML
         fetch_frontend_html();

         //Handle Share
         handle_social_share();

         //Fetch Banner Info
         fetch_banner_info();
       
    });

   
    
    //--- jQuery No Conflict
    })(jQuery);