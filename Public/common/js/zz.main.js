
var zz={};

zz.defaultOptions={
	url:'',
	data:null,
	type:"POST",
	dataType: "json",
	async: true,
	success:function(data){
   if(data.type=="success"){
     flush_data();
     // zz.tip.toptip("操作成功",1);
   }else{
     jAlert("error:"+data.context);
   }
 },
 error:function(data,text,errno){
   jAlert("操作失败:"+data.status+"\n"+data.readyState+"\n"+data.statusText+"|"+text+"|"+errno);
 },
};

zz.sendto=function(opts) {
  var options = $.extend({},this.defaultOptions, opts);
  if (options.url == '' || options.data == null) return;

  $.ajax({
    type: options.type,
    url: options.url,
    data:  options.data,
    async: options.async,
    dataType: options.dataType,
    success: options.success,
    error: options.error,
  });
};

(function ($) {
  'use strict';
  $.hctPage = function (el, options) {
    if(!(this instanceof $.hctPage)){
      return new $.hctPage(el, options);
    }
    this.options = $.extend({}, $.hctPage.defaultOptions, options);
    if (isNaN(this.options.onlyId) && this.options.onlyId !=0 ){
      var onlyId = this.options.onlyId
    }else{
      var onlyId = parseInt(Math.random()*999);
    }
    this.$container = $(el);
    this.onlyId = onlyId;
            //var jqPageClass="shownumClass"+onlyId;
      var currentPageClass="page_goto_curr";
      var totalPagesClass="page_goto_total";
      var pageSizeClass="page_goto_size";
      this.$container.data('hctPage', this);
      this.$page = $('<div class="pagination pagination-sm page_two" ></div>').appendTo(this.$container);

      var html = '<div class="page_goto"><li>\
      <span>当前第<span class="'+
      currentPageClass+'">0</span>页/共<span  class="'+
      totalPagesClass+'">0</span>页&nbsp;|&nbsp;每页<select class="'+
      pageSizeClass+'">\
      <option>10</option>\
      <option>15</option>\
      <option selected="selected">20</option>\
      <option>30</option>\
      <option>40</option>\
      <option>50</option>\
      <option>100</option>\
      <option>150</option>\
      </select>条</span></li>\
      <li><input type="number" min="1"/>\
      <a>跳转</a>\
      </li>\
      </div>\
      <div class="clear:both;"></div>';

      this.$goto = $(html).appendTo(this.$container);

      options.currentPageClass = this.$goto.find('.'+currentPageClass);
      options.totalPagesClass =this.$goto.find('.'+totalPagesClass);
      options.pageSizeClass = this.$goto.find('.'+pageSizeClass);
      options.pageInput = this.$goto.find('li input');
      options.pageInput.bind('keypress',function(event){
        if(event.keyCode == '13'){
          var num = this.value;
          if (!num) return;
          selfs.$page.jqPaginator('setCurrentPage',num);               
          options.pageInput.val(num);
        }
      });
      var selfs=this;
      options.pageSizeClass.change(function(){
        var size = Number($(this).val());
        if(!size) return;
          //alert(size);
          selfs.$page.jqPaginator('setPageSize',size);
        });

      this.$goto.find("li a").click(function (){
        var num = Number($(this).prev().val());
        if (!num) return;
        selfs.$page.jqPaginator('setCurrentPage',num);               
        options.pageInput.val(num);
      });

      options.onPageChange = function (num,type){
        if (!num) num = this.currentPage;

        if (typeof this.onChangePage === 'function') {
          if (this.onChangePage(num, type) === false){
            return false;
          }
        }
        this.totalPages = Math.ceil(this.totalCounts / this.pageSize);
        this.currentPageClass.html(num);
        this.totalPagesClass.html(this.totalPages);
        this.pageSizeClass.val(this.pageSize);
        this.pageInput.attr("max",this.totalPages);
      };
      this.$page.jqPaginator(options);
      
    };
    $.hctPage.defaultOptions = {
      shownumClass:"pagination",
      gotoPage:"pagination_goto",
      onlyId:0,
      url:'',
      onChangePage:null,
    };

$.fn.hctPage = function () {
    var self = this,
    args = Array.prototype.slice.call(arguments);

    if (typeof args[0] === 'string') {
      var $instance = $(self).data('hctPage');
      if (!$instance) {
        throw new Error('[hctPage] the element is not instantiated');
      } else {
        return $instance.callMethod(args[0], args[1]);
      }
    } else {
      return new $.hctPage(this, args[0]);
    }
  };
})(jQuery);

  zz.pageopt = {
   url: '',
   opts: {},
 };

   zz.page_normal = function(num,type){
    var opts = zz.pageopt.opts;
    opts.pgs = this.pageSize;
    if (!num) num = this.currentPage;
    opts.pgt = (num-1) * this.pageSize;
    if(Url.queryString('edit')){
      opts.edit = 1;
    }
    zz.sendto({
      type: "GET",
      url: zz.pageopt.normal_url,
      data:  opts,
      dataType: "text",
      async: false,
      success: function(data){
        $("#hct_data").html(data);
        Url.updateSearchParam('pgs', opts.pgs);
        Url.updateSearchParam('pgc', num);
      },
    });
    if(isExitsVariable('return_opts.type')){
      if (return_opts.type == "success"){
        this.totalCounts = return_opts.totalCounts;
      }else{
        this.totalCounts = 0;
  // return false;
      }
   }else{
     console.log('not found data');
     this.totalCounts = 0;
// return false;
    }
  $("#checkallid").prop('checked',false);
};

