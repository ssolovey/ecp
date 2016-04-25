/**
 * Reman Catalog  Frontend Logic 
 * Quote page logic 
 * Ajax requests for such Data as Make Year , Model , Parts Groups search , Product page,  
 * Order Profile logic
 * Order Submit logic
 * @category    Reman
 * @package     frontend_reman_default_js
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
 
/** Resolve conflicts with prototype js default library */
var $j = jQuery.noConflict();

/** Document ready event */
$j(document).ready(function(){
	/** Initiate events on Quick Quote links */
	$j('#steps').bind('click',function(event){
		Reman_QuickQuote.prototype.eventsHandler(event);
	});
	/** Remove validation error red borders from focused input*/
	$j('input').click(function(e){
		if($j(e.target).hasClass('validation-failed')){
			$j(e.target).removeClass('validation-failed');
		}
	});
	/* Enable jQuery Input PlaceHolders for IE*/
	initIEPlaceholders();



    // Trigger Shipping estimation on Enter Button Event
    $j('#sku-number').keydown(function(event) {
        if (event.which == 13) {
            if($j("#sku-number").attr("value") != ""){
                Reman_QuickQuote.prototype.loadProductPageBySku($j("#sku-number").attr("value"));
		}
	   }
	});

	
});

/** Create NameSpace for Quick Quote module */
function Reman_QuickQuote() {};

