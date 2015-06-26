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
 var storeZIPMap = {
	 53223: 'Milwaukee, WI',
	 91761: 'Ontario, CA',
	 30344: 'Atlanta, GA',
	 21113: 'Baltimore, MD',
	 75261: 'Dulles, TX',
     97201: 'Portland, OR'
 }

/**
 * Map stocks according to rules
 *
 * */

var s = [97201,91761,53223,75261,30344,21113];

var stocksRules = {
    AK : [s[0],s[2]],
    AL : [s[4],s[2]],
    AR : [s[3],s[2]],
    AZ : [s[3],s[1],s[2]],
    CA : [s[1],s[2]],
    CO : [s[3],s[2]],
    CT : [s[5],s[2]],
    DC : [s[5],s[2]],
    DE : [s[5],s[2]],
    FL : [s[4],s[2]],
    GA : [s[4],s[2]],
    HI : [s[1],s[2]],
    IA : [s[2]],
    ID : [s[0],s[2]],
    IL : [s[2]],
    IN : [s[2]],
    KS : [s[3],s[2]],
    KY : [s[2]],
    LA : [s[3],s[2]],
    MA : [s[5],s[2]],
    MD : [s[5],s[2]],
    ME : [s[5],s[2]],
    MI : [s[2]],
    MN : [s[2]],
    MO : [s[2]],
    MS : [s[4],s[2]],
    MT : [s[0],s[2]],
    NC : [s[4],s[2]],
    ND : [s[2]],
    NE : [s[2]],
    NH : [s[5],s[2]],
    NJ : [s[5],s[2]],
    NM : [s[3],s[2]],
    NV : [s[1],s[2]],
    NY : [s[5],s[2]],
    OH : [s[2]],
    OK : [s[3],s[2]],
    OR : [s[0],s[2]],
    PA : [s[5],s[2]],
    RI : [s[5],s[2]],
    SC : [s[4],s[2]],
    SD : [s[2]],
    TN : [s[4],s[2]],
    TX : [s[3],s[2]],
    UT : [s[1],s[0],s[2]],
    VA : [s[5],s[2]],
    VT : [s[5],s[2]],
    WA : [s[0],s[2]],
    WI : [s[0],s[2]],
    WV : [s[5],s[2]],
    WY : [s[0],s[2]]
}



function getStateByZip(zip,inProgress) {

    $j.ajax({

        url: "http://zip.getziptastic.com/v2/US/" + zip,
        type: 'GET',


        error: function (error) {
            console.log(error);
            return;
        },

        complete: function (data) {

            var stateShort = $j.parseJSON(data.responseText).state_short;

            var selectedStocks = stocksRules[stateShort];


            estimateShipping(selectedStocks, zip, inProgress );


        }

    });

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
    /* reset */
    filterResults={};

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

                    getClosestDistance(filteredData);

                    // FIll In result values
                    var bestDeliveryStock = Object.keys(filterResults)[0];
                    // global variables for Order Page
                    window.servicedays = filterResults[bestDeliveryStock].ServiceDays;
                    window.truecost = filterResults[bestDeliveryStock].TrueCost;
                    window.carrier = filterResults[bestDeliveryStock].CarrierName;
                    window.store = storeZIPMap[bestDeliveryStock];
                    window.storeZIP = bestDeliveryStock;

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
        TrueCost:'',
        Distance:''
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
                var distance = Number(data[key]['Distance']);

              /*console.log('ALLResultDays: ' + days );
                console.log('ALLResultPrice: ' + price );
                console.log('ALLDistance: ' + distance );*/

                if (bufferResult.ServiceDays == 'empty') {

                    bufferResult.ServiceDays = days;

                    bufferResult.TrueCost = price;

                    bufferResult.CarrierName = carrier;

                    bufferResult.OriginPostalCode = carrierId;

                    bufferResult.Distance = distance;

                } else {

                    if (days < bufferResult.ServiceDays) {

                        bufferResult.ServiceDays = days;

                        bufferResult.TrueCost = price;

                        bufferResult.CarrierName = carrier;

                        bufferResult.OriginPostalCode = carrierId;

                        bufferResult.Distance = distance;

                    }else if(days == bufferResult.ServiceDays){

                        if(price < bufferResult.TrueCost){

                            bufferResult.ServiceDays = days;

                            bufferResult.TrueCost = price;

                            bufferResult.CarrierName = carrier;

                            bufferResult.OriginPostalCode = carrierId;

                            bufferResult.Distance = distance;

                        }

                    }

                }
            }
        }
    }

    filterResults[bufferResult.OriginPostalCode] = bufferResult;
}


function getClosestDistance(data){

    var bufferResult = {

        OriginPostalCode:'',
        CarrierName: '',
        ServiceDays:'empty',
        TrueCost:'',
        Distance:''

    };

    for(key in data ){

        for(store in data[key] ) {

            if (store == 'Distance') {

                /* If service Days == 0 add 1 day if not take value fro response*/
                var days = (Number(data[key]['ServiceDays']) == 0)? 1: Number(data[key]['ServiceDays']);

                var price = Number(data[key]['TrueCost']);
                var carrier = data[key]['CarrierName'];
                var carrierId = data[key]['OriginPostalCode'];
                var distance = Number(data[key]['Distance']);



                if (bufferResult.ServiceDays == 'empty') {

                    bufferResult.ServiceDays = days;

                    bufferResult.TrueCost = price;

                    bufferResult.CarrierName = carrier;

                    bufferResult.OriginPostalCode = carrierId;

                    bufferResult.Distance = distance;

                } else {

                    if (distance < bufferResult.Distance) {

                        bufferResult.ServiceDays = days;

                        bufferResult.TrueCost = price;

                        bufferResult.CarrierName = carrier;

                        bufferResult.OriginPostalCode = carrierId;

                        bufferResult.Distance = distance;

                    }else if(distance == bufferResult.Distance){

                        if(price < bufferResult.TrueCost){

                            bufferResult.ServiceDays = days;

                            bufferResult.TrueCost = price;

                            bufferResult.CarrierName = carrier;

                            bufferResult.OriginPostalCode = carrierId;

                            bufferResult.Distance = distance;

                        }

                    }
                }
            }
        }
    }

    filterResults[bufferResult.OriginPostalCode] = bufferResult

    /*console.log(' !!!!!!!!!!!!!!!!!bufferResultCarrierID: '+bufferResult.OriginPostalCode);
    console.log(' !!!!!!!!!!!!!!!!!bufferResultDATA: '+bufferResult.ServiceDays);
    console.log('!!!!!!!!!!!!!!!!!!bufferResultPRICE: '+bufferResult.TrueCost);
    console.log('!!!!!!!!!!!!!!!!!!bufferResultDistance: '+bufferResult.Distance);*/

}

