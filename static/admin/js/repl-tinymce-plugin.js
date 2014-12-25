jQuery(document).ready(function($) {
	tinymce.create('tinymce.plugins.repl', {
		init: function(ed, url) {
			ed.addCommand('repl_insert_shortcode', function() {
				var dialogContent = '<div id="replSelectShortcodeDialog" title="Select Shortcode" style="display:none;"><p>Select which shortcode to use:</p>';
				dialogContent += '<fieldset>';
				dialogContent += '<label for="property_type">Property Type:</label>';
				dialogContent += '<input type="text" name="property_type" id="property_type" value="" class="text ui-widget-content ui-corner-all">';
				dialogContent += '</fieldset>';
				dialogContent += '</div>';
				$(dialogContent)
				.appendTo('body');
				$('#replSelectShortcodeDialog').dialog({
					buttons: {
						"All Listings": function () {
							content = '[all-listings';
							if($('#replSelectShortcodeDialog').find('#property_type').val().length > 0) {
								content += ' type="'+$('#replSelectShortcodeDialog').find('#property_type').val()+'"';
							}
							content += '][/all-listings]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"For Sale": function() {
							content = '[for-sale';
							if($('#replSelectShortcodeDialog').find('#property_type').val().length > 0) {
								content += ' type="'+$('#replSelectShortcodeDialog').find('#property_type').val()+'"';
							}
							content += '][/for-sale]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"For Lease": function () {
							content = '[for-lease';
							if($('#replSelectShortcodeDialog').find('#property_type').val().length > 0) {
								content += ' type="'+$('#replSelectShortcodeDialog').find('#property_type').val()+'"';
							}
							content += '][/for-lease]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"Map": function () {
							content = '[listings-map';
							if($('#replSelectShortcodeDialog').find('#property_type').val().length > 0) {
								content += ' type="'+$('#replSelectShortcodeDialog').find('#property_type').val()+'"';
							}
							content += '][/listings-map]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"Featured": function () {
							content = '[all-featured][/all-featured]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"Broker listings": function () {
							content = '[all-broker-listings][/all-broker-listings]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						Cancel: function() {
							$(this).dialog('close');
						}
					}
				});
				$('.ui-dialog').css('zIndex',9999); // required as the dialog is overlapped with TynyMCE controls bar
			});
			ed.addButton('repl',
			{
				title : 'Insert BuzzTarget shortcode',
				cmd : 'repl_insert_shortcode',
				image: url + '/buzztarget-shortcode-icon.png' // Image
			});
		}
	});
	tinymce.PluginManager.add('repl', tinymce.plugins.repl);
});