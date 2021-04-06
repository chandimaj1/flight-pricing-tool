(function($) {
    //--- jQuery No Conflict
    
    //Global Variables
    var ajax_url = '/wp-content/plugins/flight_booking/ajax_php/';

$(document).ready(function(){
    console.log('VeloxJetz Pricing tool : airports scripts V1.0')
    results_ui();
    table_ui();
});

function table_ui(){

    //Edit button clicked
    $('.airports_row .ap_img_edit').off().on('click',function(){
        $(this).closest('.airports_row').addClass('editing_airports_row');
        $('#update_btn').removeClass('btn-secondary');
        $('#update_btn').addClass('btn-success');
        $(this).closest('.airports_row').find('input').each(function(){
            $(this).attr('original_content', $(this).val() );
        });
        let status_class = $(this).closest('.airports_row').find('.ap_status');
        if ( status_class.hasClass('status_at_1') ){
            status_class.attr('original_content','1');
        }else{
            status_class.attr('original_content','0');
        }
    });

    //Cancel edit
    $('.airports_row .ap_img_cancel').off().on('click',function(){
        $(this).closest('.airports_row').removeClass('editing_airports_row');
        $(this).closest('.airports_row').find('input').each(function(){
            $(this).val( $(this).attr('original_content') );
        });
        let status_class = $(this).closest('.airports_row').find('.ap_status');
        status_class.removeClass('status_at_1');
        status_class.removeClass('status_at_0');
        status_class.addClass('status_at_'+status_class.attr('original_content'));
    });

    //Delete
    $('.airports_row .ap_img_delete').off().on('click',function(){
        $(this).closest('.airports_row').remove();
    });

    //Toggle visibility
    $('.ap_status button').off().on('click', function(){
        if ( $(this).parent().hasClass('status_at_1') ){
            $(this).parent().removeClass('status_at_1');
            $(this).parent().addClass('status_at_0');
        }else{
            $(this).parent().addClass('status_at_1');
            $(this).parent().removeClass('status_at_0');
        }
    });

    //Save Table Row
    $('.airports_row .ap_img_save').off().on('click',function(){
        let parent_tr = $(this).closest('.editing_airports_row');
        get_row_data(parent_tr)
    });

    $('#update_btn').off().on('click',function(){
        $('.editing_airports_row').each(function(){
            let parent_tr = $(this);
            get_row_data(parent_tr);
        });

        $('#update_btn').addClass('btn-secondary');
        $('#update_btn').removeClass('btn-success');
    });

    function get_row_data(parent_tr){

        let validate = true;

        parent_tr.find('input').each(function(){
            if ( $(this).val() =='' ){
                $(this).css('background-color',"#fcc");
                validate = false;
            }else{
                $(this).css('background-color',"#eee");
            }
        });

        //Check if  no empty validated
        if (validate){
            parent_tr.removeClass('editing_airports_row');
            
            let ap_id = parent_tr.find('.ap_id').text();
            let ap_name = parent_tr.find('.ap_name').val();
            let ap_city = parent_tr.find('.ap_city').val();
            let ap_country = parent_tr.find('.ap_country').val();
            let ap_iata = parent_tr.find('.ap_iata').val();
            let ap_icao = parent_tr.find('.ap_icao').val();
            let ap_gmt = parent_tr.find('.ap_gmt').val();
            let ap_country_code = parent_tr.find('.ap_country_code').val();
            let ap_lat = parent_tr.find('.ap_lat').val();
            let ap_long = parent_tr.find('.ap_long').val();
            let ap_status = parent_tr.find('.ap_status').attr('original_content');
            if ( parent_tr.find('.ap_status').hasClass('status_at_0') ){
                ap_status = 0;
            }else{
                ap_status = 1;
            }

            let row_values = {
                id: ap_id,
                airport_name: ap_name,
                airport_city: ap_city,
                country_name: ap_country,
                airport_iata: ap_iata,
                airport_icao: ap_icao,
                gmt: ap_gmt,
                country_code: ap_country_code,
                latitude: ap_lat,
                longitude: ap_long,
                status: ap_status
            }
            
            ajax_save_airport_row(row_values);
        }else{
            alert ("Fields cannot be empty to save the row");
        }
        
    }


    function ajax_save_airport_row(ap){
        $('#airports_row'+ap.id).addClass('redback');
        console.log('updating row: '+ap.id);
        console.log(ap);

        $.ajax({     
            url: ajax_url + 'save_ap_row.php',
            method: "POST",
            data: ap,
            success: function(data)
            { 
                //data = JSON.parse(data);
                console.log(data);
                $('#airports_row'+ap.id).removeClass('redback');
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not update Airport. Error:'+e);
            }
        });
    }


}


function results_ui(){

    //Init results settings
    if (window.limitset != 'no'){ $('#ra_perpage').val(window.limitset); }
    if (window.columnset != 'no'){ $('#ra_sort').val(window.columnset); }
    if (window.orderset != 'no'){ $('#ra_sortby').val(window.orderset); }
    if (window.searchset != 'no'){ $('#ra_search').val(window.searchset); }
    if (window.pageset != 'no'){ $('#ra_page').val(window.pageset); }


    //Sorting
    $('#sort_btn').off().on('click', function(){
        window.location.href = window.settings_page_url 
        + '&column=' + $('#ra_sort').val() 
        + '&order=' + $('#ra_sortby').val()
        + '&limit=' + $('#ra_perpage').val();
    });

    //Search
    $('#search_btn').off().on('click', function(){
        window.location.href = window.settings_page_url 
        + '&search=' + $('#ra_search').val()
        + '&limit=' + $('#ra_perpage').val();
    });

    //Pagination
    $('#ra_perpage').off().on('change', function(){
        window.location.href = window.settings_page_url 
        + '&column=' + $('#ra_sort').val() 
        + '&order=' + $('#ra_sortby').val()
        + '&search=' + $('#ra_search').val()
        + '&limit=' + $(this).val();
    });
    $('#ra_page').off().on('change', function(){
        window.location.href = window.settings_page_url 
        + '&column=' + $('#ra_sort').val() 
        + '&order=' + $('#ra_sortby').val()
        + '&search=' + $('#ra_search').val()
        + '&limit=' + $('#ra_perpage').val()
        + '&results_page=' + $(this).val();
    });
    $('#prev_page').off().on('click',function(){
        window.location.href = window.settings_page_url 
        + '&column=' + $('#ra_sort').val() 
        + '&order=' + $('#ra_sortby').val()
        + '&search=' + $('#ra_search').val()
        + '&limit=' + $('#ra_perpage').val()
        + '&results_page=' + ( parseInt( $('#ra_page').val() )-1 ) ;
    });
    $('#next_page').off().on('click',function(){
        window.location.href = window.settings_page_url 
        + '&column=' + $('#ra_sort').val() 
        + '&order=' + $('#ra_sortby').val()
        + '&search=' + $('#ra_search').val()
        + '&limit=' + $('#ra_perpage').val()
        + '&results_page=' + ( parseInt( $('#ra_page').val() )+1 ) ;
    });

    //Add row modal
    $('#addnew_btn').off().on('click',function(){

        let x = `
        <tr class="airports_row editing_airports_row new_row" id="airports_row`+window.lastid+`">
            <td><div class="ap_id  form-control">`+window.lastid+`</div> </td>
            <td><input type="text" class="ap_name  form-control" /></td>
            <td><input type="text" class="ap_city form-control"  /></td>
            <td><input type="text" class="ap_country  form-control"  /></td>
            <td><input type="text" class="ap_iata  form-control"  /></td>
            <td><input type="text" class="ap_icao form-control"  /></td>
            <td><input type="text" class="ap_gmt form-control" /> </td>
            <td><input type="text" class="ap_country_code form-control"  /></td>
            <td><input type="text" class="ap_lat form-control" /></td>
            <td><input type="text" class="ap_long form-control" "/></td>
            <td class="ap_status status_at_1" original_content="1">
                <button class='btn btn-success'><i class="fa fa-eye"></i></button>
                <button class='btn btn-danger'><i class="fa fa-eye-slash"></i></button>
            </td>
            <td class="ap_actions">
                <button class='btn btn-warning ap_img_edit'><i class="fa fa-edit"></i></button>
                <button class='btn btn-success ap_img_save'><i class="fa fa-check"></i></button>
                <button class='btn btn-secondary ap_img_cancel'><i class="fa fa-refresh"></i></button>
                <button class='btn btn-danger ap_img_delete'><i class="fa fa-minus"></i></button>
            </td>
        </tr>
        `;
        $('#ap_table').prepend(x);
        window.lastid++;
        table_ui();
    });

}
    
    
//--- jQuery No Conflict
})(jQuery);