zz.page_search = function(num,type){
  var opts = zz.pageopt.opts;
  opts.pgs = this.pageSize;
  if (!num) num = this.currentPage;
  opts.pgt = (num-1) * this.pageSize;
  if(Url.queryString('edit')){
    opts.edit = 1;
  }
  zz.sendto({
    type: "GET",
    url: zz.pageopt.search_url,
    data:  opts,
    dataType: "text",
    async: false,
    success: function(data){
      $("#hct_data").html(data);
      Url.updateSearchParam('pgs', opts.pgs);
      Url.updateSearchParam('pgc', num);
    },
  });
  if(isExitsVariable('return_opts.type')){
    if (return_opts.type == "success"){
      this.totalCounts = return_opts.totalCounts;
    }else{
      this.totalCounts = 0;
      // return false;
    }
  }else{
    console.log('not found data');
    this.totalCounts = 0;
    // return false;
  }
  var html = "共 <span style='color:red;'> "+this.totalCounts+" </span>条结果";
  html += "<a style='text-align:center;' onclick='page_list();$(\"#search_rslt\").html(\"\");'>关闭结果</a>";
  $("#search_rslt").html(html);
  $("#checkallid").prop('checked',false);
};

/*
 *顶部的提示框
 */
  zz.tip =new function(){
    var n =0;
    var arr = [];
    var $this = this;
    var yes = '<div class="container tip_container" style="display: none;"><div class="success mtip"><i class="micon"></i><span>{{text}}</span><i class="mclose"></i></div></div>'
    var no = '<div class="container tip_container" style="display: none;"><div class="error mtip"><i class="micon"></i><span">{{text}}</span><i  class="mclose"></i></div></div>';
    var hit = '<div class="container tip_container" style="display: none;"><div class="warning mtip"><i class="micon"></i><span >{{text}}</span><i class="mclose"></i></div></div>';
    this.toptip = function(text,type,sec){
      if (text == null) return;
      if (type == null) type = 0;
      if (sec == null) sec = 2;
      var html='';
      switch(type){
        case 0:
        html=hit.replace(/{{text}}/g, text);
        break;
        case 1:
        html=yes.replace(/{{text}}/g, text);
        break;
        case 2:
        html=no.replace(/{{text}}/g, text);
        break;
        default:
        return;
      }
      var obj = {};

      obj.tip = $(html).prependTo('body');
      obj.tip.find('.mclose').click(function(){
        obj.tip.slideUp("fast",function(){
          obj.tip.remove();
          $this.del(obj.n);
        });
      });
      obj.n = arr.length;

      if (arr.length>4){
        arr[0].tip.fadeOut("fast",function(){
          arr[0].tip.remove();
          $this.del(0);
          obj.tip.css('top',(arr.length * 35)+'px');
          arr.push(obj);
          obj.tip.fadeIn('slow');
        });

      }else{
        obj.tip.css('top',(arr.length * 35)+'px');
        arr.push(obj);
        obj.tip.slideDown('fast');
      }

      obj.tip.mouseenter(function(){
        obj.inbox = true;
      });
      obj.tip.mouseleave(function(){
        obj.inbox = false;
      });
      obj.time = setTimeout(returnobj(obj),2000);

    }
    var $this = this;

    function returnobj(obj){
      return function (){
        delobj(obj);
      };
    }

function delobj(obj){
  obj.tip.unbind('mouseleave');
  if (obj.inbox){
    obj.tip.mouseleave(function (){
			  		// console.log(obj.n+":out<<<<<<");
			  		obj.tip.slideUp("fast",function(){
             obj.tip.remove();
             $this.del(obj.n);
           });
         });
  }else{
			// console.log(obj.n+":--------------");
			obj.tip.slideUp("fast",function(){
				obj.tip.remove();
				$this.del(obj.n);
			});
		}
	}
	this.del = function(x){
		if(x == null) return;
		// console.log("x:"+x+ "   n:"+ n+ "   len:"+arr.length);
		arr.splice(x,1);
		for (;x<arr.length;x++){
			arr[x].tip.animate({'top':(x * 35)+'px'});
			arr[x].n=x;
		}
	}
	this.get = function (){
		return arr.length+"\n"+n;
	}
};
// 可以直接用tip()的形式来提醒了;
window.tip = zz.tip.toptip;

