
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