Reman_QuickQuote.prototype = {
	// Flags
	isYearActive: false,
	isModelActive: false,
	isGroupActive: false,

	// Current Selected values
	currentSelectedMake: '',
	currentSelectedYear: '',
	currentSelectedModel: '',
	currentCatSelected:'',
	currentPartRootSelected:[],
	currentPartNumber:'',
	currentSelectedEngine:'',
	currentSelectedDrive:'',
	partsAdditionlInfo:[],

	/*General Event Handler*/
	eventsHandler: function(event){
		//current clicked element
		var elem = event.target;
		// if Link return
		if(elem.tagName == 'A'){ 
			return;
		}
		//Hide error popup
		$j('#product_error_popup').fadeOut();

		while (elem) {
			switch (elem.className){
				/*Event for Category Select*/	
				case 'cat_select':{
					//current Selected Category
					this.currentCatSelected = $j(elem).attr('cat');
					$j('#group_select').hide(); // hide category block
					$j('#make_tbl').show(); // show make block
					// update bread crumb link
					$j('#breadcrumb_info').append('<span><span class="breadcrumb cat_link">'+elem.innerHTML+'</span></span>');
					//Update Banner text
					$j('#welcome_bunner').html('What is the vehicle make?');

					return;

					break;
				}
				/*Event for Make Table*/
				case 'make_select':{
					// calculate production years
					var year_range = $j(elem).attr('endyear') - $j(elem).attr('startyear');
					// current Selected Make
					this.currentSelectedMake = $j(elem).attr('name');
					// Select Make
					this.selectMake($j(elem).attr('value'),Number(year_range),Number($j(elem).attr('endyear'))+1);
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					//Update Banner text
					$j('#welcome_bunner').html('What is the model year?');
					
					return;

					break;
				}
				/*Event for Year Table*/
				case 'year_select': {
					//current Selected Year
					this.currentSelectedYear = $j(elem).html();
					// selectYear(makeid,year)
					this.selectYear($j(elem).attr('value'), $j(elem).attr('year'));
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					
					return;

					break;
				}
				/*Event for Model Table*/
				case 'model_select': {
					// selectModel(vehicle_id,name)
					this.selectModel($j(elem).attr('value'), elem.innerHTML);
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					
					return;

					break;
				}

				case 'parts_select':{
					// get Engine
					if($j(elem).parent().parent().attr('type') == 'engine'){
						this.currentSelectedEngine = $j(elem).html();
					}
					// get Drive
					if($j(elem).parent().parent().attr('type') == 'drive'){
						this.currentSelectedDrive = $j(elem).html();
					}
					
					//selectPart(applic_id,subgroup,id,name)
					this.selectPart($j(elem).attr('value'),$j(elem).attr('type'),elem.parentElement.parentElement.id, elem.innerHTML);
					
					// define parent element type
					var parentElementType = $j(elem.parentElement.parentElement).attr('type');
					
					//get Parts Additional Info
					if( parentElementType == 'tag number' || parentElementType == 'cyl type' || parentElementType == 'unit type' || parentElementType == 'aspiration' ){
						this.partsAdditionlInfo.push(elem.innerHTML);
					}
					
					// Need to reset All errors if necessary
					this.resetSearchErrorResults();
					return;

					break;
				}

			}
			/******************************************** BREADCRUMBS EVENTS ************************************************/
			if($j('#breadcrumb_info').hasClass('disabled')){
				return;
			}
			/*Event for Breadcrumbs Goupe links*/
			if (elem.className == 'breadcrumb group_link') {
				$j('.select_part').hide() // hide  groups
				$j('#'+$j(elem).attr('prevgroup')).show(); // show next group according to subgroup ID
				//Update Banner text
				$j('#welcome_bunner').html('What is the '+$j('#'+$j(elem).attr('prevgroup')).attr('type')+'?');
				$j('#reman-invent_info').hide();
				$j('#reman-invent_info').html('');
				// deleted other groups
				if($j(elem).parent().next().length){
					$j(elem).parent().nextAll().remove();
					$j('#reman-product_info').hide(); // hide product info block
					$j('#parts_tbl').show(); // show parts table

					for(var y=0;y<=$j(elem).parent().next().length+1;y++){
						this.currentPartRootSelected.pop();
						this.partsAdditionlInfo.pop();
					}

				}else{
					this.currentPartRootSelected = [];
					this.partsAdditionlInfo = jQuery.grep(this.partsAdditionlInfo, function (a) { return a != $j(elem).html(); });
				}

				//delete this link
				$j(elem).parent().remove();
				
				this.resetSearchErrorResults();

				return;
			}

			/*Event for Breadcrumbs Make links*/
			if (elem.className == 'breadcrumb make_link'){
				this.turnOnMakeBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				// reset Parts Additional Array data
				this.partsAdditionlInfo = [];
				return;
			}

			/*Event for Breadcrumbs Yearlinks*/

			if (elem.className == 'breadcrumb year_link'){
				this.turnOnYearBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				// reset Parts Additional Array data
				this.partsAdditionlInfo = [];
				return;
			}

			/*Event for Breadcrumbs Model links*/
			if (elem.className == 'breadcrumb model_link'){
				this.turnOnModelBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected = [];
				// reset Parts Additional Array data
				this.partsAdditionlInfo = [];
				return;
			}

			/*Event for Breadcrumbs Cat links*/
			if (elem.className == 'breadcrumb cat_link'){
				this.turnOnCatBreadcrumb();
				this.resetSearchErrorResults();
				
                /* clear all*/
                this.currentSelectedMake= '';
                this.currentSelectedYear= '';
                this.currentSelectedModel= '';
                this.currentPartRootSelected=[];
                this.currentPartNumber='';
                this.currentSelectedEngine='';
                this.currentSelectedDrive='';
                this.partsAdditionlInfo=[];
                this.currentApplic_id = '';

                $j('#sku-number').attr('value','');

				return;
			}

			/*Event for Breadcrumbs Cat links*/
			if (elem.className == 'breadcrumb sel_group_link'){
				this.turnOnGroupBreadcrumb();
				this.resetSearchErrorResults();
				this.currentPartRootSelected.pop();
				this.partsAdditionlInfo.pop();
				if($j('#'+$j(elem).attr('prevgroup')).attr('type') == "group"){
					$j('#welcome_bunner').html('Please select');
				}else{	
					$j('#welcome_bunner').html('What is the '+$j('#'+$j(elem).attr('prevgroup')).attr('type')+'?');
				}
				return;
			}
			elem = elem.parentNode;
		}
	},

	selectMake: function(makeid,range,endyear){
		var buffer = '<ul class="list list_first">'; // buffer string 
		range+=1;
		for(var i = 1; i<=range; i++){  
			buffer += '<li class="year_select" value="'+makeid+'" year="'+(endyear-=1)+'">'+endyear+'</li>';
			
			if(endyear == 2010 || endyear == 2000 || endyear == 1990 ){
				buffer += '</ul><ul class="list">';
			}
		}
		//append new info to year table
		$j('#year_tbl').append(buffer);
		// hide make table
		$j('#make_tbl').hide();
		// set selected Make name to top menu
		$j('.selected_make').html(this.currentSelectedMake);
		//show year table
		$j('#year_tbl').show();
		// year table active true
		this.isYearActive = true;
		// update bread crumb link
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb make_link">'+this.currentSelectedMake+'</span></span>');
	},
	/**
	 * Reset Search Errors Info
	*/
	resetSearchErrorResults: function(){
		//clear start over button link
		$j('#product_details_btn a').attr('href','#');
		//clear product info fields
		$j('.sku').remove();
		//reset Product DATA Error
		$j('.product_error').html('');
	},
	/**
	 * Clear Year Table
	*/
	clearYear: function(){
		//clear year
		$j('#year_tbl').hide();
		$j('#year_tbl .list').remove();
		$j('.year_link').parent().remove();
		this.isYearActive = false;
	},
	/**
	 * Clear Model Table
	*/
	clearModel: function(){
		//clear model
		$j('#model_tbl').hide();
		$j('#table_container').css('min-height', '');
		$j('#preloader_cont').css('height', '');
		$j('.model_select').parent().remove();
		$j('.model_link').parent().remove();
		this.isModelActive = false;
	},
	/**
	 * Clear Group Table
	*/
	clearGroup: function(){
		//clear group
		$j('#parts_tbl').hide();
		$j('.select_part').remove();
		$j('.group').hide();
		this.clearSubGroups();
		this.isGrouplActive = false;
	},
	/**
	 * Clear Make Table
	*/
	clearMake: function(){
		//clear Make
		$j('#make_tbl').hide();
		$j('.make_link').parent().remove();
	},
	/**
	 * Clear Subgroups Table
	*/
	clearSubGroups: function(){
		// clear subgroup
		if($j('.group_link').length){
			$j('.group_link').parent().remove();
			$j('.sel_group_link').parent().remove();
		}else{
			$j('.sel_group_link').parent().remove();
		}
		$j('.select_part').hide();
		//Show First Group
		$j($j('.select_part').get(0)).show();
	},
	/**
	 * Clear Product Table
	*/
	clearProductInfo: function(){
		//Inventary Info
		$j('#reman-invent_info').hide();
		$j('#reman-invent_info').html('');
		//Product Info
		$j('#reman-product_info').hide();
		$j('#reman-product_info').html('');
	},
	/**
	 * On Make Breadcrumb click event
	*/
	turnOnMakeBreadcrumb: function(){
		if(this.isYearActive){
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			$j('.make_link').parent().remove();
			//show make table
			$j('#make_tbl').show();
			//Update Banner text
			$j('#welcome_bunner').html('What is the vehicle make?');
			// Reset Drive variables
			Reman_QuickQuote.prototype.currentSelectedDrive = '';
			Reman_QuickQuote.prototype.currentSelectedEngine = '';
		}
	},
	/**
	 * On Year Breadcrumb click event
	*/
	turnOnYearBreadcrumb: function(){
		if(this.isModelActive){
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			$j('.year_link').parent().remove();
			//show year table
			$j('#year_tbl').show();
			//Update Banner text
			$j('#welcome_bunner').html('What is the model year?');
			// Reset Drive variables
			Reman_QuickQuote.prototype.currentSelectedDrive = '';
			Reman_QuickQuote.prototype.currentSelectedEngine = '';
		}
	},
	/**
	 * On Model Breadcrumb click event
	*/
	turnOnModelBreadcrumb: function(){
		if(this.isGroupActive){
			this.clearGroup();
			this.clearProductInfo();
			$j('.model_link').parent().remove();
			$j('.sel_group_link').parent().remove();
			//Update Banner text
			$j('#welcome_bunner').html('What is the model?');
			//show model table
			$j('#model_tbl').show();
			
			// Reset Drive variables
			Reman_QuickQuote.prototype.currentSelectedDrive = '';
			Reman_QuickQuote.prototype.currentSelectedEngine = '';
		}
	},
	/**
	 * On Category Breadcrumb click event
	*/
	turnOnCatBreadcrumb: function(){
			this.clearMake();
			this.clearYear();
			this.clearModel();
			this.clearGroup();
			this.clearProductInfo();
			$j('.cat_link').remove();
			//show cat table
			$j('#group_select').show();
			this.currentCatSelected = '';
			
			//Update Banner text
			$j('#welcome_bunner').html('Welcome! How can we help you today?');
			
			// Reset Drive and variables
			Reman_QuickQuote.prototype.currentSelectedDrive = '';
			Reman_QuickQuote.prototype.currentSelectedEngine = '';
	},
	/**
	 * On Group Breadcrumb click event
	*/
	turnOnGroupBreadcrumb: function(){
		this.clearProductInfo();
		$j('#parts_tbl').show();
		$j('.sel_group_link').parent().remove();
	},
	
	/**
	 * Select Year DataBase Query 
	*/
	selectYear: function(makeid,year){
		var self = this;
		$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 2,  
					id:makeid,
					year: year
				},
				beforeSend: function(){
					$j('#year_tbl').hide();
					$j('#preloader_cont').css('display','block');
					$j('#breadcrumb_info').addClass('disabled');
				},
				complete: function(data){
					//parse response to JSON Object		  
					var response =  $j.parseJSON(data.responseText);
					// If Session Expired
					if(response.end_session) {
						//show error popup
						$j('#preloader_cont').hide();
						$j('#session_error_popup').fadeIn();
						
						return;
					}
					
					// CHECK For DATA if NULL return
					if(response.length == 0){
						//show error popup
						$j('#product_error_popup').fadeIn();
						Reman_QuickQuote.prototype.turnOnYearBreadcrumb();
						$j('#preloader_cont').fadeOut(500,function(){
							$j('#breadcrumb_info').removeClass('disabled');
							$j('#year_tbl').show();
						});	
						return;
					}


                    /**
                     *
                     * Calculate equal items on rows according to overall model list length
                     * If number of items <= 40, keep current implementation
                     * If number of items > 40, number of items in the columns should = number of items / 4.
                     *
                     * For example if number of items = 41, then column 1 = 11, column 2 = 11, column 3 = 11, column 4 = 8.
                     *
                     * */

                    var rowCount = 0;

                    var itemCount = 0;

                    //var rowItemLength = Number(((response.length) / 3).toFixed(0));

                    var rowItemLength = Math.ceil(response.length/3);

                    //var restOfItems = ((((response.length) / 3) - rowItemLength) * 3)+rowItemLength;


                    var restOfItems = response.length - (rowItemLength * 2);

                    var buffer = '<ul class="list list_first">'; // buffer string

                    if(response.length <= 30){

                        for(var i = 0; i<=response.length-1; i++){ // nest select with options
                            buffer += '<li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';
                            if(i%10 == 0 && i!=0){
                                buffer += '</ul><ul class="list">';
                            }
                        }

                    }else{

                        //console.log('length: '+response.length+' rowItemLength: '+ rowItemLength+'('+ ((rowItemLength*2)+restOfItems) +')  '+'restOfItems: '+restOfItems + '('+ (rowItemLength*2+restOfItems)+')');

                        for(var i = 0; i<=response.length-1; i++){ // nest select with options

                            itemCount+=1;

                            if(rowCount == 2){

                                rowItemLength = restOfItems;
                            }

                            if(itemCount <= rowItemLength){

                                buffer += '<li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';


                            }else{

                                rowCount+=1;

                                itemCount = 1;

                                buffer += '</ul><ul class="list"><li class="model_select"  value="'+response[i]['vehicle_id']+'">'+response[i]['model']+'</li>';
                            }

                        }

                    }



                    $j('#model_tbl').append(buffer);

                    $j('#preloader_cont').fadeOut(500,function(){
						$j('#breadcrumb_info').removeClass('disabled');
						$j('#model_tbl').show();
					});
				}
		});

		/*Update breadcrumb*/
		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb year_link">'+this.currentSelectedYear+'</span></span>');
		
		//Update Banner text
		$j('#welcome_bunner').html('What is the model?');
		
		this.isModelActive = true;
	},
	/**
	 * Select Model DataBase Query 
	*/
	selectModel: function(vehicle_id,name){
			this.currentSelectedModel = name;
			$j.ajax({
				url: "index/ajax",
				type: 'POST',
				data: {
					step: 3,  
					id:vehicle_id,
					category: Reman_QuickQuote.prototype.currentCatSelected
				},
				beforeSend: function(){
					//hide model table
					$j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
					$j('#table_container').css('min-height', $j('#table_container').height() + 'px');
					$j('#model_tbl').hide();
					$j('#preloader_cont').css('display','block');
					$j('#breadcrumb_info').addClass('disabled');
				},
				complete: function(data){
						//parse response to JSON Object		  
						var response = $j.parseJSON(data.responseText);
						
						// If Session Expired
						if(response.end_session) {
							//show error popup
							$j('#preloader_cont').hide();
							$j('#session_error_popup').fadeIn();
							
							return;
						}
						//If Group == 0 and Subgroup == 0 and Part_number in not null
						if(response.length == 1){
							if(response[0].part_number != null){
								 Reman_QuickQuote.prototype.selectPart(response[0].applic_id,0,1,'');
								return;
							}
						}
						
						// CHECK For DATA if NULL return
						if(response.length == 0){
							$j('#preloader_cont').fadeOut(500,function(){
								$j('#breadcrumb_info').removeClass('disabled');
								$j('#model_tbl').show();
								//show error popup
								$j('#product_error_popup').fadeIn();
							});
							return;
						}
						var obj= {}; // create empty object to handle response data
						for(var i=1; i<=response.length-1;i++)						{
								if(response[i].applic == null){
								$j('#preloader_cont').fadeOut(500,function(){
										$j('#breadcrumb_info').removeClass('disabled');
										$j('#model_tbl').show();
										//show error popup
										$j('#product_error_popup').fadeIn();
									});
									return;
								}
							if($j.isEmptyObject(obj) || current_group != response[i].group_number ){
								obj[response[i].group_number] = {
									'heading': response[i].menu_heading,
									'applic' : [
													{
														name:response[i].applic , 
														id:response[i].applic_id,
														subgroup:response[i].subgroup

													}

												]

								}

							}else{
								obj[current_group].applic.push(
																{
																	name:response[i].applic,
																	id:response[i].applic_id, 
																	subgroup:response[i].subgroup
																}

															);

							}
								var current_group = response[i].group_number; 
						}
						Reman_QuickQuote.prototype.formSelectGroupBlock(obj,name);
				}
			});
	},
	/**
	 * Form HTML Template for Parts Group Table 
	*/
	formSelectGroupBlock: function(obj,name){
		for (key in obj){
			// buffer string 
			var buffer = "<ul class='select_part_box'>"; // buffer string 
			// heading
			var header = '';
			var block_counter = 0;

			for(var i = 0; i<=obj[key].applic.length-1; i++){
				// form link to part id
				buffer += '<li class="parts_select" type="'+obj[key].applic[i].subgroup+'" value="'+obj[key].applic[i].id+'">'+obj[key].applic[i].name+'</li>';
				if(obj[key].heading != null){
					header = obj[key].heading;
				}else{
					header = 'group';
				}
				// Group template
				if(i%20 == 0 && i!=0){
					block_counter +=1;
					buffer += '</ul><ul class="select_part_box block_'+block_counter+'">';
				}
			}
				var template = "<div id='"+key+"' type='"+header.toLowerCase()+"' class='select_part'>"+
										buffer
								  "</div>";
				// append to container
				$j('#parts_tbl').append(template);
				// Sort Values
				if($j('#'+key).find('ul').length >1){

					for(var x=0; x<= $j('#'+key).find('ul').length;x++){
						$j($j('#'+key).find('ul')[x]).find('li').sort(sortAlpha).appendTo($j('#'+key).find('ul')[x]);
					}

				}else{

					$j('#'+key).find('ul li').sort(sortAlpha).appendTo($j('#'+key).find('ul'));
				}

				//Show First Group
				$j($j('.select_part').get(0)).show();
				//Update Banner text
				if(header == "group"){
					$j('#welcome_bunner').html('Please select');
				}else{
					$j('#welcome_bunner').html('What is the '+$j($j('.select_part').get(0)).attr('type')+'?');
				}
		}

		$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb model_link">'+this.currentSelectedModel+'</span></span>');
		this.isGroupActive = true;
		$j('#preloader_cont').fadeOut(500,function(){
			$j('#table_container').css('min-height', '');
			$j('#breadcrumb_info').removeClass('disabled');
			$j('#parts_tbl').show();
		});

	},
	/**
	 * Select Part DataBase Query 
	*/
	selectPart: function(applic_id,subgroup,id,name){
			var self = this;
			if(subgroup == 0) {
					$j.ajax({
						url: "index/product",
						type: 'POST',
						data: {
							id:applic_id,
							category: Reman_QuickQuote.prototype.currentCatSelected
						},

						beforeSend: function(){
							$j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
							$j('#table_container').css('min-height', $j('#table_container').height() + 'px');
							$j('#parts_tbl').hide();
							$j('#preloader_cont').show();
							$j('#breadcrumb_info').addClass('disabled');
						},

						complete: function(data){

                            if(data.responseText == "no sku"){
                                $j('#preloader_cont').fadeOut(500,function(){
                                    Reman_QuickQuote.prototype.currentPartRootSelected.pop();
                                    $j('#breadcrumb_info').removeClass('disabled');
                                    $j('#parts_tbl').show();
                                    //show error popup
                                    $j('#product_error_popup').fadeIn();
                                });


                                return false;

                            }

                            Reman_QuickQuote.prototype.currentPartRootSelected.push(name);

                            // trancate group text if longer than 30 letters
                            if(name.length >30){
                                name = jQuery.trim(name).substring(0, 30).trim(this) + "...";
                            }
                            //hide parts table
                            $j('#parts_tbl').hide();
                            //insert product info block
                            $j('#preloader_cont').fadeOut(500,function(){
                                $j('#table_container').css('min-height', '');
                                // set breadcrumb info about last successful or not succssful choise
                                $j('.sel_group_link').parent().remove();

                                if(name == ""){
                                    $j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb model_link">'+Reman_QuickQuote.prototype.currentSelectedModel+'</span></span>');
                                    Reman_QuickQuote.prototype.isGroupActive = true;
                                }else{
                                    $j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb sel_group_link"  prevgroup="'+id+'" type="'+$j('#'+id).attr('type')+'">'+name+'</span></span>');

                                }

                                $j('#breadcrumb_info').removeClass('disabled');
                                // Show product page
                                $j('#reman-product_info').show();
                                // Check for IE8 USE Native innerHTML method
                                if ($j.browser.msie  && parseInt($j.browser.version, 10) === 8) {
                                    $j('#reman-product_info')[0].innerHTML = data.responseText;
                                }else{
                                    $j('#reman-product_info').html(data.responseText);
                                }

                                $j('#current_selected_year').html(Reman_QuickQuote.prototype.currentSelectedYear);

                                // Set up current sected Drive type to product page
                                if(Reman_QuickQuote.prototype.currentSelectedDrive != ''){
                                    $j('#current_selected_drive').html(Reman_QuickQuote.prototype.currentSelectedDrive);
                                    $j('#current_selected_drive_db').hide();
                                    $j('#current_selected_drive').show();

                                }

                                Reman_QuickQuote.prototype.currentApplic_id = applic_id;





                            });

							// Load Invent Block
							Reman_QuickQuote.prototype.loadInventoryInfo(applic_id);

						}
				});
			}else{
				$j('#'+id).hide() // hide current group
				$j('#'+subgroup).show(); // show next group according to subgroup ID
				//Update Banner text
				$j('#welcome_bunner').html('What is the '+$j('#'+subgroup).attr('type')+'?');
				
				this.currentPartRootSelected.push(name);

				$j('#breadcrumb_info').append('<span><span>></span><span class="breadcrumb group_link" prevgroup="'+id+'" currentgroup="'+subgroup+'" type="'+$j('#'+id).attr('type')+'">'+name+'</span></span>');
				
			}
	},


    loadProductPageBySku: function(sku){

        //remove all spaces from sku string
        sku = sku.replace(/\s+/g, '');

        $j.ajax({

            url: "index/product",

            type: 'POST',

            data: {
                sku:sku
            },

            beforeSend: function(){
                $j('#preloader_cont').css('height', $j('#table_container').height() + 'px');
                $j('#table_container').css('min-height', $j('#table_container').height() + 'px');
                $j('#group_select').hide();
                $j('#preloader_cont').show();
                $j('#breadcrumb_info').addClass('disabled');

                switch(sku[0].toUpperCase()){

                    case 'T':{


                        label = 'Automatic Transmissions';

                        Reman_QuickQuote.prototype.currentCatSelected = 'T';

                        break;
                    }

                    case 'X':{

                        label = 'Transfer Case';

                        Reman_QuickQuote.prototype.currentCatSelected = 'X';

                        break;
                    }

                    default:{

                        $j('#preloader_cont').fadeOut(500,function(){

                            $j('#group_select').show();

                            //show error popup
                            $j('#sku_error_popup').fadeIn();

                            $j('#breadcrumb_info').removeClass('disabled');


                        });

                        return false;
                        break;
                    }


                }

            },

            complete: function(data){

                if(data.responseText == 'no sku'){

                    $j('#preloader_cont').fadeOut(500,function(){

                        $j('#group_select').show();

                        //show error popup
                        $j('#sku_error_popup').fadeIn();

                        $j('#breadcrumb_info').removeClass('disabled');


                    });

                    return false;
                }


                //insert product info block
                $j('#preloader_cont').fadeOut(500,function(){

                    $j('#table_container').css('min-height', '');

                    // update bread crumb link
                    $j('#breadcrumb_info').append('<span><span class="breadcrumb cat_link">'+label+'</span></span>');


                    $j('#breadcrumb_info').removeClass('disabled');

                    // Show product page
                    $j('#reman-product_info').show();

                    // Check for IE8 USE Native innerHTML method
                    if ($j.browser.msie  && parseInt($j.browser.version, 10) === 8) {

                        $j('#reman-product_info')[0].innerHTML = data.responseText;

                    }else{

                        $j('#reman-product_info').html(data.responseText);
                    }

                });

                // Load Invent Block
                Reman_QuickQuote.prototype.loadInventoryInfoBySku(sku);

            }
        });

    },

	/**
	 * Load Inventory Info
	*/
	loadInventoryInfo: function(id){
		$j.ajax({

				url: "index/invent",
				type: 'POST',
				data: {
					id:id
				},
				complete: function(data){

                    if(data.responseText == "no sku"){
                        return false;
                    }

						$j('#reman-invent_info').show();
						$j('#reman-invent_info').html(data.responseText);

					}
				
		});
	},

    loadInventoryInfoBySku:function(sku){
        $j.ajax({

            url: "index/invent",
            type: 'POST',
            data: {
                sku:sku
            },
            complete: function(data){

                if(sku[0] != 'T' && sku[0] != 'X' ){
                    return false;
                }

                $j('#reman-invent_info').show();
                $j('#reman-invent_info').html(data.responseText);

            }

        });
    },
	
	/**
	 * Load Order Table
	*/
	loadOrder : function(zip){
		$j.ajax({

				url: "index/order",
				type: 'POST',
				data: {
					sku: Reman_QuickQuote.prototype.currentPartNumber,
					year: Reman_QuickQuote.prototype.currentSelectedYear,
					model: Reman_QuickQuote.prototype.currentSelectedModel,
					drive: Reman_QuickQuote.prototype.currentSelectedDrive,
					make: Reman_QuickQuote.prototype.currentSelectedMake,
					type: Reman_QuickQuote.prototype.currentCatSelected,
					partsAdditionlInfo:Reman_QuickQuote.prototype.partsAdditionlInfo,
					zip:zip

					

				},
				
				beforeSend: function(){
                            $j('#reman-invent_info').hide();
							$j('#steps').hide();
							$j('.reman_preloader_big').show();
				},
						
				complete: function(data){

					$j('.reman_preloader_big').hide();
					$j('#order-now-btn').hide();
					$j('#prepay').hide();		 
					$j('#ship-go-table').hide();
					$j('#order-wrapper').html(data.responseText);
				}
				
		});
	
	
	},
	/**
	 Reset Order
	*/
	resetOrder : function(){
		$j('#order-wrapper').html('');
		$j('#steps').show();
		$j('#reman-invent_info').show();
		$j('#order-now-btn').hide();
		$j('#ship-go-table').show();
		$j('#prepay').show();
        $j('#zip_value').attr('value','');


        $j('#invent-total-price').hide();

        // hide Values
        $j('.ship-time').hide();
        $j('.ship-from').hide();
		/*if(isShippingEstimation){
			

		 
		 //Show order button
		 $j('#order-now-btn').hide();
		 $j('#prepay').hide();
			
		 $j('#black-mask').hide();
		 $j('#popup-shipping-re-estimation').hide();
			
		}*/
	
	}
}


