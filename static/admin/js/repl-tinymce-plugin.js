jQuery(document).ready(function($) {
	tinymce.create('tinymce.plugins.repl', {
		init: function(ed, url) {
			ed.addCommand('repl_insert_shortcode', function() {
				$('<div id="replSelectShortcodeDialog" title="Select Shortcode" style="display:none;"><p>Select which shortcode to use:</p></div>')
				.appendTo('body');
				$('#replSelectShortcodeDialog').dialog({
				 	buttons: {
				 		"All Listings": function () {
				 			content = '[all-listings][/all-listings]';
				 			tinymce.execCommand('mceInsertContent', false, content);
				 			$(this).dialog('close');
				 		},
						"For Sale": function() {
							content = '[for-sale][/for-sale]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						"For Lease": function () {
							content =  '[for-lease][/for-lease]';
							tinymce.execCommand('mceInsertContent', false, content);
							$(this).dialog('close');
						},
						Cancel: function() {
							$(this).dialog('close');
						}
					}
				});
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