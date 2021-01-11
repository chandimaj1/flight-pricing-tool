(function($) {
//--- jQuery No Conflict
var ajax_url = '/wp-content/plugins/headphone_ranker/ajax_php/';

/**
 * 
 * 
 * 
 * 
 * 
 *  Add New Entry
 * 
 */

function hr_new_entry(){
    $('#hr_new_entry').click(function(){

        let hrt_addnewrow_template = `
            <tr id="hrt_add_new_row">
                <td></td>
                <td><input id="hrt_anr_rank" class="form-control" type="text"></td>
                <td><input id="hrt_anr_brand" class="form-control" type="text"></td>
                <td><input id="hrt_anr_device" class="form-control" type="text"></td>
                <td><input id="hrt_anr_price" class="form-control" type="number" placeholder="$"></td>
                <td><input id="hrt_anr_value" class="form-control" type="number"></td>
                <td><input id="hrt_anr_principle" class="form-control" type="text"></td>
                <td><input id="hrt_anr_overall_timbre" class="form-control" type="text"></td>
                <td><input id="hrt_anr_summary" class="form-control" type="text"></td>
                <td><input id="hrt_anr_ganre_focus" class="form-control" type="text"></td>
            <tr>
            <tr id="hrt_anr_controls">
                <td colspan="10" class="text-right">
                    <button type="button" id="hrt_anr_save" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    <button type="button" id="hrt_anr_cancel" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</button>
                </td>
            </tr>
        `;

        //Formatting template for each device type
        const device_type = $('#admin_product_select').val();
        
        if(device_type=="headphones"){
            //Do nothinge
        }else if( device_type=="iem" || device_type=="earbuds" ){
            hrt_addnewrow_template = hrt_addnewrow_template.replace('<td><input id="hrt_anr_principle" class="form-control" type="text"></td>','');
        }

        //Remove Edit row
        if ($('#hrt_edit_row').length>0){
            $('#hrt_edit_row').remove();
        }
        if ($('#hrt_edit_controls').length>0){
            $('#hrt_edit_controls').remove();
        }

        if($('#hranker_table tbody tr').eq(0).attr('id') != "hrt_add_new_row"){
            $('#hranker_table tbody').prepend(hrt_addnewrow_template);
            //Refresh event listners
            $('#hrt_anr_save').unbind('click').bind('click',save_new_row);
            $('#hrt_anr_cancel').unbind('click').bind('click',delete_new_row);
            //Remove UI lock
            $('#hranker_table tbody').removeClass('hr_locked');
        }

        
    });
} 


/**
 * 
 * Delete new row from table
 */
function delete_new_row(){
    $('#hrt_anr_controls').remove();
    $('#hrt_add_new_row').remove();

    $('#hranker_table tbody').addClass('hr_locked');
}


/**
 * 
 * Save new row to the database
 */
function save_new_row(){
    console.log('saving new row in the databse');
    $('#hranker_table thead').addClass('hr_locked');
    $('#hranker_table tbody').addClass('hr_locked');
    hr_status('secondary','Saving new row in database...');

    let ajax_data = {};
    //DEVICE SPECIFIC VALUES PASSED
    const device_type = $('#admin_product_select').val();
    
    //Headphones
    if(device_type=="headphones"){
        ajax_data = {
            "table":$('#admin_product_select').val(),
            "rank" :$('#hrt_anr_rank').val(),
            "brand" :$('#hrt_anr_brand').val(),
            "device" :$('#hrt_anr_device').val(),
            "price" :$('#hrt_anr_price').val(),
            "value" :$('#hrt_anr_value').val(),
            "principle" :$('#hrt_anr_principle').val(),
            "overall_timbre" :$('#hrt_anr_overall_timbre').val(),
            "summary" :$('#hrt_anr_summary').val(),
            "ganre_focus" :$('#hrt_anr_ganre_focus').val()
        }
    //iem
    }else if( device_type=="iem" || device_type=="earbuds" ){
        ajax_data = {
            "table":$('#admin_product_select').val(),
            "rank" :$('#hrt_anr_rank').val(),
            "brand" :$('#hrt_anr_brand').val(),
            "device" :$('#hrt_anr_device').val(),
            "price" :$('#hrt_anr_price').val(),
            "value" :$('#hrt_anr_value').val(),
            "overall_timbre" :$('#hrt_anr_overall_timbre').val(),
            "summary" :$('#hrt_anr_summary').val(),
            "ganre_focus" :$('#hrt_anr_ganre_focus').val()
        }
    }

    //Validation
    let all_filled = true;
    
    for(key in ajax_data){
        let term = ajax_data[key];
        console.log(term);
        if(term=='' || typeof term == 'undefinded'){
            all_filled = false;
        }
    }
    
    if (all_filled){
        $.ajax({     
            type: "POST",
            crossDomain: true,
            url:ajax_url+'add_new_record.php',
            data :ajax_data,
    
            success: function(data)
            {   
                console.log(data);
                data = JSON.parse(data);
                if (data=="success"){
                    console.log("Settings saved successfully...");
                    hr_status('success','Settings saved !...');
                    refresh_table();
                }else{
                    console.log("Error... ");
                    console.log(data);
                    hr_status('secondary','Error. Reason:'+data.msg);
                    $('#hranker_table thead').removeClass('hr_locked');
                    $('#hranker_table tbody').removeClass('hr_locked');
                }
            },
    
            error: function(e)
            {
                console.log(e);
            }
        });
    }else{
        $('#hranker_table thead').removeClass('hr_locked');
        $('#hranker_table tbody').removeClass('hr_locked');
        hr_status('danger','All fields must be filled...')
    }
    

}







/**
 * 
 * 
 * 
 * 
 * Upload CSV
 * 
 */
function hr_upload_csv(){
    $('#hr_upload_csv').click(function(){
        hr_status('secondary','Select CSV & Click Upload.');
        $('#csv_file').trigger('click');

        $('#csv_file').unbind('change').change(function(){
            if ($.inArray($('#csv_file').val().split('.').pop().toLowerCase(), ['csv']) == -1) {
                hr_status('danger','Only CSV Files are allowed.');
            }else{
                do_file_upload();
            }
        });
    });
}

function do_file_upload(){

    const file = $('#csv_file')[0].files[0];
    /**
     * 
     * File Upload Script
     */
    let Upload = function (file) {
        this.file = file;
    };

    Upload.prototype.getType = function() {
        return this.file.type;
    };
    Upload.prototype.getSize = function() {
        return this.file.size;
    };
    Upload.prototype.getName = function() {
        return this.file.name;
    };

    Upload.prototype.doUpload = function (file) {

        var that = this;
        var formData = new FormData();

        // add assoc key values, this will be posts values
        formData.append("file", this.file, this.getName());
        formData.append("upload_file", true);
        formData.append( "table", $('#admin_product_select').val() );
        console.log("uploading files..");

        $.ajax({
            
            type: "POST",
            url:ajax_url + 'file_upload.php',
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', that.progressHandling, false);
                }
                return myXhr;
            },
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                console.log("file uploaded...");

                if(data.upload=="success"){
                    hr_status('success','CSV Uploaded. Inserted:'+data.db_msg.totalInserted+' of '+data.db_msg.totalInCSV);

                    setTimeout(function(){
                        refresh_table();
                    },5000);
                }
            },
            error: function (error) {
                // handle error
                console.log("file upload failed...");
                console.log(error)
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000
        });
    };

    Upload.prototype.progressHandling = function (event) {
        var percent = 0;
        var position = event.loaded || event.position;
        var total = event.total;
        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        // update progressbars classes so it fits your code
        $("#hr_message:before").css("content", percent + "% ");
    };

    //Initiate file upload
    var upload = new Upload(file);
    upload.doUpload(file);
}




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




