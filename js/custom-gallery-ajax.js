jQuery(document).ready(function ($) {
    // Добавляем обработчик события клика по кнопке поиска
    $(document).on('click', '#search-button', function (e) {
        e.preventDefault();

        // Получаем значение выбранной даты из поля ввода
        var selectedDate = $('#date-input').val();

        // Отправляем AJAX-запрос на сервер
        $.ajax({
            url: myAjax.ajaxurl,
            type: 'post',
            data: {
                action: 'gallery_date_search',
                date: selectedDate,
            },
            beforeSend: function () {
                // Добавляем индикатор загрузки или что-то подобное
                $('#gallery-list').html('<p>Loading...</p>');
            },
            success: function (response) {
                // Обновляем содержимое списка галерей с помощью полученных данных
                $('#gallery-list').html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Выводим сообщение об ошибке в консоль
                console.error(textStatus + ': ' + errorThrown);
            }
        });
    });
});
