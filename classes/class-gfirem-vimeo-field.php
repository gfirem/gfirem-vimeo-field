<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Class AdPiggy_Vimeo_Field
 *
 * Class to add a new field to formidable
 */
class AdPiggy_Vimeo_Field {
	/**
	 * AdPiggy_Vimeo_Field constructor.
	 */
	public $slug;
	/**
	 * @var string|void
	 */
	public $name;
	/**
	 * @var string|void
	 */
	public $description;
	/**
	 * @var
	 */
	public $form_id;
	/**
	 * @var array
	 */
	public $defaults = array();
	/**
	 * Field version
	 *
	 * @var string
	 */
	private $version = '1.0.0';
	/**
	 * AdPiggy_Vimeo_Field constructor.
	 */
	public function __construct() {
		$this->slug        = 'AdPiggy_Video_See';
		$this->name        = esc_attr( 'AdPiggy Vimeo' );
		$this->description = esc_attr( 'AdPiggy Vimeo field to upload a video' );
		$this->defaults    = array(
			'token'           => '',
			'upgrade_to_1080' => '',
			'private'         => '',
			'susses_message'  => 'Susses Upload',
			'error_message'   => 'Error in Upload',
		);
		add_action( 'frm_pro_available_fields', array( $this, 'add_formidable_field' ) );
		add_action( 'frm_before_field_created', array( $this, 'set_formidable_field_options' ) );
		add_action( 'frm_display_added_fields', array( $this, 'show_formidable_field_admin_field' ) );
		add_action( 'frm_field_options_form', array( $this, 'field_formidable_field_option_form' ), 10, 3 );
		add_action( 'frm_update_field_options', array( $this, 'update_formidable_field_options' ), 10, 3 );
		add_action( 'frm_form_fields', array( $this, 'show_formidable_field_front_field' ), 10, 2 );
		add_action( 'frm_display_value', array( $this, 'display_formidable_field_admin_field' ), 10, 3 );
		add_filter( 'frm_display_field_options', array( $this, 'add_formidable_field_display_options' ) );
		add_filter( 'frmpro_fields_replace_shortcodes', array( $this, 'add_formidable_custom_short_code' ), 10, 4 );
		add_filter( 'frm_validate_field_entry', array( $this, 'process_validate_frm_entry' ), 10, 3 );
		add_filter( 'frm_field_classes', array( $this, 'process_fields_class' ), 10, 2 );
		add_filter( 'frm_email_value', array( $this, 'process_replace_value_in_mail' ), 15, 3 );
	}
	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function add_formidable_field( $fields ) {
		$fields[ $this->slug ] = '<b class="adpiggy_field">' . esc_html( $this->name ) . '</b>';
		return $fields;
	}
	/**
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	public function field_formidable_field_option_form( $field, $display, $values ) {
		if ( $field['type'] !== $this->get_slug() ) {
			return;
		}
		foreach ( $this->defaults as $k => $v ) {
			if ( ! isset( $field[ $k ] ) ) {
				$field[ $k ] = $v;
			}
		}
		$this->inside_field_options( $field, $display, $values );
	}
	/**
	 * Display the additional options for the new field
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		$path = $this->load_field_template( 'field_option' );
		include $path;
	}
	/**
	 * @param $field_data
	 *
	 * @return mixed
	 */
	public function set_formidable_field_options( $field_data ) {
		if ( $field_data['type'] === $this->get_slug() ) {
			$field_data['name'] = esc_attr( $this->name );
			foreach ( $this->defaults as $k => $v ) {
				$field_data['field_options'][ $k ] = $v;
			}
			$this->set_field_options( $field_data );
		}
		return $field_data;
	}
	/**
	 * Set the default options for the field
	 *
	 * @param $field_data
	 *
	 * @return mixed
	 */
	protected function set_field_options( $field_data ) {
		return $field_data;
	}
	/**
	 * @param $field
	 */
	public function show_formidable_field_admin_field( $field ) {
		if ( $field['type'] !== $this->get_slug() ) {
			return;
		}
		$description = ( ! empty( $this->description ) ) ? $this->description : 'Default placeholder to show into the admin.';
		$this->placeholder_admin_field( $field, $description );
	}
	/**
	 * Show the field placeholder in the admin area
	 *
	 * @param        $field
	 * @param string $description
	 */
	protected function placeholder_admin_field( $field, $description = '' ) {
		$path = $this->load_field_template( 'field_admin_place_holder' );
		include $path;
	}
	/**
	 * @param $part
	 *
	 * @return string
	 */
	public static function load_field_template( $part ) {
		$template = locate_template( array( 'templates/' . $part . '.php' ) );
		if ( ! $template ) {
			return ADPIGGY_TEMPLATES_PATH . $part . '.php';
		} else {
			return $template;
		}
	}
	/**
	 * @see $this->update_field_options
	 */
	public function update_formidable_field_options( $field_options, $field, $values ) {
		if ( $field->type !== $this->get_slug() ) {
			return $field_options;
		}
		return $this->update_inside_field_options( $field_options, $field, $values );
	}
	/**
	 * @param $field_options
	 * @param $field
	 * @param $values
	 *
	 * @return mixed
	 */
	protected function update_inside_field_options( $field_options, $field, $values ) {
		foreach ( $this->defaults as $opt => $default ) {
			$field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
		}
		return $field_options;
	}
	/**
	 * @param $field
	 * @param $field_name
	 */
	public function show_formidable_field_front_field( $field, $field_name ) {
		if ( $field['type'] !== $this->get_slug() ) {
			return;
		}
		$field['value'] = stripslashes_deep( $field['value'] );
		$html_id        = $field['field_key'];
		$this->form_id  = $field['form_id'];
		$this->field_front_view( $field, $field_name, $html_id );
	}
	/**
	 * Add the HTML for the field on the front end
	 *
	 * @param $field
	 * @param $field_name
	 *
	 */
	protected function field_front_view( $field, $field_name, $html_id ) {
		$print_value = $field['default_value'];
		if ( ! empty( $field['value'] ) ) {
			$print_value = $field['value'];
		}
		$this->load_script();
		include ADPIGGY_VIEWS_PATH . 'adpiggy-vimeo-view.php';
	}
	/**
	 * Load script for the field
	 */
	function load_script() {
		$adpiggy_fields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
		$params         = array(
			'video_name' => 'user_' . get_current_user_id() . '_form_' . $this->form_id . '_' . time(),
		);
		foreach ( $adpiggy_fields as $key => $field ) {
			foreach ( $this->defaults as $def_key => $def_val ) {
				$opt                = FrmField::get_option( $field, $def_key );
				$params[ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
			}
		}
		wp_enqueue_style( 'adpiggy_style', ADPIGGY_CSS_PATH . 'adpiggy.css' );
		wp_enqueue_script( 'adpiggy-upload', ADPIGGY_JS_PATH . 'adpiggy-upload.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'vimeo_upload', ADPIGGY_JS_PATH . 'vimeo-upload.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'upload-cordova', ADPIGGY_JS_PATH . 'upload-cordova.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'adpiggy-upload', 'adpiggy_obj', $params );
	}
	/**
	 * @see $this->admin_view_field
	 */
	public function display_formidable_field_admin_field( $value, $field, $attr ) {
		if ( $field->type !== $this->get_slug() ) {
			return $value;
		}
		return $this->field_admin_view( $value, $field, $attr );
	}
	/**
	 * Add the HTML to display the field in the admin area
	 *
	 * @param $value
	 * @param $field
	 * @param $attr
	 *
	 * @return string
	 */
	protected function field_admin_view( $value, $field, $attr ) {
		if ( empty( $value ) ) {
			return $value;
		}
		return $value;
	}
	/**
	 * @see $this->display_options
	 */
	public function add_formidable_field_display_options( $display ) {
		if ( $display['type'] === $this->get_slug() ) {
			return $this->display_options( $display );
		}
		return $display;
	}
	/**
	 * Set display option for the field
	 *
	 * @param $display
	 *
	 * @return mixed
	 */
	protected function display_options( $display ) {
		return $display;
	}
	/**
	 * @see $this->process_short_code
	 */
	public function add_formidable_custom_short_code( $replace_with, $tag, $attr, $field ) {
		if ( $field->type !== $this->get_slug() ) {
			return $replace_with;
		}
		return $this->process_short_code( $replace_with, $tag, $attr, $field );
	}
	/**
	 * Add custom shortcode
	 *
	 * @param $replace_with
	 * @param $tag
	 * @param $attr
	 * @param $field
	 *
	 * @return string
	 */
	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
		return $replace_with;
	}
	/**
	 * @see $this->validate_frm_entry
	 */
	public function process_validate_frm_entry( $errors, $posted_field, $posted_value ) {
		if ( $posted_field->type !== $this->get_slug() ) {
			return $errors;
		}
		return $this->validate_frm_entry( $errors, $posted_field, $posted_value );
	}
	/**
	 * Validate if exist the key in the form target
	 *
	 * @param $errors
	 * @param $posted_field
	 * @param $posted_value
	 *
	 * @return mixed
	 */
	protected function validate_frm_entry( $errors, $posted_field, $posted_value ) {
		return $errors;
	}
	/**
	 * @see $this->fields_class
	 */
	public function process_fields_class( $classes, $field ) {
		if ( $field['type'] === $this->get_slug() ) {
			$classes .= $this->fields_class( $classes, $field );
		}
		return $classes;
	}
	/**
	 * Add class to the field
	 *
	 * @param $classes
	 * @param $field
	 *
	 * @return string
	 */
	protected function fields_class( $classes, $field ) {
		return $classes;
	}
	/**
	 * @see $this->replace_value_in_mail
	 */
	public function process_replace_value_in_mail( $value, $meta, $entry ) {
		if ( $meta->field_type === $this->get_slug() ) {
			return $this->replace_value_in_mail( $value, $meta, $entry );
		}
		return $value;
	}
	/**
	 * Replace value in email notifications
	 *
	 * @param $value
	 * @param $meta
	 * @param $entry
	 *
	 * @return string
	 */
	public function replace_value_in_mail( $value, $meta, $entry ) {
		return $value;
	}
	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}
	/**
	 * @param        $entry
	 * @param        $value
	 * @param string $form_id
	 *
	 * @return string
	 */
	protected function replace_shortcode( $entry, $value, $form_id = '' ) {
		if ( ! empty( $entry ) ) {
			$form_id    = $entry->form_id;
			$shortcodes = FrmFieldsHelper::get_shortcodes( $value, $form_id );
			$content    = apply_filters( 'frm_replace_content_shortcodes', $value, $entry, $shortcodes );
		} else {
			$content = $value;
		}
		FrmProFieldsHelper::replace_non_standard_formidable_shortcodes( array(), $content );
		return do_shortcode( $content );
	}
}