/*** end tip ***/

(function( $ ) {
  $.widget( "custom.combobox", {
    _create: function() {
      this.wrapper = $( "<span>" )
      .addClass( "custom-combobox" )
      .insertAfter( this.element );
      
      this.element.hide();
      this._createAutocomplete();
      this._createShowAllButton();
    },
    
    _createAutocomplete: function() {
      var selected = this.element.children( ":selected" ),
      value = selected.val() ? selected.text() : "";
      
      this.input = $( "<input>" )
      .appendTo( this.wrapper )
      .val( value )
      .attr( "title", "" )
      .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
      .autocomplete({
        delay: 0,
        minLength: 0,
        source: $.proxy( this, "_source" )
      })
      .tooltip({
        tooltipClass: "ui-state-highlight"
      });
      
      this._on( this.input, {
        autocompleteselect: function( event, ui ) {
          ui.item.option.selected = true;
          this._trigger( "select", event, {
            item: ui.item.option
          });
        },
        
        autocompletechange: "_removeIfInvalid"
      });
    },
    
    _createShowAllButton: function() {
      var input = this.input,
      wasOpen = false;
      
      $( "<a>" )
      .attr( "tabIndex", -1 )
      .attr( "title", "Show All Items" )
      .tooltip()
      .appendTo( this.wrapper )
      .button({
        icons: {
          primary: "ui-icon-triangle-1-s"
        },
        text: false
      })
      .removeClass( "ui-corner-all" )
      .addClass( "custom-combobox-toggle ui-corner-right" )
      .mousedown(function() {
        wasOpen = input.autocomplete( "widget" ).is( ":visible" );
      })
      .click(function() {
        input.focus();
        
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
            
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
    },
    
    _source: function( request, response ) {
      var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
      response( this.element.children( "option" ).map(function() {
        var text = $( this ).text();
        if ( this.value && ( !request.term || matcher.test(text) ) )
          return {
            label: text,
            value: text,
            option: this
          };
        }) );
    },
    
    _removeIfInvalid: function( event, ui ) {
     
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
        
        // Search for a match (case-insensitive)
        var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
        
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
        
        // Remove invalid value
        this.input
        .val( "" )
        .attr( "title", value + " didn't match any item" )
        .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
      
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
})( jQuery );
