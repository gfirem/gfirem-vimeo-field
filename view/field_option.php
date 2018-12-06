<tr>
	<td><label for="token_<?php echo esc_attr( $field['id'] ) ?>">Access Token</label></td>
	<td>
		<i class="howto">This is the token generated from VIMEO app. Create an access token at <a target="_blank" href="https://developer.vimeo.com/apps">https://developer.vimeo.com/apps</a> if you don't have yet. The app need to have the next scopes <code>'public', 'private','upload' and 'edit'</code> </i>
		<input type="text" style="width:300px;" name="field_options[token_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['token'] ) ?>" class="pen-color-field" id="token_<?php echo esc_attr( $field['id'] ) ?>">
	</td>
</tr>
<tr>
	<td><label for="upgrade_to_1080_<?php echo esc_attr( $field['id'] ) ?>">Upgrade to 1080</label></td>
	<td>
		<input type="checkbox" name="field_options[upgrade_to_1080_<?php echo esc_attr( $field['id'] ) ?>]" <?php checked( $field['upgrade_to_1080'], true ); ?> value="1" class="pen-color-field" id="upgrade_to_1080_<?php echo esc_attr( $field['id'] ) ?>">
	</td>
</tr>
<tr>
	<td><label for="private_<?php echo esc_attr( $field['id'] ) ?>">Make Private</label></td>
	<td>
		<input type="checkbox" name="field_options[private_<?php echo esc_attr( $field['id'] ) ?>]" <?php checked( $field['private'], true ); ?> value="1" class="pen-color-field" id="private_<?php echo esc_attr( $field['id'] ) ?>">
	</td>
</tr>
<tr>
	<td><label for="susses_message_<?php echo esc_attr( $field['id'] ) ?>">Susses Message</label></td>
	<td>
		<input type="text" style="width:300px;" name="field_options[susses_message_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['susses_message'] ) ?>" class="pen-color-field" id="susses_message_<?php echo esc_attr( $field['id'] ) ?>">
	</td>
</tr>
<tr>
	<td><label for="error_message_<?php echo esc_attr( $field['id'] ) ?>">Error Message</label></td>
	<td>
		<input type="text" style="width:300px;" name="field_options[error_message_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['error_message'] ) ?>" class="pen-color-field" id="error_message_<?php echo esc_attr( $field['id'] ) ?>">
	</td>
</tr>
