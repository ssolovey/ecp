/** 
 *	Resolve conflict with default prototype library
 *  use #j to define jQuery namespace
*/
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
		buffer += '<option value="'+make_id+'" label="'+(start_year+=1)+'">'+start_year+'</option>';
	}
	//clear options
	$j('#qq_select_year > option').remove();
	// fill options with data
	$j('#qq_select_year').append(buffer);
	
}

function selectModel(make_id){
	
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

			// buffer string 
			var buffer = '';
			
			for(var i = 0; i<=response.length-1; i++){
				buffer += '<option  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</option>'
			}
			//clear options
			$j('#qq_select_model > option').remove();
			// fill options with data
			$j('#qq_select_model').append(buffer);
	  });
}

function selectProductID(vehicle_id){
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
				alert('DATA IS EMPTY');
				return;
			}
			
			var obj= {};
			
			for(var i=0; i<=response.length-1;i++)
			{
				
				if(response[i].applic == null)
				{
					$j('#select_part_cont > div').remove();
					
					alert('APPLIC DATA IS EMPTY');
					
					return;
				}
				
				if($j.isEmptyObject(obj) || current_group != response[i].group ){
					obj[response[i].group] = {
						'heading': response[i].menu_heading,
						'applic' : [
										{
											name:response[i].applic , 
											id:response[i].applic_id
										}
									],
					}
				}else
				{
					
					obj[current_group].applic.push({name:response[i].applic ,id:response[i].applic_id});
				}
				
				var current_group = response[i].group; 
			
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
		
		for(var i = 0; i<=obj[key].applic.length-1; i++){
			buffer += '<option  value="'+obj[key].applic[i].id+'">'+obj[key].applic[i].name+'</option>'
		}
		
		var template = "<div class='select_part'>"+
						"<span>"+obj[key].heading+"</span>"+
						"<select class='select_part_box'>"+buffer+"</select>"+
				  "</div>";
		
		$j('#select_part_cont').append(template);
		
		$j('.select_part_box').bind('change', function() {
			 onPartSelect(this.value);
		});
		
	}
	
}

function onPartSelect(applic_id){
	
	$j.post("index/ajax",
		  {
			step: 4,  
			id:applic_id,
		  },
	  function(data){
		  
			//parse response to JSON Object		  
			var response = JSON.parse(data);

			alert("PART NUMBER: " +response[0].part_number);
	  });

}

