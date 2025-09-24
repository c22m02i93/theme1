(function ($) {
    $(document).ready(function () {
        $('#search-input').on('input', function () {
            var query = $(this).val();

            if (query.length >= 3) {
                $.ajax({
                    type: 'GET',
                    url: ajax_search_object.ajax_url,
                    data: {
                        action: 'ajax_title_content_search',
                        query: query
                    },
                    success: function (response) {
                        $('#search-results').html(response);
                    },
                    error: function () {
                        $('#search-results').html('Ошибка при запросе.');
                    }
                });
            } else {
                $('#search-results').html('');
            }
        });
    });
})(jQuery);
