(function(window,undefined){

    // Prepare
    var History = window.History; // Note: We are using a capital H instead of a lower h

    History.emulated = {
    pushState: true,
    hashChange: true
    };
    if ( !History.enabled ) {
         // History.js is disabled for this browser.
         // This is because we can optionally choose to support HTML4 browsers or not.
        return false;
    }


jQuery(function($){
    $('head').removeClass('no-js');
    $.ajaxSetup({cache:false});
   

    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var 
            State = History.getState(), // Note: We are using History.getState() instead of event.state
            url = State.url;
        
        // History.log(State.data, State.title, State.url);
         //console.log(State.data);
         ajaxUpdate(State.data,url,false);
    });
    
    var 
        ajax_box = '#result',
        rootUrl = History.getRootUrl(),
        xhr= {abort: function(){}};
   
    var ajaxLink = function (event, element ) {
        event.preventDefault();
        //console.log(xhr);
        //var self = this;
        this.element = element;
        this.$element = $(element);
        this.$elements = $(event.data.selector);
        this.ajaxData = null;
        
        
        this.url = this.$element.attr('href');
        //var xhr = null;
        this.wrapped =  this.addWrapper();

        //console.log(this);
        //this.test();
        this.click();
        return this;
    };
    ajaxLink.prototype.wrapped = false;

    ajaxLink.prototype.click = function () {
        var self = this;
        this.$elements.not(this.element)
        .each(function(index) {
            self.abort(this);
        });

        this.setLoading();
        this.load();
        return true;
    }
    
    ajaxLink.prototype.load = function () {
        //console.log(this.ajaxData);
        //console.log(this);
        if (this.ajaxData) return this.ajaxUpdate(this.ajaxData,this.url,true);
        
       xhr = $.ajax({
          'url': this.url,
          dataType: 'json',
          context: this,
          success: this.ajaxSuccess
        });

    }

    ajaxLink.prototype.ajaxSuccess = function (data,url,push) {
        
        if (this.url == null) {
            this.url=$(this).attr('href');
        }
        this.ajaxData= this.ajaxUpdate(data,this.url,true);


       // this.$element.unwrap('<span />');
        return true;
    }

    var ajaxUpdate = ajaxLink.prototype.ajaxUpdate = function (data,url,push) {

        $.each(data, function(key, val) {
            $(key).html(val);
        });
        $(document).attr('title',$('<title />').html(data._title).text()); //FOR IE
        if (push) {
            History.pushState(data,$('head > title').html(),url);  
            //console.log('pushState');
            this.setReady();
        } 
        $(ajax_box).html(url);
       
        return data;
    }

    ajaxLink.prototype.setLoading = function () {
        this.$element.parent()
            .removeClass(
                this.options.pendingClass+' '+
                this.options.errorClass+' '+
                this.options.readyClass)
            .addClass(this.options.loadingClass)
            .find('.'+this.options.loadingContentClass)
                .show();
    }
    ajaxLink.prototype.setReady = function (element) {
        //console.log('setReady');
        this.abort();
    }

    ajaxLink.prototype.abort = function (element) {
        $(element).parents('li:first').removeClass('current');
        if (!element) element = this.element; 
        
        //console.log(xhr);
        xhr.abort();
        $(element).parent()
            .removeClass(
                this.options.loadingClass+' '+
                this.options.errorClass+' '+
                this.options.readyClass)
            .addClass(this.options.pendingClass)
            .find('.'+this.options.loadingContentClass)
                .hide();

    }
    ajaxLink.prototype.addWrapper = function () {
        var loadingContent = $(this.options.loadingContent).
                addClass(this.options.loadingContentClass).fadeOut();
        var loadingWrapper = $(this.options.loadingWrapper).
                addClass(this.options.pendingClass);
        this.$element.wrap(loadingWrapper).
            before(loadingContent);
        //this.wrapped = true;*/
        return true;
    }
   
    ajaxLink.prototype.test = function () {
        //console.log(this.$element.parent());
        this.$element.after( this.$element.parent().html() );
        console.log(this);
        //console.log(this);
    }
    ajaxLink.prototype.options = {
        loadingWrapper: '<span />',
        loadingContent: '',
        loadingContentClass: 'ajaxLinksLoadingContent',
        loadingClass: 'ajaxLinksLoadingState',
        readyClass: 'ajaxLinksReadyState',
        pendingClass: 'ajaxLinksPendingState',
        errorClass: 'ajaxLinksErrorState'
    };

    var $elements = $('#navigation-main a');

    $(document).on('click', $elements.selector, { selector: $elements.selector }, function(event) {
        event.preventDefault();
        var $this = $(this);

        $('#content').hide();
        
        $this.parents('li:first').addClass('current');

        //$(this).data('ajaxLink', new ajaxLink(event,this));
        if (!$this.data('ajaxLink')) {
            $(this).data('ajaxLink', new ajaxLink(event,this));
        }else {
           // console.log('cached',this)
            $(this).data('ajaxLink').click();
            
        }
        $('#content').fadeIn('slow');
    }); 
    



}); // end onDomLoad

})(window); // end closure