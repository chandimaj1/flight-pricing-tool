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
        on_edit(); // On edit button click
    })

    /**
     * 
     * Edit button
     */
    function on_edit(){
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
    }

    function ajax_save_aircraft_row(ac){
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
            },
    
            error: function(e)
            {
                console.log('Error');
                console.log(e);
                alert ('Could not update category. Error:'+e);
            }
        });
    }
    
    
    
    //--- jQuery No Conflict
    })(jQuery);