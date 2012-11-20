var $j = jQuery.noConflict();


function Reman_QuickQuote() {};

Reman_QuickQuote.prototype = {
	
	make_tbl: $j('#make_tbl'),
	
	year_tbl: $j('#year_tbl'),
	
	model_tbl: $j('#model_tbl'),
	
	isYearActive: false,
	
	isModelActive: false,
	
	isGroupActive: false,
	
	currentSelectedMake: '',
	
	currentSelectedYear: '',
	
	eventsHandler: function(event){
	
		var elem = event.target;
		
		while (elem) {
			
			//reset error
			$j('#quote_error .text').html('');
			$j('#quote_error').removeClass().addClass('reman_visibility_hide');	
			
			//reset quote details
			if($j('#search_info').hasClass('enable_by_opacity')){
				$j('#search_info').removeClass().addClass('disable_by_opacity');
				$j('#sku').remove();
			}
			
			
			if (elem.className == 'make_select') {
				var year_range = $j(elem).attr('endyear') - $j(elem).attr('startyear');
				this.currentSelectedMake = $j(elem).html();
				this.selectMake($j(elem).attr('value'),Number(year_range),Number($j(elem).attr('startyear')));
				
				
				return;
			}
			
			if (elem.className == 'year_select') {
				this.currentSelectedYear = $j(elem).html();
				this.selectYear($j(elem).attr('value'), $j(elem).attr('year'));
				return;
			}
			
			if (elem.className == 'model_select') {
				this.selectModel($j(elem).attr('value'));
				return;
			}
			
			if (elem.className == 'parts_select') {
				this.selectPart($j(elem).attr('value'),$j(elem).attr('type'),elem.parentElement.parentElement.id, elem.innerHTML);
				return;
			}
			
			if (elem.className == 'breadcrumb group_link') {
				$j('.select_part').css('display','none') // hide  groups
				$j('#'+$j(elem).attr('prevgroup')).css('display','block'); // show next group according to subgroup ID
				// deleted other groups
				if($j(elem).parent().next().length){
					$j(elem).parent().nextAll().remove();
				}
				//delete this link
				$j(elem).parent().remove();
				
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
		// show breadcrumb year link
		$j('.year').removeClass('reman_hide').addClass('reman_show');
		// hide make table
		$j(make_tbl).removeClass().addClass('reman_hide');
		// set selected Make name to top menu
		$j('.selected_make').html(this.currentSelectedMake);
		//show year table
		$j(year_tbl).removeClass().addClass('reman_show');
		// year table active true
		this.isYearActive = true;
	},
	
	clearYear: function(){
		//clear year
		$j(year_tbl).removeClass().addClass('reman_hide');
		$j('#year_tbl .list').remove();
		$j('.year').removeClass('reman_show').addClass('reman_hide');
		this.isYearActive = false;
	},
	
	clearModel: function(){
		//clear year
		$j(model_tbl).removeClass().addClass('reman_hide');
		$j('.model_select').remove();
		$j('.model').removeClass('reman_show').addClass('reman_hide');
		this.isModelActive = false;
	},
	
	
	clearGroup: function(){
		//clear year
		$j('#parts_tbl').removeClass().addClass('reman_hide');
		$j('.select_part').remove();
		$j('.group').removeClass('reman_show').addClass('reman_hide');
		this.clearSubGroups();
		
		
		this.isGrouplActive = false;
	},
	
	
	clearSubGroups: function(){
		if($j('.group_link').length){
			$j('.group_link').parent().remove();
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
			//show make table
			$j(make_tbl).removeClass().addClass('reman_show');
		}
	
	},
	
	turnOnYearBreadcrumb: function(){
		if(this.isModelActive){
			this.clearModel();
			this.clearGroup();
			//show make table
			$j(year_tbl).removeClass().addClass('reman_show');
		}
	
	},
	
	
	turnOnModelBreadcrumb: function(){
		if(this.isGroupActive){
			this.clearGroup();
			//show make table
			$j('#model_tbl').removeClass().addClass('reman_show');
		}
	
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
					$j('#preloader_cont').css('display','block');
				},
			
				complete: function(data){
					//parse response to JSON Object		  
					var response = JSON.parse(data.responseText);
				
					// CHECK For DATA if NULL return
					if(response.length == 0)
					{
							
						// Error message
						$j('#quote_error .text').append('MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR !!!!');
						$j('#quote_error').removeClass().addClass('reman_visibility_show');
						
						$j('#preloader_cont').fadeOut(500);	
						return;
					}
				
				/** Nest Select box with options*/
				
				var buffer = '<ul class="list list_first">'; // buffer string 
					for(var i = 0; i<=response.length-1; i++){ // nest select with options 
						buffer += '<li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';
						if(i%10 == 0 && i!=0){
							buffer += '</ul><ul class="list">';
						}
					}
					$j(model_tbl).append(buffer);
					
					$j('#preloader_cont').fadeOut(500);
				}
				
		});
		
		$j(year_tbl).removeClass().addClass('reman_hide');
		
		// set selected Make name to top menu
		$j('.selected_make_year').html(this.currentSelectedMake +' ('+this.currentSelectedYear+')');
		
		$j(model_tbl).removeClass().addClass('reman_show');
		
		$j('.model').removeClass('reman_hide').addClass('reman_show');
		
		this.isModelActive = true;
	
	},
	
	selectModel: function(vehicle_id){
		
			$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 3,  
					id:vehicle_id,
				},
			
				beforeSend: function(){
					$j('#preloader_cont').css('display','block');
				},
				complete: function(data){
						//parse response to JSON Object		  
						var response = JSON.parse(data.responseText);
						
						// CHECK For DATA if NULL return
						if(response.length == 0)
						{
							
							// Error message
							$j('#quote_error .text').append('VEHICLE ID DOESNT EXIST IN APPLIC DATA !!!');
							$j('#quote_error').removeClass().addClass('reman_visibility_show');
							
							$j('#preloader_cont').fadeOut(500);
							return;
						}
						
						var obj= {}; // create empty object to handle response data
						
						for(var i=0; i<=response.length-1;i++)
						{
						
								if(response[i].applic == null)
								{
									
									// Error message
									$j('#quote_error .text').append('NO DATA FOR THIS APPLIC ID!!!');
									$j('#quote_error').removeClass().addClass('reman_visibility_show');	
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
						
						
						Reman_QuickQuote.prototype.formSelectGroupBlock(obj);
				}
			});
	},
	
	formSelectGroupBlock: function(obj){

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
				}
				// Group template
				
				if(i%10 == 0 && i!=0){
					block_counter +=1;
					buffer += '</ul><ul class="select_part_box block_'+block_counter+'">';
				}
				
			}
			var template = "<div id='"+key+"' class='select_part'>"+
									"<span>"+header+"</span>"+
									buffer
							  "</div>";
			// append to container
			$j('#parts_tbl').append(template);
			
			
			$j(model_tbl).removeClass().addClass('reman_hide');
	
				
			//Show First Group
			$j($j('.select_part').get(0)).css('display','block');
			
			$j('#parts_tbl').removeClass().addClass('reman_show');
			
			// show group lable
			$j('.group').removeClass('reman_hide').addClass('reman_show');
			
			this.isGroupActive = true;
			
			$j('#preloader_cont').fadeOut(500);
					
		}
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
							if(response[0].part_number == "N/A"){
								// Error message
								$j('#quote_error .text').append('No Part ID CALL 55500000 FOR MORE INFO');
								$j('#quote_error').removeClass().addClass('reman_visibility_show');	
									
							}else{
								$j('#search_info').removeClass().addClass('enable_by_opacity');
								$j('#part_number_id').append('<span id="sku" style="color:blue"> '+response[0].part_number+'</span>');
								
							}
						}
				});
				
			}else{
				$j('#'+id).css('display','none'); // hide current group
				$j('#'+subgroup).css('display','block'); // show next group according to subgroup ID
				$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb group_link" prevgroup="'+id+'" currentgroup="'+subgroup+'">'+name+'</span>');
			}
	}	
}