/** Sort Helper */

jQuery.fn.sort = function() {  
   return this.pushStack( [].sort.apply( this, arguments ), []);  
 };  
  
function sortAlpha(a,b){  
    return a.innerHTML > b.innerHTML ? 1 : -1;  
};

/* *Profile Enable Account*/
function manageUserAccount(action,id,el){
		$j.ajax({
				url: action,
				type: 'POST',
				data: {
					id: id	
				},
				
				beforeSend: function(){
					$j(el).parent().find('a').hide();
					$j(el).parent().append('<div class="lite-loader"></div>');
				},
						
				complete: function(data){
					// If Session Expired
					if(data.responseText == 'end_session') {
						//show error popup
						$j('#session_error_popup_profile').fadeIn();
						
						return;
					}
					$j(el).parent().find('a.ac_'+data.responseText).html(data.responseText);
					$j('.lite-loader').remove();
					
					if(data.responseText == 'Deactivate'){
						$j(el).parent().find('a.ac_'+data.responseText).parent().prev().html('Active');
					}else if(data.responseText == 'Activate'){
						$j(el).parent().find('a.ac_'+data.responseText).parent().prev().html('Disabled');
					}
					
					$j(el).parent().find('a.ac_'+data.responseText).show();
				}
		});
}

/**
 * Open Order In Profile Page 
*/
function openOrder(action,id){
	$j.ajax({
		url: action,
		type: 'POST',
		data: {
			id: id
		},
		
		beforeSend: function(){
			$j('#reman_users_orders').hide();
			$j('.reman_preloader_order').show();
		},
				
		complete: function(data){
			$j('#reman_users_order_details').html(data.responseText);
			$j('.reman_preloader_order').hide();
			$j('#reman_users_order_details').show();
		}
	});
}


