(function() {
	var Button = function() {
		return {
			formVisible: false
		}
	};

	var formHandler = function() {
		//var btnIds = '#album_form_btn,#song_form_btn,#thought_form_btn';
		var btnIds = [];
		$('[id$="form_btn"]').each(function(i, el) {
			btnIds.push('#' + $(el).attr('id'));
		});
		var createButtons = function(btnIdArr) {
			var btns = {};
			btnIdArr.forEach(function(id) {
				btns[id] = new Button();
			});
			return btns;
		};
		return {
			btnIds: btnIds,
			btns: createButtons(btnIds),
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

		$(formHandler.btnIds.join()).on('click', formHandler.toggleForm);

		// datepicker
		$('#recorded_date').pikaday({
			format: 'YYYY-MM-DD'
		});

		// ckeditor
		CKEDITOR.replace('content', {
			extraPlugins: 'uploadimage',
			imageUploadUrl: '/admin/include/upload.php',
			filebrowserUploadUrl: '/admin/include/upload.php'
		});
	});
})();
