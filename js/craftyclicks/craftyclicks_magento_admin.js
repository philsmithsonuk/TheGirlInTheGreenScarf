/*
// This is a collection of JavaScript code to allow easy integration of 
// the Crafty Clicks postcode / address finder functionality into Magento
//
// Provided by www.CraftyClicks.co.uk
//
// Requires standard CraftyClicks JS - tested with v4.9.1 
//
// If you copy/use/modify this code - please keep this
// comment header in place 
//
// Copyright (c) 2009-2012 Crafty Clicks (http://www.craftyclicks.com)
//
// This code relies on prototype js, you must have a reasonably recent version loaded 
// in your template. Magento should include it as standard.
// 
// If you need any help, contact support@craftyclicks.co.uk - we will help!
//
**********************************************************************************/    
function CraftyClicksMagentoClass () {
	this.prefix = ""; 
	this.fields = { "postcode_id"	: "", // required
					"company_id"	: "", // optional 
					"street1_id"	: "", // required	
					"street2_id"	: "", // optional
					"street3_id"	: "", // optional
					"street4_id"	: "", // optional
					"town_id"		: "", // required
					"county_id"		: "", // optional 
					"country_id"	: "" // required
					};

	this.current_setup			= 'initial'; // can be 'uk' or 'non_uk'
	this.uk_postcode_width		= '75px'
	this.old_postcode_width 	= '';
	this.cp_obj					= 0;
	
	this.elem_move = function(e1, e2) {
	    e1.insert({after : e2}); 
	}

	this.rearrange_fields = function() {
		var fields = this.fields;

		if (!$(fields.town_id)) {
			_cp_hide_fields = false;
		} else if ('' != $(fields.town_id).getValue()) {
			_cp_hide_fields = false;
		}
		
		var tr_list = [ $(fields.country_id).up('tr') ];
		var idx = 1;
		tr_list[idx] = $(fields.postcode_id).up('tr'); idx++;  

		var ne = $(fields.company_id);
		if (ne) {
			tr_list[idx] = ne.up('tr'); idx++;
			ne.up('tr').addClassName(this.prefix+'_cp_address_class');
		}

		tr_list[idx] = $(fields.street1_id).up('tr'); idx++;
		$(fields.street1_id).up('tr').addClassName(this.prefix+'_cp_address_class');

		ne = $(fields.street2_id);
		if (ne) {
			tr_list[idx] = ne.up('tr');	idx++;
			ne.up('tr').addClassName(this.prefix+'_cp_address_class');
		}

		ne = $(fields.street3_id);
		if (ne) {
			tr_list[idx] = ne.up('tr');	idx++;
			ne.up('tr').addClassName(this.prefix+'_cp_address_class');
		}

		ne = $(fields.street4_id);
		if (ne) {
			tr_list[idx] = ne.up('tr');	idx++;
			ne.up('tr').addClassName(this.prefix+'_cp_address_class');
		}
		
		tr_list[idx] = $(fields.town_id).up('tr'); idx++;  
		$(fields.town_id).up('tr').addClassName(this.prefix+'_cp_address_class');

		ne = $(fields.county_id);
		if (ne) {
			tr_list[idx] = ne.up('tr');	idx++;
			ne.up('tr').addClassName(this.prefix+'_cp_address_class');
		}

		for (var ii = 0; ii < idx; ii++) {
			this.elem_move(tr_list[ii], tr_list[ii+1]); 
		}

		return (true);
	}

	this.setup_for_uk = function() {
		// check if we need to do anything
		if ('uk' != this.current_setup) {
			// do the magic for UK
			// move postcode to the uk position after the country li
			$(this.fields.country_id).up('tr').insert(  {after: $(this.fields.postcode_id).up('tr')} );
			// add result box
			if (!$(this.prefix+'_cp_result_display')) {
				var tmp_html = '<tr><td class="label"><label>&nbsp;</label></td><td class="value" id="'+this.prefix+'_cp_result_display">&nbsp;</td></tr>';
				$(this.fields.postcode_id).up('tr').insert( {after: tmp_html} );
			}

			// show result box
			$(this.prefix+"_cp_result_display").up('tr').show();
			
			// shrink postcode field if needed
			if ('' != this.uk_postcode_width) {
				this.old_postcode_width = $(this.fields.postcode_id).getStyle("width");
				$(this.fields.postcode_id).setStyle({width: this.uk_postcode_width});
			}
			
			// add button
			if (!$(this.prefix+'_cp_button_id')) {
				var tmp_html = '&nbsp;&nbsp;';
				if ('' != _cp_button_image) {
					tmp_html += '<img style="cursor: pointer;" src="'+_cp_button_image+'" id="'+this.prefix+'_cp_button_id" class="'+_cp_button_class+'" title="'+_cp_button_text+'"/>';
				} else {
					tmp_html += '<button type="button" id="'+this.prefix+'_cp_button_id" class="'+_cp_button_class+'"><span><span>'+_cp_button_text+'</span></span></button>';
				}
				$(this.fields.postcode_id).insert( {after : tmp_html} );
				$(this.prefix+"_cp_button_id").observe('click', this.button_clicked.bindAsEventListener(this));
			}
			
			// show button 
			$(this.prefix+"_cp_button_id").show();
			
		}	
		
		if ('initial' == this.current_setup && _cp_hide_fields) {
			// first time and default to UK, hide address fields
			$$('.'+this.prefix+'_cp_address_class').invoke('hide');
		}
		
		// set state
		this.current_setup = 'uk';
	}	

	this.setup_for_non_uk = function() {
		// check if we need to do anything
		if ('non_uk' != this.current_setup) {
			// hide result box (if it exist already)
			if ($(this.prefix+"_cp_result_display")) {
				this.cp_obj.update_res(null);
				$(this.prefix+"_cp_result_display").up('tr').hide();
			}
			// hide button (if it exist already)
			if ($(this.prefix+"_cp_button_id")) {
				$(this.prefix+"_cp_button_id").hide();
			}
			
			// move postcode to the non-uk position after the town
			$(this.fields.town_id).up('tr').insert(  {after: $(this.fields.postcode_id).up('tr')} );
	
			// show all other addres lines
			$$('.'+this.prefix+'_cp_address_class').invoke('show');
			// set state
			this.current_setup = 'non_uk';
		}	
	}	

	this.add_lookup = function(setup) {
		cp_obj = CraftyPostcodeCreate();
		this.cp_obj = cp_obj;
	 	// config 
	 	this.prefix = setup.prefix;
	 	this.fields = setup.fields;
		cp_obj.set("access_token", _cp_token_adm); 
		cp_obj.set("res_autoselect", "0");
		cp_obj.set("result_elem_id", this.prefix+"_cp_result_display");
		cp_obj.set("form", "");
		cp_obj.set("elem_company"  , this.fields.company_id); // optional
		cp_obj.set("elem_street1"  , this.fields.street1_id);
		cp_obj.set("elem_street2"  , this.fields.street2_id);
		cp_obj.set("elem_street3"  , this.fields.street3_id); 
		cp_obj.set("elem_town"     , this.fields.town_id);
		cp_obj.set("elem_county"   , this.fields.county_id); // optional
		cp_obj.set("elem_postcode" , this.fields.postcode_id);
		cp_obj.set("single_res_autoselect" , 1); // don't show a drop down box if only one matching address is found
		cp_obj.set("max_width" , _cp_result_box_width);
		if (1 < _cp_result_box_height) {
			cp_obj.set("first_res_line", ""); 
			cp_obj.set("max_lines" , _cp_result_box_height);
		} else {
			cp_obj.set("first_res_line", "----- please select your address ----"); 
			cp_obj.set("max_lines" , 1);
		}
		cp_obj.set("busy_img_url" , _cp_busy_img_url);
		cp_obj.set("hide_result" , _cp_clear_result);
		cp_obj.set("traditional_county" , 1);
		cp_obj.set("on_result_ready", this.result_ready.bindAsEventListener(this));
		cp_obj.set("on_result_selected", this.result_selected.bindAsEventListener(this));
		cp_obj.set("on_error", this.result_error.bindAsEventListener(this));
		cp_obj.set("first_res_line", _cp_1st_res_line);
		cp_obj.set("err_msg1", _cp_err_msg1);
		cp_obj.set("err_msg2", _cp_err_msg2);
		cp_obj.set("err_msg3", _cp_err_msg3);
		cp_obj.set("err_msg4", _cp_err_msg4);
		// initial page setup
		if (this.rearrange_fields()) {
			if (_cp_enable_for_uk_only) {
				this.country_changed();
				$(this.fields.country_id).observe('change', this.country_changed.bindAsEventListener(this));
			} else {
				this.setup_for_uk();
			}
		} else {
//			alert ('Postcode Lookup could not be added!');
		}
	}

	this.country_changed = function(e) {
		// show postcode lookup for:
		// "GB" UK
		// "JE" Jersey
		// "GG" Guernsey
		// "IM" Isle of Man
		var curr_country = $(this.fields.country_id).getValue();
		if ('GB' == curr_country || 'JE' == curr_country || 'GG' == curr_country || 'IM' == curr_country) {
			this.setup_for_uk();
		} else {
			this.setup_for_non_uk();
		}
	}

	this.button_clicked = function(e) {
		if ('' != _cp_error_class) $(this.prefix+'_cp_result_display').removeClassName(_cp_error_class);
		this.cp_obj.doLookup();
	}
	
	this.result_ready = function() {
		$$('.'+this.prefix+'_cp_address_class').invoke('show');
	}
		
	this.result_selected = function() {
		if (_cp_clear_result) this.cp_obj.update_res(null);
	}
	
	this.result_error = function() { 
		$$('.'+this.prefix+'_cp_address_class').invoke('show');
		if ('' != _cp_error_class) $(this.prefix+'_cp_result_display').addClassName(_cp_error_class);
	}
}

