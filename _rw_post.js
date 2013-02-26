// JavaScript Document
jQuery(function($){	
	
	$.ctrl('S', function(){
		$('form#post').submit();
	});	
	var nopost = true;
	$('form#post').submit(function(e){	
		if(!nopost)	return true;
		var f = eval($('#required_meta_fields').val());
		var errors = [];
		for(x in f){
			var input = $('#'+f[x].slug+'-meta .required-meta-field');
			if(input.size() > 0)
			if(
				(input.attr('name').substr(input.attr('name').length-2) == '[]' 
					&& input.attr('type') == 'checkbox' 
						&& !input.is(':checked'))
				||
				(input.val().length == 0)
			){
				errors.push('<li><a href="#'+f[x].slug+'-meta">'+f[x].title+'</a></li>');
				$('#'+f[x].slug+'-meta').css('border-color','#D99').find('.title').css('color','#c00');
			}else{
				$('#'+f[x].slug+'-meta').css('border-color','#DFDFDF').find('.title').css('color','#464646');
			}
		}
		if(errors.length > 0){
			e.preventDefault();
			if($('#error-message').size() == 0)
				$(this).before('<div id="error-message" class="error below-h2"><p>Post has not been updated. Please fill in the required fields: <ul>'+errors.join('')+'</ul></p></div>');
			else{
				$('#error-message ul').html(errors.join(''));
			}			
			$('#ajax-loading').hide();
			$('#publish').removeClass('button-primary-disabled');
			return false;
		}else{
			nopost = false;
			$('#ajax-loading').show();
			$('#publish').addClass('button-primary-disabled');
		}
	});

});
jQuery.ctrl = function(key, callback, args) {
    var isCtrl = false;
    jQuery(document).keydown(function(e) {
        if(!args) args=[]; // IE barks when args is null

        if(e.ctrlKey) isCtrl = true;
        if(e.keyCode == key.charCodeAt(0) && isCtrl) {
            callback.apply(this, args);
            return false;
        }
    }).keyup(function(e) {
        if(e.ctrlKey) isCtrl = false;
    });
};