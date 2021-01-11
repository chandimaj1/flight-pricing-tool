(function($) {
   
    
    /**
     * 
     * Admin page settings save
     * 
     */
    function sn_admin_settings_test() {
        $('#sn_save_settings .sn_save').click(function(){
            sn_test_settings();
        });   
    }
    function sn_test_settings(){
    
        let sn_data_url = $('#sn_data_url').val();
        let sn_data_host = $('#sn_data_host').val();
        let sn_data_key = $('#sn_data_key').val();
        let sn_data_query = $('#sn_data_query').val();
        if( typeof sn_data_query === 'undefined' || sn_data_query==''){
            sn_data_query = '';
        }else{
            sn_data_query = '/'+sn_data_query;
        }
    
    
            $.ajax({     
                url: sn_data_url + sn_data_query,
                method: "GET",
                "timeout": 0,
                "headers": {
                  "x-rapidapi-key": sn_data_key,
                  "x-rapidapi-host": sn_data_host,
                  "useQueryString": "true"
                },
                success: function(data)
                {   

                        $('#sn_data_result').val(data);
                                            
                    data = JSON.parse(data);
                    console.log(data);
                },
        
                error: function(e)
                {
                    $('#sn_data_result').val('Error:'+e);
                }
            });
    
    }
    
    
    /**
     * 
     * Execute functions on DOM ready
     * 
     */
    $(document).ready(function() {
        console.log('FlightBooker AdminJS - Scripts Ready...');
        sn_admin_settings_test();
    })
    //--- jQuery No Conflict
    })(jQuery);