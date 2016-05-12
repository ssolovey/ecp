/**
* Shipping estimation service
* Get the best possible shipping carrier
*
* */

(function(){

    // constructor
    var ShippingService = function(){

        /** Map Parts stores ZIP values*/

        this.storeNameMap = {

            53223: 'Milwaukee, WI',
            91761: 'Ontario, CA',
            30344: 'Atlanta, GA',
            21113: 'Baltimore, MD',
            75261: 'Dallas, TX',
            97201: 'Portland, OR'
        };


        this.storeZIPMap = {

            3237: 53223,
            3257: 91761,
            3258: 30344,
            3259: 21113,
            3236: 75261,
            3255: 97201
        };


        this.inventoryLabel = {

            3237: "parts_inventory_nc",
            3257: "parts_inventory_sw",
            3258: "parts_inventory_se",
            3259: "parts_inventory_ne",
            3236: "parts_inventory_sc",
            3255: "parts_inventory_nw"

        };


        this.stocksLabel = {

            53223: "parts_inventory_nc",
            91761: "parts_inventory_sw",
            30344: "parts_inventory_se",
            21113: "parts_inventory_ne",
            75261: "parts_inventory_sc",
            97201: "parts_inventory_nw"

        };

        /**
         * Map stocks according to rules
         * */
        //var s = [97201,91761,53223,75261,30344,21113];
        var s = [3255,3257,3237,3236,3258,3259]

        this.stocksRules = {
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
            WY : [s[0],s[2]],
            DEF: [s[2]]
        };

    }

    // helper method for getting the stock ZIP
    // according to the provided original ZIP value
    ShippingService.prototype.getWareHouses = function(zip,type,inProgress){
        //validate ZIP
        var self = this;
        if(Validation.get('IsEmpty').test(zip) || /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(zip)){
            $j.ajax({
                url: "http://zip.getziptastic.com/v2/US/" + zip,
                type: 'GET',
                error: function (error) {
                    $j('#zip_value').addClass('validation-failed');
                },
                complete: function (data) {

                    if(data.status === 200){

                        var stateShort = $j.parseJSON(data.responseText).state_short,
                            selectedStocks = self.stocksRules[stateShort];

                        //// trigger shipping estimation
                        self.estimateShipping(selectedStocks, zip, stateShort, $j.parseJSON(data.responseText).city,type, inProgress );
                    }


                }

            });

        }else{

            $j('#zip_value').addClass('validation-failed');

        }
    };

    /*Init Shipping Estimation*/
    ShippingService.prototype.estimateShipping = function(stocks,zip,state,city,type,inProgress){

        var self = this,
            result = [],
            inventoryLabel = "",
            response = {};

        for(var i=0; i<=stocks.length -1; i++){

            inventoryLabel = self.inventoryLabel[stocks[i]];

            $j.ajax({
                url: "index/estimateshipping",
                type: 'POST',

                data: {
                    stock: stocks[i],
                    destzip: zip,
                    state:state,
                    city:city,
                    invlabel:inventoryLabel
                },
                beforeSend: function(){
                    $j('.ship-preloader').show();
                },

                error: function(error){

                    alert('Shipping Service error. Try again');

                    $j('.ship-preloader').hide();

                    return;
                },

                complete: function(data) {

                    var resObj = $j.parseJSON(data.responseText);


                    if(resObj.data !== ""){
                        var  response = self.xmlToJson($j(resObj.data[0])[0]);

                        var  stock = resObj.stock;

                        //add stock field to response result
                        response.stockZIP = self.storeZIPMap[stock];

                        response.stockName = self.storeNameMap[self.storeZIPMap[stock]];

                        result.push(response);
                    }else{

                        result.push(resObj);
                    }



                    if(result.length-1 === stocks.length -1 ){

                        console.log('NEW SERVICE RESPONSE', result);

                        self.getBestService(result,type);

                        //// Calculate Tax
                        self.calculateTax(resObj.tax,type);

                    }
                }
            });

        }
    };

    ShippingService.prototype.getBestService = function(result,type){

        var buffer,
            item,
            stockZip,
            stockName;

        for(var i=0; i<=result.length-1;i++){

            if (result[i].hasOwnProperty("RATESHOPRESULTS")) {

                    item = result[i].RATESHOPRESULTS.RATESHOPRESULT;

                    stockZip = result[i].stockZIP;
                    stockName = result[i].stockName;

                    for (var z = 0; z <= item.length - 1; z++) {

                        //console.log("ALL RESULTS ",item[z]);

                        item[z].stockZIP = stockZip;
                        item[z].stockName = stockName;

                        if (buffer === undefined) {
                            buffer = item[z];
                        } else {

                            if (parseInt(buffer.TNTDAYS['#text']) == parseInt(item[z].TNTDAYS['#text'])) {

                                if (parseFloat(buffer.RATE['#text']) > parseFloat(item[z].RATE['#text'])) {
                                    buffer = item[z];
                                }

                            } else {

                                if (parseInt(buffer.TNTDAYS['#text']) > parseInt(item[z].TNTDAYS['#text'])) {
                                    buffer = item[z];
                                }
                            }
                        }
                    }
            }
        }

        if(buffer !== undefined){

            // global variables for Order Page
            this.servicedays = buffer.TNTDAYS['#text'];
            this.truecost = buffer.RATE['#text'];
            this.carrier = buffer.SERVICEDESCRIPTION['#text'];
            this.store = buffer.stockName;
            this.storeZIP = buffer.stockZIP;


            // Form Days text
            if (this.servicedays > 1) {
                this.textDays = 'Days';
            } else {
                this.textDays = 'Day';
            }

            if(type === "invent"){

                this.workWithDom();

            }else{

                this.workWithDomInOrder();

            }


            var bResult = {

                servicedays: this.servicedays,
                truecost: this.truecost,
                carrier: this.carrier,
                store: this.store,
                storeZIP: this.storeZIP

            };
        }

    };

    ShippingService.prototype.workWithDom = function(){
        ////// Show result



        // Fill in Values
        $j('#ship-price-value').html('$' + this.truecost);
        $j('#ship-from-value').html(this.store);
        $j('#ship-time-value').html(this.servicedays + ' ' + this.textDays);
        // Show Values
        $j('.ship-time').show();
        $j('.ship-from').show();

        //Show order button
        $j('#order-now-btn').show();
        //Show prepay message
        $j('#prepay').show();
        // hide shipping preloader
        $j('.ship-preloader').hide();

    };


    ShippingService.prototype.workWithDomInOrder = function(){

        // Set Shp from warehouse
        $j('#order-ship-from').html(this.store);

        $j('#order-ship-from-input').attr('value', this.store );
        $j('#order-ship-from-input-label').attr('value', this.stocksLabel[this.storeZIP] );


        // Set Shipping Days
        $j('#order-ship-time').html( this.servicedays + ' ' + this.textDays);

        $j('#order-ship-time-input').attr('value', this.servicedays + ' ' + this.textDays);

        // Set Shipping Value to Order Table
        $j('#order-shipping-cost').html('$'+this.truecost);

        // reset the fluid check box
        $j('#fluid-check-box').attr('checked',false);
        $j('#fluid-amount').attr('value', '0.00' );
        $j('#fluid-price').html('');
        $j('#check-fluid').html('Not Included');
        $j('#fluid-optional').attr('value','Not Included');

        if(typeof getTotalPrice != 'undefined'){
            getTotalPrice(false);
        }

        // hide shipping preloader
        $j('.ship-preloader').hide();

    }


    ShippingService.prototype.calculateTax = function(taxVal,type){

        if(taxVal.length > 0){

            this.taxOnZip = parseFloat(taxVal[0].tax);

        }else{

            this.taxOnZip = 0;
        }

        if(type === "invent"){

            var inventPrice = parseFloat($j('.invent-total').attr('data'));

            var calculatedTaxValue = inventPrice/100*this.taxOnZip;

            this.totalProductPrice = Number(inventPrice + calculatedTaxValue);

            $j('.invent-total').html('$'+ this.totalProductPrice.toFixed(2));

            $j('#invent-total-price').show();


        }else{

            if(this.taxOnZip !== 0){
                $j('#tax_value_text').html(this.taxOnZip+'%');
            }else{
                $j('#tax_value_text').html("");
            }
            $j('#tax_percent').attr('value', this.taxOnZip);
        }
    };

    ShippingService.prototype.xmlToJson = function(xml){

            var obj = {};
            if (xml.nodeType == 1) {
                if (xml.attributes.length > 0) {
                    obj["@attributes"] = {};
                    for (var j = 0; j < xml.attributes.length; j++) {
                        var attribute = xml.attributes.item(j);
                        obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                    }
                }
            } else if (xml.nodeType == 3) {
                obj = xml.nodeValue;
            }
            if (xml.hasChildNodes()) {
                for (var i = 0; i < xml.childNodes.length; i++) {
                    var item = xml.childNodes.item(i);
                    var nodeName = item.nodeName;
                    if (typeof (obj[nodeName]) == "undefined") {
                        obj[nodeName] = this.xmlToJson(item);
                    } else {
                        if (typeof (obj[nodeName].push) == "undefined") {
                            var old = obj[nodeName];
                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        obj[nodeName].push(this.xmlToJson(item));
                    }
                }
            }
            return obj;
    };

    window.shipService = new ShippingService();

}());

