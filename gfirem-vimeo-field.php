<?php
/**
 * @wordpress-plugin
 *
 * Plugin Name:       GFireM Vimeo Field
 * Description:       Add a Formidable Form Field to upload videos directo to Vimeo.
 * Version:           1.0.0
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 */
if (! defined('WPINC')) {
	die;
}

if (! class_exists('GFireMVimeoField')) {
	class GFireMVimeoField
	{
		public static $assets;

		public static $view;

		public static $classes;

		public static $slug = 'gfirem-vimeo-field';

		public static $version = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		private function __construct()
		{
			//TODO add freemius
			self::$assets = plugin_dir_url(__FILE__) . 'assets/';
			self::$view = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
			require_once 'classes'.DIRECTORY_SEPARATOR.'class-gfirem-vimeo-field-imp.php';
			new GFireMVimeoFieldImp();
		}

		/**
		 * Get plugin version.
		 *
		 * @return string
		 */
		public static function getVersion()
		{
			return self::$version;
		}

		/**
		 * Get plugins slug.
		 *
		 * @return string
		 */
		public static function getSlug()
		{
			return self::$slug;
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object a single instance of this class
		 */
		public static function get_instance()
		{
			// If the single instance hasn't been set, set it now.
			if (self::$instance === null) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

	add_action('plugins_loaded', array('GFireMVimeoField', 'get_instance'));
}
