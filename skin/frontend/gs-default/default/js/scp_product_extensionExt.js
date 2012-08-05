Product.Config.prototype.showFullImageDiv = function(productId, parentId) {
    var imgUrl = this.config.ajaxBaseUrl + "image/?id=" + productId + '&pid=' + parentId;
    var prodForm = $('product_addtocart_form');
    var destElement = false;
    var defaultZoomer = this.config.imageZoomer;

    prodForm.select('div.product-img-box').each(function(el) {
        destElement = el;
    });

    if(productId) {
        new Ajax.Updater(destElement, imgUrl, {
            method: 'get',
            evalScripts: false,
            onLoading: function() {
                $$('span.scp-please-wait').each(function(el) {el.show()});
                $('image').hide();
                $('thumblist').hide();
            },
            onComplete: function() {
                $$('span.scp-please-wait').each(function(el) {el.hide()});
                if ($('image') && initProductZoom){
                    // initialize jqZoom on image refresh
                    initProductZoom();
                }
                $('image').show();
                $('thumblist').show();
          }
        });
    } else {
        destElement.innerHTML = defaultZoomer;
        if ($('image') && initProductZoom){
            initProductZoom();
        }
    }
};
/*
Product.Config.prototype.configureElement = function(element) {
    this.reloadOptionLabels(element);
    if(element.value){
        this.state[element.config.id] = element.value;
        // convert single options to label
        if (this.config.convertOptionsToLabel) {
            this.convertToLabel(element);
        }
        
        if(element.nextSetting){
            element.nextSetting.disabled = false;
            this.fillSelect(element.nextSetting);
            this.reloadOptionLabels(element.nextSetting);
            // convert single options to label
            if (this.config.convertOptionsToLabel) {
                var optionsAllowed = this.getElementAttributeOptions(element.nextSetting);
                if ((optionsAllowed.length) == 1) {
                    // set first valid value as selected
                    element.nextSetting.selectedIndex = 1;
                    // convert option to label
                    this.convertToLabel(element.nextSetting);
                } else {
                    this.resetElementState(element.nextSetting);
                }
            }
            
            this.resetChildren(element.nextSetting);
            // convert single options to label
            if (this.config.convertOptionsToLabel) {
                this.configureElement(element.nextSetting);
                if (!element.nextSetting.nextSetting) {
                    this.reloadPrice();
                }
            }
        } else {
            // convert single options to label
            if (this.config.convertOptionsToLabel) {
                this.reloadPrice();
            }
        }
    }
    else {
        this.resetChildren(element);
    }
    this.reloadPrice();
};

Product.Config.prototype.convertToLabel =  function(elem) {
	var labelElem = null;
	var labelId   = 'label_option_' + elem.id;
    var value     = elem.options[1].text;
	if ($(labelId) === null) {
		if (elem.parentNode.tagName.toLowerCase() == 'li') {
			labelElem = new Element('h4', { 'id': labelId}).update(value);
		} else {
			labelElem = new Element('span', { 'id': labelId, 'class': 'default-value'}).update(value);
		}
		Element.insert($(elem), { 'before': labelElem });
	} else {
		$(labelId).update(value);
		$(labelId).show();
	}
	if (elem.nextSetting) {
		elem.nextSetting.disabled = false;
	} else {
		elem.selectedIndex = 0;
		var childProductId = this.getMatchingSimpleProduct();

		if (typeof(ProductDefaults) != "undefined") {
			ProductDefaults.addToDefault(childProductId, this);
		}
	}
    elem.selectedIndex = 1;
	$(elem).setStyle({"visibility":"hidden"});
};

Product.Config.prototype.resetChildren = function(element){
    if(element.childSettings) {
        for(var i=0;i<element.childSettings.length;i++){
            element.childSettings[i].selectedIndex = 0;
            element.childSettings[i].disabled = true;

            this.resetElementState(element.childSettings[i]);

            $(element.childSettings[i]).show();
            if(element.config){
                this.state[element.config.id] = false;
            }
        }
    }
};

Product.Config.prototype.resetElementState = function(element){
    var labelId   = 'label_option_' + element.id;
    if ($(labelId) !== null) {
        $(labelId).hide();
    }
    $(element).setStyle({"visibility":"visible"});
    //element.show();
};

Product.Config.prototype.getElementAttributeOptions = function(element) {
    var attributeId = element.attributeId;
    var options = this.getAttributeOptions(attributeId);
    
    var prevConfig = false;
    if(element.prevSetting){
        prevConfig = element.prevSetting.options[element.prevSetting.selectedIndex];
    }
    
    var optionsAllowed = [];
    
    if(options) {
        var index = 1;
        for(var i=0;i<options.length;i++){
            var allowedProducts = [];
            if(prevConfig && prevConfig.config) {
                for(var j=0;j<options[i].products.length;j++){
                    if(prevConfig.config.allowedProducts
                        && prevConfig.config.allowedProducts.indexOf(options[i].products[j])>-1){
                        allowedProducts.push(options[i].products[j]);
                    }
                }
            } else {
                allowedProducts = options[i].products.clone();
            }

            if(allowedProducts.size()>0){
                optionsAllowed.push(options[i].id);
            }
        }
    }
    return optionsAllowed;
};

Product.Config.prototype.configureForValues = function(){
    this.values = {"92":7,"136":4};
    if (this.values) {
        this.settings.each(function(element){
            var attributeId = element.attributeId;
            element.value = parseInt((typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId]);
            this.configureElement(element);
        }.bind(this));
    }
};

Product.Config.prototype.reloadOptionLabels = function(element){
    var selectedPrice;
    var childProducts = this.config.childProducts;

    //Don't update elements that have a selected option
    if(element.options[element.selectedIndex].config){
        return;
    }

    for(var i=0;i<element.options.length;i++){
        if(element.options[i].config){
            var cheapestPid = this.getProductIdOfCheapestProductInScope("finalPrice", element.options[i].config.allowedProducts);
            var mostExpensivePid = this.getProductIdOfMostExpensiveProductInScope("finalPrice", element.options[i].config.allowedProducts);
            var cheapestFinalPrice = childProducts[cheapestPid]["finalPrice"];
            var mostExpensiveFinalPrice = childProducts[mostExpensivePid]["finalPrice"];
            element.options[i].text = this.getOptionLabel(element.options[i].config, cheapestFinalPrice, mostExpensiveFinalPrice);
        }
    }
};
*/
