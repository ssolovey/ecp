/**
 * Shipping time Estimation frontend logic
 * Get shipping estimation data fro shipping service and filter the best result according 
 * to ZIP code and warehouse availability  
 * @category    Reman
 * @package     frontend_reman_default_js
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
 
 /** CONST - Shipping MAX PRICE. Fixed to $200*/
 var MAXCOST = 200;
 
 /** Map Parts stores ZIP values*/	
 var storeZIP = {
	53223: 'Milwaukee, WI',
	91761: 'Ontario, CA',
	30344: 'Atlanta, GA',
	21113: 'Baltimore, MD',
	75261: 'Dulles, TX'
 }

/** Shipping estimation filter result object*/
var filterResults={};
/** 
  *	Estimate Shipping Ajax request
  * @param {number} - Destination ZIP.
  * @param {array}  - Available Warehouses for selected Part.
  * @return JSON DATA (Shipping Service result)
*/
function estimateShipping (stocks,destzip,inProgress){

    var stock_length = stocks.length;

    var result_index = 0;

    for(var i=0; i<=stock_length-1; i++){

        $j.ajax({

            url: "index/estimateshipping",
            type: 'POST',

            data: {
                stock: stocks[i],
                destzip: destzip
            },

            beforeSend: function(){

                $j('.ship-preloader').show();

            },

            error: function(error){
                alert('Shipping Service error. Try again');
                $j('.ship-preloader').hide();
                return;
            },

            complete: function(data){

                var data = $j.parseJSON(data.responseText);

                /* Filter Best result on Service Day value */
                getBestServiceDays(data,inProgress);

                result_index+=1;

                if(stock_length == result_index ) {

                    var filteredData = filterResults;
                    // reset filter Results
                    filterResults = {};

                    getBestServiceDays(filteredData, inProgress);

                    // FIll In result values
                    var bestDeliveryStock = Object.keys(filterResults)[0];
                    // global variables for Order Page
                    window.servicedays = filterResults[bestDeliveryStock].ServiceDays;
                    window.truecost = filterResults[bestDeliveryStock].TrueCost;
                    window.carrier = filterResults[bestDeliveryStock].CarrierName;
                    window.store = storeZIP[bestDeliveryStock];

                    // Form Days text
                    if (window.servicedays > 1) {
                        var textDays = 'Days';
                    } else {
                        var textDays = 'Day';
                    }

                    // Fill in Values
                    $j('#ship-price-value').html('$' + window.truecost);
                    $j('#ship-from-value').html(window.store);
                    $j('#ship-time-value').html(window.servicedays + ' ' + textDays);
                    // Show Values
                    $j('.ship-time').show();
                    $j('.ship-from').show();

                    //Show order button
                    $j('#order-now-btn').show();
                    //Show prepay message
                    $j('#prepay').show();
                    // hide shipping preloader
                    $j('.ship-preloader').hide();


                }

            }

        });

    }
}

/**
 *
 *Filter Best carrier according to Service Day rate
 * @param data
 * @param inProgress
 */

function getBestServiceDays(data,inProgress){

    var bufferResult = {

        OriginPostalCode:'',
        CarrierName: '',
        ServiceDays:'empty',
        TrueCost:''
    };

    for(key in data ){

        for(store in data[key] ) {

            if (store == 'ServiceDays') {

                /* If service Days == 0 add 1 day if not take value fro response*/
                var days = (Number(data[key][store]) == 0)? 1: Number(data[key][store]);


                /* If part is not available on any store and is in production progress = add 2 days for delivery  estimation */

                if( key === '53223' && inProgress ){

                     days = days + 2;

                }


                var price = Number(data[key]['TrueCost']);
                var carrier = data[key]['CarrierName'];
                var carrierId = data[key]['OriginPostalCode'];

                console.log('ALLResultDays: ' + days );
                console.log('ALLResultPrice: ' + price );

                if (bufferResult.ServiceDays == 'empty') {

                    bufferResult.ServiceDays = days;

                    bufferResult.TrueCost = price;

                    bufferResult.CarrierName = carrier;

                    bufferResult.OriginPostalCode = carrierId;

                } else {

                    if (days < bufferResult.ServiceDays) {

                        bufferResult.ServiceDays = days;

                        bufferResult.TrueCost = price;

                        bufferResult.CarrierName = carrier;

                        bufferResult.OriginPostalCode = carrierId;

                    }else if(days == bufferResult.ServiceDays){

                        if(price < bufferResult.TrueCost){

                            bufferResult.ServiceDays = days;

                            bufferResult.TrueCost = price;

                            bufferResult.CarrierName = carrier;

                            bufferResult.OriginPostalCode = carrierId;

                        }

                    }

                }

            }
        }

    }

    filterResults[bufferResult.OriginPostalCode] = bufferResult;

    console.log(' !!!!!!!!!!!!!!!!!bufferResultCarrierID: '+bufferResult.OriginPostalCode);
    console.log(' !!!!!!!!!!!!!!!!!bufferResultDATA: '+bufferResult.ServiceDays);
    console.log('!!!!!!!!!!!!!!!!!!bufferResultPRICE: '+bufferResult.TrueCost);

}


