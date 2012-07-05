/**
 * Overriden by Awesemo
 * Override and add our call to setDefaultOptionValues
 */
Product.Config.prototype.configureForValues = function () {
    if (this.values) {
        this.settings.each(function(element){
            var attributeId = element.attributeId;
            element.value = (typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId];
            this.configureElement(element);
        }.bind(this));
    }
    // convert option to label if single value
    this.setDefaultOptionValues();
};

/**
 * Added by Awesemo
 * check attributes with single option value and convert to label.
 */
Product.Config.prototype.setDefaultOptionValues = function () {
    this.settings.each(function(element){
        var attributeId = element.attributeId;
        var options = this.getAttributeOptions(attributeId);
        if (options.length == 1) {
            // set first valid value as selected
            element.selectedIndex = 1;
            // convert option to label
            this.convertToLabel(element);
        }
        this.configureElement(element);
    }.bind(this));
}

/**
 * Added by Awesemo
 * convert attribute option to label
 */
Product.Config.prototype.convertToLabel =  function(elem) {
	var labelElem = null;
	var labelId   = 'label_' + this.attributePrefix + '_' + elem.id;
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
	$(elem).hide();
}
