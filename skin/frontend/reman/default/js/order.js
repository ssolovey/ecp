
/* For selecting the destination STate lable*/

function getTheStateLabel(zip){

    $j.ajax({

        url: "http://zip.getziptastic.com/v2/US/" + zip,
        type: 'GET',


        error: function (error) {

            return;
        },

        complete: function (data) {

            var stateShort = $j.parseJSON(data.responseText).state_short;

            $j.each($j('#ship-to-states option'), function(index, value){

                if($j(value).attr('code') == stateShort) {

                    $j($j('#ship-to-states option')[index]).attr('selected', 'selected');
                    // Set selected State SHIP TO
                    $j('#st_state').attr('value', $j('#ship-to-states option:selected').html());
                }

            });
        }

    });

}


/* Request the total price*/

function getTotalPrice(isfluid){

    $j.ajax({
        url: "index/getprice",
        type: 'POST',

        data: {
            isfluid: isfluid
        },

        beforeSend: function () {

        },
        error: function (error) {


        },

        complete: function (data) {

            if(shipService.taxOnZip){

                $j('#tax-total-amount').attr('value',shipService.taxOnZip);

            }

            $j('#total-price').attr('value', Number(data.responseText).toFixed(2));
            $j('#total-amount').html('$'+Number(data.responseText).toFixed(2));

            console.log("FLUID PRICE IS ", data.responseText);

        }

    });

}

