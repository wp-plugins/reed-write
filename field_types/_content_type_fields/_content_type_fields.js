jQuery(function($){

	$("#field-builder .fields").sortable({
	   'update' : function(event, ui) { order_fields(); },
	   'placeholder': 'field-placeholder'
	});

	$('#field-builder .field').each(function(i,e){		
		if($(e).find('tr.type-options td').size() == 1)
			$(e).find('tr.type-options').hide();
			
		if($(e).find('input.show-advanced').val() == '1'){
			$(e).find('a.advanced').attr('title','Hide advanced options.');
			$(e).find('a.advanced').html('&ndash;');
			$(e).find('tr.advanced').show();
			return true;
		}
		$(e).find('tr.advanced').hide();
	});
	
	$('#field-builder .add-field a').live('click', function(e){
		$('#field-builder .fields').prepend(field_row);
		order_fields();
		return false;
	});
	
	$('#field-builder a.delete').live('click', function(e){
		if(confirm('Are you sure you want to delete this?'))
			$(this).parents('.field').remove();		
		order_fields();
		return false;
	});
	
	$('#field-builder input.name-title').live('keyup', function(){
		$(this).parents('tbody').find('input.name-slug')
			.val(make_field_slug($(this).val()));
	});
	
	$('#field-builder select.type').live('change', function(){
		var field = $(this).parents('.field');
		field.find('tr.type-options').hide();		
		for(x in field_type_edit)
			if(field_type_edit[x][0] == $(this).val() && field_type_edit[x][2]){
				field.find('tr.type-options').show();
				if(field.find('tr.type-options td').size() == 1)
					field.find('tr.type-options').html(field_type_options.replace(/(fields\[)([0-9]*)/g, 'fields['+field.data('field-i')));
			}
	});
	
	function order_fields(){
		$('#field-builder .fields .field').each(function(i, e){i++;			
			$(e).attr('id','field-'+i).data('field-i', i)
			.find('input, select, textarea').each(function(ii, e){
				$(e).attr('name', $(e).attr('name').replace(/(fields\[)([0-9]*)/g, 'fields['+i));
			});
		});
	}
	order_fields();
});
function make_field_slug(text) {
    text = ('_' + text.toLowerCase().replace(/(<([^>]+)>)/g,'_').replace(/[^0-9a-z]/g,'_') + '_');
    while(text.indexOf('__')>-1) text = text.replace(/__/g,'_');    
    return text.substring(1, (text.length-1));
}