/**
 * Quote On Order Submit Query 
*/
function submitOrder(){
	/** Collect Form Data */
	var formData = $j("#form-order").serialize();
	
	$j.ajax({
		url: "index/ordersubmit",
		type: 'POST',
		data: {
			data: formData
		},
		
		beforeSend: function(){
			$j('#reman_order').hide();
			$j('#preloader-order-page').show();
		},
		
		complete: function(data){
			if(data.responseText == 'Failed'){
				$j('#order-message-text').html(data.responseText);
				$j('#order-error-back').show();
			}else{
				$j('#success-wrapper').html(data.responseText); 
			}
			$j('#preloader-order-page').hide();
			$j('#order-message').show();
		}
	});

}
/**
 * Back To Order page 
*/
function orderBack(){
	$j('#order-message').hide();
	$j('#order-error-back').hide();
	$j('#reman_order').show();
}

/** User Profile Order List Filter */

	function filterStatus(value){
		/* Reset PO Search field*/
		$j('#po-input-search').attr('value','');
		$j('#no-match-results').hide();
		var success = false;
		
		$j('#grid tr').each(function(){
			if($j(this).attr('data-status'))
				if( $j(this).attr('data-status') != value && value != '...'  )
				{
					$j(this).hide();
				}else{
					$j(this).show();
					
					success = true;
					
				}
		});
		
		if(!success){
			$j('#no-match-results').show();
		}
	}
	/** Filter PO # Value in Profile page */
	function serchPO(value){
		
		var success = false;
		
		$j('#grid tr').each(function(){
			if($j(this).attr('data-po'))
				if( $j(this).attr('data-po') != value )
				{
					$j(this).hide();
				}else{
					$j(this).show();
					
					success = true;
				}
		});
		
		if(!success){
			$j('#no-match-results').show();
		}
	
	}
	/** Reset order list filters*/
	function resetFilters(){
		$j('#no-match-results').hide();
		$j('#grid tr').each(function(){
			 /* Reset PO Search field*/
			 $j('#po-input-search').attr('value','');
			 /* Reset Status FIlter*/
			 $j($j('#order-status-select option')[0]).attr('selected','selected')
			 $j(this).show();
				
		});
	}
	
