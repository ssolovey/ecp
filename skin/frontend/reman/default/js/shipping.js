	
	


 var storeZIP = {
	53223: 'Milwaukee, WI',   // TODO Dallas, TX 
	91761: 'Ontario, CA',
	30344: 'Atlanta, GA',
	21113: 'Baltimore, MD' 
 }
	
function loadShippingPage(){
	$j.ajax({

		url: "index/shipping",
		type: 'POST',
		
		data: {
			id: Reman_QuickQuote.prototype.currentApplic_id
		},
		
		beforeSend: function(){
				$j('#steps').hide();
				$j('.reman_preloader_big').show();
		},
				
		complete: function(data){
			$j('.reman_preloader_big').hide();
			$j('#shipping-wrapper').html(data.responseText);
		}
			
	});
}


function estimateShipping (stocks,destzip){
	$j.ajax({

			url: "index/estimateshipping",
			type: 'POST',
			
			data: {
				stocks: stocks,
				destzip: destzip
			},
			
			beforeSend: function(){
				$j('.result-estimate').hide();
				$j('.result-estimate').html('');
				$j('.reman_preloader_shipping').show();
			},
			
			error: function(error){
				alert('Error !!!!');
				return;
			},
						
			complete: function(data){
				var data = $j.parseJSON(data.responseText);
				// Form Result Table
				buildTableResults(filterBestResult(data));	
			}
				
		});
}



function filterBestResult(data){
	
	var dataArray = [];
					
	for(key in data){ // Store Key (ZIP)
		
		var store = {};

		var MinServiceDays = 'Math.min('
		
		var responseLength = Object.keys(data[key]).length;
		
		var iterationIndex = 0;
		
		var carriers = {
			store: storeZIP[key]
		}
		
		for ( s in data[key]){ // SHIPPING results object 
			
			iterationIndex ++;
			
			store[data[key][s].CarrierName] = {
					'servicedays' : data[key][s].ServiceDays,
			}
			
			if(iterationIndex < responseLength){
				MinServiceDays += store[data[key][s].CarrierName].servicedays + ',';
			}else{
				MinServiceDays += store[data[key][s].CarrierName].servicedays + ')';
			}
			
		}
		
		var bestCarrier =  eval(MinServiceDays);
		
		for(c in store){
			
			if(bestCarrier == store[c].servicedays){
				
				if(store[c].servicedays == 0){
					
					var days = 1;
				
				}else{
					
					var days = store[c].servicedays;
				}
				
				carriers[c] = {
					'servicedays': days
				}
				
			}
			
		}
		
		dataArray.push(carriers);
	}
		
	return dataArray;

}


function buildTableResults(data){
	
	 for (key in data){
			
			var storeName =  data[key].store;
			
			for (k in data[key]){
				if(k != 'store'){
					var carrier =  k;
					var days = data[key][k].servicedays;
					if(carrier && days){
						var table = '<table>'+
								'<tr>'+
									'<td style="font-weight:bold">Delivery from</td>'+
									'<td>'+storeName+'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="font-weight:bold">Carrier</td>'+
									'<td>'+carrier+'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="font-weight:bold">Service Days</td>'+
									'<td>'+days+'</td>'+
								'</tr>'+
							'</table>';
							
						 $j('.result-estimate').append(table);
					}

				}
			}			
	 }
	 
	 $j('.reman_preloader_shipping').hide();
	 $j('.result-estimate').show();
}


function resetShipping(){
	$j('#shipping-wrapper').html('');
	$j('#steps').show();
}	