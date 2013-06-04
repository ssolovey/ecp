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

function estimateShipping (stocks,destzip){
	$j.ajax({

			url: "index/estimateshipping",
			type: 'POST',
			
			data: {
				stocks: stocks,
				destzip: destzip
			},
			
			beforeSend: function(){
				
				$j('.ship-preloader').show();
				
			},
			
			error: function(error){
				alert('Shipping Service error. Try againe');
				$j('.ship-preloader').hide();
				return;
			},
						
			complete: function(data){
				
				var data = $j.parseJSON(data.responseText);
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
			
			
			store[data[key][s].CarrierName] = {
					'servicedays' : servicedays,
					'truecost' : data[key][s].TrueCost
			}
			
			MinServiceDays.push(store[data[key][s].CarrierName].servicedays);	
			
		}
		
		var bestCarrier =  MinServiceDays.min();
		
		
		for(c in store){
			
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
		dataArray.push(carriers);
	}
	return dataArray;
}


function buildTableResults(data){
	 var daysFilter = [];
	 for (key in data){
		var storeName =  data[key].store;
		for (k in data[key]){
			if(k != 'store'){
				var days = Number(data[key][k].servicedays); // Add One more day to estimated
				if(days){
					daysFilter.push(Number(days));
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
				'store': key
			}
		}
 	 }
	 // global variables for Order Page
	 window.servicedays = bestCarrier[store].servicedays;
	 window.truecost = bestCarrier[store].truecost;
	 window.carrier = bestCarrier[store].carrier;
	 window.store = bestCarrier[store].store;
	 // Form Days text
	 if(window.servicedays > 1){
	 	var textDays = 'Days';
	 }else{
	 	var textDays = 'Day';
	 }
	 
	 // Fill in Values
	 $j('#ship-price-value').html( '$'+window.truecost );
	 $j('#ship-from-value').html( window.store.substring(window.store.lastIndexOf('_'),-1));
	 $j('#ship-time-value').html( window.servicedays + ' '+ textDays);
	 // Show Values
	 $j('.ship-time').show();
	 $j('.ship-from').show();
	 //Show order button
	 
	 $j('#order-now-btn').show();
	 
	 $j('.ship-preloader').hide();
	 
	 
}

Array.min = function( array ){
    return Math.min.apply( Math, array );
};
	