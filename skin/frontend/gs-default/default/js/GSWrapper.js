/**
 * @author: Awesemo
 * @email : xdreucker@gmail.com
 * Copyrights 2012
 */
if (!window.GSWrapper) {
    window.GSWrapper = {};
}

GSWrapper.HeaderCart = {
    initialize: function (container) {
        this.container = $(container);
        this.element = this.container.up(0);
        this.elementHeader = this.container.previous(0);

        this.intervalDuration = 4000;
        this.interval = null;
        this.onElementMouseOut = this.handleMouseOut.bindAsEventListener(this);
        this.onElementMouseOver = this.handleMouseOver.bindAsEventListener(this);
        this.onElementMouseClick = this.handleMouseClick.bindAsEventListener(this);

        this.element.observe('mouseout', this.onElementMouseOut);
        this.element.observe('mouseover', this.onElementMouseOver);
        this.elementHeader.observe('click', this.onElementMouseClick);

    },
	handleMouseOut: function (evt) {
        if($(this.elementHeader).hasClassName('show')) {
            this.interval = setTimeout(this.hideCart.bind(this), this.intervalDuration);
        }
    },
    handleMouseOver: function (evt) {
        if (this.interval !== null) {
             clearTimeout(this.interval);
             this.interval = null;
        }
    },
	handleMouseClick: function (evt) {
        if (!$(this.elementHeader).hasClassName('show') && !$(this.container.id).hasClassName('process') )  {
            this.showCart();
        }
        else {
            this.hideCart();
        }
    },
	showCart: function (timePeriod) {
        new Effect.SlideDown(this.container.id, { duration: 0.5,
            beforeStart: function(effect) {$( effect.element.id ).addClassName('process');},
            afterFinish: function(effect) {$( effect.element.id ).removeClassName('process'); }
            });
        $(this.elementHeader).addClassName('show');
        if(timePeriod) {
            this.timePeriod = timePeriod*1000;
            this.interval = setTimeout(this.hideCart.bind(this), this.timePeriod);
        }
    },
    hideCart: function () {
        if (!$(this.container.id).hasClassName('process') && $(this.elementHeader).hasClassName('show')) {
            new Effect.SlideUp(this.container.id, { duration: 0.5,
                beforeStart: function(effect) {$( effect.element.id ).addClassName('process');},
                afterFinish: function(effect) {
                    $( effect.element.id ).removeClassName('process');
                    }
                });
        }
        if (this.interval !== null) {
            clearTimeout(this.interval);
            this.interval = null;
        }
        $(this.elementHeader).removeClassName('show');
    }
}
