/**
 * Reman IE 8 and IE 9 HTML5 Placeholders simulator;
 * Chrom browser HTML5 PlaceHolders behaviour simulator 
 * @category    Reman
 * @package     frontend_reman_default_js
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
 
function initIEPlaceholders(){
	// get all input DOM elements
	var input = document.createElement("input");
	// Check for browser Placeholder support
    if(('placeholder' in input)==false) { 
	    // create fake placeholder labels for all inputs with placeholder attribute
		$j('[placeholder]').each(function(){
			$j(this).before('<span class="ie-placeholder">'+$j(this).attr('placeholder')+'</span>');
		});
		
		$j('.ie-placeholder').click(function(e){
			$j(e.target).next().focus();
			if($j(e.target).next().hasClass('validation-failed')){
				$j(e.target).next().removeClass('validation-failed');
			}
		});
		// Events Input Focus and Input Blur
		$j('[placeholder]').focus(function(e) {
			
			$j(e.target).keydown(function(e) {
				if(event.keyCode >= 48 && event.keyCode <= 90) {
					//the key pressed was alphanumeric
					$j(e.target).prev().hide();
				}
			});
			
			$j(e.target).keyup(function(e) {
				if($j(e.target).val() == '' ){
					$j(e.target).prev().show();
				}
			});
			
		}).blur(function(e) {
			if($j(e.target).val() == ''){
			 $j(e.target).prev().show();
			}
		});
	}
}