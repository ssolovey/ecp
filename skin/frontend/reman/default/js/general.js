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
	
	for(var i = 0; i<=range; i++){
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
			id:make_id,
			year: year
		  },
	  function(data){
			//parse response to JSON Object		  
			var response = JSON.parse(data);

			// buffer string 
			var buffer = '';
			
			for(var i = 0; i<=response.length-1; i++){
				buffer += '<option>'+response[i]+'</option>'
			}
			//clear options
			$j('#qq_select_model > option').remove();
			// fill options with data
			$j('#qq_select_model').append(buffer);
	  });
}

