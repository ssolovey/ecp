/**
 * Shipping Estimate frontend logic
 *  
 * @category    Reman
 * @package     frontend_reman_default_js
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
 
 var MAXCOST = 200;
 	
 var storeZIP = {
	53223: 'Milwaukee, WI',
	91761: 'Ontario, CA',
	30344: 'Atlanta, GA',
	21113: 'Baltimore, MD',
	75261: 'Dulles, TX'
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
				$j('#best-carriers').html('');
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
	
	var id = 0;
					
	for(key in data){ // Store Key (ZIP)
		
		var store = {};

		var MinServiceDays = [];
		
		var responseLength = Object.keys(data[key]).length;
		
		var iterationIndex = 0;
		
		var carriers = {
			store: storeZIP[key]
		}
		
		for ( s in data[key]){ // SHIPPING results object 
			
			iterationIndex ++;
			
			if( carriers.store == "Milwaukee, WI"){
				var servicedays = Number(data[key][s].ServiceDays) +2;
			}else{
				var servicedays = Number(data[key][s].ServiceDays);
			}
			
			
			if(data[key][s].TrueCost < MAXCOST){
			
				store[data[key][s].CarrierName] = {
						'servicedays' : servicedays,
						'truecost' : data[key][s].TrueCost
				}
				
				MinServiceDays.push(store[data[key][s].CarrierName].servicedays);	
			}
		}
		
		var bestCarrier =  MinServiceDays.min();
		
		
		for(c in store){
			
			if(bestCarrier == store[c].servicedays){
				id ++;
				if(store[c].servicedays == 0){
					
					var days = 1;
				
				}else{
					
					var days = store[c].servicedays;
				}
				
				carriers[c] = {
					'servicedays': days,
					'truecost' : store[c].truecost,
					'id': id
				}	
			}	
		}	
		dataArray.push(carriers);
	}
	return dataArray;
}


function buildTableResults(data){
	
	 var costFilter = [];
	 
	 var daysFilter = [];
	 
	 for (key in data){
			
			var storeName =  data[key].store;
			
			for (k in data[key]){
				if(k != 'store'){
					var carrier =  k;
					var days = Number(data[key][k].servicedays); // Add One more day to estimated
					var truecost = data[key][k].truecost;
					var id =  data[key][k].id;
			
					if(carrier && days){
						
						//costFilter.push(Number(truecost));
						
						daysFilter.push(Number(days));
						
						var table = '<table id="'+id+'">'+
								'<tr>'+
									'<td class="noborder" style="font-weight:bold">Delivery from</td>'+
									'<td class="noborder">'+storeName+'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="font-weight:bold">Carrier</td>'+
									'<td>'+carrier+'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="font-weight:bold">Service Days</td>'+
									'<td>'+days+'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="font-weight:bold">True Cost</td>'+
									'<td>'+truecost+'$</td>'+
								'</tr>'+
							'</table>';
							
						 $j('#best-carriers').append(table);
					}

				}
			}			
	 }
	 
	 getMinDaysCarrirer(data,daysFilter.min());
}


function getMinDaysCarrirer(data,mindays){
  var minDaysDelivery = {};
  var minPrice = []; 
  for (key in data){
	for (k in data[key]){
		var hash = Math.floor((Math.random()*10)+1);
		if( mindays == data[key][k].servicedays){
			minDaysDelivery[data[key].store+'_'+hash] = {
				'carrier'    : k,
				'servicedays': data[key][k].servicedays,
				'truecost' : data[key][k].truecost,
				'id': data[key][k].id
			}
			
			minPrice.push(Number(minDaysDelivery[data[key].store+'_'+hash].truecost)); 
		}
	}
  }
   bestPrice(minDaysDelivery,minPrice);
}

function bestPrice(data,minPrice){
	var bestCarrier = {};
	var price = minPrice.min();
	for (key in data){
		if(price == Number(data[key].truecost)){
			var store = key;
			bestCarrier[key] = {
				'carrier'    : data[key].carrier,
				'servicedays': data[key].servicedays,
				'truecost' : data[key].truecost,
				'id': data[key].id
			}
		}
 	 }
	 
	 
	 if(bestCarrier[store].servicedays > 1){
		var result_text =  bestCarrier[store].servicedays+' Days or less';	
	 }else{
	 	var result_text =  bestCarrier[store].servicedays+' Day';
	 }
	 
	 window.servicedays = bestCarrier[store].servicedays;
	 window.truecost = bestCarrier[store].truecost;
	 window.carrier = bestCarrier[store].carrier;
	 
	 $j('#shipping-result').html(result_text);
	 
	 $j('.reman_preloader_shipping').hide();
	 
	 $j('#'+ bestCarrier[store].id).addClass('best-sipping-price');
	 
	 $j('#estimated-price').html('$'+window.truecost);
	 
	 $j('.result-estimate').show();
	 
}

function resetShipping(){
	$j('#shipping-wrapper').html('');
	$j('#steps').show();
}

Array.min = function( array ){
    return Math.min.apply( Math, array );
};
	