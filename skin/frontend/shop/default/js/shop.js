/** 
 *	Resolve conflict with default prototype library
 *  use #j to define jQuery namespace
*/
var $j = jQuery.noConflict();


/** DOM Ready Event */

$j('document').ready(function(){
	//hoverLogo();
	
});


/** Hover logo image */
function hoverLogo(){
	$j('.logo img').hover(function(){
		
		this.src = '../skin/frontend/shop/default/images/logo-over.png';
		
	},function(){
		
		this.src = '../skin/frontend/shop/default/images/logo.png';
		
	});

}
	