jQuery(document).on('click',".add_field",function(event) {
    var row = jQuery(this).parents("div.clone").last().clone({
        withDataAndEvents: true
    }).insertAfter(".clone:last").find('input').val('');
    jQuery("div.clone:last").find('input[type="checkbox"]').removeAttr('checked');
    var rowCount = jQuery('.add-new-text-field-wrap .clone').length;
    jQuery(".clone").last().find('input[type="checkbox"]').val(rowCount);
    jQuery(this).addClass('remove_field').removeClass('add_field').html('Remove');
});
jQuery(document).on('click',".remove_field",function (event) {
    jQuery(this).parent().parent().remove();
    jQuery.each(jQuery('.clone input[name="require[]"]'), function(i,val){
    	if((jQuery(this).val()) ){
            jQuery(this).val(i+1);
        }
    });
    jQuery.each(jQuery('.clone input[name="unique[]"]'), function(i,val){
    	if((jQuery(this).val()) ){
            jQuery(this).val(i+1);
        }
    });
});
jQuery(function(){
	jQuery.each( jQuery('.input-group.floating input'), function( i, val ){
		var text_val = jQuery(this).val();
		if(text_val === "") {
		  jQuery(this).removeClass('has-value');      
		} else {
		  jQuery(this).addClass('has-value'); 
		}
	});
	jQuery('.input-group.floating input').focusout(function(){
		var text_val = jQuery(this).val();
		if(text_val === "") {
		  jQuery(this).removeClass('has-value');      
		} else {
		  jQuery(this).addClass('has-value'); 
		}
	});
});