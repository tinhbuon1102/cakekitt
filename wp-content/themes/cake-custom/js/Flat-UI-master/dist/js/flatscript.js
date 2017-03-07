jQuery(document).ready(function(){
        jQuery('select[name="inverse-dropdown"], select[name="inverse-dropdown-optgroup"], select[name="inverse-dropdown-disabled"]').select2({dropdownCssClass: 'select-inverse-dropdown'});

        jQuery('select[name="searchfield"]').select2({dropdownCssClass: 'show-select-search'});
        jQuery('select[name="inverse-dropdown-searchfield"]').select2({dropdownCssClass: 'select-inverse-dropdown show-select-search'});
      });