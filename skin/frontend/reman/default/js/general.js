var $j = jQuery.noConflict();

$j(document).ready(function(){
	var QuoteModule = new Reman_QuickQuote();
	QuoteModule.makeSelectEvent();
});


function Reman_QuickQuote() {};

Reman_QuickQuote.prototype = {
	
	make_tbl: $j('#make_tbl'),
	
	year_tbl: $j('#year_tbl'),
	
	model_tbl: $j('#model_tbl'),
	
	isYearActive: false,
	
	isModelActive: false,
	
	makeSelectEvent: function(){
		var self = this;
		$j('#make_tbl td').bind('click',function(e){
			var year_range = $j(this).attr('endyear') - $j(this).attr('startyear');
			self.selectMake($j(this).attr('value'),Number(year_range),Number($j(this).attr('startyear')));
		});
	},
	
	selectMake: function(makeid,range,startyear){
		var buffer = '<tr>'; // buffer string 
		for(var i = 1; i<=range; i++){  
			buffer += '<td class="year_select" value="'+makeid+'" year="'+(startyear+=1)+'">'+startyear+'</td>';
			if(range > 5){
				if(i%3 == 0){
					buffer += '</tr><tr>';
				}
			}else{
				buffer += '</tr>';
			}
		}
		//append new info to year table
		$j(year_tbl).append(buffer);
		// init events on year table
		this.yearSelectEvent();
		// show breadcrumb year link
		$j('.year').removeClass('reman_hide').addClass('reman_show');
		// hide make table
		$j(make_tbl).removeClass().addClass('reman_hide');
		//show year table
		$j(year_tbl).removeClass().addClass('reman_show');
		// year table active true
		isYearActive = true;
	},
	
	yearSelectEvent: function(){
		var self = this;
		$j('#year_tbl td').bind('click',function(e){
			self.selectYear($j(this).attr('value'), $j(this).attr('year'));
		});
	},
	
	clearYear: function(){
		//clear year
		$j(year_tbl).removeClass().addClass('reman_hide');
		$j('.year_select').remove();
		$j('.year').removeClass('reman_show').addClass('reman_hide');
		isYearActive = false;
	},
	
	clearModel: function(){
		//clear year
		$j(model_tbl).removeClass().addClass('reman_hide');
		$j('.model_select').remove();
		$j('.model').removeClass('reman_show').addClass('reman_hide');
		isModelActive = false;
	},
	
	
	turnOnMakeBreadcrumb: function(){
		if(isYearActive){
			this.clearYear();
			this.clearModel();
			//show make table
			$j(make_tbl).removeClass().addClass('reman_show');
		}
	
	},
	
	turnOnYearBreadcrumb: function(){
		if(isModelActive){
			this.clearModel();
			//show make table
			$j(year_tbl).removeClass().addClass('reman_show');
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
					//TODO PRELOADER
				},
			
				complete: function(data){
					//parse response to JSON Object		  
					var response = JSON.parse(data.responseText);
				
					// CHECK For DATA if NULL return
					if(response.length == 0)
					{
							
						// Error message
						//$j('.info_list').append('<li class="error">MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR !</li>');
							
						return;
					}
				
				/** Nest Select box with options*/
				
				var buffer = '<tr>'; // buffer string 
					for(var i = 0; i<=response.length-1; i++){ // nest select with options 
						buffer += '<td class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</td>';
						if(response.length > 10){
							if(i%3 == 0){
								buffer += '</tr><tr>';
							}
						}else{
							
								buffer += '</tr>';
							
						}
					}
					$j(model_tbl).append(buffer);
				}
				
		});
		
		$j(year_tbl).removeClass().addClass('reman_hide');
		
		$j(model_tbl).removeClass().addClass('reman_show');
		
		$j('.model').removeClass('reman_hide').addClass('reman_show');
		
		isModelActive = true;
	
	}
	

}

