function GfireMVimeoField() {
	function handleFileSelect(evt, fieldContainer) {
		evt.stopPropagation();
		evt.preventDefault();

		fieldContainer = fieldContainer.parent().parent();

		var files = evt.dataTransfer ? evt.dataTransfer.files : evt.target.files;
		var results = fieldContainer.find('#results');

		/* Clear the results div */
		if (jQuery(results).children()) {
			jQuery(results).empty();
		}

		/* Rest the progress bar and show it */
		updateProgress(0, fieldContainer);
		fieldContainer.find('#progress-container').show();

		showSubmitLoading();
		/* Instantiate Vimeo Uploader */
		(new VimeoUpload({
			name: gfiremVimeoField.video_name,
			description: '',
			private: !!(gfiremVimeoField.private),
			file: files[0],
			token: gfiremVimeoField.token,
			upgrade_to_1080: !!(gfiremVimeoField.upgrade_to_1080),
			onError: function(data) {
				showMessage('<strong>' + gfiremVimeoField.error_message + '</strong>', 'danger', fieldContainer);
				console.log(JSON.parse(data).error);
				removeSubmitLoading();
			},
			onProgress: function(data) {
				updateProgress(data.loaded / data.total, fieldContainer);
			},
			onComplete: function(videoId, index) {
				if (index > -1) {
					fieldContainer.find('.vimeo-upload-input').val(videoId);
				}
				removeSubmitLoading();
				showMessage('<strong>' + gfiremVimeoField.susses_message + '</strong>', 'success', fieldContainer);
			},
		})).upload();

		/* local function: show a user message */
		function showMessage(html, type, fieldContainer) {
			/* hide progress bar */
			fieldContainer.find('#progress-container').hide();

			/* display alert message */
			var element = document.createElement('div');
			jQuery(element).addClass('alert alert-' + (type || 'success'));
			jQuery(element).html(html);
			results.append(element);
		}
	}

	function showSubmitLoading() {
		var form = jQuery('form.frm_pro_form');
		if (form.length > 0) {
			var button = form.find("input[type = 'submit'], input[type = 'button'], button[type = 'submit']");
			form.addClass('frm_loading_form');
			button.attr('disabled', 'disabled');
		}
	}

	function removeSubmitLoading() {
		var form = jQuery('form.frm_pro_form');
		if (form.length > 0) {
			var button = form.find("input[type = 'submit'], input[type = 'button'], button[type = 'submit']");
			if (form.hasClass('frm_loading_form')) {
				form.removeClass('frm_loading_form');
			}
			button.attr('disabled', false);
		}
	}

	/**
	 * Drag over handler to set the drop effect.
	 */
	function handleDragOver(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		evt.dataTransfer.dropEffect = 'copy';
	}

	/**
	 * Update progress bar.
	 */
	function updateProgress(progress, fieldContainer) {
		progress = Math.floor(progress * 100);
		var element = fieldContainer.find('#progress');
		if (element) {
			jQuery(element).css('width', progress + '%');
			jQuery(element).html('&nbsp;' + progress + '%');
		}
	}

	function addListeners(field) {
		var dropZone = document.getElementById('drop_zone');
		var browse = document.getElementById('browse');
		if (dropZone && browse) {
			jQuery(dropZone).click(function(evt) {
				evt.stopPropagation();
				evt.preventDefault();
				jQuery(browse).trigger('click');
			});
			dropZone.addEventListener('dragover', handleDragOver, false);
			dropZone.addEventListener('drop', function(evt) {
				handleFileSelect(evt, field);
			}, false);
			browse.addEventListener('change', function(evt) {
				handleFileSelect(evt, field);
			}, false);
		}
	}

	return {
		init: function() {
			var fieldContainers = jQuery('.vimeo-upload-container');
			if (fieldContainers.length > 0) {
				jQuery.each(fieldContainers, function() {
					addListeners(jQuery(this));
				});
			}
		},
	};
}

var fncGfireMVimeoField = GfireMVimeoField();
jQuery(document).ready(function() {
	fncGfireMVimeoField.init();
});
