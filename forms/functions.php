<?php
	// INSERT ARRAY BETWEEN KEYS
	function buzztarget_array_insert(&$array, $position, $insert) {
		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}

	// SANITIZE META
	function buzztarget_sanizite_meta($meta) {
		if (is_array($meta) && !empty($meta)) {
			foreach ($meta as &$m) if (is_array($m) && count($m) == 1) $m = $m[0];
			return $meta;
		}
	}
	
	// GET GENERIC FIELD DATA (for admin)
	function buzztarget_get_generic_field($key_prefix, $name_prefix) {
		$is_email = $key_prefix === 'email';
		$name_prefix = '';
		return array(
			array(
				'name'        => $name_prefix . ' Label',
				'key'         => $key_prefix . '_label',
				'type'        => PostMetaManagerFieldType::Textbox
			),
			array(
				'name'        => $name_prefix . ' Placeholder Text',
				'key'         => $key_prefix . '_placeholder_text',
				'type'        => PostMetaManagerFieldType::Textbox
			),
			array(
				'name'        => 'Bold',
				'key'         => $key_prefix . '_bold',
				'type'        => PostMetaManagerFieldType::Checkbox,
				'custom_vars' => array(
					'label' => 'Bold'
				)
			),
			array(
				'name'        => 'Italic',
				'key'         => $key_prefix . '_italic',
				'type'        => PostMetaManagerFieldType::Checkbox,
				'custom_vars' => array(
					'label' => 'Italic'
				)
			),
			array(
				'name'        => 'Required',
				'key'         => $key_prefix . '_required',
				'type'        => PostMetaManagerFieldType::Checkbox,
				'custom_vars' => array(
					'label'              => 'Required',
					'disabled'           => $is_email,
					'checked_by_default' => $is_email
				)
			),
			array(
				'name'        => 'Display',
				'key'         => $key_prefix . '_display',
				'type'        => PostMetaManagerFieldType::Checkbox,
				'custom_vars' => array(
					'label'              => 'Display',
					'disabled'           => $is_email,
					'checked_by_default' => true
				)
			)
		);
	}
	
	// GET FORM FIELDS (admin)
	function buzztarget_get_form_fields() {
		$buzztarget_form_fields = array();
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Form Title',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);			
		$buzztarget_form_fields[] = array(
			array(
				'name'        => 'Form Title',
				'key'         => 'form_title',
				'type'        => PostMetaManagerFieldType::WPEditor
			)
		);
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Email',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);		
		$buzztarget_form_fields[] = buzztarget_get_generic_field('email', 'Email');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'First Name',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);	
		$buzztarget_form_fields[] = buzztarget_get_generic_field('fname', 'First Name');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);	
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Last Name',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);		
		$buzztarget_form_fields[] = buzztarget_get_generic_field('lname', 'Last Name');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);	
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Company Name',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);		
		$buzztarget_form_fields[] = buzztarget_get_generic_field('cname', 'Company Name');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);	
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Title',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);		
		$buzztarget_form_fields[] = buzztarget_get_generic_field('title', 'Title');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);	
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Phone Number',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);		
		$buzztarget_form_fields[] = buzztarget_get_generic_field('phoneno', 'Phone Number');
		$buzztarget_form_fields[] = array(
			array(
				'type' => PostMetaManagerFieldType::Delimiter
			)
		);			
		$buzztarget_form_fields[] = array(
			array(
				'name' => 'Button Label',
				'type' => PostMetaManagerFieldType::SectionHeader
			)
		);	
		$buzztarget_form_fields[] = array(
			array(
				'name'        => 'Button Label',
				'key'         => 'button_label',
				'type'        => PostMetaManagerFieldType::Textbox
			)
		);
		$buzztarget_form_fields[] = array(
			array(
				'name'        => 'Button Style',
				'key'         => 'button_style',
				'type'        => PostMetaManagerFieldType::Textbox,
				'desc'        => 'Please input custom CSS styles here (ie. background-color: red; border: 1px solid black; ...)'
			)
		);		
		$buzztarget_form_fields_out = array();
		foreach ($buzztarget_form_fields as $ff_group) {
			foreach ($ff_group as $ff) {
				$buzztarget_form_fields_out[] = $ff;
			}
		}
		return $buzztarget_form_fields_out;
	}
	
	// RENDER CONTACT FORM (used for shortcode & widget)
	function buzztarget_render_form($id) {
		global $buzztarget_shortcode_counter, $buzztarget_form_field_keys;
		
		$meta = get_post_meta($id);
		$buzztarget_settings = buzztarget_sanizite_meta($meta);
		
		$buzztarget_shortcode_counter++;
		
		ob_start();
		
		if (isset($buzztarget_settings['form_title']) && !empty($buzztarget_settings['form_title'])) {
			$buzztarget_settings['form_title'] = html_entity_decode($buzztarget_settings['form_title']);
			$buzztarget_settings['form_title'] = strip_tags($buzztarget_settings['form_title']);
			_e($buzztarget_settings['form_title']);
		}
		
		?><form action="" method="post" id="buzztarget-form-<?php _e($buzztarget_shortcode_counter) ?>" class="buzztarget-form"><dl><?php
		foreach ($buzztarget_form_field_keys as $ff_key) {
			if ($buzztarget_settings[$ff_key . '_display'] === PostMetaManagerHelper::CheckboxChecked) {
				$field = new stdClass;
				$field->id               = $ff_key . '-' . $buzztarget_shortcode_counter;
				$field->label            = PostMetaManagerHelper::esc_attr($buzztarget_settings[$ff_key . '_label']);
				
				$field->placeholder_text = PostMetaManagerHelper::sanitize_wp_editor_html($buzztarget_settings[$ff_key . '_placeholder_text']);
				$field->placeholder_text = PostMetaManagerHelper::esc_attr($field->placeholder_text);
				
				$field->bold             = isset($buzztarget_settings[$ff_key . '_bold'])     && $buzztarget_settings[$ff_key . '_bold']     === PostMetaManagerHelper::CheckboxChecked;
				$field->italic           = isset($buzztarget_settings[$ff_key . '_italic'])   && $buzztarget_settings[$ff_key . '_italic']   === PostMetaManagerHelper::CheckboxChecked;
				$field->required         = isset($buzztarget_settings[$ff_key . '_required']) && $buzztarget_settings[$ff_key . '_required'] === PostMetaManagerHelper::CheckboxChecked;
				?>
					<dt><label for="<?php _e($field->id) ?>">
						<?php if (!empty($field->label)) { ?>
							<?php _e($field->bold ? '<b>' : '') ?>
							<?php _e($field->italic ? '<i>' : '') ?>
							<?php _e($field->label) ?>
							<?php _e($field->italic ? '</i>' : '') ?>
							<?php _e($field->bold ? '</b>' : '') ?>
							<?php _e($field->required ? '&nbsp;<b>*</b>' : '') ?>
						<?php } ?>
					</label></dt>
					<dd>
						<input type="text" name="<?php _e($field->id) ?>" id="<?php _e($field->id) ?>" placeholder="<?php _e($field->placeholder_text) ?>" class="<?php _e($ff_key) ?> <?php _e($field->required ? 'buzztarget-required' : '') ?>" />
					</dd>
				<?php
			}
		}
		?>
			<dd><input type="submit" value="<?php _e($buzztarget_settings['button_label']) ?>" class="buzztarget-submit" data-id="<?php _e($id) ?>" style="<?php _e($buzztarget_settings['button_style']); ?>" /></dd>
		<?php
		?></dl><div class="buzztarget-message"></div><div class="buzztarget-clear"></div><div class="buzztarget-loader"><img src="<?php _e(BUZZTARGET_FILE_URL) ?>ajax-loader.gif" alt="" /></div></form><div class="buzztarget-clear"></div><?php
		
		return ob_get_clean();
	}
	
	// AJAX RESPONSE (for communication with JS on front-end)
	function buzztarget_ajax_response($msg, $code, $echo = true) {
		$out = json_encode(
			array(
				'msg'  => $msg,
				'code' => $code
			)
		);
		
		if ($echo)
			_e($out);
		else
			return $out;
	}
?>