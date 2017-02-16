jQuery(document).ready(function($) {

	window.codeopus_tb_height = (92 / 100) * jQuery(window).height();
    window.codeopus_shortcodes_height = (71 / 100) * jQuery(window).height();
    if(window.codeopus_shortcodes_height > 550) {
        window.codeopus_shortcodes_height = (78 / 100) * jQuery(window).height();
    }

    jQuery(window).resize(function() {
        window.codeopus_tb_height = (92 / 100) * jQuery(window).height();
        window.codeopus_shortcodes_height = (71/ 100) * jQuery(window).height();

        if(window.codeopus_shortcodes_height > 550) {
            window.codeopus_shortcodes_height = (78 / 100) * jQuery(window).height();
        }
    });

    var codeopuss = {
	
		
		optcolorpicker:function()
		{
		
		var $color_inputs = $('input.cdo-popup-colorpicker');
		
		$color_inputs.each(function(){
		
		$(this).minicolors({
					change: function(hex, opacity) {
                        var log;
                        try {
                            log = hex ? hex : 'transparent';
                            if( opacity ) log += ', ' + opacity;
                            console.log(log);
                        } catch(e) {}
                    },
                    theme: 'default'
                });
		
		});
		
		},
	
		getchosen:function()
		{
		// Chosen Select Box replacement
		$('.codeopus_icon').chosen({
			search_contains : true,
			width : "100%"
		});
		
		$('.codeopus-form-select ').chosen({
			disable_search: true,
			width : "100%"
		});
		
		},
	
    	loadVals: function()
    	{
    		var shortcode = $('#_codeopus_shortcode').text(),
    			uShortcode = shortcode;
    		
    		// fill in the gaps eg {{param}}
    		$('.codeopus-input').each(function() {
    			var input = $(this),
    				id = input.attr('id'),
    				id = id.replace('codeopus_', ''),		// gets rid of the codeopus_ prefix
    				re = new RegExp("{{"+id+"}}","g");
    				
    			uShortcode = uShortcode.replace(re, input.val());
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_codeopus_ushortcode').remove();
    		$('#codeopus-sc-form-table').prepend('<div id="_codeopus_ushortcode" class="hidden">' + uShortcode + '</div>');
			
    	},
    	cLoadVals: function()
    	{
    		var shortcode = $('#_codeopus_cshortcode').text(),
    			pShortcode = '';
    			shortcodes = '';
				
				if(shortcode.indexOf("<li>") < 0) {
    				shortcodes = '<br />';
    			} else {
    				shortcodes = '';
    			}
    		
    		// fill in the gaps eg {{param}}
    		$('.child-clone-row').each(function() {
    			var row = $(this),
    				rShortcode = shortcode;
    			
    			$('.codeopus-cinput', this).each(function() {
    				var input = $(this),
    					id = input.attr('id'),
    					id = id.replace('codeopus_', '')		// gets rid of the codeopus_ prefix
    					re = new RegExp("{{"+id+"}}","g");
    					
    				rShortcode = rShortcode.replace(re, input.val());
    			});
    	
				if(shortcode.indexOf("<li>") < 0) {
    				shortcodes = shortcodes + rShortcode + '<br />';
    			} else {
    				shortcodes = shortcodes + rShortcode;
    			}
				
    			//shortcodes = shortcodes + rShortcode + "\n";	
				
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_codeopus_cshortcodes').remove();
    		$('.child-clone-rows').prepend('<div id="_codeopus_cshortcodes" class="hidden">' + shortcodes + '</div>');
    		
    		// add to parent shortcode
    		this.loadVals();
    		pShortcode = $('#_codeopus_ushortcode').text().replace('{{child_shortcode}}', shortcodes);
    		
    		// add updated parent shortcode
    		$('#_codeopus_ushortcode').remove();
    		$('#codeopus-sc-form-table').prepend('<div id="_codeopus_ushortcode" class="hidden">' + pShortcode + '</div>');
			
    	},
    	children: function()
    	{
    		// assign the cloning plugin
    		$('.child-clone-rows').appendo({
    			subSelect: '> div.child-clone-row:last-child',
    			allowDelete: false,
    			focusFirst: false
    		});
    		
    		// remove button
    		$('.child-clone-row-remove').live('click', function() {
    			var	btn = $(this),
    				row = btn.parent();
    			
    			if( $('.child-clone-row').size() > 1 )
    			{
    				row.remove();
    			}
    			else
    			{
    				alert('You need a minimum of one row');
    			}
    			
    			return false;
    		});
    		
    		// assign jUI sortable
    		$( ".child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row'
				
			});
			
    	},
    	resizeTB: function()
    	{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window').addClass('cdo-shortcodes-popup'),
				codeopusPopup = $('#codeopus-popup');

            tbWindow.css({
                height: window.codeopus_tb_height,
                width: codeopusPopup.outerWidth(),
                marginLeft: -(codeopusPopup.outerWidth()/2)
            });

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: window.codeopus_tb_height,
				overflow: 'auto', // IMPORTANT
				width: codeopusPopup.outerWidth()
			});
			
			
			$('#codeopus-popup').addClass('no_preview');
			$('#codeopus-shortcode-wrap #codeopus-sc-form').height(window.codeopus_shortcodes_height);
    	},
    	load: function()
    	{
    		var	codeopuss = this,
    			popup = $('#codeopus-popup'),
    			form = $('#codeopus-sc-form', popup),
    			shortcode = $('#_codeopus_shortcode', form).text(),
    			popupType = $('#_codeopus_popup', form).text(),
    			uShortcode = '';
    		
    		// resize TB
    		codeopuss.resizeTB();
    		$(window).resize(function() { codeopuss.resizeTB() });
			
				
    		// initialise
    		codeopuss.loadVals();
    		codeopuss.children();
    		codeopuss.cLoadVals();
			codeopuss.getchosen();
			codeopuss.optcolorpicker();
			
			$( "#form-child-add" ).click(function() {
				codeopuss.getchosen();
				codeopuss.optcolorpicker();
			});
			
    		
    		// update on children value change
    		$('.codeopus-cinput', form).live('change', function() {
    			codeopuss.cLoadVals();
				codeopuss.getchosen();
				codeopuss.optcolorpicker();
    		});
    		
    		// update on value change
    		$('.codeopus-input', form).change(function() {
    			codeopuss.loadVals();
				codeopuss.getchosen();
				codeopuss.optcolorpicker();
    		});	
						    		
    		// when insert is clicked
    		$('.codeopus-insert', form).click(function() {    		 			
    			if(window.tinyMCE)
				{
					window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, $('#_codeopus_ushortcode', form).html()); //
					tb_remove();
				}
    		});
			
    	}
	}
	
    // run
    $('#codeopus-popup').livequery( function() {codeopuss.load(); });
});