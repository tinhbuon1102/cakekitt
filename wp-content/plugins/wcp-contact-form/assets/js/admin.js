(function($) {  
    $(document).ready(function() { 
        $('.scfp-color-picker').wpColorPicker();    
        if (csvVar.active) {
            var csv = '<div class="alignleft actions"><a class="button" title="Export to CSV" href="'+csvVar.href+'">Export to CSV</a></div>'
            $(csv).insertAfter($('.tablenav.top').find('.alignleft.actions:last'));
        }

        $('.scfp-form-wrap .scfp-settings-content [id="scfp_notification_settings[email_from]"]').closest('tr').addClass('scfp-warning-hint');
        $('.scfp-form-wrap .scfp-settings-content [id="scfp_notification_settings[email_from_name]"]').closest('tr').addClass('scfp-warning-hint');
        $('.scfp-form-wrap .scfp-settings-content [id="scfp_notification_settings[user_email_from]"]').closest('tr').addClass('scfp-warning-hint');        
        $('.scfp-form-wrap .scfp-settings-content [id="scfp_notification_settings[user_email_from_name]"]').closest('tr').addClass('scfp-warning-hint');
        
        $('.scfp-warning-hint .scfp-field-settings-notice span.dashicons-editor-help').removeClass('dashicons-editor-help').addClass('dashicons-warning');
        
        $('.scfp-mode').on('change', function() {
            $('.scfp-mode-item').hide();
            $('.scfp-mode-' + $(this).val()).show();
        });
        
        
        $('.scfp-field-extended-settings-choices-list-box-data').sortable({
            helper: function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            scroll: true,
            placeholder: "ui-sortable-placeholder",
            stop: function(event,ui) {sortChoices('.scfp-field-extended-settings-choices-list-box-data');}            
        });   

        
        $(window).click(function (event) {
            event = event || window.event;
            if ($(event.target).closest('.scfp-field-settings-notice').length > 0 && ( $(event.target).hasClass('dashicons-editor-help') || $(event.target).hasClass('dashicons-warning'))) {
                var el = $(event.target).closest('.scfp-field-settings-notice').children('.description');
                if ($(event.target).closest('.scfp-field-settings-notice').hasClass('open')) {
                    el.fadeOut(100);                
                    $(event.target).closest('.scfp-field-settings-notice').removeClass('open');
                } else {
                    $('.scfp-field-settings-notice').each(function() {
                       $(this).removeClass('open');
                       $(this).children('.description').fadeOut(100);
                    }); 
                    el.fadeIn(100);                
                    $(event.target).closest('.scfp-field-settings-notice').addClass('open');
                }                
            } else if ($(event.target).closest('.scfp-field-settings-notice').length > 0 && $(event.target).hasClass('description')) {
                return;
            } else {
                $('.scfp-field-settings-notice').each(function() {
                   $(this).removeClass('open');
                   $(this).children('.description').fadeOut(100);
                });                                                 
            }
        });
        
        $(document).on('change', '.scfp-field-type select', function(e) {
            var container = $(this).closest('.scfp-field-row').find('.scfp-field-extended-settings-box');
            
            var data = {};
            data.action = 'getExtendedSettingsTemplate';
            data.nonce = csvVar.ajax_nonce;
            data.type = $(this).val();
            data.params = $(container).find('.scfp-field-extended-settings').data('params');
            data.enabled = !$(container).find('.scfp-field-extended-settings').hasClass('disabled');

            $.ajax({
                url:csvVar.ajax_url,
                type: 'POST' ,
                data: data,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    var index = $(container).closest('.scfp-field-row').data('key');
                    data.template = data.template.replace(/\[0\]/g, '[' + index + ']').replace(/_0_/g, '_' + index + '_');

                    $(container).html(data.template);
                    if ( typeof data.hasTemplate != 'undefined') {
                        $(container).closest('.scfp-field-row').find('.agp-settings-row').removeClass('disabled');
                        $(container).find('.scfp-field-extended-settings-choices-list-box-data').sortable({
                            helper: function (e, ui) {
                                ui.children().each(function () {
                                    $(this).width($(this).width());
                                });
                                return ui;
                            },
                            scroll: true,
                            placeholder: "ui-sortable-placeholder",
                            stop: function(event,ui) {sortChoices('.scfp-field-extended-settings-choices-list-box-data');}
                        });                                                      
                    } else {
                        $(container).closest('.scfp-field-row').find('.agp-settings-row').addClass('disabled');
                    }
                },
                error: function (request, status, error) {
                }
            });            
            
        });
        
        
        $(document).on('click', '.agp-settings-row', function(e) {
            e.preventDefault();
            var container = $(this).closest('.scfp-field-row').find('.scfp-field-extended-settings');
            
            if ( $(container).length > 0 ) {
                var $disabled = $(container).hasClass('disabled');
                
                $('.scfp-field-extended-settings').slideUp();
                $('.scfp-field-extended-settings').addClass('disabled');
                $('.scfp-field-row').removeClass('scfp-active-row');                
                
                if ( $disabled ) {
                    $(container).slideDown();
                    $(container).removeClass('disabled');
                    $(container).closest('.scfp-field-row').addClass('scfp-active-row');
                }
            }
            
            return false;
        });
        
        $(document).on('click', '.extended-settings-add-row', function(e) {
            var content = $(this).closest('.scfp-field-extended-settings-choices-list-box').find('.scfp-field-row-template').html();
            var index = 'xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {var r = Math.random()*16|0,v=c=='x'?r:r&0x3|0x8;return v.toString(16);});
            content = '<div class="scfp-field-extended-settings-choices-list-box-data-row scfp-field-row ui-sortable-handle">' + content.replace(/\(00\)/g, index) + '</div>';
            
            $(this).closest('.scfp-field-extended-settings-choices-list-box').find('.scfp-field-extended-settings-choices-list-box-data').append(content);
            sortChoices('.scfp-field-extended-settings-choices-list-box-data');
        });        
        
        
        $(document).on('click', '.extended-settings-del-row', function(e) {
           $(this).closest('.scfp-field-extended-settings-choices-list-box-data-row').remove();
           sortChoices('.scfp-field-extended-settings-choices-list-box-data');
        });        
        
        $(document).on('click', '.extended-settings-up-row', function(e) {            
            var el = $(this).closest('.scfp-field-row');
            var prev = $(el).prev('.scfp-field-row');
            $(el).insertBefore(prev);
            sortChoices('.scfp-field-extended-settings-choices-list-box-data');
        });            

        $(document).on('click', '.extended-settings-down-row', function(e) {                        
            var el = $(this).closest('.scfp-field-row');
            var next = $(el).next('.scfp-field-row');
            $(el).insertAfter(next);
            sortChoices('.scfp-field-extended-settings-choices-list-box-data');
        });                            
        
        $(document).on('change', '.scfp-field-extended-settings-row .scfp-field-extended-settings-choices-mode', function(e) {
            e.preventDefault();
            var mode = $(this).val();
            
            $(this).closest('.scfp-field-extended-settings-row').find('.scfp-field-extended-settings-choices-item').each(function() {
                if ($(this).hasClass('scfp-field-extended-settings-choices-' + mode)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            return false;
        });
        
        
        function sortChoices(tableID) {     
            $(tableID).each( function () {
                var i = 0;
                $(this).find(".scfp-field-row").each(function(index, value) {
                    $(this).find('.scfp-field-priority').html(++i);
                });                
            });
        }        
        
        $(document).on('click', '.extended-settings-choices-list-edit-values', function(e) {                        
            var checked = $(this).attr('checked');
            if (checked == 'checked') {
                $(this).closest('.scfp-field-extended-settings-choices-list').find('.list-edit-values').show();
            } else {
                $(this).closest('.scfp-field-extended-settings-choices-list').find('.list-edit-values').hide();
            }
        });         
        

        $(document).on('click', '.extended-settings-choices-list-multiselect', function(e) {                        
            var checked = $(this).attr('checked');
            var el = $(this).closest('.scfp-field-extended-settings-choices-list').find('.scfp-field-default input');
            var row = $(el).data('row');
            var id = $(el).data('id');
            var key = $(el).data('key');
            if (checked == 'checked') {
                $(el).attr('type', 'checkbox');
            } else {
                $(el).attr('type', 'radio');
            }
        });  

        $(document).on('change', 'input.scfp-choice-type-radio', function(e) {                        
            $(this).attr('checked','checked');
        });          
        
        
        $(document).on('click', 'input.scfp-choice-type-radio', function(e) {                        
            $(this).removeAttr('checked');
        });                  
        
        
        $(document).on('change', '.scfp-field-extended-settings-captcha-type', function(e) {
            var container = $(this).closest('.scfp-field-row').find('.scfp-field-extended-settings-box');

            var data = {};
            data.action = 'getExtendedSettingsCaptchaTemplate';
            data.nonce = csvVar.ajax_nonce;
            data.type = $(this).val();
            data.params = $(container).find('.scfp-field-extended-settings').data('params');

            $.ajax({
                url:csvVar.ajax_url,
                type: 'POST' ,
                data: data,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $(container).find('.scfp-field-extended-settings-captcha-box').html(data.template);
                },
                error: function (request, status, error) {
                }
            });            
            
        });        
    });
})(jQuery);


