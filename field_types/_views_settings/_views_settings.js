jQuery(function($){});
function rewrite_slug(rewrite){
	rewrite.find('.slug-sample').val(
		(rewrite.find('input.rewrite_with_front').attr('checked') && rewrite.find('.front').val() ?
			rewrite.find('.front').val()+'/' : '')
		+ (rewrite.find('input.rewrite_slug').val() ? 
			rewrite.find('input.rewrite_slug').val() + '/' : '') 
		+ '=%taxonomyname%'
	);
}
function make_singular(word){
	var ends = 'os=o&ies=y&xes=x&oes=o&ies=y&ves=f&s= '.split('&');		
	for(i in ends){
		p_end = ends[i].split('=')[0];
		s_end = ends[i].split('=')[1];
		s_end = (s_end == ' ') ? '' : s_end;
		if(p_end != word.substring(word.length-p_end.length)) continue;
		return word.substring(0, word.length-p_end.length) + s_end; break;
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