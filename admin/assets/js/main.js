(function() {
	var Button = function() {
		return {
			formVisible: false
		}
	};

	var formHandler = function() {
		var btnIds = '#album_form_btn,#song_form_btn';
		var createButtons = function() {
			var btnIdArr = btnIds.split(','),
				btns = {};
			btnIdArr.forEach(function(id) {
				btns[id] = new Button();
			});
			return btns;
		};
		return {
			btnIds: btnIds,
			btns: createButtons(),
			toggleForm: function() {
				var id = this.id.substring(0, this.id.lastIndexOf('_'));
				$('#' + id).slideToggle('fast', formHandler.slideComplete);
			},
			slideComplete: function() {
				var $btn = $(this).prev(),
					id = '#' + $btn.attr('id'),
					btn = formHandler.btns[id],
					text;
				btn.formVisible = !btn.formVisible;
				text = btn.formVisible ? 'Cancel' : btn.text;
				$(id).text(text);
			}
		}
	}();
	$(function docReady() {
		// get button text
		for (var id in formHandler.btns) {
			var btn = formHandler.btns[id];
			btn.text = $(id).text();
		}

		$(formHandler.btnIds).on('click', formHandler.toggleForm);

		// datepicker
		$('#recorded_date').pikaday({
			format: 'YYYY-MM-DD'
		});
	});
})();
