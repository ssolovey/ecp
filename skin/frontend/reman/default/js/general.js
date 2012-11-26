/*Resolve conflicts with prototype js default library*/
var $j = jQuery.noConflict();
/* Document ready event*/
$j(document).ready(function(){
	/*Initiate events on breadcrumb links */
	$j('#breadcrumb_info').bind('click',function(event){
		Reman_QuickQuote.prototype.eventsHandler(event);
	});
	/*Initiate events on quote links */
	$j('#table_container').bind('click',function(event){
		Reman_QuickQuote.prototype.eventsHandler(event);
	});
});

/*Create NameSpace for Quick Quote module*/
function Reman_QuickQuote() {};


Reman_QuickQuote.prototype = {
	
	make_tbl: $j('#make_tbl'), // cache make table reference
	
	year_tbl: $j('#year_tbl'), // cache year table reference
	
	model_tbl: $j('#model_tbl'), // cache model table reference
	// Flags
	isYearActive: false,
	
	isModelActive: false,
	
	isGroupActive: false,
	// Current Selected values
	currentSelectedMake: '',
	
	currentSelectedYear: '',
	
	currentSelectedModel: '',
	
	currentCatSelected:'',
	
	/*General Event Handler*/
	eventsHandler: function(event){
	
		var elem = event.target;
		
		if(elem.tagName == 'A') return
		
		while (elem) {
			/*Event for Category Select*/
			if (elem.className == 'cat_select'){
				this.currentCatSelected = $j(elem).attr('cat');
				$j('#group_select').removeClass().addClass('reman_hide');
				$j(make_tbl).removeClass().addClass('reman_show');
				// update bread crumb link
				$j('#breadcrumb_info').append('<span><span class="breadcrumb cat_link">'+elem.innerHTML+'</span>');
				return;
			}
			/*Event for Make Table*/
			if (elem.className == 'make_select') {
				var year_range = $j(elem).attr('endyear') - $j(elem).attr('startyear');
				this.currentSelectedMake = $j(elem).html();
				this.selectMake($j(elem).attr('value'),Number(year_range),Number($j(elem).attr('startyear')));
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Year Table*/
			if (elem.className == 'year_select') {
				this.currentSelectedYear = $j(elem).html();
				this.selectYear($j(elem).attr('value'), $j(elem).attr('year'));
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Model Table*/
			if (elem.className == 'model_select') {
				this.selectModel($j(elem).attr('value'), elem.innerHTML);
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Parts Table*/
			if (elem.className == 'parts_select') {
				this.selectPart($j(elem).attr('value'),$j(elem).attr('type'),elem.parentElement.parentElement.id, elem.innerHTML);
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Breadcrumbs Goupe links*/
			if (elem.className == 'breadcrumb group_link') {
				$j('.select_part').css('display','none') // hide  groups
				$j('#'+$j(elem).attr('prevgroup')).css('display','block'); // show next group according to subgroup ID
				// deleted other groups
				if($j(elem).parent().next().length){
					$j(elem).parent().nextAll().remove();
				}
				//delete this link
				$j(elem).parent().remove();
				this.resetSearchErrorResults();
				
				return;
			}
			/*Event for Breadcrumbs Make links*/
			if (elem.className == 'breadcrumb make_link'){
				this.turnOnMakeBreadcrumb();
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Breadcrumbs Yearlinks*/
			if (elem.className == 'breadcrumb year_link'){
				this.turnOnYearBreadcrumb();
				this.resetSearchErrorResults();
				return;
			}
			/*Event for Breadcrumbs Model links*/
			if (elem.className == 'breadcrumb model_link'){
				this.turnOnModelBreadcrumb();
				this.resetSearchErrorResults();
				return;
			}
			
			/*Event for Breadcrumbs Cat links*/
			if (elem.className == 'breadcrumb cat_link'){
				this.turnOnCatBreadcrumb();
				this.resetSearchErrorResults();
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
		$j(year_tbl).append(buffer);
		// hide make table
		$j(make_tbl).removeClass().addClass('reman_hide');
		// set selected Make name to top menu
		$j('.selected_make').html(this.currentSelectedMake);
		//show year table
		$j(year_tbl).removeClass().addClass('reman_show');
		// year table active true
		this.isYearActive = true;
		// update bread crumb link
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb make_link">'+this.currentSelectedMake+'</span>');
	},
	
	
	resetSearchErrorResults: function(){
		//clear start over button link
		$j('#product_details_btn a').attr('href','#');
		//clear product info fields
		$j('.sku').remove();
		//reset Product DATA Error
		$j('.product_error').html('');
	},
	
	clearYear: function(){
		//clear year
		$j(year_tbl).removeClass().addClass('reman_hide');
		$j('#year_tbl .list').remove();
		$j('.year_link').parent().remove();
		this.isYearActive = false;
	},
	
	clearModel: function(){
		//clear model
		$j(model_tbl).removeClass().addClass('reman_hide');
		$j('#table_container').css('min-height', '');
		$j('#preloader_cont').css('height', '');
		$j('.model_select').parent().remove();
		$j('.model_link').parent().remove();
		this.isModelActive = false;
	},
	
	
	clearGroup: function(){
		//clear group
		$j('#parts_tbl').removeClass().addClass('reman_hide');
		$j('.select_part').remove();
		$j('.group').removeClass('reman_show').addClass('reman_hide');
		this.clearSubGroups();
		this.isGrouplActive = false;
	},
	
	clearMake: function(){
		//clear Make
		$j(make_tbl).removeClass().addClass('reman_hide');
		$j('.make_link').parent().remove();
	},
	
	
	clearSubGroups: function(){
		// clear subgroup
		if($j('.group_link').length){
			$j('.group_link').parent().remove();
			//$j('.sel_group_link').parent().remove();
		}
		$j('.select_part').css('display','none');
		//Show First Group
		$j($j('.select_part').get(0)).css('display','block');
	},

	turnOnMakeBreadcrumb: function(){
		if(this.isYearActive){
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			$j('.make_link').parent().remove();
			//show make table
			$j(make_tbl).removeClass().addClass('reman_show');
		}
	
	},
	
	turnOnYearBreadcrumb: function(){
		if(this.isModelActive){
			this.clearModel();
			this.clearGroup();
			$j('.year_link').parent().remove();
			//show year table
			$j(year_tbl).removeClass().addClass('reman_show');
		}
	
	},
	
	
	turnOnModelBreadcrumb: function(){
		if(this.isGroupActive){
			this.clearGroup();
			$j('.model_link').parent().remove();
			//show model table
			$j('#model_tbl').removeClass().addClass('reman_show');
		}
	
	},
	
	turnOnCatBreadcrumb: function(){
			this.clearMake();
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			$j('.cat_link').remove();
			//show cat table
			$j('#group_select').removeClass().addClass('reman_show');
			
			this.currentCatSelected = '';
		
	
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
					$j(year_tbl).removeClass().addClass('reman_hide');
					$j('#preloader_cont').css('display','block');
				},
			
				complete: function(data){
					//parse response to JSON Object		  
					var response = JSON.parse(data.responseText);
				
					// CHECK For DATA if NULL return
					if(response.length == 0)
					{
						// Error message
						$j('.product_error').append('Not available');
						Reman_QuickQuote.prototype.turnOnYearBreadcrumb();
						$j('#preloader_cont').fadeOut(500,function(){
							$j(year_tbl).removeClass().addClass('reman_show');
						});	
						return;
					}
				
				/** Nest Select box with options*/
				
				var buffer = '<ul class="list list_first">'; // buffer string 
					for(var i = 0; i<=response.length-1; i++){ // nest select with options 
						buffer += '<li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';
						if(i%15 == 0 && i!=0){
							buffer += '</ul><ul class="list">';
						}
					}
					$j(model_tbl).append(buffer);
					$j('#preloader_cont').fadeOut(500,function(){
						$j(model_tbl).removeClass().addClass('reman_show');
					});
					
				}
				
		});
		/*Update breadcrumb*/
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb year_link">'+this.currentSelectedYear+'</span>');
		
		this.isModelActive = true;
	
	},
	
	selectModel: function(vehicle_id,name){
		
			$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 3,  
					id:vehicle_id,
				},
			
				beforeSend: function(){
					//hide model table
					$j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
					$j('#table_container').css('min-height', $j('#table_container').height() + 'px');
					$j(model_tbl).removeClass().addClass('reman_hide');
					$j('#preloader_cont').css('display','block');
				},
				complete: function(data){
						//parse response to JSON Object		  
						var response = JSON.parse(data.responseText);
						
						// CHECK For DATA if NULL return
						if(response.length == 0)
						{
							
							// Error message
							$j('.product_error').append('Not available');
							$j('#preloader_cont').fadeOut(500,function(){
								$j(model_tbl).removeClass().addClass('reman_show');
							});
							return;
						}
						
						var obj= {}; // create empty object to handle response data
						
						for(var i=0; i<=response.length-1;i++)
						{
						
								if(response[i].applic == null)
								{
									
									// Error message
									$j('.product_error').append('Not available');
									$j('#preloader_cont').fadeOut(500);
									return;
								}
							
							if($j.isEmptyObject(obj) || current_group != response[i].group_number ){
							
								obj[response[i].group_number] = {
									'heading': response[i].menu_heading,
									'applic' : [
													{
														name:response[i].applic , 
														id:response[i].applic_id,
														subgroup:response[i].subgroup,
													}
												],
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

		for (key in obj)
		{
			
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
					header = '<span class="label">Select Group:</span>';
				}
				// Group template
				
				if(i%20 == 0 && i!=0){
					block_counter +=1;
					buffer += '</ul><ul class="select_part_box block_'+block_counter+'">';
				}
				
			}
				var template = "<div id='"+key+"' class='select_part'>"+
										"<span class='label'>"+header+"</span>"+
										buffer
								  "</div>";
				// append to container
				$j('#parts_tbl').append(template);
					
				//Show First Group
				$j($j('.select_part').get(0)).css('display','block');
		}
		
			this.currentSelectedModel = name;
			
			$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb model_link">'+this.currentSelectedModel+'</span>');
			
			this.isGroupActive = true;
			
			$j('#preloader_cont').fadeOut(500,function(){
				$j('#table_container').css('min-height', '');
				$j('#parts_tbl').removeClass().addClass('reman_show');
			});
					
	},
	
	selectPart: function(applic_id,subgroup,id,name){
			if(subgroup == 0) {
				
					$j.ajax({
						url: "index/ajax",
						type: 'POST',
						data: {
							step: 4,  
							id:applic_id,
						},
					
						beforeSend: function(){
							//TODO PRELOADER
						},
						complete: function(data){	
							//parse response to JSON Object		  
							
							var response = JSON.parse(data.responseText);
							
							if(response.error){
								$j('#search_info').removeClass().addClass('enable_by_opacity');
								$j('.product_error').html(response.message);
							
								return;
							}
							
							if(response.sku == "N/A" || response.sku.split('')[0] != Reman_QuickQuote.prototype.currentCatSelected){
								// Error message
								$j('.product_error').append('Not available');
								//$j('.sel_group_link').parent().remove();
							}else{
								$j('.sku').remove();
								$j('#part_number_id').append('<span class="sku"> '+response.sku+'</span>');
								$j('#part_family_id').append('<span class="sku"> '+response.family+'</span>');
								$j('#part_msrp_id').append('<span class="sku"> $'+Number(response.msrp).toFixed(1)+'</span>');
								$j('#part_price_id').append('<span class="sku"> $'+Number(response.price).toFixed(1)+'</span>');
								$j('#part_core_id').append('<span class="sku"> $'+Number(response.core).toFixed(1)+'</span>');
								$j('#product_details_btn a').attr('href',response.url);
							}
							//$j('.sel_group_link').parent().remove();
							//$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb sel_group_link">'+name+'</span>');
						}
				});
				
			}else{
				//$j('.sel_group_link').parent().remove(); // if was selected remove selected lable
				$j('#'+id).css('display','none'); // hide current group
				$j('#'+subgroup).css('display','block'); // show next group according to subgroup ID
				$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb group_link" prevgroup="'+id+'" currentgroup="'+subgroup+'">'+name+'</span>');
			}
	}	
}
