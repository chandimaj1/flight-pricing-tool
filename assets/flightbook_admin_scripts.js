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
    $('#aircraft_row'+ac_row+' '+ac_img_type).addClass('redback');

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
    
    
    //--- jQuery No Conflict
    })(jQuery);