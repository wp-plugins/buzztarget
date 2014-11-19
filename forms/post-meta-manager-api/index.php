<?php
	/* ************************************************** */
	// GLOBALS
	/* ************************************************** */
	define('PMM_FILE_PATH',   trailingslashit(dirname(__FILE__ )));
	define('PMM_FILE_URL' ,   str_replace('\\', '/', trailingslashit(plugins_url('', __FILE__))));
	define('PMM_FIELDS_PATH', PMM_FILE_PATH . 'fields/');
	
	if (!class_exists('PostMetaManager')) require_once(PMM_FILE_PATH . 'PostMetaManager.class.php');
	
	// require_once('tmp/cpt.php');
	
	function pmm_add_scripts() {
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');	
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-tabs');
		
		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();		
		} else {
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
		wp_enqueue_script('json2');
		
		wp_enqueue_script('jquery-nested-sortable', PMM_FILE_URL . 'libs/nestedSortable/jquery.nestable.js', array('jquery-ui-sortable'));
		wp_enqueue_style('jquery-nested-sortable', PMM_FILE_URL . 'libs/nestedSortable/style.css');
		wp_enqueue_script('pmm', PMM_FILE_URL . 'libs/pmm.js', array('jquery'));
		wp_enqueue_style('jquery-ui', (is_ssl() ? 'https' : 'http') . '://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css');
	}
	
	add_action('admin_enqueue_scripts', 'pmm_add_scripts');
?>