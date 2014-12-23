<?php
	/* ***************************************************** */
	// GLOBALS
	/* ***************************************************** */
	define('OXFIRE_CPT_BUZZTARGET_FORM_METABOX_FIELDS', 'buzztarget_settings_fields');
	define('OXFIRE_CPT_BUZZTARGET_FORM_METABOX_NONCE',  '#$%&/8xducadfhz');
	
	global $buzztarget_settings_fields;
	$buzztarget_settings_fields = array(
		'Field Settings' => buzztarget_get_form_fields()
	);	
	
	/* ***************************************************** */
	// ADD CUSTOM FIELDS META BOX
	/* ***************************************************** */
	function buzztarget_add_metabox_fields() {
		add_meta_box('buzztarget_render_metabox_fields', 'BuzzTarget Form Meta', 'buzztarget_render_metabox_fields', CPT_BUZZTARGET_FORM, 'normal', 'high');
	}
	
	function buzztarget_render_metabox_fields() {
		global $post, $buzztarget_settings_fields;
		wp_nonce_field($post->post_type . '_verification', OXFIRE_CPT_BUZZTARGET_FORM_METABOX_NONCE);
		$meta = get_post_meta($post->ID);
		$pmm = new PostMetaManager(OXFIRE_CPT_BUZZTARGET_FORM_METABOX_FIELDS, $meta);
		$pmm->output($buzztarget_settings_fields, false);
	}
	
	add_action('add_meta_boxes', 'buzztarget_add_metabox_fields');
	
	/* ***************************************************** */
	// ADD CUSTOM FIELDS META BOX
	/* ***************************************************** */
	function buzztarget_add_metabox_fields_shortcode() {
		add_meta_box('buzztarget_render_metabox_fields_shortcode', 'BuzzTarget Form Shortcode', 'buzztarget_render_metabox_fields_shortcode', CPT_BUZZTARGET_FORM, 'side', 'high');
	}
	
	function buzztarget_render_metabox_fields_shortcode() {
		global $post;
		$buzztarget_settings_fields = array(
			'Shortcode' => array(
				array(
					'name'        => 'Shortcode',
					'key'         => 'shortcode',
					'type'        => PostMetaManagerFieldType::Textbox,
					'custom_vars' => array(
						'readonly' => true
					)
				)	
			)
		);
		
		wp_nonce_field($post->post_type . '_verification', OXFIRE_CPT_BUZZTARGET_FORM_METABOX_NONCE);
		$meta = get_post_meta($post->ID);
		$pmm = new PostMetaManager(OXFIRE_CPT_BUZZTARGET_FORM_METABOX_FIELDS, $meta);
		$pmm->output($buzztarget_settings_fields);
	}
	
	add_action('add_meta_boxes', 'buzztarget_add_metabox_fields_shortcode');	
	
	/* ***************************************************** */
	// SAVE CUSTOM FIELDS
	/* ***************************************************** */
	function buzztarget_save_metabox_fields($post_id) {
		$post_type = get_post_type($post_id);
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return;

		if (!wp_verify_nonce($_POST[OXFIRE_CPT_BUZZTARGET_FORM_METABOX_NONCE], $post_type . '_verification'))
			return;
	
		if ($_POST['post_type'] == CPT_BUZZTARGET_FORM) {
			if (!current_user_can('edit_page', $post_id))
				return;
		}
		
		$fields = isset($_POST[OXFIRE_CPT_BUZZTARGET_FORM_METABOX_FIELDS]) ? $_POST[OXFIRE_CPT_BUZZTARGET_FORM_METABOX_FIELDS] : array();

		foreach ($fields as $key => $value) {
			$value = PostMetaManagerHelper::sanitize_meta_for_db_input($value);
			update_post_meta($post_id, $key, $value);
		}
	}
	
	add_action('save_post', 'buzztarget_save_metabox_fields');
?>