/**
 * 
 * 
 *  Listen for product category change
 */
function hr_category_change(){
    $('#admin_product_select').change(function(){
        const hr_showing =$(this).val();
        $('#hranker_table').attr('hr_showing',hr_showing);

        if(hr_showing=="headphones"){
            //$('#hr_device_name').text('Device');
            $('#hr_search_term').attr('data-original-title','Search by Model, Principle or Genre');

        }else if(hr_showing=="iem"){
            //$('#hr_device_name').text('Device');
            $('#hr_search_term').attr('data-original-title','Search by Device or genre');

        }else if(hr_showing=="earbuds"){
            //$('#hr_device_name').text('Device');
            $('#hr_search_term').attr('data-original-title','Search by Device or genre');
        }
        refresh_table();
        fetch_frontend_html();
    });
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

        $('#hrt_er_save, #hrt_er_cancel')
        .addClass('hr_locked')
        .addClass('hr_hidden');
        $('#hr_edit_selected, #hr_delete_selected')
            .removeClass('hr_locked')
            .removeClass('hr_hidden');

        get_table_results();
}





/**
 * 
 * 
 * Listen for row select
 */
function hr_table_rowselect(){
    $('#hranker_table td.hrt_select input').off('change').on('change',function(){
        $('#hranker_table td.hrt_select input').parent().parent().removeClass('selected_row');
        $('#hranker_table td.hrt_select input:checked').parent().parent().addClass('selected_row');

        $('#hr_delete_selected').addClass('hr_locked');
        $('#hr_edit_selected').addClass('hr_locked');

       if($('#hranker_table td.hrt_select input:checked').length >= 1){
            $('#hr_edit_selected').removeClass('hr_locked');
            $('#hr_delete_selected').removeClass('hr_locked');
        }
        //If all checked
        if(  $('#hranker_table td.hrt_select input:checked').length == $('#hranker_table td.hrt_select input').length  ){
            $('#hrt_select_all').prop('checked',true);
        }else{
            $('#hrt_select_all').prop('checked',false);
        }
    });

    //Select All
    $('#hrt_select_all').unbind('change').change(function(){
        if( $('#hrt_select_all').prop('checked') ){
            $('#hranker_table td.hrt_select input').prop('checked',true);
        }else{
            $('#hranker_table td.hrt_select input').prop('checked',false);
        }
        $('#hranker_table td.hrt_select input').trigger('change');
    });
}