//
//
//Reman.QuickQuoteModule = {
//	
//	selectMake: function(makeid){
//		
//		var startyear = Number($j('#qq_select_make > option:selected').attr('startyear'));
//		var endyear = Number($j('#qq_select_make > option:selected').attr('endyear'));
//		var range = endyear - startyear; //range between start and end year
//		var year_selectBox = $j('#qq_select_year'); // select year selectbox obj
//		
//		var buffer = ''; // buffer string 
//		
//		for(var i = 0; i<=range-1; i++){ // nest select with options 
//			if(i==0){
//				buffer += '<option selected="selected">...</option>';
//			}
//			buffer += '<option value="'+makeid+'" label="'+(startyear+=1)+'">'+startyear+'</option>';
//		}
//		
//		//clear options for model
//		$j('#qq_select_model > option').remove()
//		$j('#qq_select_model').append('<option selected="selected">...</option>'); 
//		//clear options for year
//		$j('#qq_select_year > option').remove();
//		//clear select part number selectors
//		$j('#select_part_cont > div').remove();
//		
//		//clear info
//		$j('#info ul li.search_steps').remove();
//		$j('#info ul li.part_info').remove();
//		$j('#info ul li.error').remove();
//		$j('#info').css('display','none');
//		
//		year_selectBox.append(buffer);
//		
//	},
//	
//	selectYear: function(makeid){
//			
//			if(makeid == 'none') return; // return if make id none
//			
//			var year = $j('#qq_select_year > option:selected').attr('label');
//			
//			var model_selectBox = $j('#qq_select_model'); // select model selectbox obj
//			
//			$j.ajax({
//				url: "index/ajax",
//				type: 'POST',
//				data: {
//					step: 2,  
//					id:makeid,
//					year: year
//				},
//			
//				beforeSend: function(){
//					//TODO PRELOADER
//				},
//			
//				complete: function(data){
//					//parse response to JSON Object		  
//					var response = JSON.parse(data.responseText);
//				
//					// CHECK For DATA if NULL return
//					if(response.length == 0)
//					{
//						
//							//clear info
//							$j('#info ul li.search_steps').remove();
//							$j('#info ul li.part_info').remove();
//							$j('#info ul li.error').remove();
//							
//							// Error message
//							$j('.info_list').append('<li class="error">MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR !</li>');
//							
//						return;
//					}
//				
//				/** Nest Select box with options*/
//				
//				// buffer string 
//				var buffer = '';
//				
//				for(var i = 0; i<=response.length-1; i++){
//					if(i==0){
//						buffer += '<option value="none" selected="selected">...</option>';
//					}
//						buffer += '<option  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</option>'
//					}
//				
//					//clear options
//					$j('#qq_select_model > option').remove();
//					
//					//clear select part number selectors
//					$j('#select_part_cont > div').remove();
//					
//					//clear info
//					$j('#info ul li.search_steps').remove();
//					$j('#info ul li.part_info').remove();
//					$j('#info ul li.error').remove();
//					$j('#info').css('display','none');
//				
//					// fill options with data
//					
//					model_selectBox.append(buffer);
//				}
//			});
//	},
//	
//	selectModel: function(vehicle_id){
//			
//			if(vehicle_id == 'none') return; // return if vehicle_id id none
//			
//			
//			$j.ajax({
//				url: "index/ajax",
//				type: 'POST',
//				data: {
//					step: 3,  
//					id:vehicle_id,
//				},
//			
//				beforeSend: function(){
//					//TODO PRELOADER
//				},
//				complete: function(data){
//						//parse response to JSON Object		  
//						var response = JSON.parse(data.responseText);
//						
//						// CHECK For DATA if NULL return
//						if(response.length == 0)
//						{
//							//clear info
//							$j('#info ul li.search_steps').remove();
//							$j('#info ul li.part_info').remove();
//							$j('#info ul li.error').remove();
//							
//							// Error message
//							$j('.info_list').append('<li class="error">VEHICLE ID DOESNT EXIST IN APPLIC DATA !</li>');
//							
//							// show info block
//							$j('#info').css('display','block');
//							
//							return;
//						}
//						
//						var obj= {}; // create empty object to handle response data
//						
//						for(var i=0; i<=response.length-1;i++)
//						{
//						
//								if(response[i].applic == null)
//								{
//									$j('#select_part_cont > div').remove();
//								
//									//clear info
//									$j('#info ul li.search_steps').remove();
//									$j('#info ul li.part_info').remove();
//									$j('#info ul li.error').remove();
//									
//									// Error message
//									$j('.info_list').append('<li  class="error">NO DATA FOR THIS APPLIC ID !</li>');
//									
//									return;
//								}
//							
//							if($j.isEmptyObject(obj) || current_group != response[i].group_number ){
//							
//								obj[response[i].group_number] = {
//									'heading': response[i].menu_heading,
//									'applic' : [
//													{
//														name:response[i].applic , 
//														id:response[i].applic_id,
//														subgroup:response[i].subgroup,
//													}
//												],
//								}
//							}else{
//							
//								obj[current_group].applic.push(
//																{
//																	name:response[i].applic,
//																	id:response[i].applic_id, 
//																	subgroup:response[i].subgroup
//																}
//															);
//							}
//							
//								var current_group = response[i].group_number; 
//						
//						}
//						
//						Reman.QuickQuoteModule.generateSelectPartName(obj);
//				}
//			});
//	},
//	
//	generateSelectPartName: function(obj){
//		//clear info
//		$j('#info ul li.search_steps').remove();
//		$j('#info ul li.part_info').remove();
//		$j('#info ul li.error').remove();
//		$j('#info').css('display','none');
//		//clear group select
//		$j('#select_part_cont > div').remove();
//	
//		for (key in obj)
//		{
//			
//			// buffer string 
//			var buffer = '';
//			// heading
//			var header = '';
//			
//			for(var i = 0; i<=obj[key].applic.length-1; i++){
//				// form link to part id
//				buffer += '<li type="'+obj[key].applic[i].subgroup+'" value="'+obj[key].applic[i].id+'">'+obj[key].applic[i].name+'</li>';
//				
//				if(obj[key].heading != null){
//					header = obj[key].heading; 
//				}
//				// Group template
//				var template = "<div id='"+key+"' class='select_part'>"+
//									"<span>"+header+"</span>"+
//									"<ul class='select_part_box'>"+buffer+"</ul>"+
//							  "</div>";
//				
//			}
//			// append to container
//			$j('#select_part_cont').append(template);
//	
//			//Show First Group
//			$j($j('.select_part').get(0)).css('display','block');
//					
//		}
//		
//		// Group Block links event
//		$j('.select_part_box li').bind('click', function(event) {
//			 if($(event.target).value == this.value){
//			 	Reman.QuickQuoteModule.selectPart(this.value,$j(this).attr('type'),this.parentElement.parentElement.id, this.innerHTML);
//			 }
//		});
//	
//	},
//	
//	selectPart: function(applic_id,subgroup,id,name){
//			if(subgroup == 0) {
//				
//					$j.ajax({
//						url: "index/ajax",
//						type: 'POST',
//						data: {
//							step: 4,  
//							id:applic_id,
//						},
//					
//						beforeSend: function(){
//							//TODO PRELOADER
//						},
//						complete: function(data){	
//							//parse response to JSON Object		  
//							var response = JSON.parse(data.responseText);
//							if(response[0].part_number == "N/A"){
//								$j('.info_list').append('<li class="part_info">No Part ID CALL 55500000 FOR MORE INFO.</li>');
//							}else{
//								$j('.info_list .part_info').remove();
//								$j('.info_list').append('<li class="part_info">Part ID: '+response[0].part_number+'</li>')
//							}
//						}
//				});
//				
//			}else{
//				$j('#'+id).css('display','none'); // hide current group
//				$j('#'+subgroup).css('display','block'); // show next group according to subgroup ID
//		
//				// if info is not empty, clear it
//				if($j('.info_list .part_info').length >0){
//					//clear info
//					$j('#info ul li.search_steps').remove();
//					$j('#info ul li.part_info').remove();
//					$j('#info ul li.error').remove();
//				}
//				// insert search step
//				$j('.info_list').append('<li class="search_steps">group: '+id+' > '+name+'</li>');
//			}
//			
//				// show info block
//				$j('#info').css('display','block');
//	}	
//}
