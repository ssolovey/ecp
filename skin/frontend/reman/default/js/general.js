var $j = jQuery.noConflict();


/** DOM Ready Event */

$j('document').ready(function(){
	// TODO INIT if needed
});

/** Create NameSpace */
var Reman = {
	QuickQuoteModule:{} // Quick Quote Module
};


Reman.QuickQuoteModule = {
	
	
	
	group_block: $j('#select_part_cont'),
	
	info_block: $j('#info'),
	
	
	selectMake: function(startyear,endyear,makeid){
		
		var startyear = Number(startyear);
		var endyear = Number(endyear);
		var range = endyear - startyear; //range between start and end year
		var year_selectBox = $j('#qq_select_year'); // select year selectbox obj
		
		var buffer = ''; // buffer string 
		
		for(var i = 0; i<=range-1; i++){ // nest select with options 
			if(i==0){
				buffer += '<option selected="selected">...</option>';
			}
			buffer += '<option value="'+makeid+'" label="'+(startyear+=1)+'">'+startyear+'</option>';
		}
		
		year_selectBox.append(buffer);
		
	},
	
	selectYear: function(makeid,year){
			
			if(makeid == 'none') return; // return if make id none
			
			var model_selectBox = $j('#qq_select_model'); // select model selectbox obj
			
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
						//showInfoPopup('MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR!!!');	
						return;
					}
				
				/** Nest Select box with options*/
				
				// buffer string 
				var buffer = '';
				
				for(var i = 0; i<=response.length-1; i++){
					if(i==0){
						buffer += '<option value="none" selected="selected">...</option>';
					}
						buffer += '<option  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</option>'
					}
				
					/*//clear options
					$j('#qq_select_model > option').remove();
					
					//clear select part number selectors
					$j('#select_part_cont > div').remove();
					
					//clear info
					$j('#info ul li.search_steps').remove();
					$j('#info ul li.part_info').remove();
					$j('#info').css('display','none');
				*/
					// fill options with data
					
					model_selectBox.append(buffer);
				}
			});
	},
	
	selectModel: function(vehicle_id){
			
			if(vehicle_id == 'none') return; // return if vehicle_id id none
			
			
			$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 3,  
					id:vehicle_id,
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
							//showInfoPopup('VEHICLE ID DOESNT EXIST IN APPLIC DATA !!!');
							return;
						}
						
						var obj= {}; // create empty object to handle response data
						
						for(var i=0; i<=response.length-1;i++)
						{
						
							/*if(response[i].applic == null)
							{
								$j('#select_part_cont > div').remove();
							
								showInfoPopup('NO DATA FOR THIS APPLIC ID !!!');
								
								return;
							}*/
						
						if($j.isEmptyObject(obj) || current_group != response[i].groupp ){
						
							obj[response[i].groupp] = {
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
						
							var current_group = response[i].groupp; 
						
						}
						
						selectPartBuildHTML(obj);
				}
			});
	},
	
	generateSelectPartName: function(){
	}
	
	
}




/** AJAX Request for select make from reman_make table*/
function selectMake(){
	
	//start year
	var start_year = Number($j('#qq_select_make > option:selected').attr('startyear'));
	//end year
	var end_year = Number($j('#qq_select_make > option:selected').attr('endyear'));
	//make id
	var make_id = $j('#qq_select_make > option:selected').attr('value');
	//range between start and end year
	var range = end_year - start_year;
	
	/** Build Select year select   */
	
	// buffer string 
	var buffer = '';
	
	for(var i = 0; i<=range-1; i++){
		if(i==0){
			buffer += '<option selected="selected">...</option>';
		}
		buffer += '<option value="'+make_id+'" label="'+(start_year+=1)+'">'+start_year+'</option>';
	}
	
	resetSelectBoxes();
	// fill options with data
	$j('#qq_select_year').append(buffer);
	
}

function resetSelectBoxes(){
	//clear options for model
	$j('#qq_select_model > option').remove()
	$j('#qq_select_model').append('<option selected="selected">...</option>'); 
	//clear options for year
	$j('#qq_select_year > option').remove();
	//clear select part number selectors
	$j('#select_part_cont > div').remove();
	
	//clear info
	$j('#info ul li.search_steps').remove();
	$j('#info ul li.part_info').remove();
	$j('#info').css('display','none');

}

