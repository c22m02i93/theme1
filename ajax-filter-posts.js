jQuery(document).ready(function($) {
	
		//$('.taguniq').click( function(event) { //По нажатию на кнопку
		$('.filter').on('change', function () {  //При изменении формы

		// Prevent defualt action - opening tag page
		if (event.preventDefault) {
			event.preventDefault();
		} else {
			event.returnValue = false;
		}

		// Get tag slug from title attirbute

	    var category = $(this).attr('title'); //Чтобы брать из этой же категории
	    var sortValues = $("#country option:selected").val();
	    var sortValues2 = $("#power option:selected").val();
	    var sortValues3 = $("#date-after").val();
	    var sortValues4 = $("#date-before").val();
	    var sortValues4 = $("#date-before").val();

	  
	    var arrList = $('.metka:checkbox:checked').map(function(){
    		return $(this).attr('value');
		}).get();
		var tags = arrList.join(', '); // преобразовываем массив в строку с разделителем ' '
		
		//alert (tags);
		
		var arrList = $('.label:checkbox:checked').map(function(){
    		return $(this).attr('value');
		}).get();
		var labels = arrList.join(', ');
			

		$('.tagged-posts').fadeOut();

		data = {
			action: 'filter_posts',
			afp_nonce: afp_vars.afp_nonce,
		    category: category,
			taxonomy: sortValues,
		    taxonomy2: sortValues2,
		    date: sortValues3,
		    date2: sortValues4,
		    tags: tags,
		  	labels:labels,
		};
	  
	  
		$.ajax({
			type: 'post',
			dataType: 'html',
			url: afp_vars.afp_ajax_url,
			data: data,
			success: function( data, textStatus, XMLHttpRequest ) {
				$('.tagged-posts').html( data );
				$('.tagged-posts').fadeIn();
				console.log( textStatus );
				console.log( XMLHttpRequest );
			},
			error: function( MLHttpRequest, textStatus, errorThrown ) {
				console.log( MLHttpRequest );
				console.log( textStatus );
				console.log( errorThrown );
				$('.tagged-posts').html( 'No posts found' );
				$('.tagged-posts').fadeIn();
			}
		})

	});
  
  
  
  	$('#all').click( function(event) {
	  

		// Получаем данные из различных атрибутов
	    var selecetd_cat = $('#curcat').attr('title');
 

		$('.tagged-posts').fadeOut();

		data = {
			action: 'filter_posts',
			afp_nonce: afp_vars.afp_nonce,
		    category: selecetd_cat, //Не передаем лишних параметров
		};
	  

		$.ajax({
			type: 'post',
			dataType: 'html',
			url: afp_vars.afp_ajax_url,
			data: data,
			success: function( data, textStatus, XMLHttpRequest ) {
				$('.tagged-posts').html( data );
				$('.tagged-posts').fadeIn();
				console.log( textStatus );
				console.log( XMLHttpRequest );
			},
			error: function( MLHttpRequest, textStatus, errorThrown ) {
				console.log( MLHttpRequest );
				console.log( textStatus );
				console.log( errorThrown );
				$('.tagged-posts').html( 'No posts found' );
				$('.tagged-posts').fadeIn();
			}
		})
	  
	  
    });
  
});