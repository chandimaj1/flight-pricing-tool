(function($) {

    var ajax_url = '/wp-content/plugins/flight_booking/ajax_php/';
    
    /**
     * 
     * Execute functions on DOM ready
     * 
     */
    $(document).ready(function() {
        console.log('FlightBooker AdminJS - Scripts Ready...');
        
        //UI functions
        row_actions(); // On edit button click
        image_uploads();//Image Uploads

        language_selects(); // Language Selects
        plugin_settings(); // Plugin settings

        theme_settings(); // Theme settings

        google_fonts(); //Google fonts
    })

    /**
     * 
     * Edit button
     */
    function row_actions(){
        $('.aircraft_row .ac_img_edit').on('click', function(){
            let parent_tr = $(this.parentElement.parentElement);
            parent_tr.addClass('editable_row');
            parent_tr.find('input').each(function(index,item){
                $(this).attr('init_val', $(this).val());
            });
        });

        $('.aircraft_row .ac_img_cancel').on('click', function(){
            let parent_tr = $(this.parentElement.parentElement);
            parent_tr.removeClass('editable_row');
            parent_tr.find('input').each(function(index,item){
                $(this).val($(this).attr('init_val'));
            });
        });

        $('.aircraft_row .ac_img_save').on('click', function(){
            let parent_tr = $(this.parentElement.parentElement);
            parent_tr.removeClass('editable_row');

            let row_id = parent_tr.attr('id');
            row_id = row_id.replace('aircraft_row','');

            let ac_id = row_id;
            let ac_name = parent_tr.find('.ac_name').val();
            let ac_desc = parent_tr.find('.ac_desc').val();
            let ac_pax_min = parent_tr.find('.ac_pax_min').val();
            let ac_pax_max = parent_tr.find('.ac_pax_max').val();
            let ac_range = parent_tr.find('.ac_range').val();
            let ac_speed = parent_tr.find('.ac_speed').val();
            let ac_per_hr_fee = parent_tr.find('.ac_per_hr_fee').val();
            let ac_per_landing_fee = parent_tr.find('.ac_landing_fee').val();
            let ac_additions = parent_tr.find('.ac_additions').val();
            let ac_ground_mins = parent_tr.find('.ac_ground_mins').val();
            let ac_interior_img = parent_tr.find('.ac_img_int').attr('img_name');
            let ac_exterior_img = parent_tr.find('.ac_img_ext').attr('img_name');

            let row_values = {
                id: ac_id,
                ac_name: ac_name,
                ac_desc: ac_desc,
                ac_pax_min: ac_pax_min,
                ac_pax_max: ac_pax_max,
                ac_range: ac_range,
                ac_speed: ac_speed,
                ac_per_hr_fee: ac_per_hr_fee,
                ac_per_landing_fee: ac_per_landing_fee,
                ac_additions: ac_additions,
                ac_ground_mins: ac_ground_mins,
                ac_interior_img: ac_interior_img,
                ac_exterior_img: ac_exterior_img
            }

            ajax_save_aircraft_row(row_values);
        });


        $('.ac_img_int .ac_img_view').on('click', function(){
            let parent_tr = $(this.parentElement.parentElement);
            let ac_name = parent_tr.find('.ac_name').val();
            let ac_interior_img = window.plugin_url+'/assets/images/aircrafts/'+parent_tr.find('.ac_img_int').attr('img_name');
            $('#ac_img_viewer .modal-title').html(ac_name+' Interior');
            $('#ac_img_viewer img').attr('src',ac_interior_img);
            $('#ac_img_viewer').modal();
        });

        $('.ac_img_ext .ac_img_view').on('click', function(){
            let parent_tr = $(this.parentElement.parentElement);
            let ac_name = parent_tr.find('.ac_name').val();
            let ac_exterior_img = window.plugin_url+'/assets/images/aircrafts/'+parent_tr.find('.ac_img_ext').attr('img_name');
            $('#ac_img_viewer .modal-title').html(ac_name+' Exterior');
            $('#ac_img_viewer img').attr('src',ac_exterior_img);
            $('#ac_img_viewer').modal();
        });
    }

    function ajax_save_aircraft_row(ac){
        $('#aircraft_row'+ac.id).addClass('redback');
        console.log('updating row...');
        console.log(ac);

        $.ajax({     
            url: ajax_url + 'save_ac_row.php',
            method: "POST",
            data: ac,
            success: function(data)
            { 
                //data = JSON.parse(data);
                console.log(data);
                $('#aircraft_row'+ac.id).removeClass('redback');
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not update category. Error:'+e);
            }
        });
    }
    

    /**
     * 
     * Upload Aircraft Image
     */
    /**
 * 
 * 
 * 
 * 
 * Upload CSV
 * 
 */
function image_uploads(){
    $('.ac_img_change').click(function(){
        let ac_img_type = $(this.parentElement).attr('class');
        let ac_row_id = $(this.parentElement.parentElement).attr('id');
            ac_row_id = ac_row_id.replace('aircraft_row','');

        $('#ac_file_upload').trigger('click');

        $('#ac_file_upload').unbind('change').change(function(){
            if ($.inArray($('#ac_file_upload').val().split('.').pop().toLowerCase(), ['jpg', 'jpeg', 'png']) == -1) {
                alert('Only jpg, jpeg & png image types are allowed.');
            }else{
                do_file_upload(ac_img_type,ac_row_id);
            }
        });
    });
}

function do_file_upload(ac_img_type,ac_row){
    $('#aircraft_row'+ac_row+' .'+ac_img_type).addClass('redback');

    const file = $('#ac_file_upload')[0].files[0];
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
        formData.append( "img_type", ac_img_type );
        formData.append( "ac_row", ac_row );
        console.log("uploading files..");

        $.ajax({
            
            type: "POST",
            url:ajax_url + 'file_upload.php',
            success: function (data) {
                console.log(data);
                let parsed_data = JSON.parse(data);
                if (parsed_data.upload=="success"){
                    $('#aircraft_row'+ac_row+' .'+ac_img_type).removeClass('redback');
                    $('#aircraft_row'+ac_row+' .'+ac_img_type).attr('img_name',parsed_data.filename);
                }else{
                    alert ('File upload unsuccessful'); 
                }
            },
            error: function (error) {
                // handle error
                console.log("file upload failed...");
                console.log(error)
                alert('file upload error. '+error);
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000
        });
    };

    //Initiate file upload
    var upload = new Upload(file);
    upload.doUpload(file);
}



/**
 * 
 * Language Selects
 */

 function language_selects(){
     $('.lang_tab_link').on('click', function(){
        $('.lang_tab_link').removeClass('btn-primary');
        $('.lang_tab_link').addClass('btn-secondary');
        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-primary');

        let lang_id = $(this).attr('lang_id');
        $('.lang_tab').removeClass('lg_active');
        $('#lang_tab'+lang_id).addClass('lg_active');
     });

     $('#update_language').on('click',function(){
        $('.lg_active').addClass('redback');
        let langs = {};
        
        let id = $('.lg_active').attr('id');
            id = id.replace('lang_tab','');

        langs['id']=id;

        let language = $('.lg_active').attr('lang_name');
        langs['language']=language;

        $('.lg_active .lang_row_set').each(function(){
            let lang_key = $(this).attr('row_key');
            langs[lang_key] = $(this).val();
        });

        console.log(langs);

        $.ajax({     
            url: ajax_url + 'save_lang_row.php',
            method: "POST",
            data: langs,
            success: function(data)
            { 
                //data = JSON.parse(data);
                console.log("Language database update :"+data);
                $('.lang_tab').removeClass('redback');
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not update language file. Error:'+e);
            }
        });
     });
 }





/**
 * 
 * 
 * Plugin settings
 */
 function plugin_settings(){
    $('#update_settings').on('click', function(){
        $('#settings_row').addClass('redback');
        console.log('updating settings...');
        
        let settings = {
            inquiry_email:$('#settings_inquiry_email').val(),
            greatcircle_api_key:$('#settings_greatcircle_api_key').val(),
            greatcircle_api_host:$('#settings_greatcircle_api_host').val(),
            fixer_api_key:$('#settings_fixer_api_key').val(),
            fixer_api_host:$('#settings_fixer_api_host').val()
        }
        console.log(settings);

        $.ajax({     
            url: ajax_url + 'save_settings_row.php',
            method: "POST",
            data: settings,
            success: function(data)
            { 
                //data = JSON.parse(data);
                console.log("Settings database update :"+data);
                $('#settings_row').removeClass('redback');
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not connect to settings update. Error:'+e);
            }
        });
    });



    $('#reset_settings').on('click', function(){
        $('#settings_row').addClass('redback');
        console.log('Resetting settings...');
        
        $('#settings_inquiry_email').val('charter@veloxaircharter.com');
        $('#settings_greatcircle_api_key').val('0dade7188emsh9333ccd18ebfa18p1df4a7jsn3e15a17341bb');
        $('#settings_greatcircle_api_host').val('greatcirclemapper.p.rapidapi.com');
        $('#settings_fixer_api_key').val('1495fc83ad76e1ecfdd2e8773e9af9a2');
        $('#settings_fixer_api_host').val('http://data.fixer.io/api/latest');


        $('#update_settings').trigger('click');
    });
 }


 /**
 * 
 * 
 * Theme settings
 */
  function theme_settings(){
    $('#update_theme_settings').on('click', function(){
        $('#themes_row').addClass('redback');
        console.log('updating Theme settings...');
        
        let settings = {
            googlefonts_api_key:$('#themes_googlefonts_api_key').val(),
            google_font:$('#settings_google_font').val(),
            tabs_font_size:$('#themes_tabs_font_size').val(),
            tabs_font_color:$('#themes_tabs_font_color').val(),
            input_fields_font_size:$('#themes_input_fields_font_size').val(),
            input_fields_font_color:$('#themes_input_fields_font_color').val(),
            input_fields_icon_backgroundcolor:$('#themes_input_fields_icon_backgroundcolor').val(),
            buttons_font_size:$('#themes_buttons_font_size').val(),
            buttons_font_color:$('#themes_buttons_font_color').val(),
            buttons_backgroundcolor:$('#themes_buttons_backgroundcolor').val(),
            buttons_hovercolor:$('#themes_buttons_hovercolor').val(),
            aircraft_category_font_size:$('#themes_aircraft_category_font_size').val(),
            aircraft_category_font_color:$('#themes_aircraft_category_font_color').val(),
            accents_background_color:$('#themes_accents_background_color').val(),
        }
        console.log(settings);

        $.ajax({     
            url: ajax_url + 'save_theme_row.php',
            method: "POST",
            data: settings,
            success: function(data)
            { 
                //data = JSON.parse(data);
                console.log("Themes database update :"+data);
                $('#themes_row').removeClass('redback');
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not connect to theme update.');
            }
        });
    });



    $('#reset_theme_settings').on('click', function(){
        $('#themes_row').addClass('redback');
        console.log('Resetting Theme settings...');
        
        $('#themes_googlefonts_api_key').val('AIzaSyBjeHBEKp__9rXvXEfCPkN6afUywdtvHAw');
        $('#settings_google_font').val('Nunito');
        $('#themes_tabs_font_size').val('16');
        $('#themes_tabs_font_color').val('#444');
        $('#themes_input_fields_font_size').val('14');
        $('#themes_input_fields_font_color').val('#898989');
        $('#themes_input_fields_icon_backgroundcolor').val('transparent');
        $('#themes_buttons_font_size').val('16');
        $('#themes_buttons_font_color').val('#fff');
        $('#themes_buttons_backgroundcolor').val('#cbcbcb');
        $('#themes_buttons_hovercolor').val('#666');
        $('#themes_aircraft_category_font_size').val('20');
        $('#themes_aircraft_category_font_color').val('#383838');
        $('#themes_accents_background_color').val('#898989');


        $('#update_theme_settings').trigger('click');
    });
 }



 /**
  * 
  *  Google fonts
  */

 function google_fonts(){
     //APi key
     const google_api_key = 'AIzaSyBjeHBEKp__9rXvXEfCPkN6afUywdtvHAw';
     const google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key='+google_api_key;
    
     $.getJSON(google_api_url, function(fonts){
         console.log(fonts);
        for (var i = 0; i < fonts.items.length; i++) {      
        $('#settings_google_font')
            .append($("<option></option>")
            .attr("value", fonts.items[i].family)
            .text(fonts.items[i].family));
        }    

        let selected_font = $('#settings_google_font').attr('selected_font');
        $('#settings_google_font').val( selected_font );
        addGoogleFont(selected_font);

        change_google_font();

        $('#settings_google_font').on('change',change_google_font);
    });
 }

    function addGoogleFont(FontName) {
        $('link[href^="https://fonts.googleapis.com/css?family="]').each(function(){ 
            $(this).remove();
        });
        $("head").append("<link href='https://fonts.googleapis.com/css?family=" + FontName + "' rel='stylesheet' type='text/css'>");
    }
    
    function change_google_font(){
        let selected_font = $('#settings_google_font').val();
        console.log('font selected: '+selected_font);
        addGoogleFont(selected_font);

        $('#sample_text').css({
            'font-family':selected_font,
            'color':'red',
        });
    }
    
    //--- jQuery No Conflict
    })(jQuery);