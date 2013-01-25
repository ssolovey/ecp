/**
 * Online Quick Quote frontend logic
 *  
 * @category    Reman
 * @package     frontend_reman_default_js
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
 
/*Resolve conflicts with prototype js default library*/
var $j = jQuery.noConflict();

/* Document ready event*/
$j(document).ready(function(){
	
	/*Initiate events on Quick Quote links */
	$j('#steps').bind('click',function(event){
		Reman_QuickQuote.prototype.eventsHandler(event);
	});
	
	/* Slide Down About reman list*/
	$j('#about_reman_link').bind('mouseenter', function(){
		$j('.reman_about_link').addClass('hover');
		$j("#dropdown_menu").animate({
			height: '190px'
		},500);
		$j("#dropdown_menu").clearQueue();
	});
	/* Slide DUp About reman list*/
	$j('#about_reman_link').bind('mouseleave', function(event){
		$j('.reman_about_link').removeClass('hover');
		$j("#dropdown_menu").animate({
			height: '0px'
		},500);
		$j("#dropdown_menu").clearQueue();
	});
});

/*Create NameSpace for Quick Quote module*/

function Reman_QuickQuote() {};

Reman_QuickQuote.prototype = {
	// Flags
	isYearActive: false,
	isModelActive: false,
	isGroupActive: false,
	
	// Current Selected values
	currentSelectedMake: '',
	currentSelectedYear: '',
	currentSelectedModel: '',
	currentCatSelected:'',
	currentPartRootSelected:[],
	currentPartNumber:'',

	/*General Event Handler*/
	eventsHandler: function(event){
		//current clicked element
		var elem = event.target;
		// if Link return
		if(elem.tagName == 'A'){ 
			return;
		}
		
		//Hide error popup
		$j('#product_error_popup').fadeOut();
		
		while (elem) {
			
			switch (elem.className){
				/*Event for Category Select*/	
				case 'cat_select':{
					//current Selected Category
					this.currentCatSelected = $j(elem).attr('cat');
					$j('#group_select').hide(); // hide category block
					$j('#make_tbl').show(); // show make block
					
					// update bread crumb link
					if(this.currentCatSelected == 'T'){
						$j('#step_1_message').html("You are looking for automation transmission? Well, you've come to the right place!");
					}else{
						//TODO When Trasfer Case will be enable !!!!!
					}
					return;
					
					break;
				}
				/*Event for Make Table*/
				case 'make_select':{
					// calculate production years
					var year_range = $j(elem).attr('endyear') - $j(elem).attr('startyear');
					// current Selected Make
					this.currentSelectedMake = $j(elem).attr('name');
					// Select Make
					this.selectMake($j(elem).attr('value'),Number(year_range),Number($j(elem).attr('startyear')));
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					return;
					
					break;
				}
				/*Event for Year Table*/
				case 'year_select': {
					//current Selected Year
					this.currentSelectedYear = $j(elem).html();
					// selectYear(makeid,year)
					this.selectYear($j(elem).attr('value'), $j(elem).attr('year'));
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();		
					return;
					
					break;
				}
				/*Event for Model Table*/
				case 'model_select': {
					// selectModel(vehicle_id,name)
					this.selectModel($j(elem).attr('value'), elem.innerHTML);
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					return;
					
					break;
				}
			
				case 'parts_select':{
					//selectPart(applic_id,subgroup,id,name)
					this.selectPart($j(elem).attr('value'),$j(elem).attr('type'),elem.parentElement.parentElement.id, elem.innerHTML);
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					return;
				
					break;
				}
			
			}
			/******************************************** BREADCRUMBS EVENTS ************************************************/
			if($j('#breadcrumb_info').hasClass('disabled')){
				return;
			}
			/*Event for Breadcrumbs Goupe links*/
			if (elem.className == 'breadcrumb group_link') {
				$j('.select_part').hide() // hide  groups
				$j('#'+$j(elem).attr('prevgroup')).show(); // show next group according to subgroup ID
				$j('#reman-invent_info').hide();
				$j('#reman-invent_info').html('');
				// deleted other groups
				if($j(elem).parent().next().length){
					$j(elem).parent().nextAll().remove();
					$j('#reman-product_info').hide(); // hide product info block
					$j('#parts_tbl').show(); // show parts table
					
					for(var y=0;y<=$j(elem).parent().next().length+1;y++){
						this.currentPartRootSelected.pop();
					}
					
				}else{
					this.currentPartRootSelected = [];
				}

				//delete this link
				$j(elem).parent().remove();
				this.resetSearchErrorResults();
				
				
				
				return;
			}

			/*Event for Breadcrumbs Make links*/
			if (elem.id == 'step_2'){
				this.turnOnMakeBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				return;
			}

			/*Event for Breadcrumbs Yearlinks*/

			if (elem.className == 'breadcrumb year_link'){
				this.turnOnYearBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				return;
			}

			/*Event for Breadcrumbs Model links*/
			if (elem.className == 'breadcrumb model_link'){
				this.turnOnModelBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				return;
			}
			
			/*Event for Breadcrumbs Cat links*/
			if (elem.id == 'step_1'){
				this.turnOnCatBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				return;
			}

			/*Event for Breadcrumbs Cat links*/
			if (elem.className == 'breadcrumb sel_group_link'){
				this.turnOnGroupBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected.pop();
				return;
			}
			elem = elem.parentNode;
		}
	},

	selectMake: function(makeid,range,startyear){
		var buffer = '<ul class="list list_first">'; // buffer string 
		for(var i = 1; i<=range; i++){  
			buffer += '<li class="year_select" value="'+makeid+'" year="'+(startyear+=1)+'">'+startyear+'</li>';
			if(i%10 == 0){
				buffer += '</ul><ul class="list">';
			}
		}
		//append new info to year table
		$j('#year_tbl').append(buffer);
		// hide make table
		$j('#make_tbl').hide();
		// set selected Make name to top menu
		$j('.selected_make').html(this.currentSelectedMake);
		//show year table
		$j('#year_tbl').show();
		// year table active true
		this.isYearActive = true;
		// update bread crumb link
		
		var vowels = ['A','E','I','O','U'];
		var prefix = 'A';
		for(var v = 0; v <= vowels.length -1; v++){
			if(this.currentSelectedMake[0] == vowels[v]){
				var prefix = 'An';
			}
		}
		
		
		$j('#step_2_message').html(prefix+' '+this.currentSelectedMake+" transmision? You're in luck! We've got bunches.");
		$j('#step_2_block').show();
		//$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb make_link">'+this.currentSelectedMake+'</span></span>');
	},
	/**
	 * Reset Search Errors Info
	*/
	resetSearchErrorResults: function(){
		//clear start over button link
		$j('#product_details_btn a').attr('href','#');
		//clear product info fields
		$j('.sku').remove();
		//reset Product DATA Error
		$j('.product_error').html('');
	},
	/**
	 * Clear Year Table
	*/
	clearYear: function(){
		//clear year
		$j('#year_tbl').hide();
		$j('#year_tbl .list').remove();
		$j('.year_link').parent().remove();
		this.isYearActive = false;
	},
	/**
	 * Clear Model Table
	*/
	clearModel: function(){
		//clear model
		$j('#model_tbl').hide();
		$j('#table_container').css('min-height', '');
		$j('#preloader_cont').css('height', '');
		$j('.model_select').parent().remove();
		$j('.model_link').parent().remove();
		this.isModelActive = false;
	},
	/**
	 * Clear Group Table
	*/
	clearGroup: function(){
		//clear group
		$j('#parts_tbl').hide();
		$j('.select_part').remove();
		$j('.group').hide();
		this.clearSubGroups();
		this.isGrouplActive = false;
	},
	/**
	 * Clear Make Table
	*/
	clearMake: function(){
		//clear Make
		$j('#make_tbl').hide();
		$j('.make_link').parent().remove();
	},
	/**
	 * Clear Subgroups Table
	*/
	clearSubGroups: function(){
		// clear subgroup
		if($j('.group_link').length){
			$j('.group_link').parent().remove();
			$j('.sel_group_link').parent().remove();
		}else{
			$j('.sel_group_link').parent().remove();
		}
		$j('.select_part').hide();
		//Show First Group
		$j($j('.select_part').get(0)).show();
	},
	/**
	 * Clear Product Table
	*/
	clearProductInfo: function(){
		//Inventary Info
		$j('#reman-invent_info').hide();
		$j('#reman-invent_info').html('');
		//Product Info
		$j('#reman-product_info').hide();
		$j('#reman-product_info').html('');
	},

	turnOnMakeBreadcrumb: function(){
		if(this.isYearActive){
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			$j('#step_2_block').hide();
			//$j('.make_link').parent().remove();
			//show make table
			$j('#make_tbl').show();
		}
	},
	
	turnOnYearBreadcrumb: function(){
		if(this.isModelActive){
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			$j('.year_link').parent().remove();
			//show year table
			$j('#year_tbl').show();
		}
	},
	turnOnModelBreadcrumb: function(){
		if(this.isGroupActive){
			this.clearGroup();
			this.clearProductInfo();
			$j('.model_link').parent().remove();
			$j('.sel_group_link').parent().remove();
			//show model table
			$j('#model_tbl').show();
		}
	},
	
	turnOnCatBreadcrumb: function(){
			this.clearMake();
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			//$j('.cat_link').remove();
			$j('#step_1_message').html("How we can help you today?");
			$j('#step_2_block').hide();
			//show cat table
			$j('#group_select').show();
			this.currentCatSelected = '';
	},

	turnOnGroupBreadcrumb: function(){
		this.clearProductInfo();
		$j('#parts_tbl').show();
		$j('.sel_group_link').parent().remove();
	},

	selectYear: function(makeid,year){
		var self = this;
		$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 2,  
					id:makeid,
					year: year
				},
				beforeSend: function(){
					$j('#year_tbl').hide();
					$j('#preloader_cont').css('display','block');
					$j('#breadcrumb_info').addClass('disabled');
				},
				complete: function(data){
					//parse response to JSON Object		  
					var response =  $j.parseJSON(data.responseText);
					// CHECK For DATA if NULL return
					if(response.length == 0){
						//show error popup
						$j('#product_error_popup').fadeIn();
						Reman_QuickQuote.prototype.turnOnYearBreadcrumb();
						$j('#preloader_cont').fadeOut(500,function(){
							$j('#breadcrumb_info').removeClass('disabled');
							$j('#year_tbl').show();
						});	
						return;
					}
				var buffer = '<ul class="list list_first">'; // buffer string 
					for(var i = 0; i<=response.length-1; i++){ // nest select with options 
						buffer += '<li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';
						if(i%15 == 0 && i!=0){
							buffer += '</ul><ul class="list">';
						}
					}
					$j('#model_tbl').append(buffer);
					$j('#preloader_cont').fadeOut(500,function(){
						$j('#breadcrumb_info').removeClass('disabled');
						$j('#model_tbl').show();
					});
				}
		});

		/*Update breadcrumb*/
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb year_link">'+this.currentSelectedYear+'</span></span>');
		this.isModelActive = true;
	},
	selectModel: function(vehicle_id,name){
		
			$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 3,  
					id:vehicle_id
				},
				beforeSend: function(){
					//hide model table
					$j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
					$j('#table_container').css('min-height', $j('#table_container').height() + 'px');
					$j('#model_tbl').hide();
					$j('#preloader_cont').css('display','block');
					$j('#breadcrumb_info').addClass('disabled');
				},
				complete: function(data){
						//parse response to JSON Object		  
						var response = $j.parseJSON(data.responseText);
						// CHECK For DATA if NULL return
						if(response.length == 0){
							$j('#preloader_cont').fadeOut(500,function(){
								$j('#breadcrumb_info').removeClass('disabled');
								$j('#model_tbl').show();
								//show error popup
								$j('#product_error_popup').fadeIn();
							});
							return;
						}
						var obj= {}; // create empty object to handle response data
						for(var i=0; i<=response.length-1;i++)						{

								if(response[i].applic == null){
								$j('#preloader_cont').fadeOut(500,function(){
										$j('#breadcrumb_info').removeClass('disabled');
										$j('#model_tbl').show();
										//show error popup
										$j('#product_error_popup').fadeIn();
									});
									return;
								}
							if($j.isEmptyObject(obj) || current_group != response[i].group_number ){
								obj[response[i].group_number] = {
									'heading': response[i].menu_heading,
									'applic' : [
													{
														name:response[i].applic , 
														id:response[i].applic_id,
														subgroup:response[i].subgroup

													}

												]

								}

							}else{
								obj[current_group].applic.push(
																{
																	name:response[i].applic,
																	id:response[i].applic_id, 
																	subgroup:response[i].subgroup
																}

															);

							}
								var current_group = response[i].group_number; 
						}
						Reman_QuickQuote.prototype.formSelectGroupBlock(obj,name);
				}
			});
	},
	
	formSelectGroupBlock: function(obj,name){
		for (key in obj){
			// buffer string 
			var buffer = "<ul class='select_part_box'>"; // buffer string 
			// heading
			var header = '';
			var block_counter = 0;
			
			for(var i = 0; i<=obj[key].applic.length-1; i++){
				// form link to part id
				buffer += '<li class="parts_select" type="'+obj[key].applic[i].subgroup+'" value="'+obj[key].applic[i].id+'">'+obj[key].applic[i].name+'</li>';
				if(obj[key].heading != null){
					header = obj[key].heading; 
				}else{
					header = 'Group';
				}
				// Group template
				if(i%20 == 0 && i!=0){
					block_counter +=1;
					buffer += '</ul><ul class="select_part_box block_'+block_counter+'">';
				}
			}
				var template = "<div id='"+key+"' class='select_part'>"+
										"<span class='label'>Select "+header+"</span>"+
										buffer
								  "</div>";
				// append to container
				$j('#parts_tbl').append(template);
				// Sort Values
				if($j('#'+key).find('ul').length >1){
					
					for(var x=0; x<= $j('#'+key).find('ul').length;x++){
						$j($j('#'+key).find('ul')[x]).find('li').sort(sortAlpha).appendTo($j('#'+key).find('ul')[x]);
					}
					
				}else{
				
					$j('#'+key).find('ul li').sort(sortAlpha).appendTo($j('#'+key).find('ul'));
				}
				
				//Show First Group
				$j($j('.select_part').get(0)).show();
		}
		this.currentSelectedModel = name;
			
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb model_link">'+this.currentSelectedModel+'</span></span>');
		this.isGroupActive = true;
		$j('#preloader_cont').fadeOut(500,function(){
			$j('#table_container').css('min-height', '');
			$j('#breadcrumb_info').removeClass('disabled');
			$j('#parts_tbl').show();
		});

	},

	selectPart: function(applic_id,subgroup,id,name){
			if(subgroup == 0) {
					$j.ajax({
						url: "index/ajax",
						type: 'POST',
						data: {
							step:4,
							id:applic_id,
							category: Reman_QuickQuote.prototype.currentCatSelected
						},

						beforeSend: function(){
							$j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
							$j('#table_container').css('min-height', $j('#table_container').height() + 'px');
							$j('#parts_tbl').hide();
							$j('#preloader_cont').show();
							$j('#breadcrumb_info').addClass('disabled');
						},

						complete: function(data){
							//parse response to JSON Object		  
							var response = $j.parseJSON(data.responseText);
								
							//Set current Group Name 
							Reman_QuickQuote.prototype.currentPartRootSelected.push(name);
							// Set Current Part Number Name
							Reman_QuickQuote.prototype.currentPartNumber = response.sku;
							// Load Product Page
							Reman_QuickQuote.prototype.loadProductInfo(applic_id,name);		
							// Load Invent Block
							Reman_QuickQuote.prototype.loadInventoryInfo(applic_id);
							
						}
				});
			}else{
				$j('#'+id).hide() // hide current group
				$j('#'+subgroup).show(); // show next group according to subgroup ID
				
				Reman_QuickQuote.prototype.currentPartRootSelected.push(name);
				
				$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb group_link" prevgroup="'+id+'" currentgroup="'+subgroup+'">'+name+'</span></span>');
			}
	},

	loadProductInfo: function(id,name){
		var aplicStr = Reman_QuickQuote.prototype.currentPartRootSelected.join(' > ');
		var make = Reman_QuickQuote.prototype.currentSelectedMake.trim();
		$j.ajax({
				url: "index/product",
				type: 'POST',
				data: {
					id:id,
					make: make,
					year: Reman_QuickQuote.prototype.currentSelectedYear,
					model: Reman_QuickQuote.prototype.currentSelectedModel,
					applic: aplicStr,
					partnum: Reman_QuickQuote.prototype.currentPartNumber
				},
				complete: function(data){
					if(Reman_QuickQuote.prototype.currentPartNumber == "N/A" || 
						Reman_QuickQuote.prototype.currentPartNumber == "" ){
						$j('#preloader_cont').fadeOut(500,function(){
							Reman_QuickQuote.prototype.currentPartRootSelected.pop();
							$j('#breadcrumb_info').removeClass('disabled');
							$j('#parts_tbl').show();
							//show error popup
							$j('#product_error_popup').fadeIn();
						});
						return;
					}					
					// trancate group text if longer than 30 letters
					if(name.length >30){
						name = jQuery.trim(name).substring(0, 30).trim(this) + "...";
					}
					//hide parts table					
					$j('#parts_tbl').hide();
					//insert product info block	
					$j('#preloader_cont').fadeOut(500,function(){
						$j('#table_container').css('min-height', '');
						// set breadcrumb info about last successful or not succssful choise
						$j('.sel_group_link').parent().remove();
						$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb sel_group_link">'+name+'</span></span>');
						$j('#breadcrumb_info').removeClass('disabled');
						// Show product page
						$j('#reman-product_info').show();
						$j('#reman-invent_info').show();
						$j('#reman-product_info').html(data.responseText);

					});
				}
		});
	},
	
	loadInventoryInfo: function(id){
		$j.ajax({

				url: "index/invent",
				type: 'POST',
				data: {
					id:id
				},
				complete: function(data){
					$j('#reman-invent_info').html(data.responseText);
				}
		});
	}
}


/* Sort Helper */

jQuery.fn.sort = function() {  
   return this.pushStack( [].sort.apply( this, arguments ), []);  
 };  
  
function sortAlpha(a,b){  
    return a.innerHTML > b.innerHTML ? 1 : -1;  
};  
  


