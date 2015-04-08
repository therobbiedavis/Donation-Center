/*
 * jQuery UI Progressbar 1.8.2
 *
 * Copyright (c) 2010 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Progressbar
 *
 * Depends:
 *   jquery.ui.core.js
 *   jquery.ui.widget.js
 */
(function( $ ) {

$.widget( "ui.progressbar", {
	options: {
		value: 0,
		orientation : "horizontal"
	},
	_create: function() {
		this.element
			.addClass( "ui-progressbar ui-widget ui-widget-content ui-corner-all" )
			.addClass("ui-progressbar-" + this.options.orientation)
			.attr({
				role: "progressbar",
				"aria-valuemin": this._valueMin(),
				"aria-valuemax": this._valueMax(),
				"aria-valuenow": this._value()
			});
		var orientationCornerClass = this.options.orientation == "horizontal"? "ui-corner-left" : "ui-corner-top"
		this.valueDiv = $( "<div class='ui-progressbar-value ui-widget-header " + orientationCornerClass +"'></div>" )
			.appendTo( this.element );

		this._refreshValue();
	},

	destroy: function() {
		this.element
			.removeClass( "ui-progressbar ui-widget ui-widget-content ui-corner-all" )
			.removeClass("ui-progressbar-" + this.options.orientation)
			.removeAttr( "role" )
			.removeAttr( "aria-valuemin" )
			.removeAttr( "aria-valuemax" )
			.removeAttr( "aria-valuenow" );

		this.valueDiv.remove();

		$.Widget.prototype.destroy.apply( this, arguments );
	},

	value: function( newValue ) {
		if ( newValue === undefined ) {
			return this._value();
		}

		this._setOption( "value", newValue );
		return this;
	},

	_setOption: function( key, value ) {
		switch ( key ) {
			case "value":
				this.options.value = value;
				this._refreshValue();
				this._trigger( "change" );
				break;
		}

		$.Widget.prototype._setOption.apply( this, arguments );
	},

	_value: function() {
		var val = this.options.value;
		// normalize invalid value
		if ( typeof val !== "number" ) {
			val = 0;
		}
		if ( val < this._valueMin() ) {
			val = this._valueMin();
		}
		if ( val > this._valueMax() ) {
			val = this._valueMax();
		}

		return val;
	},

	_valueMin: function() {
		return 0;
	},

	_valueMax: function() {
		return 100;
	},

	_refreshValue: function() {
		var value = this.value();
		if(this.options.orientation == "horizontal"){
			this.valueDiv[value == this._valueMax() ? "addClass"
					: "removeClass"]("ui-corner-right");
			this.valueDiv.width(value + "%");
		}
		else{
			this.valueDiv[value == this._valueMax() ? "addClass"
					: "removeClass"]("ui-corner-bottom");
			this.valueDiv.height(value + "%");
		}
		this.element.attr( "aria-valuenow", value );
	}
});

$.extend( $.ui.progressbar, {
	version: "1.8.2"
});

})( jQuery );
