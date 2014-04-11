<?php
	/* ************************************************** */
	// CUSTOM POST TYPE
	/* ************************************************** */
	define('CPT_BUZZTARGET_FORM',             'buzztarget_form');
	define('CPT_BUZZTARGET_FORM_SINGLE_NAME', 'Contact Form');
	
	/* ************************************************** */
	// ADD CUSTOM POST TYPE
	/* ************************************************** */	
	register_post_type(
		CPT_BUZZTARGET_FORM,
		array(
			'labels'                 => array(
				'name'               => __(CPT_BUZZTARGET_FORM_SINGLE_NAME . 's'),
				'singular_name'      => __(CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'add_new'            => __('Add New ' . CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'add_new_item'       => __('Add New ' . CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'edit_item'          => __('Edit ' . CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'new_item'           => __('New ' . CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'view_item'          => __('View ' . CPT_BUZZTARGET_FORM_SINGLE_NAME),
				'search_items'       => __('Search ' . CPT_BUZZTARGET_FORM_SINGLE_NAME . 's'),
				'not_found'          => __('No ' . CPT_BUZZTARGET_FORM_SINGLE_NAME . 's Found'),
				'not_found_in_trash' => __('No ' . CPT_BUZZTARGET_FORM_SINGLE_NAME . 's Found In Trash')	
			),
			'has_archive'       => true,
			'rewrite'           => false,
			'supports'          => array(
				'title'
			),
			'public'            => true,
			'hierarchical'      => true,
			'_builtin'          => false,
			'capability_type'   => 'post',
			'menu_icon'         => BUZZTARGET_FILE_URL . '/images/cpt-icon.png'
		)
	);
	
	/* ***************************************************** */
	// ADD CPT STYLE (remove slug)
	/* ***************************************************** */	
	function buzztarget_add_form_admin_page_styles() {
		global $typenow;

		$show_css = false;
		
		if ($typenow == CPT_BUZZTARGET_FORM) {
			$show_css = true;
			remove_meta_box('postimagediv', CPT_BUZZTARGET_FORM, 'side');
			remove_meta_box('slugdiv', CPT_BUZZTARGET_FORM, 'normal');
		}
		
		if ($show_css) {
			ob_start();
			?>
				<style type="text/css">
					#edit-slug-box, #preview-action { display: none; }
					#buzztarget_render_metabox_fields_shortcode input[type=text] { text-align: center; }
					#field-settings .PMMRoot { margin: 40px auto !important; }
				</style>
				<script type="text/javascript">
					jQuery(function ($)
					{
						var postID = $("#post_ID").val();
						var shortcodeIdentifier = "<?php _e(PostMetaManagerHelper::sanitize_css_selector('buzztarget_settings_fields[shortcode]')) ?>";
						var $shortcode = $("#" + shortcodeIdentifier);
						var text = '[<?php _e(BUZZTARGET_SHORTCODE_KEY) ?> id="' + postID + '"]';
						$shortcode.val(text).click(function (e)
						{
							e.preventDefault();
							$(this).select();
						});
					});
				</script>
			<?php
			_e(ob_get_clean());
		}
	}
	
	add_action('admin_head', 'buzztarget_add_form_admin_page_styles');	
	
	/* ************************************************** */
	// MODIFY CUSTOM POST TYPE ADMIN TABLE
	/* ************************************************** */	
	function buzztarget_homepage_slider_add_image_colunn($posts_columns) {
		$posts_columns = array(
			'cb'             => '<input type="checkbox" />',
			'title'          => 'Title',
			'shortcode'      => 'Shortcode',
			'date'           => 'Date'
		);
		return $posts_columns;
	}
	
	add_filter('manage_edit-' . CPT_BUZZTARGET_FORM . '_columns', 'buzztarget_homepage_slider_add_image_colunn');	
	
	function buzztarget_homepage_slider_add_image_to_column($column_name) {
		global $post, $gon, $ji;	
		if ($column_name == 'shortcode') {
			$shortcode = get_post_meta($post->ID, 'shortcode', $is_single = true);
			_e($shortcode);
		}
	}
	
	add_action('manage_' . CPT_BUZZTARGET_FORM . '_posts_custom_column', 'buzztarget_homepage_slider_add_image_to_column');	
	
	// SORT COLUMNS
	function buzztarget_homepage_slider_manage_sortable_columns($columns) {
		$columns['shortcode'] = 'shortcode';
		return $columns;
	}

	add_filter('manage_edit-' . CPT_BUZZTARGET_FORM . '_sortable_columns', 'buzztarget_homepage_slider_manage_sortable_columns');	
	
	/* ************************************************** */
	// ADD META BOXES
	/* ************************************************** */
	require_once('metaboxes.php');
?>