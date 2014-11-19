<?php	$is_featured_img = isset($is_featured_img) && is_bool($is_featured_img) ? $is_featured_img : false;	$library         = isset($library)         && !empty($library)          ? $library         : '';	$multiple        = isset($multiple)        && is_bool($multiple)        ? $multiple        : false;	$title           = isset($title)           && !empty($title)            ? $title           : '';	$force_extension = isset($force_extension) && !empty($force_extension)  ? $force_extension : '';		if (!empty($force_extension)) {		$force_extension = explode(',', $force_extension);	} else {		$force_extension = array();	}		$src = '';	if (!empty($saved_value) && is_numeric($saved_value)) {		$att = get_post($saved_value);		$src = $att->guid;	}?><input	type="hidden" 	name="<?php _e($identifier) ?>" 	value="<?php _e($saved_value) ?>" 	id="MU_HIDDEN_<?php _e($field_data['key']) ?>" /><a href="#" id="PICK_FILE_<?php _e($field_data['key']) ?>" class="button">Upload file</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" id="REMOVE_FILE_<?php _e($field_data['key']) ?>" class="button">Remove file</a><br /><br /><span id="MU_FILENAME_<?php _e($field_data['key']) ?>"><?php _e($src) ?></span><?php if ($library  == 'image') { ?>	<img 		src="<?php _e($src) ?>" 		alt="" 		id="MU_IMG_<?php _e($field_data['key']) ?>" 		style="max-width: 400px; max-height: 400px; width: auto; height: auto; display: block; margin-top: 10px; <?php _e(empty($src) ? 'display: none;' : '') ?>"	/><?php } ?><script type="text/javascript">	jQuery(function ($)	{		var $img            = $("#MU_IMG_<?php _e($field_data['key']) ?>");		var $filename       = $("#MU_FILENAME_<?php _e($field_data['key']) ?>");		var $pick           = $("#PICK_FILE_<?php _e($field_data['key']) ?>");		var $hid            = $("#MU_HIDDEN_<?php _e($field_data['key']) ?>");		var $rem            = $("#REMOVE_FILE_<?php _e($field_data['key']) ?>");		var isFeaturedImage = <?php _e($is_featured_img ? 'true' : 'false') ?>;		var forceExtensions = <?php _e(json_encode($force_extension)); ?>				var imageMediaPopup = wp.media({			<?php if (!empty($title)) { ?>				title : "<?php _e($title) ?>",			<?php } ?>						multiple : <?php _e($multiple ? 'true' : 'false') ?>,						<?php if (!empty($library)) { ?>				library : { type : '<?php _e($library) ?>'},			<?php } ?>						button : { text : 'Insert' }		});			$pick.click(function (e)		{			e.preventDefault();			imageMediaPopup.open();		});				if ($img.length > 0)		{			$img.click(function (e)			{				e.preventDefault();				imageMediaPopup.open();			});		}				$rem.click(function (e)		{			e.preventDefault();			$hid.val("");			$filename.text("");			if ($img.length > 0)			{											$img.attr("src", "");				$img.hide(0);			}			if (isFeaturedImage) $("#remove-post-thumbnail").click();		});				function mediaUploaderOpen (frame) 		{			var selection    = frame.state().get('selection');			var attachmentID = $hid.val();			attachmentID     = Number(attachmentID);			if (typeof attachmentID === "number" && attachmentID > 0)			{				var attachment = wp.media.attachment(attachmentID);				attachment.fetch();				selection.add(attachment ? [attachment] : []);			}			else			{				selection.add([]);			}		}				function endsWith (str, suffix)		{			return str.indexOf(suffix, str.length - suffix.length) !== -1;		}		function mediaUploaderSelect (frame)		{			var selection = frame.state().get('selection');			selection.each(function (attachment)			{				if (forceExtensions.length > 0)				{					var matchFound = false;					for (var i = 0; i < forceExtensions.length; i++)					{						var ext = $.trim(forceExtensions[i].toLowerCase());						var url = $.trim(attachment.collection._byId[attachment.id].attributes.url.toLowerCase());						if (endsWith(url, ext))						{							matchFound = true;							break;						}					}					if (!matchFound)					{						var err = "The chosen file extension isn't allowed for this field, please pick a file with one of the following extensions:\n\n";						for (var i = 0; i < forceExtensions.length; i++)						{							err += String.fromCharCode(8226) + " " + forceExtensions[i] + "\n";						}						alert(err);						return;					}				}				if (isFeaturedImage) $("#remove-post-thumbnail").click();				$hid.val(attachment.id);				$filename.text(attachment.collection._byId[attachment.id].attributes.url);				if ($img.length > 0)				{					$img.attr("src", attachment.collection._byId[attachment.id].attributes.url);					$img.css("display", "block");				}				if ("<?php _e($identifier) ?>" === "programs_metabox_fields[pdf_male]" || "<?php _e($identifier) ?>" === "programs_metabox_fields[pdf_female]")				{					var identifier = "<?php _e($identifier) ?>";					var identifierSanitized = "<?php _e(PostMetaManagerHelper::sanitize_css_selector($identifier)) ?>";					$.post(						"<?php _e(admin_url('admin-ajax.php')) ?>",						{ action: "get_encrypted_url", att_id: attachment.id },						function (resp)						{							if ("<?php _e($identifier) ?>" === "programs_metabox_fields[pdf_male]")							{								var encIdentifier = "<?php _e(PostMetaManagerHelper::sanitize_css_selector('programs_metabox_fields[male_enc_url]')) ?>";							}							else if ("<?php _e($identifier) ?>" === "programs_metabox_fields[pdf_female]")							{								var encIdentifier = "<?php _e(PostMetaManagerHelper::sanitize_css_selector('programs_metabox_fields[female_enc_url]')) ?>";							}							$("#" + encIdentifier).val(resp);						}					);				}			});		}			imageMediaPopup.on("open", function () { mediaUploaderOpen(imageMediaPopup); });		imageMediaPopup.on("select", function () { mediaUploaderSelect(imageMediaPopup); });	});</script>		