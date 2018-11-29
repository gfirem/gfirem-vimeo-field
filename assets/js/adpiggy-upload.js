
function handleFileSelect(evt) {
	evt.stopPropagation()
	evt.preventDefault()

	var files = evt.dataTransfer ? evt.dataTransfer.files : $(this).get(0).files
	var results = document.getElementById('results')

	/* Clear the results div */
	while (results.hasChildNodes()) results.removeChild(results.firstChild)

	/* Rest the progress bar and show it */
	updateProgress(0)
	document.getElementById('progress-container').style.display = 'block'

	/* Instantiate Vimeo Uploader */
	;(new VimeoUpload({
		name: adpiggy_obj.video_name,
		description: '',
		private: !!(adpiggy_obj.private),
		file: files[0],
		token: adpiggy_obj.token,
		upgrade_to_1080: !!(adpiggy_obj.upgrade_to_1080),
		onError: function (data) {
			showMessage('<strong>' + adpiggy_obj.error_message + '</strong>', 'danger')
			console.log(JSON.parse(data).error)
		},
		onProgress: function (data) {
			updateProgress(data.loaded / data.total)
		},
		onComplete: function (videoId, index) {
			if (index > -1) {
				document.getElementsByClassName('vimeo-upload-input')[0].value = videoId;
			}
			showMessage('<strong>' + adpiggy_obj.susses_message + '</strong>')
		}
	})).upload()

	/* local function: show a user message */
	function showMessage(html, type) {
		/* hide progress bar */
		document.getElementById('progress-container').style.display = 'none'

		/* display alert message */
		var element = document.createElement('div')
		element.setAttribute('class', 'alert alert-' + (type || 'success'))
		element.innerHTML = html
		results.appendChild(element)
	}
}

/**
 * Dragover handler to set the drop effect.
 */
function handleDragOver(evt) {
	evt.stopPropagation()
	evt.preventDefault()
	evt.dataTransfer.dropEffect = 'copy'
}

/**
 * Updat progress bar.
 */
function updateProgress(progress) {
	progress = Math.floor(progress * 100)
	var element = document.getElementById('progress')
	if (element) {
		element.setAttribute('style', 'width:' + progress + '%')
		element.innerHTML = '&nbsp;' + progress + '%'
	}
}

/**
 * Wire up drag & drop listeners once page loads
 */
document.addEventListener('DOMContentLoaded', function () {
	var dropZone = document.getElementById('drop_zone');
	var browse = document.getElementById('browse');
	if (dropZone && browse) {
		dropZone.addEventListener('dragover', handleDragOver, false);
		dropZone.addEventListener('drop', handleFileSelect, false);
		browse.addEventListener('change', handleFileSelect, false);
	}
});