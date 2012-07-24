// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	var Link = {

		init: function( options, element, plugin) {
			var self = this;

			self.element = element;
			self.plugin = plugin;

			self.$element = $(element);
			self.url = self.$element.attr('href');

			self.options = $.extend( {}, $.fn.ajaxLinks.options, options );


			self.addWrapper();
			

			
			self.$element.bind('click.ClickEvent',function(event) {
				self.click(event,self);
				//self.hello();
			});
			
			
			//self.plugin[0].hello();

			/*
			console.log('self/this',self);
			console.log('element',element);
			console.log('url',self.url);
			console.log('options',self.options);
			*/
			
		},
		addWrapper: function () {
			var self=this;
			var loadingContent = $(self.options.loadingContent).
					addClass(self.options.loadingContentClass).hide();
			var loadingWrapper = $(self.options.loadingWrapper).
					addClass(self.options.pendingClass);

			self.$element.wrap(loadingWrapper).
				before(loadingContent);
			return self;
				
		},
		hello: function () {
			console.log('hello');
			console.log(Link);
			console.log(this.plugin);
		},
		click: function(event, self) {
			event.preventDefault();
			console.log(self.element);
			//self.$element.css('background','red');
			//console.log(self.plugin.elements[0].element);
			
			$(self.plugin.elements).each(function(){
				if (self != this) this.abort();
			});
			self.$element.parent()
				.removeClass(self.options.pendingClass+' '+self.options.errorClass+' '+self.options.readyClass)
				.addClass(self.options.loadingClass)
				.find('.'+self.options.loadingContentClass).show();
			self.load();
			return self;
		},
		ajaxUpdate: function (data,url,push) {
			$.each(data, function(key, val) {
			 	//$(key).html($(val));
			 	$(key).html(val);
			});
			$(document).attr('title',$('<title />').html(data.name).text()); //FOR IE
			if (push) {
				History.pushState(data,data.name,url);	
				//console.log('pushState');
			} 
			$('#result').html(url);
			var mynav = $('#navigation a').ajaxLinks();

			return true;
		},
		ajaxSuccess: function (data,test) {
			if (this.url == null) {
				this.url=$(this).attr('href');
			}
			Link.ajaxUpdate(data,this.url,true);
			$(this).unwrap();
		},
		load: function() {
			var xhr = $.ajax({
				  'url': self.url,
				  dataType: 'json',
				  context: self,
				  success: Link.ajaxSuccess
				});
		},
		destroy: function() {
			return this.each(function(){
	         	$(window).unbind('.CLickEvent');
	       })
		},
		abort: function() {
			var self=this;
			self.$element.parent()
				.removeClass(self.options.loadingClass+' '+self.options.errorClass+' '+self.options.readyClass)
				.addClass(self.options.pendingClass)
				.find('.'+self.options.loadingContentClass).hide();
			return self;
		},
		


	};


	$.fn.ajaxLinks = function( options ) {
		var self = this;
		self.current = null;
		self.elements = [];
		//console.log('this/ajaxLinks',self);
		return self.each(function(){
			var link = Object.create( Link );
 			link.init( options, this, self);
 			
 			self.elements.push(link);
 			//DATA RESULT????

			//console.log('this/link',link);
		});
		

	}

	$.fn.ajaxLinks.options = {
		loadingWrapper: '<span />',
		loadingContent: '<img src="/img/loading_48x48.gif" alt="" />',
		loadingContentClass: 'ajaxLinksLoadingContent',
		loadingClass: 'ajaxLinksLoadingState',
		readyClass: 'ajaxLinksReadyState',
		pendingClass: 'ajaxLinksPendingState',
		errorClass: 'ajaxLinksErrorState'
	};

})( jQuery, window, document );