/*** Check Order Page View Change ***/

function checkOrder(){
	/* Validate Form*/
	dataForm.validator.validate();
	
	/* If For Validated False return false*/
	if(!dataForm.validator.validate() || $j("#zip-re-estimation")[0].value.length < 5) {
		/* Validate ZIP CODE input length*/
		if($j("#zip-re-estimation")[0].value.length < 5){
			$j("#zip-re-estimation").addClass('validation-failed');
		}
		return false;
	}
			
	$j('#order-title').hide();
	$j('#check-order-back').show();
	
	
	$j('#check-order-baloon').show();
	
	/** Vehicle info */
	$j('#com-app-block').css('margin-left','35px');
	$j('#check-vin').html($j('#input-vin > input').attr('value'));
	$j('#input-vin').hide();
	
	$j('#check-mileage').html($j('#input-mileage > input').attr('value'));
	$j('#input-mileage').hide();
	
	
	$j('#com-app-input').hide();
	$j('#check-application-block').show();
	
	/** Order Details */

	$j('#check-po').html($j('#input-po > input').attr('value'));
	$j('#input-po').hide();
	
	if($j('#input-claim > input').attr('value') != ''){
		$j('#check-claim').html($j('#input-claim > input').attr('value'));
	}else{
		$j('#check-claim').html('N/A');
	}
	
	$j('#input-claim').hide();
	
	if($j('#input-ro > input').attr('value') != ''){
		$j('#check-ro').html($j('#input-ro > input').attr('value'));
	}else{
		$j('#check-ro').html('N/A');
	}
	
	
	$j('#input-ro').hide();
	
	if($j('#input-end_username > input').attr('value') != ''){
		$j('#check-end_username').html($j('#input-end_username > input').attr('value'));
	}else{
		$j('#check-end_username').html('N/A');
	}
	
	$j('#input-end_username').hide();
	
	$j('.check-price-order').hide();
	$j('#family_order').hide();
	$j('#check-family').show();
	$j('.check-tr').css('height','20px');

	/** Ship To*/

	$j('#check-same-as-sold-cont').hide();
	$j('#check-st_cust_name').html($j('#input-st_cust_name > input').attr('value'));
	$j('#input-st_cust_name').hide();
	
	$j('#check-st_cont_name').html($j('#input-st_cont_name > input').attr('value'));
	$j('#input-st_cont_name').hide();
	
	$j('#check-st_phone').html($j('#input-st_phone > input').attr('value'));
	$j('#input-st_phone').hide();
	
	$j('#check-st_addr1').html($j('#input-st_addr1 > input').attr('value'));
	$j('#input-st_addr1').hide();


    $j('#check-st_addr2').html($j('#input-st_addr2 > input').attr('value'));
    $j('#input-st_addr2').hide();


	$j('#check-st_city').html($j('#input-st_city > input').attr('value'));
	$j('#input-st_city').hide();
	
	$j('#check-st_state').html($j('#ship-to-states option:selected').html());
	$j('#input-st_state').hide();
	
	$j('#check-st_zip').html($j('#input-st_zip > input').attr('value'));
	$j('#input-st_zip').hide();
	
	$j('.check-bottom-blocks').css('height','180px');
	
	
	$j('#input-fluid').hide();
	$j('#check-order-fluid').show();
	
	
	$j('#check-warranty').html($j('#warrenty_select option:selected').html());
	
	$j('#check-order-notes').html($j('#order-notes textarea').attr('value'));
	
	$j('#order-notes-input').attr('value',$j('#order-notes textarea').attr('value'));
	
	$j('#check-notes').show();

}
	
