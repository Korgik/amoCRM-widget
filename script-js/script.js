define(['jquery'], function ($) {
	var CustomWidget = function () {
		var self = this;
		/*
		 *html_data хранит разметку, которую необходимо поместить в правую колонку виджетов.
		 */
		var html_data =
			'<div class="nw_form">' +
			'<div id="w_logo">' +
			'<img src="images/logo.png" id="firstwidget_image"></img>' +
			'</div>' +
			'<div id="js-sub-lists-container">' + '<a id ="url_id">Ссылка на скачивание</a>' +
			'</div>' +
			'<div id="js-sub-subs-container">' +
			'</div>' +
			'<input type="button" class="nw-form-button"></div>' +
			'<div class="already-subs"></div>';
		// this.get_lcard_info = 
		// this.send_info = function () {

		// };

		this.callbacks = {
			render: function () {
				self.render_template(
					{
						caption: {
							class_name: 'test_widget', //имя класса для обертки разметки
						},
						body: html_data,//разметка
						render: '' //шаблон не передается
					}
				);
				return true;

			},
			init: function () {
				console.log('init');
				return true;
			},
			bind_actions: function () {
				console.log('bind_actions');
				return true;
			},
			settings: function () {
				// self.set_settings({ par1: "text" });
				return true;
			},
			onSave: function () {
				alert('click');
				return true;
			},
			leads: {
				//select leads in list and clicked on widget name
				// сюда заносим id
				selected: function () {
					var elements = self.list_selected().selected;
					var element_ids = [];

					for (var i = 0; i < elements.length; i++){
						element_ids[i] = elements[i].id;
					}
					// var id = data[{id}];
						console.log(element_ids);
						// console.log(id);
						// здесь ссылка на локалхост?
						// url
						$.ajax({
							type: 'POST',
							url: 'https://widget.ru/index.php',
							data: {ids: element_ids},
							// dataType: 'json',
							success: function(responseData, textStatus, jqXHR)
							{
								document.getElementById('url_id').href = responseData;
							console.log("the response is", responseData);
							},
							error: function (responseData, textStatus, errorThrown)
							{
							console.warn(responseData, textStatus, errorThrown);
							// alert('JSONP failed - ' + textStatus);
							}
							});

							// $.ajax({
							// 	type: 'POST',
							// 	url: 'https://widget.ru/send.php',
							// 	// data: {ids: element_ids},
							// 	// dataType: 'json',
							// 	success: function(responseData, textStatus, jqXHR)
							// 	{
							// 	// $.fileDownload('https://widget.ru/'+responseData)
    						// 	// 	.done(function () { alert('File download a success!'); })
    						// 	// 	.fail(function () { alert('File download failed!'); });
							// 	console.log("Отправка файла", responseData);
							// 	},
							// 	error: function (responseData, textStatus, errorThrown)
							// 	{
							// 	console.warn(responseData, textStatus, errorThrown);
							// 	// alert('JSONP failed - ' + textStatus);
							// 	}
							// 	});
					// request.done(function(msg){
					// 	$("#log").html( msg );
					// })
					// request.fail(function(jqXHR, textStatus) {
					// 	alert( "Request failed: " + textStatus );
					// });

				}
			},
		};
		return this;
	};

	return CustomWidget;
});