var $j = jQuery.noConflict();


/** DOM Ready Event */

$j('document').ready(function(){
	// TODO INIT if needed
});

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

}

function selectModel(make_id){
	if(make_id == '...') return;
	var year = $j('#qq_select_year > option:selected').attr('label');
	
	$j.post("index/ajax",
		  {
			step: 2,  
			id:make_id,
			year: year
		  },
	  function(data){
			//parse response to JSON Object		  
			var response = JSON.parse(data);
			
			// CHECK For DATA if NULL return
			if(response.length == 0)
	  		{
				alert('MODELS DOESNT EXIST IN MODEL DATA FOR THIS YEAR!!!');
				return;
			}
			
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
			// fill options with data
			$j('#qq_select_model').append(buffer);
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
				alert('VEHICLE ID DOESNT EXIST IN APPLIC DATA !!!');
				return;
			}
			
			var obj= {};
			
			for(var i=0; i<=response.length-1;i++)
			{
				
				if(response[i].applic == null)
				{
					$j('#select_part_cont > div').remove();
					
					alert('NO DATA FOR THIS APPLIC ID !!!');
					
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
						"<span>"+header+" (Group: "+key+")</span>"+
						"<ul class='select_part_box'>"+buffer+"</ul>"+
				  "</div>";
			
		}
		
		$j('#select_part_cont').append(template);
		
	}
	$j('.select_part_box li').bind('click', function(event) {
			 if($(event.target).value == this.value)
			 {
			 	onPartSelect(this.value,this.type);
			 }
		});
	
}

function onPartSelect(applic_id,subgroup){
	
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
				alert("NO PART NUMBER CALL 55500000 FOR MORE INFO !!!");
			}else
			{
				alert("PART NUMBER: " +response[0].part_number);
			}
			
	  });

	
	}
	else
	{
		
		for (var i=0; i<= $j('.select_part').length-1; i++)
		{
				if($j('.select_part')[i].id != subgroup)
				{
					$j('.select_part')[i].style.display = 'none';
					
				}
				else
				{
					if($j('.select_part')[i].style.display == 'none')
					{
						$j('.select_part')[i].style.display = 'block';
					}
				}
		}
		
		if(!$j('#showall').length)
		{
		$j('#select_part_cont').prepend('<div id="showall" >Show All groups</div>');
					
		showAllGroups();
		}else
		{
			$j('#showall').css('display','block');
		}
	}
	
	
}

function showAllGroups (){
	
	$j('#showall').bind('click', function(event) {
		$j('.select_part').css('display','block');
		$j('#showall').css('display','none');

	});
	
}

