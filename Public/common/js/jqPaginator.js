(function ($) {
    'use strict';

    $.jqPaginator = function (el, options) {

        /*

        $.jqPaginator 与 $.fn.jqPaginator 的区别
        $.fn = $.prototype 相等
        prototype: 原型
        $.fn.jqPaginator 创建一个 jqPaginator 的属性 $("p").jqPaginator
        jQuery.prototype = jQuery.fn = jQuery.fn.init.prototype     
        $.jqPaginator 相当于静态方法了吧, 像 $.ajax
        */
        /*        instance：实例,例子
        判断 this 是否 是  $.jqPaginator 的实例化
        不是则 new 即实例化并返回;已经实例化就初始化
        */
        if(!(this instanceof $.jqPaginator)){
            return new $.jqPaginator(el, options);
        }

        var self = this;
        self.$container = $(el);
        self.$container.data('jqPaginator', self);
        self.init = function () {
            if (options.first || options.prev || options.next || options.last || options.page) {
                options = $.extend({}, {
                    first: '',
                    prev: '',
                    next: '',
                    last: '',
                    page: ''
                }, options);
            }
            //暴露默认设置 // build main options before element iteration  
            self.options = $.extend({}, $.jqPaginator.defaultOptions, options);
            self.verify();
            self.extendJquery();
            /*  render:给予; 使成为; 递交; 表达
                这的意思:渲染
            */
            self.render();
            self.fireEvent(this.options.currentPage, 'init');
        };

        self.verify = function () {
            var opts = self.options;

            if (!self.isNumber(opts.totalPages)) {
                throw new Error('[jqPaginator] type error: totalPages');
            }

            if (!self.isNumber(opts.totalCounts)) {
                throw new Error('[jqPaginator] type error: totalCounts');
            }

            if (!self.isNumber(opts.pageSize)) {
                throw new Error('[jqPaginator] type error: pageSize');
            }

            if (!self.isNumber(opts.currentPage)) {
                throw new Error('[jqPaginator] type error: currentPage');
            }

            if (!self.isNumber(opts.visiblePages)) {
                throw new Error('[jqPaginator] type error: visiblePages');
            }
            // VVVVVVVVVVVV
            // if (!opts.totalPages && !opts.totalCounts) {
            //     throw new Error('[jqPaginator] totalCounts or totalPages is required');
            // }

            if (!opts.totalPages && opts.totalCounts && !opts.pageSize) {
                throw new Error('[jqPaginator] pageSize is required');
            }

            if (opts.totalCounts && opts.pageSize) {
                opts.totalPages = Math.ceil(opts.totalCounts / opts.pageSize);
            }

            // VVVVVVVVVVVV
            // if (opts.currentPage < 1 || opts.currentPage > opts.totalPages) {
            //     throw new Error('[jqPaginator] currentPage is incorrect');
            // }

            // VVVVVVVVVVVV
            // if (opts.totalPages < 1) {
            //     throw new Error('[jqPaginator] totalPages cannot be less currentPage');
            // }
        };
        /*
    $('<p>').append(this.eq(0).clone()).html()
    检测传过来的html代码有没有问题,并自动修正,如少一个</b> 就加一个
    */
        self.extendJquery = function () {
            $.fn.jqPaginatorHTML = function (s) {
                return s ? this.before(s).remove() : $('<p>').append(this.eq(0).clone()).html();
            };
        };

        self.render = function () {
            self.renderHtml();
            self.setStatus();
            self.bindEvents();
        };

        self.renderHtml = function () {
            var html = [];

            var pages = self.getPages();
            for (var i = 0, j = pages.length; i < j; i++) {
                if(pages[i] == 0)
                    html.push(self.buildItem('unknow', '...'));
                else
                    html.push(self.buildItem('page', pages[i]));
            }

            self.isEnable('prev') && html.unshift(self.buildItem('prev', self.options.currentPage - 1));
            self.isEnable('first') && html.unshift(self.buildItem('first', 1));
            self.isEnable('statistics') && html.unshift(self.buildItem('statistics'));
            self.isEnable('next') && html.push(self.buildItem('next', self.options.currentPage + 1));
            self.isEnable('last') && html.push(self.buildItem('last', self.options.totalPages));

            if (self.options.wrapper) {
                self.$container.html($(self.options.wrapper).html(html.join('')).jqPaginatorHTML());
            } else {
                self.$container.html(html.join(''));
            }
        };

        self.buildItem = function (type, pageData) {
            var html = self.options[type]
                .replace(/{{page}}/g, pageData)
                .replace(/{{totalPages}}/g, self.options.totalPages)
                .replace(/{{totalCounts}}/g, self.options.totalCounts);

            return $(html).attr({
                'jp-role': type,
                'title': pageData
            }).jqPaginatorHTML();
        };

        self.bindEvents = function () {
            var opts = self.options;

            self.$container.off();
            self.$container.on('click', '[jp-role]', function () {
                var $el = $(this);
                if ($el.hasClass(opts.disableClass) || $el.hasClass(opts.activeClass)) {
                    return;
                }

                var pageIndex = +$el.attr('title');
                if (self.fireEvent(pageIndex, 'change')) {
                    self.switchPage(pageIndex);
                }
            });
        };
        self.setStatus = function () {
            var options = self.options;

            if (!self.isEnable('first') || options.currentPage === 1) {
                $('[jp-role=first]', self.$container).addClass(options.disableClass);
            }
            if (!self.isEnable('prev') || options.currentPage === 1) {
                $('[jp-role=prev]', self.$container).addClass(options.disableClass);
            }
            if (!self.isEnable('next') || options.currentPage >= options.totalPages) {
                $('[jp-role=next]', self.$container).addClass(options.disableClass);
            }
            if (!self.isEnable('last') || options.currentPage >= options.totalPages) {
                $('[jp-role=last]', self.$container).addClass(options.disableClass);
            }
            if (self.isEnable('unknow')){
                $('[jp-role=unknow]', self.$container).addClass(options.disableClass);
            }
            $('[jp-role=page]', self.$container).removeClass(options.activeClass);
            $('[jp-role=page][title=' + options.currentPage + ']', self.$container).addClass(options.activeClass);
        };

            /*
            返回显示的每一页
            */
        self.getPages = function () {
            var pages = [],
                visiblePages = self.options.visiblePages,
                currentPage = self.options.currentPage,
                totalPages = self.options.totalPages;

            if (visiblePages > totalPages) {
                visiblePages = totalPages;
            }

            var half = Math.floor(visiblePages / 2);
            var start = currentPage - half + 1 - visiblePages % 2;
            var end = currentPage + half;

            if (start < 1) {
                start = 1;
                end = visiblePages;
            }
            if (end > totalPages) {
                end = totalPages;
                start = 1 + totalPages - visiblePages;
            }

            var itPage = start;
            if (totalPages > visiblePages && start > 1 ){
                    pages.push(0);
                    itPage++;
            }
            while (itPage <= end) {
                pages.push(itPage);
                itPage++;
            }
            if (totalPages > visiblePages && end < totalPages ){
                pages.pop();
                pages.push(0);
            }
            return pages;
        };

        self.isNumber = function (value) {
            var type = typeof value;
            return type === 'number' || type === 'undefined';
        };

        self.isEnable = function (type) {
            return self.options[type] && typeof self.options[type] === 'string';
        };
        //选择页面,没有调用onPageChange
        self.switchPage = function (pageIndex) {
            self.options.currentPage = pageIndex;
            self.render();
            // alert(self.options.pageSize);
        };
        //调用回调onPageChange
        self.fireEvent = function (pageIndex, type) {
            return (typeof self.options.onPageChange !== 'function') || (self.options.onPageChange(pageIndex, type) !== false);

        };

        self.callMethod = function (method, options) {
            switch (method) {
                case 'option':
                    self.options = $.extend({}, self.options, options);
                    self.verify();
                    if (self.fireEvent(self.options.currentPage, 'change')) {
                        self.render();
                    }
                    break;
                case 'destroy':
                    self.$container.empty();
                    self.$container.removeData('jqPaginator');
                    break;
                case 'getOption':
                    return self.options;
                    break;
                /*
                *  传入页码跳转
                */
                case 'setCurrentPage':
                    if(!isNaN(options) && options > 0 && options <= self.options.totalPages && self.fireEvent(options,'change'))
                        self.switchPage(options);
                    break;
                case 'setPageSize':
                    if(!isNaN(options) && options > 0 ){
                   //     console.log(currentCounts + ' | '  + self.options.pageSize +' | ' + self.options.currentPage);
                    
                        var currentCounts = (self.options.currentPage - 1) * self.options.pageSize;
                        var currentPage =  Math.ceil(currentCounts / options);
                        self.options.currentPage = currentPage > 0 ? currentPage : 1;
                        self.options.pageSize = options;
                  //      console.log(currentCounts + ' | '  + self.options.pageSize +' | ' + self.options.currentPage);
                  //      alert(options);
                        if (self.fireEvent(self.options.currentPage, 'change')) {
                            self.switchPage(self.options.currentPage);
                        }

                    }
                    break;
                default :
                    throw new Error('[jqPaginator] method "' + method + '" does not exist');
            }

            return self.$container;
        };


        self.init();

        return self.$container;
    };

    $.jqPaginator.defaultOptions = {
        wrapper: '',
        first: '<li class="first"><a href="javascript:void(0);">首页</a></li>',
        prev: '<li class="prev"><a href="javascript:void(0);">上页</a></li>',
        next: '<li class="next"><a href="javascript:void(0);">下页</a></li>',
        last: '<li class="last"><a href="javascript:void(0);">末页</a></li>',
        page: '<li class="page"><a href="javascript:void(0);">{{page}}</a></li>',        
        unknow: '<li class="unknow"><a href="javascript:;">...</a></li>',
        totalPages: 0,
        totalCounts: 0,
        pageSize: 0,
        currentPage: 1,
        visiblePages: 7,
        disableClass: 'disabled',
        activeClass: 'active',
        onPageChange: null,
        currentPageClass:null,
        totalPagesClass:null,
        pageSizeClass:null,
        pageInput:null,
        onChangePage:null,
    };
    /*
        初始化,如果是传过来的不是 string ,就实例化
        实例化就调用静态的
        string 就调用 回调jqPaginator 去完成初始化
    */
    $.fn.jqPaginator = function () {
        var self = this,
            args = Array.prototype.slice.call(arguments);

        if (typeof args[0] === 'string') {
            var $instance = $(self).data('jqPaginator');
            if (!$instance) {
                throw new Error('[jqPaginator] the element is not instantiated');
            } else {
                return $instance.callMethod(args[0], args[1]);
            }
        } else {
            return new $.jqPaginator(this, args[0]);
        }
    };

})(jQuery);