/** Back to Order Edit Page */	
function backToOrderEdit(){
	$j('#order-is-checked').attr('checked',false)
	$j('#check-order-back').hide();
	$j('#order-title').show();
	$j('#check-order-baloon').hide();
	/** Vehicle info */
	$j('#com-app-block').css('margin-left','88px');
	$j('#check-vin').html('');
	$j('#input-vin').show();
	$j('#check-mileage').html('');
	$j('#input-mileage').show();
	$j('#com-app-input').show();
	$j('#check-application-block').hide();
	
	/** Order Details */
	$j('#check-po').html('');
	$j('#input-po').show();
	$j('#check-claim').html('');
	$j('#input-claim').show();
	$j('#check-ro').html('');
	$j('#input-ro').show();
	$j('#check-end_username').html('');
	$j('#input-end_username').show();
	$j('.check-price-order').show();
	$j('#family_order').show();
	$j('#check-family').hide();
	$j('.check-tr').css('height','');
	$j('.check-top-blocks').css('height','');

	/** Ship To*/
	$j('#check-same-as-sold-cont').show();
	$j('#check-st_cust_name').html('');
	$j('#input-st_cust_name').show();
	$j('#check-st_cont_name').html('');
	$j('#input-st_cont_name').show();
	$j('#check-st_phone').html('');
	$j('#input-st_phone').show();
	$j('#check-st_addr1').html('');
	$j('#input-st_addr1').show();
    $j('#check-st_addr2').html('');
    $j('#input-st_addr2').show();
	$j('#check-st_city').html('');
	$j('#input-st_city').show();
	$j('#check-st_state').html('');
	$j('#input-st_state').show();
	$j('#check-st_zip').html('');
	$j('#input-st_zip').show();
	$j('.check-bottom-blocks').css('height','');
	$j('#check-warranty').html('');
	$j('#check-order-notes').html('');
	$j('#check-notes').hide();
	$j('#submit-order-button').hide();
	
	
	$j('#check-order-fluid').hide();
	$j('#input-fluid').show();

}
		
