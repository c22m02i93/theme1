(function ($) {
    $(document).ready(function () {
        $('#date-search-form').on('submit', function (e) {
            e.preventDefault();

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            $.ajax({
                type: 'GET',
                url: ajax_object.ajax_url,
                data: {
                    action: 'date_range_search',
                    start_date: start_date,
                    end_date: end_date
                },
                success: function (response) {
                    $('#search-results').html(response);
                },
                error: function () {
                    $('#search-results').html('Ошибка при запросе.');
                }
            });
        });
    });
})(jQuery);
