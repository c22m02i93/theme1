$(document).ready(function() {
    $('#search_by_date').on('click', function() {
        var nonce = $('#date_search_nonce').val(); // Получаем nonce
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var paged = 1; // Например, страница пагинации

        $.ajax({
            url: ajaxurl, // Используем глобальный ajaxurl
            type: 'POST',
            data: {
                action: 'custom_date_search',
                nonce: nonce, // Добавляем nonce для безопасности
                start_date: start_date,
                end_date: end_date,
                paged: paged,
            },
            success: function(response) {
                $('#results').html(response); // Отображаем результаты в нужном элементе
            },
            error: function() {
                console.log('Ошибка в AJAX-запросе');
            },
        });
    });
});
