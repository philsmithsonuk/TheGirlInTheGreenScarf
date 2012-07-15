/**
 * Overriden by Awesemo
 * Override and add our call to setDefaultOptionValues
 */
Product.Config.prototype.configureForValues = function () {
    if (this.values) {
        this.settings.each(function(element){
            var attributeId = element.attributeId;
            element.value = (typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId];
            var options = this.getElementAttributeOptions(element);
            if (options.length == 1) {
                // set first valid value as selected
                element.selectedIndex = 1;
                // convert option to label
                this.convertToLabel(element);
            }
            this.configureElement(element);
        }.bind(this));
    } else {
        this.settings.each(function(element){
            var attributeId = element.attributeId;
            var options = this.getElementAttributeOptions(element);
            if (options.length == 1) {
                // set first valid value as selected
                element.selectedIndex = 1;
                // convert option to label
                this.convertToLabel(element);
            }
            this.configureElement(element);
        }.bind(this));
    }
    // convert option to label if single value
};

/**
 * Overriden by Awesemo
 * Override SCP configureElement, add single option to label feature
 */
Product.Config.prototype.configureElement = function(element) {
    this.reloadOptionLabels(element);
    if(element.value){
        this.state[element.config.id] = element.value;
        if(element.nextSetting){
            element.nextSetting.disabled = false;
            this.fillSelect(element.nextSetting);
            this.reloadOptionLabels(element.nextSetting);
            
            var optionsAllowed = this.getElementAttributeOptions(element.nextSetting);

            if ((optionsAllowed.length) == 1) {
                // set first valid value as selected
                element.nextSetting.selectedIndex = 1;
                // convert option to label
                this.convertToLabel(element.nextSetting);
            } else {
                this.resetElementState(element.nextSetting);
            }
            this.resetChildren(element.nextSetting);
            this.configureElement(element.nextSetting);
            if (!element.nextSetting.nextSetting) {
                this.reloadPrice();
            }
        } else {
            this.reloadPrice();
        }
    }
    else {
        this.resetChildren(element);
    }
    //if (this.settings[this.settings.length-1].attributeId == element.
    //this.reloadPrice();
};

/**
 * Added by Awesemo
 * convert attribute option to label
 */
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

/**
 * Added by Awesemo
 * get actual options for a given attribute
 */
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
}

/**
 * Overriden by Awesemo
 * Override resetChildren, add call to ::resetElementState
 */
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

/**
 * Added by Awesemo
 * hide label and show corresponding option element
 */
Product.Config.prototype.resetElementState = function(element){
    var labelId   = 'label_option_' + element.id;
    if ($(labelId) !== null) {
        $(labelId).hide();
    }
    $(element).setStyle({"visibility":"visible"});
    //element.show();
};

Product.Config.prototype.hasAllAttributeSet = function() {
    var hasAllSet = true;
    this.settings.each(function(element){
        //console.log(element.attributeId + ":" + element.value + ":" + $('attribute'+element.attributeId).selectedIndex);
        if (!element.value) {
            hasAllSet = false;
        }
    }.bind(this));
    return hasAllSet;
}