document.observe("dom:loaded", function() {
	
	if (!_cp_integrate) return;
	
	if ($('address_list')) {
		// looks like we are on the customer edit page, check and attach to all existing address forms (there could be many!)
		var add_list = $$("#address_list > li");
		
		if (add_list && 0 < add_list.length) {
			var max_id = 0;
			for (var ii = 0; ii < add_list.length; ii++) {
				var item = add_list[ii];
				var item_id = '';
				var item_html_id = '';
				if ('address_item_' == item.id.substr(0,13)) {
					item_id = item.id.substr(13);
				} else if ('new_item' == item.id.substr(0,8)) {
					item_id = item.id.substr(8);
				}
				// old magento templates can have "idX", new ones have "_itemX", se we test for this here
				if ($('id'+item_id+'postcode')) {
					item_html_id = 'id'+item_id;
				} if ($('_item'+item_id+'postcode')) {
					item_html_id = '_item'+item_id;
				}
				
				if ('' != item_html_id) {
					var cc_tmp = new CraftyClicksMagentoClass();
					cc_tmp.add_lookup({
					"prefix"				: item_html_id, 
					"fields"				: { "postcode_id" : item_html_id+"postcode", 
												"company_id"  : item_html_id+"company", 
												"street1_id"  : item_html_id+"street0", 
												"street2_id"  : item_html_id+"street1", 
												"street3_id"  : item_html_id+"street2", 
												"street4_id"  : item_html_id+"street3", 
												"town_id"	  : item_html_id+"city",
												"county_id"   : item_html_id+"region", 
												"country_id"  : item_html_id+"country_id" }
					});
				}
				
				if (item_id > max_id) max_id = item_id;
			}
			
			// check for magento bug in address numbering
			if (customerAddresses.itemCount <= max_id) customerAddresses.itemCount = max_id;
		}
		
		// make sure lookup is added when a new adress form is created....
		$('add_address_button').observe('click', function() {
			var item_html_id = '_item'+(customerAddresses.itemCount);
			var cc_tmp = new CraftyClicksMagentoClass();
			cc_tmp.add_lookup({
			"prefix"				: item_html_id, 
			"fields"				: { "postcode_id" : item_html_id+"postcode", 
										"company_id"  : item_html_id+"company", 
										"street1_id"  : item_html_id+"street0", 
										"street2_id"  : item_html_id+"street1", 
										"street3_id"  : item_html_id+"street2", 
										"street4_id"  : item_html_id+"street3", 
										"town_id"	  : item_html_id+"city",
										"county_id"   : item_html_id+"region", 
										"country_id"  : item_html_id+"country_id" }
			});
		});
	} else {
		// probably on the new order page, check and attach
		_cp_check_and_attach();
	}
});

