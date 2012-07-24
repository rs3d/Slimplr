(function( $ ) {

	var getComputedStyle = document.defaultView && document.defaultView.getComputedStyle,
		// The following variables are used to convert camelcased attribute names
		// into dashed names, e.g. borderWidth to border-width
		rupper = /([A-Z])/g,
		rdashAlpha = /-([a-z])/ig,
		fcamelCase = function( all, letter ) {
			return letter.toUpperCase();
		},
		// Returns the computed style for an elementn
		getStyle = function( elem ) {
			if ( getComputedStyle ) {
				return getComputedStyle(elem, null);
			}
			else if ( elem.currentStyle ) {
				return elem.currentStyle;
			}
		},
		// Checks for float px and numeric values
		rfloat = /float/i,
		rnumpx = /^-?\d+(?:px)?$/i,
		rnum = /^-?\d/;

	// Returns a list of styles for a given element
	$.styles = function( el, styles ) {
		if (!el ) {
			return null;
		}
		var  currentS = getStyle(el),
			oldName, val, style = el.style,
			results = {},
			i = 0,
			left, rsLeft, camelCase, name;

		// Go through each style
		for (; i < styles.length; i++ ) {
			name = styles[i];
			oldName = name.replace(rdashAlpha, fcamelCase);

			if ( rfloat.test(name) ) {
				name = jQuery.support.cssFloat ? "float" : "styleFloat";
				oldName = "cssFloat";
			}

			// If we have getComputedStyle available
			if ( getComputedStyle ) {
				// convert camelcased property names to dashed name
				name = name.replace(rupper, "-$1").toLowerCase();
				// use getPropertyValue of the current style object
				val = currentS.getPropertyValue(name);
				// default opacity is 1
				if ( name === "opacity" && val === "" ) {
					val = "1";
				}
				results[oldName] = val;
			} else {
				// Without getComputedStyles
				camelCase = name.replace(rdashAlpha, fcamelCase);
				results[oldName] = currentS[name] || currentS[camelCase];

				// convert to px
				if (!rnumpx.test(results[oldName]) && rnum.test(results[oldName]) ) {
					// Remember the original values
					left = style.left;
					rsLeft = el.runtimeStyle.left;

					// Put in the new values to get a computed value out
					el.runtimeStyle.left = el.currentStyle.left;
					style.left = camelCase === "fontSize" ? "1em" : (results[oldName] || 0);
					results[oldName] = style.pixelLeft + "px";

					// Revert the changed values
					style.left = left;
					el.runtimeStyle.left = rsLeft;
				}

			}
		}

		return results;
	};

	/**
	 * @function jQuery.fn.styles
	 * @parent jQuery.styles
	 * @plugin jQuery.styles
	 *
	 * Returns a set of computed styles. Pass the names of the styles you want to
	 * retrieve as arguments:
	 *
	 *      $("div").styles('float','display')
	 *      // -> { cssFloat: "left", display: "block" }
	 *
	 * @param {String} style pass the names of the styles to retrieve as the argument list
	 * @return {Object} an object of `style` : `value` pairs
	 */
	$.fn.styles = function() {
		// Pass the arguments as an array to $.styles
		return $.styles(this[0], $.makeArray(arguments));
	};
})(jQuery);
(function ($) {

	// Overwrites `jQuery.fn.animate` to use CSS 3 animations if possible

	var
		// The global animation counter
		animationNum = 0,
		// The stylesheet for our animations
		styleSheet = null,
		// The animation cache
		cache = [],
		// Stores the browser properties like transition end event name and prefix
		browser = null,
		// Store the original $.fn.animate
		oldanimate = $.fn.animate,

		// Return the stylesheet, create it if it doesn't exists
		getStyleSheet = function () {
			if(!styleSheet) {
				var style = document.createElement('style');
				style.setAttribute("type", "text/css");
				style.setAttribute("media", "screen");

				document.getElementsByTagName('head')[0].appendChild(style);
				if (!window.createPopup) { /* For Safari */
					style.appendChild(document.createTextNode(''));
				}

				styleSheet = style.sheet;
			}

			return styleSheet;
		},

		//removes an animation rule from a sheet
		removeAnimation = function (sheet, name) {
			for (var j = sheet.cssRules.length - 1; j >= 0; j--) {
				var rule = sheet.cssRules[j];
				// 7 means the keyframe rule
				if (rule.type === 7 && rule.name == name) {
					sheet.deleteRule(j)
					return;
				}
			}
		},

		// Returns whether the animation should be passed to the original $.fn.animate.
		passThrough = function (props, ops) {
			var nonElement = !(this[0] && this[0].nodeType),
				isInline = !nonElement && $(this).css("display") === "inline" && $(this).css("float") === "none";

			for (var name in props) {
				// jQuery does something with these values
				if (props[name] == 'show' || props[name] == 'hide' || props[name] == 'toggle'
					// Arrays for individual easing
					|| $.isArray(props[name])
					// Negative values not handled the same
					|| props[name] < 0
					// unit-less value
					|| name == 'zIndex' || name == 'z-index'
					) {
					return true;
				}
			}

			return props.jquery === true || getBrowser() === null ||
				// Animating empty properties
				$.isEmptyObject(props) ||
				// We can't do custom easing
				ops.length == 4 || typeof ops[2] == 'string' ||
				// Second parameter is an object - we can only handle primitives
				$.isPlainObject(ops) ||
				// Inline and non elements
				isInline || nonElement;
		},

		// Gets a CSS number (with px added as the default unit if the value is a number)
		cssValue = function(origName, value) {
			if (typeof value === "number" && !$.cssNumber[ origName ]) {
				return value += "px";
			}
			return value;
		},

		// Feature detection borrowed by http://modernizr.com/
		getBrowser = function(){
			if(!browser) {
				var t,
					el = document.createElement('fakeelement'),
					transitions = {
						'transition': {
							transitionEnd : 'transitionEnd',
							prefix : ''
						},
//						'OTransition': {
//							transitionEnd : 'oTransitionEnd',
//							prefix : '-o-'
//						},
//						'MSTransition': {
//							transitionEnd : 'msTransitionEnd',
//							prefix : '-ms-'
//						},
						'MozTransition': {
							transitionEnd : 'animationend',
							prefix : '-moz-'
						},
						'WebkitTransition': {
							transitionEnd : 'webkitAnimationEnd',
							prefix : '-webkit-'
						}
					}

				for(t in transitions){
					if( el.style[t] !== undefined ){
						browser = transitions[t];
					}
				}
			}
			return browser;
		},

		// Properties that Firefox can't animate if set to 'auto':
		// https://bugzilla.mozilla.org/show_bug.cgi?id=571344
		// Provides a converter that returns the actual value
		ffProps = {
			top : function(el) {
				return el.position().top;
			},
			left : function(el) {
				return el.position().left;
			},
			width : function(el) {
				return el.width();
			},
			height : function(el) {
				return el.height();
			},
			fontSize : function(el) {
				return '1em';
			}
		},

		// Add browser specific prefix
		addPrefix = function(properties) {
			var result = {};
			$.each(properties, function(name, value) {
				result[getBrowser().prefix + name] = value;
			});
			return result;
		},

		// Returns the animation name for a given style. It either uses a cached
		// version or adds it to the stylesheet, removing the oldest style if the
		// cache has reached a certain size.
		getAnimation = function(style) {
			var sheet, name, last;

			// Look up the cached style, set it to that name and reset age if found
			// increment the age for any other animation
			$.each(cache, function(i, animation) {
				if(style === animation.style) {
					name = animation.name;
					animation.age = 0;
				} else {
					animation.age += 1;
				}
			});

			if(!name) { // Add a new style
				sheet = getStyleSheet();
				name = "jquerypp_animation_" + (animationNum++);
				// get the last sheet and insert this rule into it
				sheet.insertRule("@" + getBrowser().prefix + "keyframes " + name + ' ' + style,
					(sheet.cssRules && sheet.cssRules.length) || 0);
				cache.push({
					name : name,
					style : style,
					age : 0
				});

				// Sort the cache by age
				cache.sort(function(first, second) {
					return first.age - second.age;
				});

				// Remove the last (oldest) item from the cache if it has more than 20 items
				if(cache.length > 20) {
					last = cache.pop();
					removeAnimation(sheet, last.name);
				}
			}

			return name;
		};

	/**
	 * @function $.fn.animate
	 * @parent $.animate
	 *
	 * Animate CSS properties using native CSS animations, if possible.
	 * Uses the original [$.fn.animate()](http://api.$.com/animate/) otherwise.
	 *
	 * @param {Object} props The CSS properties to animate
	 * @param {Integer|String|Object} [speed=400] The animation duration in ms.
	 * Will use $.fn.animate if a string or object is passed
	 * @param {Function} [callback] A callback to execute once the animation is complete
	 * @return {jQuery} The jQuery element
	 */
	$.fn.animate = function (props, speed, easing, callback) {
		//default to normal animations if browser doesn't support them
		if (passThrough.apply(this, arguments)) {
			return oldanimate.apply(this, arguments);
		}

		var optall = jQuery.speed(speed, easing, callback);

		// Add everything to the animation queue
		this.queue(optall.queue, function(done) {
			var
				//current CSS values
				current,
				// The list of properties passed
				properties = [],
				to = "",
				prop,
				self = $(this),
				duration = optall.duration,
				//the animation keyframe name
				animationName,
				// The key used to store the animation hook
				dataKey,
				//the text for the keyframe
				style = "{ from {",
				// The animation end event handler.
				// Will be called both on animation end and after calling .stop()
				animationEnd = function (currentCSS, exec) {
					self.css(currentCSS);
					
					self.css(addPrefix({
						"animation-duration" : "",
						"animation-name" : "",
						"animation-fill-mode" : "",
						"animation-play-state" : ""
					}));

					// Call the original callback
					if (optall.old && exec) {
						// Call success, pass the DOM element as the this reference
						optall.old.call(self[0], true)
					}

					$.removeData(self, dataKey, true);
				}

			for(prop in props) {
				properties.push(prop);
			}

			if(getBrowser().prefix === '-moz-') {
				// Normalize 'auto' properties in FF
				$.each(properties, function(i, prop) {
					var converter = ffProps[$.camelCase(prop)];
					if(converter && self.css(prop) == 'auto') {
						self.css(prop, converter(self));
					}
				});
			}

			// Use $.styles
			current = self.styles.apply(self, properties);
			$.each(properties, function(i, cur) {
				// Convert a camelcased property name
				var name = cur.replace(/([A-Z]|^ms)/g, "-$1" ).toLowerCase();
				style += name + " : " + cssValue(cur, current[cur]) + "; ";
				to += name + " : " + cssValue(cur, props[cur]) + "; ";
			});

			style += "} to {" + to + " }}";

			animationName = getAnimation(style);
			dataKey = animationName + '.run';

			// Add a hook which will be called when the animation stops
			$._data(this, dataKey, {
				stop : function(gotoEnd) {
					// Pause the animation
					self.css(addPrefix({
						'animation-play-state' : 'paused'
					}));
					// Unbind the animation end handler
					self.off(getBrowser().transitionEnd, animationEnd);
					if(!gotoEnd) {
						// We were told not to finish the animation
						// Call animationEnd but set the CSS to the current computed style
						animationEnd(self.styles.apply(self, properties), false);
					} else {
						// Finish animaion
						animationEnd(props, true);
					}
				}
			});

			// set this element to point to that animation
			self.css(addPrefix({
				"animation-duration" : duration + "ms",
				"animation-name" : animationName,
				"animation-fill-mode": "forwards"
			}));

			// Attach the transition end event handler to run only once
			self.one(getBrowser().transitionEnd, function() {
				// Call animationEnd using the passed properties
				animationEnd(props, true);
				done();
			});

		});

		return this;
	};
})(jQuery);
(function($) {

var
	//margin is inside border
	weird = /button|select/i,
	getBoxes = {},
    checks = {
        width: ["Left", "Right"],
        height: ['Top', 'Bottom'],
        oldOuterHeight: $.fn.outerHeight,
        oldOuterWidth: $.fn.outerWidth,
        oldInnerWidth: $.fn.innerWidth,
        oldInnerHeight: $.fn.innerHeight
    };

$.each({ 

/**
 * @function jQuery.fn.outerWidth
 * @parent jQuery.dimensions
 *
 * `jQuery.fn.outerWidth([value], [includeMargins])` lets you set
 * the outer width of an object where:
 *
 *      outerWidth = width + padding + border + (margin)
 *
 * And can be used like:
 *
 *      $("#foo").outerWidth(100); //sets outer width
 *      $("#foo").outerWidth(100, true); // uses margins
 *      $("#foo").outerWidth(); //returns outer width
 *      $("#foo").outerWidth(true); //returns outer width + margins
 *
 * When setting the outerWidth, it adjusts the width of the element.
 * If *includeMargin* is set to `true` margins will also be included.
 * It is also possible to animate the outer width:
 * 
 *      $('#foo').animate({ outerWidth: 200 });
 *
 * @param {Number} [width] The width to set
 * @param {Boolean} [includeMargin=false] Makes setting the outerWidth adjust
 * for margins.
 * @return {jQuery|Number} Returns the outer width or the jQuery wrapped elements
 * if you are setting the outer width.
 */
width: 
/**
 * @function jQuery.fn.innerWidth
 * @parent jQuery.dimensions
 *
 * `jQuery.fn.innerWidth([value])` lets you set the inner width of an element where
 * 
 *      innerWidth = width + padding
 *      
 * Use it like:
 *
 *      $("#foo").innerWidth(100); //sets inner width
 *      $("#foo").outerWidth(); // returns inner width
 *      
 * Or in an animation like:
 * 
 *      $('#foo').animate({ innerWidth : 200 });
 *
 * Setting inner width adjusts the width of the element.
 *
 * @param {Number} [width] The inner width to set
 * @return {jQuery|Number} Returns the inner width or the jQuery wrapped elements
 * if you are setting the inner width.
 */
"Width", 
/**
 * @function jQuery.fn.outerHeight
 * @parent jQuery.dimensions
 *
 * `jQuery.fn.outerHeight([value], [includeMargins])` lets
 * you set the outer height of an object where:
 *
 *      outerHeight = height + padding + border + (margin)
 *
 * And can be used like:
 *
 *      $("#foo").outerHeight(100); //sets outer height
 *      $("#foo").outerHeight(100, true); // uses margins
 *      $("#foo").outerHeight(); //returns outer height
 *      $("#foo").outerHeight(true); //returns outer height + margins
 *
 * When setting the outerHeight, it adjusts the height of the element.
 * If *includeMargin* is set to `true` margins will also be included.
 * It is also possible to animate the outer heihgt:
 *
 *      $('#foo').animate({ outerHeight : 200 });
 *
 * @param {Number} [height] The height to set
 * @param {Boolean} [includeMargin=false] Makes setting the outerHeight adjust
 * for margins.
 * @return {jQuery|Number} Returns the outer height or the jQuery wrapped elements
 * if you are setting the outer height.
 */
height: 
/**
 * @function jQuery.fn.innerHeight
 * @parent jQuery.dimensions
 *
 * `jQuery.fn.innerHeight([value])` lets you set the inner height of an element where
 *
 *      innerHeight = height + padding
 *
 * Use it like:
 *
 *      $("#foo").innerHeight(100); //sets inner height
 *      $("#foo").outerHeight(); // returns inner height
 *
 * Or in an animation like:
 *
 *      $('#foo').animate({ innerHeight : 200 });
 *
 * Setting inner height adjusts the height of the element.
 *
 * @param {Number} [height] The inner height to set
 * @return {jQuery|Number} Returns the inner height or the jQuery wrapped elements
 * if you are setting the inner height.
 */
// for each 'height' and 'width'
"Height" }, function(lower, Upper) {

    //used to get the padding and border for an element in a given direction
    getBoxes[lower] = function(el, boxes) {
        var val = 0;
        if (!weird.test(el.nodeName)) {
            //make what to check for ....
            var myChecks = [];
            $.each(checks[lower], function() {
                var direction = this;
                $.each(boxes, function(name, val) {
                    if (val)
                        myChecks.push(name + direction+ (name == 'border' ? "Width" : "") );
                })
            })
            $.each($.styles(el, myChecks), function(name, value) {
                val += (parseFloat(value) || 0);
            })
        }
        return val;
    }

    //getter / setter
    $.fn["outer" + Upper] = function(v, margin) {
        var first = this[0];
		if (typeof v == 'number') {
			// Setting the value
            first && this[lower](v - getBoxes[lower](first, {padding: true, border: true, margin: margin}))
            return this;
        } else {
			// Return the old value
            return first ? checks["oldOuter" + Upper].call(this, v) : null;
        }
    }
    $.fn["inner" + Upper] = function(v) {
        var first = this[0];
		if (typeof v == 'number') {
			// Setting the value
            first&& this[lower](v - getBoxes[lower](first, { padding: true }))
            return this;
        } else {
			// Return the old value
            return first ? checks["oldInner" + Upper].call(this, v) : null;
        }
    }
    //provides animations
	var animate = function(boxes){
		// Return the animation function
		return function(fx){
			if (fx.state == 0) {
	            fx.start = $(fx.elem)[lower]();
	            fx.end = fx.end - getBoxes[lower](fx.elem,boxes);
	        }
	        fx.elem.style[lower] = (fx.pos * (fx.end - fx.start) + fx.start) + "px"
		}
	}
    $.fx.step["outer" + Upper] = animate({padding: true, border: true})
	$.fx.step["outer" + Upper+"Margin"] =  animate({padding: true, border: true, margin: true})
	$.fx.step["inner" + Upper] = animate({padding: true})

})

})(jQuery)