function selectModel(make_id){
	if(make_id == '...') return;
	
	var year = $j('#qq_select_year > option:selected').attr('label');
	
	$j.ajax({
		url: "index/ajax",
		type: 'POST',
		data: {
			step: 2,  
			id:make_id,
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
				showInfoPopup('MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR!!!');	
				return;
			}
			
			/** Nest Select box with options*/
			
			// buffer string 
			var buffer = '';
			
			for(var i = 0; i<=response.length-1; i++){
				if(i==0){
					buffer += '<option selected="selected">...</option>';
				}
				buffer += '<option  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</option>'
			}
			//clear options
			$j('#qq_select_model > option').remove();
			
			//clear select part number selectors
			$j('#select_part_cont > div').remove();
			
			//clear info
			$j('#info ul li.search_steps').remove();
			$j('#info ul li.part_info').remove();
			$j('#info').css('display','none');
			
			// fill options with data
			$j('#qq_select_model').append(buffer);
	  }
	});
}

function selectProductID(vehicle_id){
	if(vehicle_id == '...') return;
	$j.post("index/ajax",
		  {
			step: 3, 
			id:vehicle_id
		  },
	  function(data){
		 
		    //parse response to JSON Object		  
			var response = JSON.parse(data);
			
			
			// CHECK For DATA if NULL return
			if(response.length == 0)
	  		{
				showInfoPopup('VEHICLE ID DOESNT EXIST IN APPLIC DATA !!!');
				return;
			}
			
			var obj= {};
			
			for(var i=0; i<=response.length-1;i++)
			{
				
				if(response[i].applic == null)
				{
					$j('#select_part_cont > div').remove();
					
					showInfoPopup('NO DATA FOR THIS APPLIC ID !!!');
					
					
					return;
				}
				
				if($j.isEmptyObject(obj) || current_group != response[i].groupp ){
					
					obj[response[i].groupp] = {
						'heading': response[i].menu_heading,
						'applic' : [
										{
											name:response[i].applic , 
											id:response[i].applic_id,
											subgroup:response[i].subgroup,
										}
									],
					}
				}else
				{
					
					obj[current_group].applic.push({name:response[i].applic ,id:response[i].applic_id , subgroup:response[i].subgroup});
				}
				
				var current_group = response[i].groupp; 
			
			}
			
			selectPartBuildHTML(obj);
			
	  });
}

function selectPartBuildHTML(obj){
	//clear info
	$j('#info ul li.search_steps').remove();
	$j('#info ul li.part_info').remove();
	$j('#info').css('display','none');
	//clear group select
	$j('#select_part_cont > div').remove();
	
	for (key in obj)
	{
		
		// buffer string 
		var buffer = '';
		// heading
		var header = '';
		
		for(var i = 0; i<=obj[key].applic.length-1; i++){
			
			buffer += '<li type="'+obj[key].applic[i].subgroup+'" value="'+obj[key].applic[i].id+'">'+obj[key].applic[i].name+'</li>';
			
			if(obj[key].heading != null){
				header = obj[key].heading; 
			}
			
			var template = "<div id='"+key+"' class='select_part'>"+
						"<span>"+header+"</span>"+
						"<ul class='select_part_box'>"+buffer+"</ul>"+
				  "</div>";
			
		}
		
		$j('#select_part_cont').append(template);

		//SHhow First Group
		$j($j('.select_part').get(0)).css('display','block');
				
	}
	$j('.select_part_box li').bind('click', function(event) {
			 if($(event.target).value == this.value)
			 {
			 	onPartSelect(this.value,this.type,this.parentElement.parentElement.id, this.innerHTML);
			 }
		});
	
}

function onPartSelect(applic_id,subgroup,id,name){
	
	if(subgroup == 0)
	{
		$j.post("index/ajax",
		  {
			step: 4,  
			id:applic_id,
		  },
	  function(data){
		  
			//parse response to JSON Object		  
			var response = JSON.parse(data);
			if(response[0].part_number == "N/A")
			{
				$j('.info_list').append('<li class="part_info">No Part ID CALL 55500000 FOR MORE INFO.</li>');
				
				
			}else
			{
				$j('.info_list .part_info').remove();
				$j('.info_list').append('<li class="part_info">Part ID: '+response[0].part_number+'</li>')
				
			}
			
	  });

	}
	else
	{
		$j('#'+id).css('display','none');
		$j('#'+subgroup).css('display','block');
		
		
		if($j('.info_list .part_info').length >0){
			//clear info
			$j('#info ul li.search_steps').remove();
			$j('#info ul li.part_info').remove();
		}
		
		$j('.info_list').append('<li class="search_steps">group: '+id+' > '+name+'</li>')
	}
	
	$j('#info').css('display','block');
}

function showInfoPopup(message){
	
	$j('#info_popup').html(message).css('display','block');
	
	setTimeout(function(){
		$j('#info_popup').fadeOut(1000);
	},2000);
	
}

