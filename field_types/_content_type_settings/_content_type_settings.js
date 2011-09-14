jQuery(function($){
	$('#content-type-settings table tbody.title td.show-hide a').click(function(e){
		e.preventDefault();
		var text = $(this).text();
		if(text == 'Show'){
			$(this).text('Hide');
			$(this).parents('tbody').next().show();
		}else{
			$(this).text('Show');
			$(this).parents('tbody').next().hide();
		}
	});

	$('#advanced-setting-option a').click(function(){
		if($(this).text() == 'Show Advanced Options'){
			$('#advanced-settings').show();
			$(this).text('Hide Advanced Options');
			$('#settings-advanced-show').val('yes');
		}else if($(this).text() == 'Hide Advanced Options'){
			$('#advanced-settings').hide();
			$(this).text('Show Advanced Options');
			$('#settings-advanced-show').val('no');
		}
		return false;
	});
	$('#title').keyup(function(){
		$('#title-singular').val(make_singular($('#title').val()));
		$('#slug').val(string_to_slug($('#title-singular').val()));
	});
	$('#use-category').click(function(){
		$("#use-category1").toggle(this.checked);
		$("#use-category2").toggle(this.checked);
	});
	$("#use-category1 input").keyup(function(){
		$('#use-category2 input').val(make_singular($(this).val()));
	});
	$('#use-tags').click(function(){
		$("#use-tags1").toggle(this.checked);
		$("#use-tags2").toggle(this.checked);
	});
	$("#use-tags1 input").keyup(function(){
		$('#use-tags2 input').val(make_singular($(this).val()));
	});
	
	$('#menu-icon-row select').change(function(){	
		$('#menu-icon-row img').attr('src', $('#menu-icon-row input').val() + ''+ $(this).val());
	});
	
	$('#show_menu_select').change(function(){
		if($(this).val() == '1'){
			$('#show_menu_options input[name="arguments[show_ui]"]').val(1);
			$('#show_menu_options').show();
		}else if($(this).val() == '2'){
			$('#show_menu_options input[name="arguments[show_ui]"]').val(1);
			$('#show_menu_options').hide();
		}else{
			$('#show_menu_options input[name="arguments[show_ui]"]').val('');
			$('#show_menu_options').hide();
		}
	});
	
	if($('#rewrite_type_select').val() == '2')
		$('#rewrite_options').show();
	$('#rewrite_type_select').change(function(){
		if($(this).val() == '1'){
			$('#rewrite_options').hide();
			$('#rewrite_options input[name="arguments[rewrite_slug]"]').val($('#slug').val());
			$('#rewrite_options input[name="arguments[rewrite_with_front]"]').attr('checked', false);
		}else if($(this).val() == '2'){
			$('#rewrite_options').show();
		}else{
			$('#rewrite_options').hide();			
		}
	});
	
	$('#rewrite_options input.rewrite_slug').keyup(function(){
		rewrite_slug($('#rewrite_options'));
	});
	$('#rewrite_options input.rewrite_with_front').change(function(){
		rewrite_slug($('#rewrite_options'));
	});
	
});
function rewrite_slug(rewrite){
	rewrite.find('.slug-sample').val(
		(rewrite.find('input.rewrite_with_front').attr('checked') && rewrite.find('.front').val() ?
			rewrite.find('.front').val()+'/' : '')
		+ (rewrite.find('input.rewrite_slug').val() ? 
			rewrite.find('input.rewrite_slug').val() + '/' : '') 
		+ '%postname%'
	);
}
function make_singular(word){
	var ends = {'ies':'y','xes':'x','oes':'o','ies':'y','ves':'f','os':'o','s':''};	
	for(p_end in ends){
		if(p_end == word.substring(word.length-p_end.length))
			return word.substring(0, word.length-p_end.length) + ends[p_end];
	}
	return word;
}
function string_to_slug(str) {
	str = str.replace(/^\s+|\s+$/g, ''); // trim
	str = str.toLowerCase();
	
	// remove accents, swap ñ for n, etc
	var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
	var to   = "aaaaeeeeiiiioooouuuunc------";
	for (var i=0, l=from.length ; i<l ; i++) {
	str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}
	
	str = str.replace(/[^a-z0-9 -]/g, '-') // remove invalid chars
	.replace(/\s+/g, '-') // collapse whitespace and replace by -
	.replace(/-+/g, '-'); // collapse dashes
	
	return str;
}