<?php
	/* ************************************************** */
	// WIDGET CONTENT
	/* ************************************************** */	
	class Buzz_Target_Form extends WP_Widget {
		// CONSTRUCT
		public function __construct() {
			parent::__construct(
				'buzztarget_form',
				'BuzzTarget Contact Registration Form',
				array('description' => '')
			);
		}

		// WIDGET OUTPUT
		public function widget($args, $instance) {
			extract( $args );	
			$instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
			$instance['form_id'] = apply_filters('widget_title', empty($instance['form_id']) ? '' : $instance['form_id']);
			echo $before_widget;
			if (!empty($instance['title'])) echo $before_title . $instance['title'] . $after_title;
			if (!empty($instance['form_id']) && is_numeric($instance['form_id']) && $instance['form_id'] > 0) _e( buzztarget_render_form($instance['form_id']) );
			
			echo $after_widget;
		}

		// WIDGET INPUT
		public function form($instance) {
			if (isset( $instance['title'])) {
				$title = $instance['title'];
			} else {
				$title = '';
			}
			if (isset( $instance['form_id'])) {
				$form_id = $instance['form_id'];
			} else {
				$form_id = '';
			}			
			$forms = new WP_Query( 
				array(
					'posts_per_page' => -1,
					'post_type'      => CPT_BUZZTARGET_FORM,
					'post_status'    => 'publish'
				)
			);
			?>
			<p>
				<label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				<label for="<?php echo $this->get_field_name('form_id'); ?>"><?php _e('Form ID:'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('form_id'); ?>" name="<?php echo $this->get_field_name('form_id'); ?>">
					<option value="-1">--</option>
					<?php foreach ($forms->posts as $f) { ?>
						<option value="<?php _e($f->ID) ?>" <?php _e($form_id == $f->ID ? 'selected="selected"' : '') ?>><?php _e($f->post_title) ?> (ID: <?php _e($f->ID) ?>)</option>
					<?php } ?>
				</select>
			</p>
			<?php 
		}

		// WIDGET UPDATE
		public function update($new_instance, $old_instance) {
			$instance = array();
			$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
			$instance['form_id'] = (!empty($new_instance['form_id'])) ? strip_tags($new_instance['form_id']) : '';
			return $instance;
		}
	}
?>