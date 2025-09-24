(document).ready(function() {
    jQuery('#search_submit', '#my_search_wrapper').on('click', function() {
 
        // Instantiate our filter var
        var filter = [];
 
        // Get the values
        var s_keywords = jQuery('#keyword_search', '#my_search_wrapper').val();
        var s_date_from = jQuery('#date_from', '#my_search_wrapper').val();
        var s_date_to = jQuery('#date_to', '#my_search_wrapper').val();
 
        // If keywords exist, push to filter
        if (s_keywords != '') {
            filter.push({
                s_keywords: s_keywords
            });
        }
 
        // If date from exists, push to filter
        if (s_date_from != '') {
            filter.push({
                s_date_from: s_date_from
            });
        }
 
        // If date to exists, push to filter
        if (s_date_to != '') {
            filter.push({
                s_date_to: s_date_to
            });
        }
 
        // Output the results in the console
        console.log(JSON.stringify(filter));
 
        // Send to my_search_results
        var Ajax = {
            ajaxurl: "/wp-admin/admin-ajax.php"
        };
        jQuery.post( Ajax.ajaxurl, {
            filter: filter,
            action: 'my_search_results'
        }, function(res) {
 
            // Return the results from the function
            jQuery('#my_results', '#my_search_wrapper').html(res);
        });
    });
});