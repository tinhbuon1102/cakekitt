(function ()
{
	"use strict";

	// create CDOShortcodes plugin
	tinymce.create("tinymce.plugins.CDOShortcodes",
	{
		
		init: function ( editor, url )
		{
		
		
			editor.addCommand("cdoPopup", function ( a, params )
			{
				var popup = params.identifier;
				
				// load thickbox
				tb_show("Codeopus Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
			});
			
			// Add a button that opens a window
			editor.addButton('cdo_button', {
				type: 'menubutton',
				text: 'Codeopus Shortcodes',
				icon: false,
				onclick : function(e) {},
				menu: [
				
				{text: 'Section',onclick:function(){
					editor.execCommand("cdoPopup", false, {title: 'Section',identifier: 'section'})
				}},
				
				{text: 'Row & Columns',onclick:function(){
					editor.execCommand("cdoPopup", false, {title: 'Row & columns',identifier: 'row'})
				}},
				
				{text: 'Columns',onclick:function(){
					editor.execCommand("cdoPopup", false, {title: 'columns',identifier: 'column'})
				}},
				
				{
				text: 'Elements',
					menu: [
					{text: 'Accordion',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Accordion',identifier: 'accordion'})
					}},
					{text: 'Button',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Button',identifier: 'button'})
					}},
					{text: 'Div',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Div',identifier: 'div'})
					}},
					{text: 'Dropcap',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Dropcap',identifier: 'dropcap'})
					}},
					{text: 'Google Map',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Google Map',identifier: 'gmap'})
					}},
					{text: 'Heading',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Heading',identifier: 'heading'})
					}},
					{text: 'Icon',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Icon',identifier: 'icon'})
					}},
					{text: 'List',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'List',identifier: 'list'})
					}},
					{text: 'Paragraph',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Paragraph',identifier: 'paragraph'})
					}},
					{text: 'Small',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Small',identifier: 'small'})
					}},
					{text: 'Span',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Span',identifier: 'span'})
					}},
					{text: 'Spacer',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Spacer',identifier: 'spacer'})
					}},
					{text: 'Tab',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Tab',identifier: 'tabs'})
					}},
					{text: 'Table',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Table',identifier: 'table'})
					}},
					{text: 'Toggle',onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Toggle',identifier: 'toggle'})
					}},
					]
				},
				{
				text : 'Content',
					menu: [
					{text: 'Banner', classes:'cdoBanner', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Banner',identifier: 'banner'})
					}},
					{text: 'Box with Text', classes:'cdoBoxwithText', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Box with Text',identifier: 'box_withtext'})
					}},
					{text: 'Icon with Text', classes:'cdoIconwithText', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Icon with Text',identifier: 'icon_withtext'})
					}},
					{text: 'Image with Text', classes:'cdoImagewithText', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Image with Text',identifier: 'img_withtext'})
					}},
					{text: 'Lookbook Content', classes:'cdoLbContent', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Lookbook Content',identifier: 'lookbook'})
					}},
					{text: 'Lookbook Footer', classes:'cdoLbFooter', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Lookbook Footer',identifier: 'lookbookfooter'})
					}},
					{text: 'Lookbook Header', classes:'cdoLbHeader', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Lookbook Header',identifier: 'lookbookheader'})
					}},
					{text: 'Cake Messes', classes:'cdoCakemesses', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Cake Messes Animation',identifier: 'messes'})
					}},
					{text: 'Cake Pricing Tables', classes:'cdoCakePricingTables', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Pricing Tables',identifier: 'pricing'})
					}},
					{text: 'Portfolio', classes:'cdoPortfolio', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Portfolio',identifier: 'portfolio'})
					}},
					{text: 'Newsflash', classes:'cdoNewsflash', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Newsflash',identifier: 'newsflash'})
					}},
					{text: 'Product Category', classes:'cdoProductcategory', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Product Category',identifier: 'products'})
					}},
					{text: 'Product Category', classes:'cdoProductcategory2', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Product Category',identifier: 'product_category'})
					}},
					{text: 'Product Slider', classes:'cdoProductslider', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Product Slider',identifier: 'products_slider'})
					}},
					{text: 'Team', classes:'cdoTeam', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Team',identifier: 'team'})
					}},
					{text: 'Testimonial', classes:'cdoTestimonialr', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Testimonial',identifier: 'testimonial'})
					}},
					{text: 'Testimonial Slider', classes:'cdoTestimonialSlider', onclick:function(){
						editor.execCommand("cdoPopup", false, {title: 'Testimonial Slider',identifier: 'testislider'})
					}},
					]	
				},
				]
			});
			
		}
		
	});
	
	// add CDOShortcodes plugin
	tinymce.PluginManager.add("CDOShortcodes", tinymce.plugins.CDOShortcodes);
})();