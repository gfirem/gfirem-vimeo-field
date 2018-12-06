<input type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="vimeo-upload-input"/>
<div id="results">
	<?php if ( ! empty( $print_value ) ) : ?>
		<strong>Video ID</strong>: <?php echo esc_attr( $print_value ) ?>.
	<?php endif; ?>
</div>
<div class="frm_dropzone frm_single_upload dz-clickable">
	<div class="dz-message needsclick vimeo-upload-container">
		<span class="frm_icon_font frm_upload_icon"></span>
		<div id="drop_zone">Drop a file here or click to upload</div>
		<label class="btn btn-block btn-info" style="visibility: hidden;">
			<input id="browse" type="file">
		</label>
		<div id="progress-container" class="progress">
			<div id="progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 0%">&nbsp;0%
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/websemantics/bragit/0.1.2/bragit.js"></script>
