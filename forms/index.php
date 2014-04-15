<?php
	/* ************************************************** */
	// GLOBALS
	/* ************************************************** */
	define('BUZZTARGET_PLUGIN_VER', 1.0);
	define('BUZZTARGET_NONCE',      '$E%R/&TZPeizd%)(Z');

	define('BUZZTARGET_FILE_PATH', str_replace('\\', '/', trailingslashit(dirname(__FILE__ ))));
	define('BUZZTARGET_FILE_URL',  str_replace('\\', '/', trailingslashit(plugins_url('', __FILE__))));

	define('BUZZTARGET_SHORTCODE_KEY',       'buzztarget_form');
	define('BUZZTARGET_API_URL_PLACEHOLDER', 'http://' . (BUZZTARTGET_USE_LIVE_API ? 'api' : 'dev') . '.buzztarget.com/api/contacts/%s');

	require_once(BUZZTARGET_FILE_PATH . 'post-meta-manager-api/index.php');
	require_once(BUZZTARGET_FILE_PATH . 'functions.php');
	require_once(BUZZTARGET_FILE_PATH . 'ajax.php');

	global $buzztarget_shortcode_counter, $buzztarget_form_field_keys;
	$buzztarget_shortcode_counter = 0;

	$buzztarget_form_field_keys = array(
		'email',
		'fname',
		'lname',
		'cname',
		'title',
		'phoneno'
	);

	/* ************************************************** */
	// INIT PLUGIN
	/* ************************************************** */
	function buzztarget_init() {
		// ADD SCRIPTS & STYLES
		wp_enqueue_script('jquery');
		wp_enqueue_style('buzztarget-style', BUZZTARGET_FILE_URL . 'style.css', '', '1.0');
		wp_register_script('buzztarget-script', BUZZTARGET_FILE_URL . 'script.js', array('jquery'));
		wp_enqueue_script('buzztarget-script');

		// ADD CPT
		require_once(BUZZTARGET_FILE_PATH . 'cpt/cpt.php');

		// COMBINE 2 PLUGINS
		add_action('admin_menu', 'buzztarget_edit_admin_menus', 999);
	}

	add_action('init', 'buzztarget_init');

	/* ************************************************** */
	// ADD WP HEAD SCRIPT
	/* ************************************************** */
	function buzztarget_wp_head() {
		?>
		<script type="text/javascript">
		/*<![CDATA[*/
			BUZZTARGET_AJAX_URL = "<?php _e(admin_url('admin-ajax.php')) ?>";
		/*]]>*/
		</script>
		<?php
	}

	add_action('wp_head', 'buzztarget_wp_head');

	/* ************************************************** */
	// ADD ADMIN HEAD SCRIPT
	/* ************************************************** */
	function buzztarget_admin_head() {
		global $post;
		if (!empty($post) && $post->post_type === CPT_BUZZTARGET_FORM) {
			?>
			<script type="text/javascript">
			/*<![CDATA[*/
				jQuery(function ($)
				{
					$("#toplevel_page_repl_admin").attr("class", "wp-has-submenu wp-has-current-submenu wp-menu-open menu-top").find(" > a").attr("class", "wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-icon-post");
				});
			/*]]>*/
			</script>
			<?php
		}
	}

	add_action('admin_head', 'buzztarget_admin_head');

	/* ************************************************** */
	// REGISTER WIDGETS
	/* ************************************************** */
	function buzztarget_register_widgets() {
		require_once('widget.php');
		register_widget('Buzz_Target_Form');
	}

	add_action('widgets_init', 'buzztarget_register_widgets');

	/* ************************************************** */
	// SHORTCODE CONTENT
	/* ************************************************** */
	function buzztarget_shortcode_render_form($atts, $content = null) {
		extract(
			shortcode_atts(
				array(
					'id' => 0
				),
				$atts
			)
		);

		if ($id > 0) {
			return '<div class="buzztarget-middle">' . buzztarget_render_form($id) . '</div>';
		} else {
			return '<div class="buzztarget-message buzztarget-error">BuzzTarget Contact Registration Form Plugin settings not found!</div>';
		}
	}

	add_shortcode(BUZZTARGET_SHORTCODE_KEY, 'buzztarget_shortcode_render_form');

	function buzztarget_edit_admin_menus() {
		global $menu;
		global $submenu;

		foreach ($menu as $key => $m) {
			# REMOVE POST TYPE ADMIN ITEM
			if ($menu[$key][0] == (CPT_BUZZTARGET_FORM_SINGLE_NAME . 's')) unset($menu[$key]);
		}

		foreach ($submenu as $key => $sm) {
			# COMBINE 2 PLUGINS
			if ($key == 'repl_admin') {
				foreach ($submenu['edit.php?post_type=' . CPT_BUZZTARGET_FORM] as $ssm) $submenu[$key][] = $ssm;

				$new_submenu = array();
				$new_submenu[] = $submenu[$key][1];
				$new_submenu[] = $submenu[$key][2];
				// $new_submenu[] = $submenu[$key][3];
				$submenu[$key][0][0] = 'Listings';
				$new_submenu[] = $submenu[$key][0];
				$submenu[$key] = $new_submenu;

				unset($submenu['edit.php?post_type=' . CPT_BUZZTARGET_FORM]);
				break;
			}
		}

		// print_r($menu);
		// print_r($submenu);
		// die();
	}
?>