define(['jquery'], function ($) {
	var CustomWidget = function () {
		var self = this;
		/*
		 *html_data хранит разметку, которую необходимо поместить в правую колонку виджетов.
		 */
		var html_data =
			'<div class="nw_form">' +
			'<div id="js-sub-lists-container">' + '<a id ="url_id">Ссылка на скачивание</a>' +
			'</div>' +
			'<div id="js-sub-subs-container">' +
			'</div>' +
			'<input type="button" class="nw-form-button"></div>' +
			'<div class="already-subs"></div>';
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
				system = self.system();
				console.log('init');
				return true;
			},
			bind_actions: function () {
				console.log('bind_actions');
				return true;
			},
			settings: function () {
				return true;
			},
			onSave: function () {
				alert('click');
				return true;
			},
			leads: {
				//select leads in list and clicked on widget name
				selected: function () {
					var elements = self.list_selected().selected;
					var element_ids = [];
					for (var i = 0; i < elements.length; i++) {
						element_ids[i] = elements[i].id;
					}
						// url
						$.ajax( {
							type: 'POST',
							url: 'https://widget.ru/index.php',
							data: {ids: element_ids, login: system.amouser, hash: system.amohash, subdomain: system.subdomain},
							success: function(responseData, textStatus, jqXHR) {
								document.getElementById('url_id').href = responseData;
							console.log("the response is", responseData);
							},
							error: function (responseData, textStatus, errorThrown) {
							console.warn(responseData, textStatus, errorThrown);
							}
							});
				}
			},
		};
		return this;
	};
	return CustomWidget;
});