var _cp_last_billing_attachement = null;
var _cp_last_shipping_attachement = null;
function _cp_check_and_attach() {

	if ($('order-billing_address_postcode') && _cp_last_billing_attachement != $('order-billing_address_postcode')) {
		_cp_last_billing_attachement = $('order-billing_address_postcode');
		var item_html_id = 'order-billing_address_';
		var cc_tmp = new CraftyClicksMagentoClass();
		cc_tmp.add_lookup({
		"prefix"				: item_html_id, 
		"fields"				: { "postcode_id" : item_html_id+"postcode", 
									"company_id"  : item_html_id+"company", 
									"street1_id"  : item_html_id+"street0", 
									"street2_id"  : item_html_id+"street1", 
									"street3_id"  : item_html_id+"street2", 
									"street4_id"  : item_html_id+"street3", 
									"town_id"	  : item_html_id+"city",
									"county_id"   : item_html_id+"region", 
									"country_id"  : item_html_id+"country_id" }
		});
	}

	if ($('order-shipping_address_postcode') && !$('order-shipping_address_postcode').disabled && _cp_last_shipping_attachement != $('order-shipping_address_postcode')) {
		_cp_last_shipping_attachement = $('order-shipping_address_postcode');
		var item_html_id = 'order-shipping_address_';
		var cc_tmp = new CraftyClicksMagentoClass();
		cc_tmp.add_lookup({
		"prefix"				: item_html_id, 
		"fields"				: { "postcode_id" : item_html_id+"postcode", 
									"company_id"  : item_html_id+"company", 
									"street1_id"  : item_html_id+"street0", 
									"street2_id"  : item_html_id+"street1", 
									"street3_id"  : item_html_id+"street2", 
									"street4_id"  : item_html_id+"street3", 
									"town_id"	  : item_html_id+"city",
									"county_id"   : item_html_id+"region", 
									"country_id"  : item_html_id+"country_id" }
		});
	}

	window.setTimeout(function() { _cp_check_and_attach(); }, 500);
}