/***
 * 
 * 
 * Listen for Delete row
 */
function hr_delete_selected_rows(){
    $('#hr_delete_selected').off('click').on('click',function(){
        hr_status('secondary','Deleting rows...');

        let selected_ids = [];
        $('#hranker_table tbody .selected_row').each(function(){
            selected_ids.push(  parseInt( $(this).attr('id') )  );
        });

        if(selected_ids.length>0){
            $('#delete_records_no').html(selected_ids.length);
            $('#modal_confirm_delete').modal('show');
            $('#confirmed_delete').off('click').on('click',function(){
                delete_ids_from_database(selected_ids);
                $('#modal_confirm_delete').modal('hide');
            });
            $('#confirmed_delete_cancel').off('click').on('click',function(){
                $('#modal_confirm_delete').modal('hide');
                hr_status('danger','0 Records deleted !');
            });
            
        }else{
            hr_status('danger','No selected rows to delete...');
        }
    })
}

/***
 * 
 * Delete Ids from Database
 */
function delete_ids_from_database(selected_ids){
    $('#hranker_table thead').addClass('hr_locked');
    $('#hranker_table tbody').addClass('hr_locked');
    $('#hr_edit_selected').addClass('hr_locked');
    $('#hr_delete_selected').addClass('hr_locked');

    $.ajax({     
        type: "POST",
        crossDomain: true,
        url:ajax_url+'delete_records.php',
        data :{
            "ids":selected_ids,
            "table":$('#admin_product_select').val(),
        },

        success: function(data)
        {   
            console.log(data);
            data = JSON.parse(data);
            if (data=="success"){
                console.log("Records deleted successfully...");
                hr_status('success','Records Deleted !...');
                get_table_results();
            }else{
                console.log("Error... ");
                console.log(data);
                hr_status('secondary','Error. Reason:'+data.msg);
                $('#hranker_table thead').removeClass('hr_locked');
                $('#hranker_table tbody').removeClass('hr_locked');
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
 * Edit Records
 */
function hr_edit_selected_row(){
    $('#hr_edit_selected').off('click').on('click',function(){
        hr_status('secondary','Edit record...');
        
        $('.hrt_edit_row').remove();

        //Remove add new element row
        if ($('#hrt_add_new_row').length>0){
            $('#hrt_add_new_row').remove();
        }
        if ($('#hrt_anr_controls').length>0){
            $('#hrt_anr_controls').remove();
        }

        $('tr.selected_row').each(function(){
            const selected_row = $(this);
            let edit_selected_row_template = edit_a_row(selected_row);
            $(edit_selected_row_template).insertAfter(selected_row);
            $(selected_row).remove();
        });

        //Remove UI lock
        $('#hranker_table tbody').removeClass('hr_locked');

        //Activate save and cancel buttons
        $('#hrt_er_save, #hrt_er_cancel')
            .removeClass('hr_locked')
            .removeClass('hr_hidden');
        $('#hr_edit_selected, #hr_delete_selected')
            .addClass('hr_locked')
            .addClass('hr_hidden');
        //Refresh event listners
        $('#hrt_er_save').off('click').on('click',save_edited_row);
        $('#hrt_er_cancel').off('click').on('click',cancel_edit_row);
    });
}

function edit_a_row(selected_row){

     //Formatting template for each device type
     const device_type = $('#admin_product_select').val();
        
     let principle_td = '';
     
     if(device_type=="headphones"){
        principle_td = `<td><input id="hrt_er_principle" class="form-control" type="text" value="`+edit_row_seperate_fields( selected_row.children('.hrt_principle').html() )+`"></td>`;
     }else if( device_type=="iem" || device_type=="earbuds" ){
         principle_td ='';
     }


    let edit_selected_row_template =
        `<tr class="hrt_edit_row" edit_row_id="`+selected_row.attr('id')+`">
            <td></td>
            <td><input id="hrt_er_rank" class="form-control" type="text" value="`+selected_row.children('.hrt_rank').text()+`"></td>
            <td><input id="hrt_er_brand" class="form-control" type="text" value="`+selected_row.children('.hrt_brand').text()+`"></td>
            <td><input id="hrt_er_device" class="form-control" type="text" value="`+selected_row.children('.hrt_device').text()+`"></td>
            <td><input id="hrt_er_price" class="form-control" type="number" placeholder="$" value="`+selected_row.children('.hrt_price').text()+`"></td>
            <td><input id="hrt_er_value" class="form-control" type="number" value="`+selected_row.children('.hrt_value').text()+`"></td>
            `+principle_td+`
            <td><input id="hrt_er_overall_timbre" class="form-control" type="text" value="`+edit_row_seperate_fields( selected_row.children('.hrt_overall_timbre').html() )+`"></td>
            <td><input id="hrt_er_summary" class="form-control" type="text" value="`+selected_row.children('.hrt_summary').text()+`"></td>
            <td><input id="hrt_er_ganre_focus" class="form-control" type="text" value="`+edit_row_seperate_fields( selected_row.children('.hrt_ganre_focus').html() )+`"></td>
        <tr>
       `;

       

        return edit_selected_row_template;
}

//Re format in span values to comma seperated values for editing
 function edit_row_seperate_fields(field){
     field = field.replaceAll('</span><span>',', ');
     field = field.replaceAll('<span>','');
     field = field.replaceAll('</span>','');

     return (field);
 }
/**
 * 
 *  Save edited row
 */
function save_edited_row(){
    console.log('saving edited row in the databse');
    $('#hranker_table thead').addClass('hr_locked');
    $('#hranker_table tbody').addClass('hr_locked');
    hr_status('secondary','Saving edited row in database...');

    let values = [];
    
    //DEVICE SPECIFIC VALUES PASSED
    const device_type = $('#admin_product_select').val();

    //Headphones
    $('.hrt_edit_row').each(function(){
        let values_data = {};
        if(device_type=="headphones"){
            values_data = {
                "table":$('#admin_product_select').val(),
                "id":$(this).attr('edit_row_id'),
                "rank" :$(this).find('#hrt_er_rank').val(),
                "brand" :$(this).find('#hrt_er_brand').val(),
                "device" :$(this).find('#hrt_er_device').val(),
                "price" :$(this).find('#hrt_er_price').val(),
                "principle" :$(this).find('#hrt_er_principle').val(),
                "value" :$(this).find('#hrt_er_value').val(),
                "overall_timbre" :$(this).find('#hrt_er_overall_timbre').val(),
                "summary" :$(this).find('#hrt_er_summary').val(),
                "ganre_focus" :$(this).find('#hrt_er_ganre_focus').val()
            }
        //iem or earbuds
        }else if( device_type=="iem" || device_type=="earbuds" ){
            values_data = {
                "table":$('#admin_product_select').val(),
                "id":$(this).attr('edit_row_id'),
                "rank" :$(this).find('#hrt_er_rank').val(),
                "brand" :$(this).find('#hrt_er_brand').val(),
                "device" :$(this).find('#hrt_er_device').val(),
                "price" :$(this).find('#hrt_er_price').val(),
                "value" :$(this).find('#hrt_er_value').val(),
                "overall_timbre" :$(this).find('#hrt_er_overall_timbre').val(),
                "summary" :$(this).find('#hrt_er_summary').val(),
                "ganre_focus" :$(this).find('#hrt_er_ganre_focus').val()
            }
        }
        
        values.push(values_data);
    });
    
    let ajax_data = {
        data:values
    };

    let all_filled = true;
    if (all_filled){
        $.ajax({     
            type: "POST",
            crossDomain: true,
            url:ajax_url+'save_edited_row.php',
            data :ajax_data,
    
            success: function(data)
            {   
                console.log(data);
                data = JSON.parse(data);
                
                let no_rows = 0;
                let no_success_rows = 0;
                
                for (let i in data) {
                    console.log(i, data[i]);

                    no_rows++;
                    if (data[i].msg == "success"){
                        $('tr[edit_row_id='+data[i].rowid+']').removeClass('hrt_edit_row').addClass('hrt_saved_row');
                        no_success_rows++
                    }
                }

                console.log("no_success_rows: "+no_success_rows);
                console.log("no_rows: "+no_rows);

                if ( no_success_rows == no_rows ){
                    console.log("All "+no_rows+" edits saved successfully...");
                    hr_status('success','All  '+no_rows+' edits saved !...');
                    $('#hrt_er_save, #hrt_er_cancel')
                    .addClass('hr_locked')
                    .addClass('hr_hidden');
                    $('#hr_edit_selected, #hr_delete_selected')
                        .removeClass('hr_locked')
                        .removeClass('hr_hidden');
                    get_table_results();
                }else if( no_success_rows>0 ){
                    console.log("Only "+no_success_rows+ " of "+no_rows+" Saved Successfully");
                    hr_status('success',"Only "+no_success_rows+ " of "+no_rows+" Saved");
                    //get_table_results();
                }else{
                    console.log("Error... ");
                    console.log(data);
                    hr_status('secondary','Error. Reason:'+data.msg);
                    $('#hranker_table thead').removeClass('hr_locked');
                    $('#hranker_table tbody').removeClass('hr_locked');
                }
            },
    
            error: function(e)
            {
                console.log(e);
            }
        });
    }else{
        $('#hranker_table thead').removeClass('hr_locked');
        $('#hranker_table tbody').removeClass('hr_locked');
        hr_status('danger','All fields must be filled...')
    }
    
}





/**
 * 
 * 
 *  Cancel Edited Row
 */
function cancel_edit_row(){
    refresh_table();
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
            <td class="hrt_select"><input type="checkbox" class="form_control"/></td>
            <td class="hrt_rank">`+item.rank+`</td>
            <td class="hrt_brand">`+item.brand+`</td>
            <td class="hrt_device">`+item.device+`</td>
            <td class="hrt_price">`+item.price+`</td>
            <td class="hrt_value">`+item.value+`</td>`
            +row_principle+
            `<td class="hrt_overall_timbre">`+format_comma_seperated_text(item.overall_timbre)+`</td>
            <td class="hrt_summary">`+item.summary+`</td>
            <td class="hrt_ganre_focus">`+format_comma_seperated_text(item.ganre_focus)+`</td>
        </tr>`;

        
        
        $('#hranker_table tbody').append(row);
        $('#hranker_table').removeClass('hr_locked');

        //refresh row select event listners
        hr_table_rowselect();
        hr_delete_selected_rows();
        hr_edit_selected_row();
    });  
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
 
    $('#filter_brand, #filter_principle, #filter_genre').off('change').on('change',function(){
        $('#hr_search_term').val('');    
        get_table_results();
    });

    $('#hr_price_from, #hr_price_to').off('change').on('change',function(){
        if( $('#hr_price_from').val() >=0 && $('#hr_price_to').val() >0 && $('#hr_price_to').val()>$('#hr_price_from').val() ){
            get_table_results();
        }
    });


    //Select 2
    //$("#filter_principle, #filter_genre").select2("destroy");
    //$("#filter_principle, #filter_genre").select2();
}



/**
 * 
 * 
 *  Listen for sort
 */
function hr_listen_sort(){
    $('.hr_sort').on('click',function(){
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
               $('#frontend_html').html(data.result);
               $('#hr_category_html').off('click').on('click',function(){
                    update_frontend_html();
               });
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

    function update_frontend_html(){
        let table = $('#admin_product_select').val();
        let html = $('#frontend_html').val();

        hr_status('secondary','Updating HTML...');

        $.ajax({     
            type: "POST",
            crossDomain: true,
            url:ajax_url+'update_frontend_html.php',
            data :{
                table:table,
                html: html,
            },

            success: function(data)
            {   
                console.log(data);
                try {
                    data = JSON.parse(data);
                }
                catch (e) {
                    console.log("error: "+e);
                };

                if (data=="success"){
                    console.log(data);
                    console.log("Update success...");
                    hr_status('success','FrontEnd HTML updated.');
                    fetch_frontend_html();
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
                $('#banner_image1 img').attr('src',ajax_url+'../assets/img/'+data.banner1);
                $('#banner_image1 img').attr('file_name',data.banner1);
                $('#banner_image1 .bannerlink').val(data.url1);
                $('#banner_image2 img').attr('src',ajax_url+'../assets/img/'+data.banner2);
                $('#banner_image2 img').attr('file_name',data.banner2);
                $('#banner_image2 .bannerlink').val(data.url2);
                $('#banner_image3 img').attr('src',ajax_url+'../assets/img/'+data.banner3);
                $('#banner_image3 img').attr('file_name',data.banner3);
                $('#banner_image3 .bannerlink').val(data.url3);
                $('#banner_image4 img').attr('src',ajax_url+'../assets/img/'+data.banner4);
                $('#banner_image4 img').attr('file_name',data.banner4);
                $('#banner_image4 .bannerlink').val(data.url4);
                

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


function hr_upload_banner_img(){
    $('.small_banner .btn-primary').click(function(){
        hr_status('secondary','Select Banner image & click upload.');
        $('#banner_img_file').trigger('click');

        let change_image_of = $(this.parentElement).attr('id');

        $('#banner_img_file').off('change').on('change',function(){
            if ($.inArray($('#banner_img_file').val().split('.').pop().toLowerCase(), ['jpg', 'png']) == -1) {
                hr_status('danger','Only jpg and png files are allowed.');
            }else{
                do_banner_image_upload(change_image_of);
            }
        });
    });

    $('.small_banner .btn-danger').click(function(){
        let plugin_url = $('#hr_pluginurl').val();
        $(this.parentElement).find('img').attr('src',plugin_url+'assets/img/small_banner_default.jpg');
        $(this.parentElement).find('img').attr('file_name','small_banner_default.jpg');
    });
}

function do_banner_image_upload(banner_image_id){

    const file = $('#banner_img_file')[0].files[0];
    /**
     * 
     * File Upload Script
     */
    let Upload = function (file) {
        this.file = file;
    };

    Upload.prototype.getType = function() {
        return this.file.type;
    };
    Upload.prototype.getSize = function() {
        return this.file.size;
    };
    Upload.prototype.getName = function() {
        return this.file.name;
    };

    Upload.prototype.doUpload = function (file) {

        var that = this;
        var formData = new FormData();

        // add assoc key values, this will be posts values
        formData.append("file", this.file, this.getName());
        formData.append("upload_file", true);
        formData.append("image_id", banner_image_id);
        console.log("uploading banner img..");

        $.ajax({
            
            type: "POST",
            url:ajax_url + 'banner_image_upload.php',
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', that.progressHandling, false);
                }
                return myXhr;
            },
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                console.log("file uploaded...");

                if(data.upload=="success"){
                    hr_status('success','Image Uploaded. Save to apply changes.');
                    $('#'+banner_image_id+' img').attr('src',ajax_url+'../assets/img/'+data.file_name);
                    $('#'+banner_image_id+' img').attr('file_name',data.file_name);
                    $('#banner_img_file').val('');
                }
            },
            error: function (error) {
                // handle error
                console.log("file upload failed...");
                console.log(error)
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000
        });
    };

    Upload.prototype.progressHandling = function (event) {
        var percent = 0;
        var position = event.loaded || event.position;
        var total = event.total;
        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        // update progressbars classes so it fits your code
        $("#hr_message:before").css("content", percent + "% ");
    };

    //Initiate file upload
    var upload = new Upload(file);
    upload.doUpload(file);
}

/**
 * 
 * 
 * 
 * 
 * Save Banner info
 */
function hr_update_banner(){

    $('#hr_save_banner').click(function(){
        let banner_image1 = $('#banner_image1 img').attr('file_name');
        let banner_image2 = $('#banner_image2 img').attr('file_name');
        let banner_image3 = $('#banner_image3 img').attr('file_name');
        let banner_image4 = $('#banner_image4 img').attr('file_name');
        let banner_url1 = $('#banner_image1 .bannerlink').val();
        let banner_url2 = $('#banner_image2 .bannerlink').val();
        let banner_url3 = $('#banner_image3 .bannerlink').val();
        let banner_url4 = $('#banner_image4 .bannerlink').val();

        hr_status('secondary','Updating Banner...');

        $.ajax({     
            type: "POST",
            crossDomain: true,
            url:ajax_url+'update_banner.php',
            data :{
                banner_image1: banner_image1,
                banner_image2: banner_image2,
                banner_image3: banner_image3,
                banner_image4: banner_image4,
                banner_url1: banner_url1,
                banner_url2: banner_url2,
                banner_url3: banner_url3,
                banner_url4: banner_url4
            },

            success: function(data)
            {   
                console.log(data);
                try {
                    data = JSON.parse(data);
                }
                catch (e) {
                    console.log("error: "+e);
                };

                if (data=="success"){
                    console.log(data);
                    console.log("Update success...");
                    hr_status('success','Banner updated.');
                    
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

 // --- Execute Admin page specific functions   
 if($('#isadminpage').val()=="true"){
     //Event Listeners
     hr_new_entry(); // new entry click
     hr_category_change();// category select change
     hr_listen_sort(); // Sorting
     hr_upload_csv(); // CSV upload
     hr_search(); // Search
     cancel_search();// Cancel Search
     hr_upload_banner_img();//Banner Image upload
     hr_update_banner();// Update banner
 
     //Onload fetch results
     get_table_results();

     //Enable Tooltips
     $('[data-toggle="tooltip"]').tooltip();

     //Select 2
     //$("#filter_principle, #filter_genre").select2();

     fetch_frontend_html();
     fetch_banner_info();
 }
   
})

//--- jQuery No Conflict
})(jQuery);