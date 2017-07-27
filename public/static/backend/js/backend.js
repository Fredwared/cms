var AdminLTE = {
    settings: {
        menu: '.sidebar',
        sidebarToggleSelector: '[data-toggle="offcanvas"]',
        animationSpeed: 500,
        screenSize: 750,
        sidebarExpandOnHover: false
    },
    init: function(options) {
        var _this = this;

        _this.settings = $.extend(this.settings, options || {});

        _this.fixHeight();
        $(window, '.wrapper').resize(function () {
            _this.fixHeight();
        });

        _this.toggleMenu();
        _this.treeMenu();

        /*
         * INITIALIZE BUTTON TOGGLE
         * ------------------------
         */
        $('.btn-group[data-toggle="btn-toggle"]').each(function () {
            var group = $(this);
            $(this).find('.btn').on('click', function (e) {
                group.find('.btn.active').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });
        });
    },
    fixHeight: function () {
        //Get window height and the wrapper height
        var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
        var window_height = $(window).height();
        var sidebar_height = $('.sidebar').height();

        //Set the min-height of the content and sidebar based on the height of the document.
        if ($('body').hasClass('fixed')) {
            $('.content-wrapper').css('min-height', window_height - $('.main-footer').outerHeight());
        } else {
            if (window_height >= sidebar_height) {
                $('.content-wrapper').css('min-height', window_height - neg);
            } else {
                $('.content-wrapper').css('min-height', sidebar_height);
            }
        }
    },
    toggleMenu: function() {
        var _this = this;

        //check cookie
        /*if (Backend.device_env != 1) {
         $('body').addClass(Cookies.get('toggle-menu-class'));
         }*/

        //Enable sidebar toggle
        $(this.settings.sidebarToggleSelector).on('click', function (e) {
            e.preventDefault();

            //Enable sidebar push menu
            if ($(window).width() >= _this.settings.screenSize) {
                if ($('body').hasClass('sidebar-collapse')) {
                    $('body').removeClass('sidebar-collapse');
                    Cookies.set('toggle-menu-class', 'sidebar-open');
                } else {
                    $('body').addClass('sidebar-collapse');
                    Cookies.set('toggle-menu-class', 'sidebar-collapse');
                }
            }
            //Handle sidebar push menu for small screens
            else {
                if ($('body').hasClass('sidebar-open')) {
                    $('body').removeClass('sidebar-open').removeClass('sidebar-collapse');
                    Cookies.set('toggle-menu-class', 'sidebar-collapse');
                } else {
                    $('body').addClass('sidebar-open');
                    Cookies.set('toggle-menu-class', 'sidebar-open');
                }
            }
        });

        $('.content-wrapper').on('click', function () {
            //Enable hide menu when clicking on the content-wrapper on small screens
            if ($(window).width() < _this.settings.screenSize && $('body').hasClass('sidebar-open')) {
                $('body').removeClass('sidebar-open');
            }
        });

        //Enable expand on hover for sidebar mini
        if (this.settings.sidebarExpandOnHover || ($('body').hasClass('fixed') && $('body').hasClass('sidebar-mini'))) {
            this.expandOnHover();
        }
    },
    expandOnHover: function () {
        var _this = this;

        //Expand sidebar on hover
        $('.main-sidebar').hover(function () {
            if ($('body').hasClass('sidebar-mini') && $('body').hasClass('sidebar-collapse') && $(window).width() >= _this.settings.screenSize) {
                _this.expand();
            }
        }, function () {
            if ($('body').hasClass('sidebar-mini') && $('body').hasClass('sidebar-expanded-on-hover') && $(window).width() >= _this.settings.screenSize) {
                _this.collapse();
            }
        });
    },
    expand: function () {
        $('body').removeClass('sidebar-collapse').addClass('sidebar-expanded-on-hover');
    },
    collapse: function () {
        if ($('body').hasClass('sidebar-expanded-on-hover')) {
            $('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
        }
    },
    treeMenu: function() {
        var _this = this;

        $(this.settings.menu).on('click',  'li a', function (e) {
            // Get the clicked link and the next element
            var $this = $(this);
            var checkElement = $this.next();

            // Check if the next element is a menu and is visible
            if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible'))) {
                // Close the menu
                checkElement.slideUp(_this.settings.animationSpeed, function () {
                    checkElement.removeClass('menu-open');
                });
                checkElement.parent('li').removeClass('active');
            }
            // If the menu is not visible
            else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
                // Get the parent menu
                var parent = $this.parents('ul').first();
                // Close all open menus within the parent
                var ul = parent.find('ul:visible').slideUp(_this.settings.animationSpeed);
                // Remove the menu-open class from the parent
                ul.removeClass('menu-open');
                // Get the parent li
                var parent_li = $this.parent("li");

                // Open the target menu and add the menu-open class
                checkElement.slideDown(_this.settings.animationSpeed, function () {
                    // Add the class active to the parent li
                    checkElement.addClass('menu-open');
                    parent.children('li.active').removeClass('active');
                    parent_li.addClass('active');
                });
            }
            // if this isn't a link, prevent the page from being redirected
            if (checkElement.is('.treeview-menu')) {
                e.preventDefault();
            }
        });
    }
};

var Backend = {
    /**
     * show message when submit data
     */
    showMessage: function(message, options) {
        options = $.extend({
            messageId: '.message-container',
            className: 'alert-danger',
            timeout: 2000,
            duration: '500',
            tagName: '<span />',
            how: 'html',
            callback: null
        }, options || {});

        var classNameMessage = '';
        if (options.className == 'alert-danger') {
            classNameMessage = 'error';
        }

        if (!message.blank()) {
            var el_message = $(options.tagName, {
                html: message,
                'class': classNameMessage
            }).hide().fadeIn('fast');

            $(options.messageId)[options.how](el_message);
        }

        $(options.messageId)
            .removeClass('alert-info alert-danger alert-success')
            .addClass(options.className)
            .fadeIn(options.duration, function() {
                $(this).removeClass('hide');

                // auto close message
                if (options.timeout > 0) {
                    setTimeout(function() {
                        $(options.messageId).fadeOut(options.duration, function() {
                            if ($.isFunction(options.callback)) {
                                options.callback();
                            }
                        });
                    }, options.timeout);
                } else {
                    if ($.isFunction(options.callback)) {
                        options.callback();
                    }
                }
            });
    },
    showError: function(errors, form) {
        if (typeof (form) == 'undefined') {
            form = $('body');
        }

        $('.has-error', form).removeClass('has-error');

        for (var key in errors) {
            var errorName = errorID = key;

            if (key.indexOf('.') !== -1) {
                //Splitting it with . as the separator
                var arrError = key.split('.');
                // The shift() method removes the first element from an array
                var errorName = arrError.shift(); // Example: data
                var errorID = errorName;

                $.each(arrError, function (key, er) {
                    errorName = errorName + '[' + er + ']';
                    errorID = errorID + '_' + er;
                });
            }

            if ($('#' + key + '-error', form).size() > 0) {
                $('#' + key + '-error', form).html(errors[key][0]).show();
            } else {
                if ($('#' + errorID, form).size() > 0) {
                    $('<label id="' + key + '-error" class="error" for="' + key + '">' + errors[key][0] + '</label>').insertAfter($('#' + errorID, form));
                } else {
                    $('<label id="' + key + '-error" class="error" for="' + key + '">' + errors[key][0] + '</label>').insertAfter($('[name="' + errorName + '"]', form));
                }
            }

            $('#' + errorID, form).addClass('error').parents('.form-group').addClass('has-error');
            $('[name="' + errorName + '"]', form).addClass('error').parents('.form-group').addClass('has-error');
        }
    },
    addUrlParam: function (url, param, value) {
        var a = document.createElement('a'), regex = /(?:\?|&amp;|&)+([^=]+)(?:=([^&]*))*/gi;
        var match, str = []; a.href = url;
        while (match = regex.exec(a.search))
            if (encodeURIComponent(param) != match[1])
                str.push(match[1] + (match[2] ? '=' + match[2] : ''));
        str.push(encodeURIComponent(param) + (value ? '=' + encodeURIComponent(value) : ''));
        a.search = str.join('&');
        return a.href;
    },
    multiSelect: function() {
        $('[data-multiselect="true"]').each(function() {
            var _this = $(this);

            if (_this.data('ajax') == 1) {
                var url = _this.data('url');
                var fields = _this.data('fields').split('|');
                var field_id = fields[0];
                var field_text = fields[1];
                if (field_text.search(',') > -1) {
                    field_text = field_text.split(',');
                }
                delete fields;

                _this.select2({
                    placeholder: _this.data('placeholder'),
                    theme: 'bootstrap',
                    multiple: true,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;

                            var results = [];
                            if (data.total > 0) {
                                var i = 0;
                                while (i < data.items.length) {
                                    results[i] = {};
                                    results[i]['id'] = data.items[i][field_id];
                                    if ($.isArray(field_text)) {
                                        results[i]['text'] = data.items[i][field_text[0]] + ' - ' + data.items[i][field_text[1]];
                                    } else {
                                        results[i]['text'] = data.items[i][field_text];
                                    }
                                    i++;
                                }
                            }

                            return {
                                results: results,
                                pagination: {
                                    more: (params.page * 30) < data.total
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 3,
                    escapeMarkup: function (markup) { return markup; },
                    templateResult: function (response) {
                        if (response.loading) {
                            return response.text;
                        }

                        return response.text;
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                });
            } else {
                _this.select2({
                    placeholder: _this.data('placeholder'),
                    theme: 'bootstrap',
                    multiple: true
                });
            }
        });
    },
    createCode: function () {
        // Create code for title
        $(':text[data-code="true"]').on('blur', function() {
            var _this = $(this);
            var code = $(_this.data('for'));

            if (!_this.val().blank() && (code.val().blank() || (typeof (_this.data('force')) != 'undefined' && _this.data('force') == 1))) {
                code.prop('disabled', true);
                $.ajax({
                    url: _this.data('link'),
                    data: {
                        title: _this.val(),
                        language: $('#language_id').val()
                    },
                    success: function(data) {
                        code.val(data).prop('disabled', false);
                    }
                });
            }
        });

        $('button[data-code="true"]').on('click', function() {
            $(':text[data-code="true"]').data('force', 1).trigger('blur');
        });
    },
    import: function() {
        var uploadConfig = $('#fileUploader').data('config');
        $('#fileUploader').removeAttr('data-config');

        $('#fileUploader').uploadFile({
            url: uploadConfig.url,
            maxFileAllowed: uploadConfig.maxFileAllowed,
            allowedTypes: uploadConfig.allowedTypes, //seperate with ','
            maxFileSize: uploadConfig.maxFileSize, //in byte
            maxFileAllowedErrorStr: uploadConfig.maxFileAllowedErrorStr,
            dragDropStr: uploadConfig.dragDropStr,
            dragDropErrorStr: uploadConfig.dragDropErrorStr,
            uploadErrorStr: uploadConfig.uploadErrorStr,
            extErrorStr: uploadConfig.extErrorStr,
            sizeErrorStr: uploadConfig.sizeErrorStr,
            onSuccess: function(instance, panel, files, data, xhr) {
                panel.html('<div class="alert-info p05">' + data.message + '</div>');
                setInterval(function() {
                    window.location.reload(true);
                }, 1000);
            }
        });
    },
    loadCKEditor: function(editorid, config) {
        var toolbar = {
            'Content': [
                ['FontSize', 'Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript'],
                ['TextColor', 'BGColor'],
                ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['Form', 'Checkbox', 'Radio', 'TextField', 'TextArea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
                ['Undo', 'Redo', 'RemoveFormat', 'Find', 'Replace', 'SelectAll'],
                ['Link', 'Unlink', 'Anchor'],
                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                ['InsertImage', 'InsertVideo', 'InsertPost', 'InsertLink', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'Iframe'],
                ['Source']
            ],
            'Description': [
                ['Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript'],
                ['TextColor', 'BGColor'],
                ['Undo', 'Redo', 'RemoveFormat'],
                ['Link', 'Unlink', 'Anchor']
            ],
            'Full': 'Full',
            'Basic': 'Basic'
        };
        var myconfig = $.extend({
            toolbar: toolbar[config['toolbarName']],
            skin: 'moono',
            resize_enabled: false,
            language: 'en',
            width: '100%',
            height: '150px',
            allowedContent: true,
            coreStyles_bold: { element: 'b', overrides: 'strong' },
            coreStyles_italic: { element: 'i', overrides: 'em' }
        }, config || {});

        if (config['toolbarName'] == 'Content') {
            myconfig.extraPlugins = 'wordcount,notification,insertimage,insertvideo,insertpost,insertlink';
            myconfig.filebrowserBrowseUrl = Common.url.root + '/backend/media/file?modal=1&multi=0&source=ckeditor';
            myconfig.insertImageUrl = Common.url.root + '/backend/media/image?modal=1&multi=1&source=ckeditor_plugin&editor_name=' + editorid;
            myconfig.insertPostUrl = Common.url.root + '/backend/utils/editor/insertpost?ditor_name=' + editorid;
            myconfig.insertVideoUrl = Common.url.root + '/backend/utils/editor/insertvideo?editor_name=' + editorid;
            myconfig.insertLinkUrl = Common.url.root + '/backend/utils/editor/insertlink?editor_name=' + editorid;
            myconfig.contentsCss = Common.url.static.frontend.css + '/style.css';
        } else {
            myconfig.extraPlugins = 'wordcount,notification';
        }

        var editor = CKEDITOR.replace(editorid, myconfig);

        if (config['toolbarName'] == 'Content') {
            editor.on('instanceReady', function (e) {
                editor.addCommand('thumbnail_url', {
                    exec: function (editor) {
                        var e = editor.getSelection().getSelectedElement();
                        e = $(e.$);

                        $('#thumbnail_url').val(e.attr('src'));
                        $('img[data-for="thumbnail_url"]').attr('src', e.attr('src'));
                    }
                });

                editor.addCommand('thumbnail_url2', {
                    exec: function (editor) {
                        var e = editor.getSelection().getSelectedElement();
                        e = $(e.$);

                        $('#thumbnail_url2').val(e.attr('src'));
                        $('img[data-for="thumbnail_url2"]').attr('src', e.attr('src'));
                    }
                });

                editor.contextMenu.addListener(function (element, selection) {
                    if (!element || element.data('cke-realelement') || element.isReadOnly() || !$(element.$).is('img') || $(element.$).attr('data-component')) {
                        return null;
                    }

                    return {
                        thumbnail_url: CKEDITOR.TRISTATE_OFF,
                        thumbnail_url2: CKEDITOR.TRISTATE_OFF
                    };
                });

                editor.addMenuItems({
                    thumbnail_url: {
                        label: 'Chọn làm hình ngang',
                        command: 'thumbnail_url',
                        group: 'image'
                    },
                    thumbnail_url2: {
                        label: 'Chọn làm hình dọc',
                        command: 'thumbnail_url2',
                        group: 'image'
                    }
                });
            });
        }
    },
    showSidebarOption: function() {
        $(document).on('click', '.btn-show-sidebar', function(evt){
            evt.preventDefault();

            var button = $(this);
            var link = $(button).data('link') ? $(button).data('link') : $(button).attr('href');
            var oldHtml = button.html();
            var oldText = button.text();

            $(window).on('resize', function() {
                if ($(window).scrollTop() != 0 && !$('body').hasClass('fixed')) {
                    var top = 0;
                } else {
                    //get height of header
                    var top = $('.main-header').height();
                }

                $('.sidebar-option').css('top', top + 'px');
            }).resize();

            $(window).on('scroll', function(evt) {
                if ($(window).scrollTop() != 0 && !$('body').hasClass('fixed')) {
                    var top = 0;
                } else {
                    //get height of header
                    var top = $('.main-header').height();
                }

                $('.sidebar-option').css('top', top + 'px');
            }).scroll();

            if (link) {
                $.ajax({
                    url: link,
                    beforeSend: function () {
                        button.html('<i class="fa fa-spin fa-spinner"></i> ' + oldText).prop('disabled', true);

                        $('.sidebar-option').find('.icon-loading').show();
                        $('.sidebar-option').find('.icon-close').hide();
                        $('.sidebar-option').find('.sidebar-body').html('');

                        $('.sidebar-option').animate({ right: 0 }, 200, 'linear');
                    },
                    success: function (response) {
                        button.html(oldHtml).prop('disabled', false);

                        $('.sidebar-option').find('.icon-loading').hide();
                        $('.sidebar-option').find('.icon-close').show();
                        $('.sidebar-option').find('.sidebar-body').html(response);
                    },
                    error: function () {
                        button.html(oldHtml).prop('disabled', false);
                        $('.sidebar-option').find('.icon-loading').hide();
                    }
                });
            } else {
                $('.sidebar-option').animate({ right: 0 }, 500, 'linear', function () {
                    $(this).find('.icon-close').show();
                });
            }
        });

        // When click icon close
        $('.sidebar-option').on('click', '.icon-close', function(evt){
            evt.preventDefault();
            $(this).parent().animate({ right: '-' + ($(this).parent().width() + 500) + 'px' }, 200, 'linear');
        });
        $('.sidebar-option').on('click', '.btn-close', function(evt){
            $('.sidebar-option .icon-close').trigger('click');
        });
    },
    initSort: function() {
        $('#frmSort ul').sortable({
            cursor: 'move',
            forceHelperSize: true
        }).disableSelection();

        $('#frmSort a').on('click', function(evt) {
            evt.preventDefault();

            $(this).next('ul').slideToggle(function() {
                $(this).parent().toggleClass('active');
            });
        });

        $('#frmSort').on('submit', function(evt) {
            evt.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function(response) {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    Backend.showMessage(response.message, {
                        className: response.error == 0 ? 'alert-success' : 'alert-danger',
                        callback: function() {
                            if (response.error == 0) {
                                window.location.reload(true);
                            }
                        }
                    });
                },
                error: function (response) {
                    Backend.showMessage(response.statusText);
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                }
            });
        });
    },
    initDate: function(date_from, date_to) {
        //Using for validate startdate and enddate
        $(date_from).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayBtn: 'linked',
            todayHighlight: true,
            zIndexOffset: 99999
        }).on('hide', function(e) {
            $(date_to).datepicker('setStartDate', e.date);
            $(date_to).find('input').focus();
        }).on('click', function(e) {
            $(this).datepicker('setEndDate', $(date_to).find('input').val());
        }).data('datepicker');

        $(date_to).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayBtn: 'linked',
            todayHighlight: true,
            zIndexOffset: 99999
        }).on('click', function(e) {
            $(this).datepicker('setStartDate', $(date_from).find('input').val());
        });
    },
    initPagingAjax: function (callback) {
        $('.pagination-ajax .pagination').on('click', 'a', function(evt) {
            evt.preventDefault();
            callback($(this), $(this).attr('href'));
        });
    },
    initScrollPaging: function(options) {
        var settings = $.extent({
            objScroll: '',
            contentSelector: '',
            autoTrigger: true,
            nextParent: '.next-parent',
            nextSelector: 'a:last',
            loadingHtml: '<div><i class="fa fa-3x fa-spin fa-spinner"></i></div>'
        }, options || {});

        var objScroll = settings.objScroll;
        var contentSelector = settings.contentSelector;
        var loadingHtml = $(settings.loadingHtml).appendTo($(contentSelector)).hide();

        $(objScroll).on('scroll', function(evt) {
            var _self = $(this);

            if ((typeof (_self.data('loading')) == 'undefined' || _self.data('loading') == 0) && _self.height() + _self.scrollTop() == _self.prop('scrollHeight')) {
                var page = typeof ($(contentSelector).data('page')) == 'undefined' ? 2 : $(contentSelector).data('page');
                var loaded = typeof ($(contentSelector).data('loaded')) == 'undefined' ? 0 : $(contentSelector).data('loaded');

                $.ajax({
                    url: $(container).data('link'),
                    data: {
                        page: page
                    },
                    beforeSend: function() {
                        _self.data('loading', 1);
                        loadingHtml.show();
                    },
                    success: function (response) {
                        $(contentSelector).append(response);
                        loadingHtml.hide();

                        loaded = loaded + 1;
                        page = page + 1;

                        $(contentSelector).data('page', page);
                        _self.data('loading', 0);
                        _self.data('loaded', loaded);

                        if (!typeof(settings.autoTrigger) === 'boolean' && settings.autoTrigger == loaded) {
                            $(objScroll).off('scroll');
                            $(settings.nextParent).show();
                            $(settings.nextParent).find(settings.nextSelector).attr('href', $(container).data('link') + '?page=' + page);
                        }
                    }
                });
            }
        });
    },
    init: function () {
        //check menu active in sidebar
        $('ul.treeview-menu li.active').parents('li.treeview').addClass('active');

        //scroll to top
        $('#scrolltop a').bind('click', function (evt) {
            evt.preventDefault();
            $('body,html').animate({scrollTop: 0}, 500);
        });
        $(window).scroll(function () {
            if ($(this).scrollTop() > 30) {
                $('#scrolltop').show();
            } else {
                $('#scrolltop').hide();
            }
        });

        //show sidebar option
        this.showSidebarOption();

        //show date
        this.initDate('#date_from', '#date_to');

        // all elelment has attribute data-ajax = true
        $(document).on('click', '[data-ajax="true"]', function(evt) {
            evt.preventDefault();

            if (typeof ($(this).data('processing')) == 'undefined' || $(this).data('processing') == 0) {
                var _this = $(this);
                var link = _this.data('link') ? _this.data('link') : _this.attr('href');
                var text = _this.html();

                $.ajax({
                    url: link,
                    method: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        _this.data('processing', 1).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function (response) {
                        _this.data('processing', 0).html(text);
                        Backend.showMessage(response.message, {
                            className: 'alert-info'
                        });
                    },
                    error: function (response) {
                        Backend.showMessage(response.statusText);
                        _this.data('processing', 0).html(text);
                    }
                });
            }
        });

        // click button delete
        $(document).on('click', '[data-delete="true"]', function(evt) {
            evt.preventDefault();
            var message = $(this).data('message');
            var reload = $(this).data('reload');
            var parent = typeof ($(this).data('parent')) == 'undefined' ? 'tr' : $(this).data('parent');
            var link = $(this).data('link') ? $(this).data('link') : $(this).attr('href');
            var _this = $(this);
            var text = _this.html();

            bootbox.confirm(message, function(result) {
                if (result) {
                    $.ajax({
                        url: link,
                        method: 'delete',
                        beforeSend: function () {
                            _this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function (response) {
                            _this.prop('disabled', false).html(text);
                            if (response.error == 0) {
                                if (_this.parents(parent).parent().children().size() == 1) {
                                    reload = true;
                                } else {
                                    _this.parents(parent).remove();
                                }

                                Backend.showMessage(response.message, {
                                    className: 'alert-info',
                                    callback: function() {
                                        if (reload) {
                                            window.location.reload(true);
                                        }
                                    }
                                });
                            } else {
                                Backend.showMessage(response.message);
                            }
                        },
                        error: function (response) {
                            Backend.showMessage(response.statusText);
                            _this.prop('disabled', false).html(text);
                        }
                    });
                }
            });
        });

        // click button change status
        $(document).on('click', '[data-status="true"]', function(evt) {
            evt.preventDefault();

            var _this = $(this);
            var arrId = [];

            $('input:checkbox[data-for="chkAll"]:enabled:checked').each(function () {
                arrId.push($(this).val());
            });

            if (!arrId.blank()) {
                var link = _this.data('link');
                var text = _this.html();
                var status = link.getNum();

                $.ajax({
                    url: link,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: arrId
                    },
                    beforeSend: function () {
                        $('[data-status="true"]').prop('disabled', true);
                        _this.html('<i class="fa fa-spinner fa-spin"></i>');
                        for (var i = 0; i < arrId.length; i++) {
                            var obj = $('[data-forstatus="' + arrId[i] + '"]');
                            var tagName = obj.prop('tagName').toLowerCase();

                            if (tagName == 'select') {
                                obj.prop('disabled', true);
                            } else {
                                obj.prepend('<i class="fa fa-spinner fa-spin mr05"></i>');
                            }
                        }
                    },
                    success: function (response) {
                        $('[data-status="true"]').prop('disabled', false);
                        _this.html(text);

                        if (response.error == 0) {
                            Backend.showMessage(response.message, {
                                className: 'alert-info'
                            });
                            for (var i = 0; i < arrId.length; i++) {
                                var obj = $('[data-forstatus="' + arrId[i] + '"]');
                                var tagName = obj.prop('tagName').toLowerCase();

                                if (tagName == 'select') {
                                    obj.prop('disabled', false).val(status);
                                } else {
                                    if (obj.hasClass('label-danger')) {
                                        obj.removeClass('label-danger').addClass('label-success');
                                    } else {
                                        obj.removeClass('label-success').addClass('label-danger');
                                    }
                                    obj.html(text);
                                }
                            }
                        } else {
                            for (var i = 0; i < arrId.length; i++) {
                                var obj = $('[data-forstatus="' + arrId[i] + '"]');
                                var tagName = obj.prop('tagName').toLowerCase();

                                if (tagName == 'select') {
                                    obj.prop('disabled', false);
                                } else {
                                    obj.find('.fa').remove();
                                }
                            }
                            Backend.showMessage(response.message);
                        }
                    },
                    error: function (response) {
                        Backend.showMessage(response.statusText);

                        $('[data-status="true"]').prop('disabled', false);
                        _this.html(text);

                        for (var i = 0; i < arrId.length; i++) {
                            var obj = $('[data-forstatus="' + arrId[i] + '"]');
                            var tagName = obj.prop('tagName').toLowerCase();

                            if (tagName == 'select') {
                                obj.prop('disabled', false);
                            } else {
                                obj.find('.fa').remove();
                            }
                        }
                    }
                });
            } else {
                $('#chkAll').prop('checked', false);
            }
        });

        // select change status
        $(document).on('change', '[data-status="true"]', function(evt) {
            evt.preventDefault();

            var _this = $(this);
            var old_status = _this.data('old');
            var new_status = _this.val();
            var link = _this.data('link');

            $.ajax({
                url: link.replace('0', new_status),
                method: 'post',
                dataType: 'json',
                data: {
                    id: _this.data('forstatus')
                },
                beforeSend: function () {
                    _this.prop('disabled', true);
                },
                success: function (response) {
                    _this.prop('disabled', false);
                    if (response.error == 0) {
                        Backend.showMessage(response.message, {
                            className: 'alert-info'
                        });
                    } else {
                        _this.val(old_status);
                        Backend.showMessage(response.message);
                    }
                },
                error: function (response) {
                    Backend.showMessage(response.statusText);
                    _this.prop('disabled', false).val(old_status);
                }
            });
        });

        // click checkbox check all
        $(document).on('click', '#chkAll', function() {
            $(this).parents('table').find('input:checkbox[data-for="chkAll"]:enabled').prop('checked', $(this).is(':checked'));
        });
        $(document).on('click', ':checkbox[data-for="chkAll"]:enabled', function() {
            var parent = $(this).parents('table');

            if (!$(this).is(':checked')) {
                $(parent).find(':checkbox#chkAll').prop('checked', false);
            } else {
                if ($(parent).find(':checkbox[data-for="chkAll"]:enabled').size() == $(parent).find(':checkbox[data-for="chkAll"]:enabled:checked').size()) {
                    $(parent).find(':checkbox#chkAll').prop('checked', true);
                }
            }
        });

        //select item in paging
        $(document).on('change', 'select[data-pagination="true"]', function () {
            var link = window.location.href;
            link = Backend.addUrlParam(link, 'page', 1);
            link = Backend.addUrlParam(link, 'item', $(this).val());

            window.location.href = link;
        });

        // load ckeditor if detect element has attribute is data-editor
        $.each($('*[data-editor]'), function(index, obj) {
            Backend.loadCKEditor($(obj).attr('id'), $.parseJSON($(obj).attr('data-editor')));
        });

        $('[data-toggle="popover"]').popover();
        //click button choose image or file
        $(document).on('click', '.btn-show-filemanager', function(evt) {
            evt.preventDefault();
            var _this = $(this);

            bootbox.dialog({
                title: 'File Manager',
                message: '<div id="filemanager_modal" class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>',
                backdrop: true,
                animate: true,
                size: 'large'
            });

            $.ajax({
                url: _this.data('link'),
                success: function(response) {
                    $('#filemanager_modal').removeClass('text-center').html(response);
                }
            });
        });

        $('form').bind({
            reset: function() {
                if (typeof (CKEDITOR) != 'undefined') {
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData();
                    }
                }
            },
            submit: function() {
                var form = $(this);

                if (typeof (CKEDITOR) != 'undefined') {
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                        var content = CKEDITOR.instances[instance].getData();
                        var arrImgContent = [];

                        $.each($(content).find('img'), function(i, o) {
                            if ($(o).attr('data-component')) {
                                return true;
                            }

                            if (!$(o).hasClass('component')) {
                                arrImgContent.push($(o).attr('src'));
                            }
                        });

                        $.each(arrImgContent, function(index, source) {
                            $('<input/>').attr({ name: 'image_content[' + index + ']', type: 'hidden' }).val(source).appendTo(form);
                        });
                    }
                }
            }
        });

        if ($.validator) {
            $.validator.setDefaults({
                ignore: '.ignore',
                errorClass: 'error',
                showErrors: function(errorMap, errorList) {
                    $(this.currentForm).find(':disabled').removeClass('has-error').parent().removeClass('has-error');
                    this.defaultShowErrors();
                },
                errorPlacement: function(error, element) {
                    element.parents('.form-group').addClass('has-error');
                    error.appendTo(element.parents('.form-group'));
                },
                highlight: function (element) {
                    $(element).addClass('has-error').parent().addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).removeClass('has-error').parent().removeClass('has-error');
                }
            });

            $.validator.addMethod('username', function(value, element) {
                return this.optional(element) || /^[a-z][a-z0-9\-\_]+$/.test(value);
            }, 'Username is invalid.');

            $.validator.addMethod('code', function(value, element) {
                return this.optional(element) || /^[a-z0-9\-]+$/.test(value);
            }, 'Code is invalid.');

            $.validator.addMethod('maxWords', function(value, element, params) {
                return this.optional(element) || value.countWords() <= params;
            }, $.validator.format('Please enter at least {0} words.'));

            $.validator.addMethod('minWords', function(value, element, params) {
                return this.optional(element) || value.countWords() >= params;
            }, $.validator.format('Please enter {0} words or less.'));

            $.validator.addMethod('rangeWords', function(value, element, params) {
                return this.optional(element) || (value.countWords() >= params[0] && value.countWords() <= params[1]);
            }, $.validator.format('Please enter between {0} and {1} words.'));

            // Accept a value from a file input based on a required mimetype
            $.validator.addMethod('accept', function(value, element, param) {
                // Split mime on commas in case we have multiple types we can accept
                var typeParam = typeof param === 'string' ? param.replace( /\s/g, '' ) : 'image/*', optionalValue = this.optional( element ), i, file, regex;

                // Element is optional
                if (optionalValue) {
                    return optionalValue;
                }

                if ($(element).attr('type') === 'file') {
                    // Escape string to be used in the regex
                    // see: http://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex
                    // Escape also "/*" as "/.*" as a wildcard
                    typeParam = typeParam.replace(/[\-\[\]\/\{\}\(\)\+\?\.\\\^\$\|]/g, '\\$&' ).replace( /,/g, '|').replace('\/*', '/.*');

                    // Check if the element has a FileList before checking each file
                    if (element.files && element.files.length) {
                        regex = new RegExp('.?(' + typeParam + ')$', 'i');
                        for (i = 0; i < element.files.length; i++) {
                            file = element.files[ i ];

                            // Grab the mimetype from the loaded file, verify it matches
                            if (!file.type.match(regex)) {
                                return false;
                            }
                        }
                    }
                }

                // Either return true because we've validated each file, or because the
                // browser does not support element.files and the FileList feature
                return true;
            }, $.validator.format('Please enter a value with a valid mimetype.'));
        }
    }
};

var Auth = {
    checkLogin: function(messages) {
        $('#frmLogin').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: messages.email.required,
                    email: messages.email.email
                },
                password: {
                    required: messages.password.required,
                }
            }
        });
    },
    resetPass: function(messages) {
        $('#frmResetPass').validate({
            rules: {
                password: {
                    required: true,
                    rangelength: [6, 20]
                },
                re_password: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    required: messages.password.required,
                    rangelength: messages.password.rangelength.replace(':min', 6).replace(':max', 20)
                },
                re_password: {
                    required: messages.re_password.required,
                    equalTo: messages.re_password.equalTo
                }
            }
        });
    }
};

var Menu = {
    validate: function(messages) {
        $('#frmMenu').validate({
            rules: {
                menu_code: {
                    maxlength: 200
                },
                parent_id: {
                    required: true
                },
                route_name: {
                    maxlength: 200
                },
                menu_icon: {
                    maxlength: 200
                },
                status: {
                    required: true
                },
                display_order: {
                    number: true
                }
            },
            messages: {
                menu_code: {
                    maxlength: messages.menu_code.maxlength.replace(':max', 200)
                },
                parent_id: {
                    required: messages.parent_id.required
                },
                route_name: {
                    maxlength: messages.route_name.maxlength.replace(':max', 200)
                },
                menu_icon: {
                    maxlength: messages.menu_icon.maxlength.replace(':max', 200)
                },
                status: {
                    required: messages.status.required
                },
                display_order: {
                    number: messages.display_order.number
                }
            }
        });
        $.each(Common.arrLanguage, function(code, data) {
            $('#frmMenu #menu_name_' + code).rules('add', {
                required: true,
                maxlength: 100,
                messages: {
                    required: messages.menu_name.required,
                    maxlength: messages.menu_name.maxlength.replace(':max', 100)
                }
            });
        });
    }
};

var Role = {
    validate: function(messages) {
        $('#frmRole').validate({
            rules: {
                role_code: {
                    required: true,
                    maxlength: 10
                },
                role_priority: {
                    number: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                role_code: {
                    required: messages.role_code.required,
                    maxlength: messages.role_code.maxlength.replace(':max', 10)
                },
                role_priority: {
                    number: messages.role_priority.number
                },
                status: {
                    required: messages.status.required
                }
            }
        });
        $.each(Common.arrLanguage, function(code, data) {
            $('#frmRole #role_name_' + code).rules('add', {
                required: true,
                maxlength: 100,
                messages: {
                    required: messages.role_name.required,
                    maxlength: messages.role_name.maxlength.replace(':max', 100)
                }
            });
        });
    }
};

var Group = {
    validate: function(messages) {
        $('#frmGroup').validate({
            rules: {
                group_name: {
                    required: true,
                    maxlength: 50
                },
                group_description: {
                    maxlength: 200
                },
                status: {
                    required: true
                }
            },
            messages: {
                group_name: {
                    required: messages.group_name.required,
                    maxlength: messages.group_name.maxlength
                },
                group_description: {
                    required: messages.group_description.maxlength.replace(':max', 200)
                },
                status: {
                    required: messages.status.required
                }
            }
        });
    },
    checkAllRole: function(role, index) {
        $(':checkbox[data-role="' + role + '"]').click(function(){
            var checked = $(this).is(':checked');

            $.each($(':checkbox[data-role]:gt(' + index + ')'), function(j, o) {
                return !(checked = $(o).prop('checked'));
            });

            if (checked) $(this).prop('checked', checked);
            $(':checkbox[data-forrole="' + role + '"]').prop('checked', $(this).is(':checked'));

            if ($(this).is(':checked')) {
                $(':checkbox[data-role]:lt(' + index + ')').trigger('click');
                $(':checkbox[data-role]:lt(' + index + ')').prop('checked', $(this).is(':checked'));
            }
        });
    },
    setRoleChecked: function(role, index) {
        var isChecked = false;
        $.each($(':checkbox[data-forrole="' + role + '"]'), function(i, o) {
            return (isChecked = $(o).is(':checked'));
        });

        $(':checkbox[data-role="' + role + '"]').prop('checked', isChecked);
        if (index && isChecked) {
            $(':checkbox[data-role]:lt(' + index + ')').prop('checked', true);
        }
    },
    setMenuChecked: function(menu) {
        var isChecked = false;
        $.each($(':checkbox[data-formenu="' + menu + '"]'), function(i, o) {
            return (isChecked = $(o).is(':checked'));
        });

        $(':checkbox[data-menu="' + menu + '"]').prop('checked', isChecked);
    },
    initProfile: function() {
        var _this = this;
        $.each($('#tblProfile tbody tr'), function(i, tr) {
            $.each($('[name^="profile"]:checkbox', $(tr)), function(index, chk) {
                $(chk).click(function() {
                    var checked = $(this).is(':checked');

                    /*$.each($('[name^="profile"]:checkbox:gt(' + index + ')', $(tr)), function(j, o) {
                     return !(checked = $(o).prop('checked'));
                     });*/

                    if (checked) $(this).prop('checked', checked);
                    //if ($(this).is(':checked')) $('[name^="profile"]:checkbox:lt(' + index + ')', $(tr)).prop('checked', $(this).is(':checked'));

                    _this.setRoleChecked($(this).data('forrole'), index);
                    _this.setMenuChecked($(this).data('formenu'));
                });
            });
        });

        $(':checkbox[data-menu]').each(function() {
            _this.setMenuChecked($(this).data('menu'));
            $(this).click(function () {
                $(':checkbox[data-formenu="' + $(this).data('menu') + '"]').prop('checked', $(this).is(':checked'));
            });
        });

        $.each($(':checkbox[data-role]'), function(i, o) {
            _this.setRoleChecked($(o).data('role'));
            _this.checkAllRole($(o).data('role'), i);
        });
    }
};

var BlockIp = {
    validate: function(messages) {
        $('#frmBlockIp').validate({
            rules: {
                ip_address: {
                    required: true,
                    maxlength: 50
                },
                status: {
                    required: true
                }
            },
            messages: {
                ip_address: {
                    required: messages.ip_address.required,
                    maxlength: messages.ip_address.maxlength.replace(':max', 50)
                },
                status: {
                    required: messages.status.required
                }
            }
        });
        $.each(Common.arrLanguage, function(code, data) {
            $('#frmBlockIp #ip_description_' + code).rules('add', {
                maxlength: 100,
                messages: {
                    maxlength: messages.ip_description.maxlength.replace(':max', 100)
                }
            });
        });
    }
};

var User = {
    validate: function(messages) {
        $('#frmUser').validate({
            rules: {
                fullname: {
                    required: true,
                    maxlength: 200
                },
                email: {
                    required: true,
                    emai: true,
                    maxlength: 200
                },
                address: {
                    maxlength: 500
                },
                phone: {
                    number: true,
                    maxlength: 20
                },
                'groups[]': {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                fullname: {
                    required: messages.fullname.required,
                    maxlength: messages.fullname.maxlength.replace(':max', 200)
                },
                email: {
                    required: messages.email.required,
                    email: messages.email.email,
                    maxlength: messages.email.maxlength.replace(':max', 200)
                },
                address: {
                    maxlength: messages.address.maxlength.replace(':max', 500)
                },
                phone: {
                    number: messages.phone.number,
                    maxlength: messages.phone.maxlength.replace(':max', 20)
                },
                'groups[]': {
                    required: messages.group.required
                },
                status: {
                    required: messages.status.required
                }
            }
        });
    },
    updateProfile: function(messages) {
        $('#frmUserProfile').validate({
            rules: {
                fullname: {
                    required: true,
                    maxlength: 200
                },
                email: {
                    required: true,
                    emai: true,
                    maxlength: 200
                },
                address: {
                    maxlength: 500
                },
                phone: {
                    number: true,
                    maxlength: 20
                },
                password: {
                    required: true,
                    rangelength: [6, 20]
                },
                re_password: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                fullname: {
                    required: messages.fullname.required,
                    maxlength: messages.fullname.maxlength.replace(':max', 200)
                },
                email: {
                    required: messages.email.required,
                    email: messages.email.email,
                    maxlength: messages.email.maxlength.replace(':max', 200)
                },
                address: {
                    maxlength: messages.address.maxlength.replace(':max', 500)
                },
                phone: {
                    number: messages.phone.number,
                    maxlength: messages.phone.maxlength.replace(':max', 20)
                },
                password: {
                    required: messages.password.required,
                    rangelength: messages.password.rangelength.replace(':min', 6).replace(':max', 20)
                },
                re_password: {
                    required: messages.re_password.required,
                    equalTo: messages.re_password.equalTo
                }
            }
        });
    },
    uploadAvatar: function (setting) {
        $('#frmUserAvatar #fileUploader').uploadFile({
            url: setting.url,
            uploadPanel: setting.uploadPanel,
            maxFileAllowed: setting.maxFileAllowed,
            allowedTypes: setting.allowedTypes, //seperate with ','
            maxFileSize: setting.maxFileSize, //in byte
            maxFileAllowedErrorStr: setting.maxFileAllowedErrorStr,
            dragDropStr: setting.dragDropStr,
            dragDropErrorStr: setting.dragDropErrorStr,
            uploadErrorStr: setting.uploadErrorStr,
            extErrorStr: setting.extErrorStr,
            sizeErrorStr: setting.sizeErrorStr,
            onSuccess: function(instance, panel, files, data, xhr) {
                if (instance.fileCounter > 0) {
                    instance.fileCounter--;
                }

                $('#avatar').val(data.info.filename);
                panel.html('<img src="' + setting.mediaUrl + data.info.filename + '" class="img-circle img-thumbnail w90px" />');
            },
            onDelete: function(obj, instance, panel) {
                instance.fileCounter--;
            }
        });

        $('#frmUserAvatar button').on('click', function() {
            var form = $('#frmUserAvatar');
            var button = $(this);

            $.ajax({
                url: $(form).data('link'),
                method: $(form).data('method'),
                data: {
                    avatar: $(form).find('#avatar').val()
                },
                beforeSend: function () {
                    button.prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function (response) {
                    button.prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    $('.sidebar-option .icon-close').trigger('click');

                    Backend.showMessage(response.message, {
                        className: 'alert-info'
                    });
                },
                error: function (response) {
                    var errors = response.responseJSON;
                    button.prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');

                    Backend.showError(errors);
                }
            });
        });
    }
};

var Media = {
    link_media: null,
    media_id: 0,
    settings: {
        type: 1,
        link_menu: null,
        class_loading: 'fa-3x fa-spinner fa-spin',
        modal: 0,
        multi: 0,
        source: 'ckeditor'
    },
    init: function(settings) {
        var _this = this;

        _this.media_message = $('#media_message');
        _this.media_form = $('#media_form');
        _this.media_data = $('#media_data');

        $.extend(_this.settings, settings || {});

        Backend.multiSelect();
        //show date
        Backend.initDate('#date_from', '#date_to');

        //click button search
        $('#frmSearch', _this.media_form).submit(function(evt) {
            evt.preventDefault();
            _this.loadMedia($(this));
        });

        //click paging
        Backend.initPagingAjax(function(obj, link) {
            _this.loadMedia(link);
        });

        var uploadConfig = $('#fileUploader', _this.media_data).data('config');
        $('#fileUploader', _this.media_data).removeAttr('data-config');

        $('#fileUploader', _this.media_data).uploadFile({
            url: uploadConfig.url,
            maxFileAllowed: uploadConfig.maxFileAllowed,
            allowedTypes: uploadConfig.allowedTypes, //seperate with ','
            maxFileSize: uploadConfig.maxFileSize, //in byte
            maxFileAllowedErrorStr: uploadConfig.maxFileAllowedErrorStr,
            dragDropStr: uploadConfig.dragDropStr,
            dragDropErrorStr: uploadConfig.dragDropErrorStr,
            uploadErrorStr: uploadConfig.uploadErrorStr,
            extErrorStr: uploadConfig.extErrorStr,
            sizeErrorStr: uploadConfig.sizeErrorStr,
            onSuccess: function(instance, panel, files, data, xhr) {
                if (instance.fileCounter > 0) {
                    instance.fileCounter--;
                }

                _this.displayData(data, uploadConfig.mediaUrl, _this.media_data.find('#media_content .media-content-body'));
            }
        });

        //right click on item
        _this.media_data.on('contextmenu', '.media-item', function(evt) {
            _this.showMenuMedia($(this), evt);
        });

        //when click on item
        _this.media_data.on('click', '.media-item', function(evt) {
            _this.showNavControlMedia($(this), evt);
        });

        $(document).click(function(e) {
            var media_menu = $('.media-menu');
            //var media_nav = $('.media-nav');
            var $clicked = $(e.target);

            // Hide when clicked outside of this element
            if (!$clicked.is('.media-menu') && media_menu.is(':visible')) {
                media_menu.hide();
            }
            /*if (!$clicked.is('.media-nav') && media_nav.is(':visible')) {
             media_nav.hide();
             }*/
        });
    },
    createModal: function() {
        var _this = this;

        _this.media_data.parents('.panel-body').css('position', 'relative');
        _this.media_overlay = $('<div class="media-overlay"></div>')
            .appendTo(_this.media_data.parents('.panel-body'))
            .click(function() {
                $(this).remove();
                _this.media_modal.remove();
            });
        _this.media_modal = $('<div class="media-modal pr15"></div>').appendTo(_this.media_data.parents('.panel-body'));
    },
    closeModal: function (callback) {
        this.media_overlay.remove();
        this.media_modal.remove();

        if ($.isFunction(callback)) {
            callback();
        }
    },
    loadMedia: function(form) {
        var _this = this;

        if (typeof (form) != 'string') {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: $(form).serialize(),
                beforeSend: function () {
                    _this.media_data.find('#media_content').html('<i class="fa ' + _this.settings.class_loading + '"></i>');
                },
                success: function (response) {
                    _this.media_data.find('#media_content').html(response);

                    //click paging
                    Backend.initPagingAjax(function(obj, link) {
                        _this.loadMedia(link);
                    });
                },
                error: function () {
                    _this.media_data.find('#media_content').html('');
                }
            });
        } else {
            $.ajax({
                url: form,
                method: 'get',
                beforeSend: function () {
                    _this.media_data.find('#media_content').html('<i class="fa ' + _this.settings.class_loading + '"></i>');
                },
                success: function (response) {
                    _this.media_data.find('#media_content').html(response);

                    //click paging
                    Backend.initPagingAjax(function(obj, link) {
                        _this.loadMedia(link);
                    });
                },
                error: function () {
                    _this.media_data.find('#media_content').html('');
                }
            });
        }
    },
    showMenuMedia: function(obj, evt) {
        evt.preventDefault();
        this.media_id = obj.data('id');

        var _this = this;
        var ul = $('ul.media-menu');

        if (ul.size() <= 0) {
            ul = $('<ul class="media-menu bdr-c2 r05"></ul>').appendTo($('body')[0]);
        }
        ul.css({ top: evt.pageY, left: evt.pageX });

        $.ajax({
            url: _this.settings.link_menu.replace('/0', '/' + _this.media_id),
            success: function(response) {
                ul.html(response).slideDown(function() {
                    ul.find('a')
                        .off('click')
                        .on('click', function(evt) {
                            evt.preventDefault();
                            _this.doActionMedia($(this), $(this).data('action'));
                        });
                });
            },
            error: function () {
                ul.remove();
            }
        });
    },
    showNavControlMedia: function(obj, evt) {
        evt.preventDefault();
        this.media_id = obj.data('id');

        var _this = this;

        //if can select multi
        if (obj.hasClass('selected')) {
            obj.removeClass('selected');
        } else {
            if (_this.settings.multi == 1) {
                obj.addClass('selected');
            } else {
                _this.media_data.find('.media-item').removeClass('selected');
                obj.addClass('selected');
            }
        }

        var nav = _this.media_data.find('div.media-nav');
        if (obj.hasClass('selected')) {
            if (nav.size() <= 0) {
                nav = $('<div class="media-nav mb10 mt10"></div>').insertBefore(_this.media_data.find('#media_content'));
            }
            var btnSelect = '<a href="#" class="btn btn-sm btn-info" role="button" data-action="select"><i class="fa fa-check"></i> Chọn</a>';

            if (_this.media_data.find('.media-item.selected').size() <= 1) {
                $.ajax({
                    url: _this.settings.link_menu.replace('/0', '/' + _this.media_id),
                    beforeSend: function() {
                        nav.html('<i class="fa fa-2x ' + _this.settings.class_loading + '"></i>');
                    },
                    data: {
                        type: 'nav'
                    },
                    success: function(response) {
                        nav.html(response);

                        if (_this.settings.modal == 1) {
                            nav.append(btnSelect);
                        }

                        nav.find('a')
                            .off('click')
                            .on('click', function(evt) {
                                evt.preventDefault();
                                _this.doActionMedia($(this), $(this).data('action'));
                            });
                    },
                    error: function () {
                        nav.remove();
                    }
                });
            } else {
                if (_this.settings.modal == 1) {
                    nav.html(btnSelect).find('a')
                        .off('click')
                        .on('click', function(evt) {
                            evt.preventDefault();
                            _this.doActionMedia($(this), $(this).data('action'));
                        });
                }
            }
        } else {
            if (_this.media_data.find('.media-item.selected').size() <= 0) {
                nav.remove();
            } else if (_this.media_data.find('.media-item.selected').size() == 1) {
                _this.media_data.find('.media-item.selected').removeClass('selected').trigger('click');
            }
        }
    },
    doActionMedia: function(obj, action) {
        var _this = this;

        switch (action) {
            case 'edit':
                _this.createModal();
                _this.media_modal.css({ 'left': 'auto', 'right': 0, 'top': 0, 'padding': '15px', 'background-color': '#fff' });

                $.ajax({
                    url: obj.data('link'),
                    beforeSend: function() {
                        _this.media_modal.html('<i class="fa ' + _this.settings.class_loading + '"></i>');
                    },
                    success: function(data) {
                        _this.media_modal.html(data);
                        $('<a href="#" style="position: absolute; top: -2px; right: 4px; z-index: 999;"><i class="fa fa-close fa-2x"></i></a>').prependTo(_this.media_modal).on('click', function(evt) {
                            evt.preventDefault();
                            _this.closeModal();
                        });
                        Backend.multiSelect();

                        // click button delete
                        _this.media_modal.find('[data-action="delete"]').off().on('click', function(evt) {
                            evt.preventDefault();

                            var aself = $(this);
                            var old_text = aself.html();
                            var message = aself.data('message');
                            var link = aself.data('link') ? aself.data('link') : aself.attr('href');

                            bootbox.confirm(message, function(result) {
                                if (result) {
                                    $.ajax({
                                        url: link,
                                        method: 'delete',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            aself.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                                        },
                                        success: function(response) {
                                            if (response.error == 0) {
                                                _this.closeModal(function() {
                                                    //_this.media_data.find('#media_content').find('[data-id="' + aself.data('id') + '"]').parent().remove();
                                                    _this.loadMedia($('#frmSearch', _this.media_form));
                                                });
                                            } else {
                                                aself.prop('disabled', false).html(old_text);
                                            }

                                            Backend.showMessage(response.message, {
                                                messageId: _this.media_message,
                                                className: 'alert-' + (response.error == 0 ? 'info' : 'danger')
                                            });
                                        },
                                        error: function () {
                                            aself.prop('disabled', false).html(old_text);
                                        }
                                    });
                                }
                            });
                        });

                        // click button update label
                        _this.media_modal.find('button.btn-updatelabel').on('click', function(evt) {
                            evt.preventDefault();

                            var aself = $(this);
                            var old_text = aself.html();

                            $.ajax({
                                url: aself.data('link'),
                                method: 'put',
                                dataType: 'json',
                                data: {
                                    media_label: _this.media_modal.find('#media_label').val()
                                },
                                beforeSend: function () {
                                    aself.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                                },
                                success: function(response) {
                                    aself.prop('disabled', false).html(old_text);

                                    Backend.showMessage(response.message, {
                                        messageId: _this.media_message,
                                        className: 'alert-' + (response.error == 0 ? 'info' : 'danger')
                                    });
                                },
                                error: function () {
                                    aself.prop('disabled', false).html(old_text);
                                }
                            });
                        });
                    }
                });
                break;
            case 'delete':
                var item = _this.media_data.find('.media-item[data-id="' + _this.media_id + '"]');
                if (typeof (item.attr('deleting')) == 'undefined' || item.attr('deleting') == 0) {
                    bootbox.confirm(obj.data('message'), function(result) {
                        if (result) {
                            $.ajax({
                                url: obj.data('link'),
                                method: 'delete',
                                dataType: 'json',
                                beforeSend: function() {
                                    item.attr('deleting', 1);
                                },
                                success: function(response) {
                                    item.attr('deleting', 0);

                                    if (response.error == 0) {
                                        item.parent().remove();

                                        if (item.hasClass('selected')) {
                                            _this.media_data.find('.media-nav').remove();
                                        }

                                        _this.loadMedia($('#frmSearch', _this.media_form));
                                    }

                                    Backend.showMessage(response.message, {
                                        messageId: _this.media_message,
                                        className: 'alert-' + (response.error == 0 ? 'info' : 'danger')
                                    });
                                },
                                error: function(response) {
                                    item.attr('deleting', 0);
                                    Backend.showMessage('Unknown error!', {
                                        messageId: _this.media_message,
                                        timeout: 0
                                    });
                                }
                            });
                        }
                    });
                }
                break;
            case 'download':
                window.location.href = obj.data('link');
                break;
            case 'select':
            default:
                _this.selectMedia();
                break;
        }
    },
    selectMedia: function() {
        var arrSelected = this.media_data.find('.media-item.selected');
        var source = this.settings.source;

        switch (source) {
            case 'thumbnail_url':
            case 'thumbnail_url2':
                $('#' + source).val(arrSelected.data('filename'));
                $('img[data-for="' + source + '"]').attr('src', arrSelected.data('filename'));

                //close modal;
                bootbox.hideAll();
                break;
            case 'album':
                var arrId = $('#article_photo').val().blank() ? [] : $('#article_photo').val().split(',');
                var arrThumb = $('#article_photo_thumb').val().blank() ? [] : $('#article_photo_thumb').val().split(',');

                $.each(arrSelected, function(i, o) {
                    var id = $(o).data('id').toString();
                    var src = $(o).data('filename').replace('/original/', '/medium/');

                    if (!arrId.in_array(id)) {
                        arrId.push(id);
                        arrThumb.push(src);

                        $('#photo_panel').append('<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group" style="position: relative;">'
                            + '<div class="row">'
                                + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">'
                                    + '<img src="' + src + '" class="img-responsive">'
                                + '</div>'
                                + '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">'
                                    + '<textarea class="form-control" rows="4" id="media_description_' + id + '" name="media_description[' + id + ']"></textarea>'
                                + '</div>'
                                + '<button type="button" class="btn btn-sm btn-primary r0" data-id="' + id + '" data-filename="' + src + '" style="position: absolute; left: 15px; top: 0;"><i class="fa fa-close"></i></button>'
                            + '</div>'
                        + '</div>');

                        Backend.loadCKEditor('media_description_' + id, {
                            width: '99%',
                            height: '90px',
                            toolbarName: 'Description'
                        });
                    } else {
                        $('#photo_panel').find('button[data-id="' + id + '"]').parent().addClass('bg-danger');
                    }
                });

                $('#photo_panel img').draggable({
                    revert: true,
                    helper: function () {
                        return $(this).clone().width('100px');
                    },
                    zIndex: 99999,
                    cursor: 'move',
                    containment: 'document'
                });

                $('#article_photo').val(arrId.join(','));
                $('#article_photo_thumb').val(arrThumb.join(','));
                window.scrollTo(0, $('#photo_panel').position().top);

                //close modal;
                bootbox.hideAll();
                break;
            case 'infographic':
                $('#article_photo').val(arrSelected.data('id'));
                $('#article_photo_thumb').val(arrSelected.data('filename'));

                if ($('#photo_panel img').size() <= 0) {
                    $('#photo_panel').append('<img src="' + arrSelected.data('filename') + '" class="img-responsive">');
                } else {
                    $('#photo_panel img').attr('src', arrSelected.data('filename'));
                }

                $('#photo_panel img').draggable({
                    revert: true,
                    helper: function () {
                        return $(this).clone().width('100px');
                    },
                    zIndex: 99999,
                    cursor: 'move',
                    containment: 'document'
                });

                window.scrollTo(0, $('#photo_panel').position().top);

                //close modal;
                bootbox.hideAll();
                break;
            case 'ckeditor':
                var fileURL = arrSelected.data('filename');
                this.CKEditor.object.tools.callFunction(this.CKEditor.funcNum, fileURL, '');

                //close modal;
                window.close();
                break;
            case 'ckeditor_plugin':
                var editor = CKEDITOR.instances[this.settings.editor_name];
                var wrap = $('<div></div>');

                $.each(arrSelected, function(i, o) {
                    var elementImg = editor.document.createElement('img');
                    elementImg.$.setAttribute('src', $(o).data('filename'));
                    elementImg.$.setAttribute('class', 'img-responsive');

                    var imag_wrap = $('<div class="img-wrap"><div class="img-thumb"></div><div class="img-caption">[Caption]</div></div>');
                    imag_wrap.find('div:first').append($(elementImg.$));

                    wrap.append(imag_wrap);
                });

                editor.insertHtml(wrap.html(), 'unfiltered_html');

                //close modal;
                bootbox.hideAll();
                break;
            case 'slide':
                Slide.displayData(arrSelected);

                //close modal;
                bootbox.hideAll();
                break;
            default:
                //close modal;
                bootbox.hideAll();
                break;
        }
    },
    displayData: function(data, mediaUrl, panel) {
        if (data.info.media_type == 1) {
            var div = $('<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 pl05 pr05" title="' + data.info.media_title + '">'
                + '<div class="thumbnail media-item" data-id="' + data.info.media_id + '" data-filename="' + mediaUrl + '/' + data.info.media_filename + '" data-title="' + data.info.media_title + '">'
                    + '<img src="' + mediaUrl + '/' + data.info.media_filename + '" class="wp100" alt="' + data.info.media_title + '">'
                + '</div>'
            + '</div>').prependTo(panel);
        } else {
            var div = $('<div class="media-item clearfix pb05 pt05" data-id="' + data.info.media_id + '" data-filename="' + mediaUrl + '/' + data.info.media_filename + '" data-title="' + data.info.media_title + '">'
                + '<div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 filename" title="' + data.info.media_title + '">'
                    + '<i class="fa fa-file-pdf-o"></i>'
                    + '<span class="ml05">' + data.info.media_title + '</span>'
                + '</div>'
                + '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"></div>'
                + '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-right"></div>'
            + '</div>').prependTo(panel);
        }
    },
    validateVideo: function(messages) {
        $('#frmMediaVideo').validate({
            rules: {
                media_filename: {
                    required: true,
                    maxlength: 500
                },
                media_source: {
                    required: true
                }
            },
            messages: {
                media_filename: {
                    required: messages.media_filename.required,
                    maxlength: messages.media_filename.maxlength.replace(':max', 500)
                },
                media_source: {
                    required: messages.media_source.required
                }
            }
        });

        // get video info when input link
        $('#frmMediaVideo').on('blur', '#media_filename', function() {
            var _this = $(this);
            if (!_this.val().blank()) {
                $.ajax({
                    url: _this.data('link'),
                    data: {
                        filename: _this.val(),
                        source: $('#media_source').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        _this.val(response.linkembed);
                        if ($('#previewVideo').find('iframe').size() < 1) {
                            $('#previewVideo').append('<iframe class="embed-responsive-item" src="' + response.linkembed + '"></iframe>');
                        } else {
                            $('#previewVideo').find('iframe').attr('src', response.linkembed);
                        }
                    }
                });
            }
        });
        $('#frmMediaVideo').on('click', '.btn-preview', function () {
            $('#frmMediaVideo #media_filename').trigger('blur');
        });
    }
};

var Config = {
    validate: function(messages) {
        $('#frmConfig').validate({
            rules: {
                field_name: {
                    required: true,
                    maxlength: 50
                }
            },
            messages: {
                field_name: {
                    required: messages.field_name.required,
                    maxlength: messages.field_name.maxlength.replace(':max', 50)
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                    },
                    success: function (response) {
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                        $('.sidebar-option .icon-close').trigger('click');

                        Backend.showMessage(response.message, {
                            className: 'alert-info',
                            callback: function () {
                                window.location.reload(true);
                            }
                        });
                    },
                    error: function (response) {
                        var errors = response.responseJSON;
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');

                        Backend.showError(errors);
                    }
                });
            }
        });

        $.each(this.arrLanguage, function(code, data) {
            $('#frmConfig #field_value_' + code).rules('add', {
                required: true,
                maxlength: 1000,
                messages: {
                    required: messages.field_value.required,
                    maxlength: messages.field_value.maxlength.replace(':max', 1000)
                }
            });
        });
    }
};

var Translate = {
    validate: function(messages) {
        $('#frmTranslate').validate({
            rules: {
                translate_code: {
                    required: true,
                    maxlength: 50
                },
                translate_mode: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                translate_code: {
                    required: messages.translate_code.required,
                    maxlength: messages.translate_code.maxlength.replace(':max', 50)
                },
                translate_mode: {
                    required: messages.translate_mode.required
                },
                status: {
                    required: messages.status.required
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                    },
                    success: function (response) {
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                        $('.sidebar-option .icon-close').trigger('click');

                        Backend.showMessage(response.message, {
                            className: 'alert-info',
                            callback: function () {
                                window.location.reload(true);
                            }
                        });
                    },
                    error: function (response) {
                        var errors = response.responseJSON;
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');

                        Backend.showError(errors);
                    }
                });
            }
        });

        $.each(this.arrLanguage, function(code, data) {
            $('#frmTranslate #translate_content_' + code).rules('add', {
                required: true,
                maxlength: 5000,
                messages: {
                    required: messages.translate_content.required,
                    maxlength: messages.translate_content.maxlength.replace(':max', 5000)
                }
            });
        });

        //check current translate mode
        var _this = this;
        if ($('#translate_mode').val() == 'editor') {
            $.each(_this.arrLanguage, function(code, data) {
                Backend.loadCKEditor('translate_content_' + code, $('#translate_content_' + code).data('editor'));
            });
        }

        //switch translate mode
        $('#translate_mode').on('change', function() {
            if ($(this).val() == 'text') {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                    CKEDITOR.instances[instance].destroy();

                    $('#' + instance).val($('#' + instance).val().stripTags());
                }
            } else {
                $.each(_this.arrLanguage, function(code, data) {
                    Backend.loadCKEditor('translate_content_' + code, $('#translate_content_' + code).data('editor'));
                });
            }
        });
    }
};

var Crawler = {
    parseLink: function(messages, callback) {
        $('#frmParseLink').validate({
            rules: {
                link: {
                    required: true
                }
            },
            messages: {
                link: {
                    required: messages.link.required
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $(form).find('button:submit').prop('disabled', true).prepend('<i class="fa fa-spinner fa-spin"></i> ');
                    },
                    success: function (response) {
                        $(form).find('button:submit').prop('disabled', false).find('.fa').remove();
                        $('.sidebar-option .icon-close').trigger('click');

                        if (response.error == 0 && $.isFunction(callback)) {
                            callback(response.data);
                        }
                    },
                    error: function (response) {
                        var errors = response.responseJSON;
                        $(form).find('button:submit').prop('disabled', false).find('.fa').remove();

                        Backend.showError(errors);
                    }
                });
            }
        });
    }
};

var Category = {
    settings: {},
    languageSelector: function () {
        var _this = this;

        if ($('select#language_id').is(':disabled')) {
            $('<input type="hidden" name="language_id" value="' + $('select#language_id').val() + '">').insertAfter($('select#language_id'));
            $('select#language_id').removeAttr('name');
        }

        $('select#language_id').on('change', function () {
            var self = $(this);

            if (_this.settings.default_language == self.val()) {
                $('#category_source').prop('disabled', true);
                $('#category_source').prev().removeClass('required');
            } else {
                $('#category_source').prop('disabled', false);
                $('#category_source').prev().addClass('required');
            }

            //load cateparent
            $.ajax({
                url: self.data('linkcategory'),
                data: {
                    language_id: self.val(),
                    selected: $('#cateparent_id').data('id')
                },
                success: function (data) {
                    $('#cateparent_id').html(data);
                }
            });

            //load category source
            $.ajax({
                url: self.data('linksource'),
                data: {
                    source: 'category',
                    language_id: self.val(),
                    selected: $('#category_source').data('id')
                },
                success: function (data) {
                    $('#category_source').html(data);
                }
            });
        }).trigger('change');
    },
    validate: function() {
        var _this = this;

        $('#frmCategory').validate({
            rules: {
                category_title: {
                    required: true,
                    maxlength: 200
                },
                category_code: {
                    required: true,
                    code: true,
                    maxlength: 200
                },
                cateparent_id: {
                    required: true
                },
                language_id: {
                    required: true
                },
                status: {
                    required: true
                },
                category_source: {
                    required: function () {
                        return _this.settings.default_language != $('#language_id').val();
                    }
                },
                category_icon: {
                    maxlength: 50
                },
                category_order: {
                    number: true
                }
            },
            messages: {
                category_title: {
                    required: _this.settings.messages.category_title.required,
                    maxlength: _this.settings.messages.category_title.maxlength.replace(':max', 200)
                },
                category_code: {
                    required: _this.settings.messages.category_code.required,
                    code: _this.settings.messages.category_code.code,
                    maxlength: _this.settings.messages.category_code.maxlength.replace(':max', 200)
                },
                cateparent_id: {
                    required: _this.settings.messages.cateparent_id.required
                },
                language_id: {
                    required: _this.settings.messages.language_id.required
                },
                category_source: {
                    required: _this.settings.messages.category_source.required
                },
                status: {
                    required: _this.settings.messages.status.required
                },
                category_icon: {
                    maxlength: _this.settings.messages.category_icon.maxlength
                },
                category_order: {
                    number: _this.settings.messages.category_order.number
                }
            }
        });
    },
    init: function(options) {
        this.settings = $.extend(this.settings, options || {});

        this.languageSelector();
        this.validate();
    }
};

var Topic = {
    settings: {},
    arrArticle: [],
    languageSelector: function () {
        var _this = this;

        if ($('select#language_id').is(':disabled')) {
            $('<input type="hidden" name="language_id" value="' + $('select#language_id').val() + '">').insertAfter($('select#language_id'));
            $('select#language_id').removeAttr('name');
        }

        $('select#language_id').on('change', function () {
            var self = $(this);

            if (_this.settings.default_language == self.val()) {
                $('#topic_source').prop('disabled', true);
                $('#topic_source').prev().removeClass('required');
            } else {
                $('#topic_source').prop('disabled', false);
                $('#topic_source').prev().addClass('required');
            }

            //load category
            $.ajax({
                url: self.data('linkcategory'),
                data: {
                    language_id: self.val(),
                    selected: $('#category_id').data('id'),
                    type: 'list'
                },
                success: function(data) {
                    $('#category_id').html(data);
                }
            });

            //load category source
            $.ajax({
                url: self.data('linksource'),
                data: {
                    source: 'category',
                    language_id: self.val(),
                    selected: $('#topic_source').data('id')
                },
                success: function(data) {
                    $('#topic_source').html(data);
                }
            });
        }).trigger('change');
    },
    validate: function() {
        var _this = this;

        $('#frmTopic').validate({
            rules: {
                topic_title: {
                    required: true,
                    maxlength: 200
                },
                category_id: {
                    required: true
                },
                language_id: {
                    required: true
                },
                topic_source: {
                    required: function () {
                        return _this.settings.default_language != $('#language_id').val();
                    }
                },
                status: {
                    required: true
                }
            },
            messages: {
                topic_title: {
                    required: _this.settings.messages.topic_title.required,
                    maxlength: _this.settings.messages.topic_title.maxlength.replace(':max', 200)
                },
                category_id: {
                    required: _this.settings.messages.category_id.required
                },
                language_id: {
                    required: _this.settings.messages.language_id.required
                },
                topic_source: {
                    required: _this.settings.messages.topic_source.required
                },
                status: {
                    required: _this.settings.messages.status.required
                }
            }
        });
    },
    initArticle: function() {
        var _this = this;

        Backend.initDate('#date_from', '#date_to');

        //click button search
        $('#frmListArticle').on('submit', function(evt) {
            evt.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function(data) {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    $('.sidebar-body').html(data);
                },
                error: function() {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                }
            });
        });

        //click paging
        Backend.initPagingAjax(function(obj, link) {
            var form = $('#frmListArticle');

            $.ajax({
                url: link,
                method: form.attr('method'),
                beforeSend: function() {
                    $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function(data) {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    $('.sidebar-body').html(data);
                },
                error: function() {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                }
            });
        });

        //click checkbox
        $('#panelListArticle table tbody input:checkbox').each(function() {
            if (_this.arrArticle.in_array($(this).val().toString())) {
                $(this).prop('checked', true);
            }

            $(this).on('click', function() {
                var id = $(this).val();

                if ($(this).is(':checked')) {
                    _this.arrArticle.push(id.toString());
                    $(this)
                        .parents('tr')
                        .clone()
                        .appendTo($('#tblArticleTopic tbody'))
                        .attr('data-id', id)
                        .find('td:first').html('<button type="button" class="btn btn-link btn-xs" data-id="' + id + '"><i class="glyphicon glyphicon-remove"></i></button>');
                } else {
                    _this.arrArticle.splice(_this.arrArticle.indexOf(id.toString()), 1);
                    $('#tblArticleTopic tr[data-id="' + id + '"]').remove();
                }

                $('#article_topic').val(_this.arrArticle.join(','));
            });
        });
    },
    init: function(options) {
        var _this = this;
        _this.settings = $.extend(_this.settings, options || {});

        this.languageSelector();
        this.validate();

        //get list article reference
        _this.arrArticle = $('#article_topic').val().blank() ? [] : $('#article_topic').val().split(',');

        //click button delete article
        $('#tblArticleTopic').on('click', 'button', function() {
            $(this).parents('tr').remove();

            _this.arrArticle.splice(_this.arrArticle.indexOf($(this).data('id').toString()), 1);
            $('#article_topic').val(_this.arrArticle.join(','));
        });
    }
};

var Article = {
    hasData: false,
    settings: {},
    arrReference: [],
    arrTopic: [],
    languageSelector: function () {
        var _this = this;

        if ($('select#language_id').is(':disabled')) {
            $('<input type="hidden" name="language_id" value="' + $('select#language_id').val() + '">').insertAfter($('select#language_id'));
            $('select#language_id').removeAttr('name');
        }

        $('select#language_id').on('change', function () {
            var self = $(this);

            $('select[data-searchajax="true"]').data('url', Backend.addUrlParam($('select[data-searchajax="true"]').data('url'), 'l', self.val()));
            Backend.multiSelect();

            if (_this.settings.default_language == self.val()) {
                $('#article_source').prop('disabled', true);
                $('#article_source').prev().removeClass('required');
            } else {
                $('#article_source').prop('disabled', false);
                $('#article_source').prev().addClass('required');
            }

            //load category
            $.ajax({
                url: self.data('linkcategory'),
                data: {
                    language_id: self.val(),
                    selected: $('[data-for="category_id"]').data('id'),
                    liston: $('[data-for="category_id"]').data('liston')
                },
                success: function(data) {
                    $('[data-for="category_id"]').html(data);
                }
            });

            //load category source
            $.ajax({
                url: self.data('linksource'),
                data: {
                    source: 'article',
                    language_id: self.val(),
                    type: $('#article_source').data('type'),
                    selected: $('#article_source').data('id')
                },
                success: function(data) {
                    $('#article_source').html(data);
                }
            });
        }).trigger('change');
    },
    setOnCategory: function(obj) {
        var parent = $(obj).parent().parent();
        var input = $('#' + $(parent).data('for'));
        if (!$(obj).hasClass('seton')) {
            $(parent).find('span').removeClass('seton');
            $(obj).addClass('seton');
            $(obj).prev().prop('checked', true);
            $(input).val($(obj).prev().val());
        } else {
            $(obj).removeClass('seton');
            $(input).val('');
        }
    },
    listOnCategory: function(obj) {
        var parent = $(obj).parent().parent();
        var input = $('#' + $(parent).data('for'));
        if (!$(obj).prop('checked')) {
            if ($(obj).next().hasClass('seton')) {
                $(input).val('');
            }
            $(obj).next().removeClass('seton');
        }
    },
    validate: function() {
        var _this = this;

        $('#frmArticle').validate({
            rules: {
                article_title: {
                    required: true,
                    maxlength: 200
                },
                article_code: {
                    required: true,
                    code: true,
                    maxlength: 200
                },
                article_description: {
                    required: true,
                    maxlength: 1000
                },
                article_content: {
                    required: true
                },
                language_id: {
                    required: true
                },
                category_id: {
                    required: true
                },
                article_source: {
                    required: function() {
                        return _this.settings.default_language != $('#language_id').val();
                    }
                },
                status: {
                    required: true
                }
            },
            messages: {
                article_title: {
                    required: _this.settings.messages.article_title.required,
                    maxlength: _this.settings.messages.article_title.maxlength.replace(':max', 200)
                },
                article_code: {
                    required: _this.settings.messages.article_code.required,
                    code: _this.settings.messages.article_code.code,
                    maxlength: _this.settings.messages.article_code.maxlength.replace(':max', 200)
                },
                article_description: {
                    required: _this.settings.messages.article_description.required,
                    maxlength: _this.settings.messages.article_description.maxlength.replace(':max', 1000)
                },
                article_content: {
                    required: _this.settings.messages.article_content.required
                },
                language_id: {
                    required: _this.settings.messages.language_id.required
                },
                category_id: {
                    required: _this.settings.messages.category_id.required
                },
                article_source: {
                    required: _this.settings.messages.article_source.required
                },
                status: {
                    required: _this.settings.messages.status.required
                }
            }
        });
    },
    delThumb: function(thumbName) {
        $('#' + thumbName).val('');
        $('img[data-for="' + thumbName + '"]').attr('src', Common.url.static.backend.images + '/noimage_' + thumbName + '.jpg');
    },
    initReference: function() {
        var _this = this;

        //click button search
        $('#frmArticleReference').on('submit', function(evt) {
            evt.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function(data) {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    $('.sidebar-body').html(data);
                },
                error: function() {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                }
            });
        });

        //click checkbox
        $('#panelArticleReference table tbody input:checkbox').each(function() {
            if (_this.arrReference.in_array($(this).val().toString())) {
                $(this).prop('checked', true);
            }

            $(this).on('click', function() {
                if ($(this).is(':checked')) {
                    _this.arrReference.push($(this).val().toString());
                } else {
                    _this.arrReference.splice(_this.arrReference.indexOf($(this).val().toString()), 1);
                }

                $('#article_reference').val(_this.arrReference.join(','));
            });
        });
    },
    initTopic: function() {
        var _this = this;

        //click button search
        $('#frmArticleTopic').on('submit', function(evt) {
            evt.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                },
                success: function(data) {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                    $('.sidebar-body').html(data);
                },
                error: function() {
                    $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                }
            });
        });

        //click checkbox
        $('#panelArticleTopic table tbody input:checkbox').each(function() {
            if (_this.arrTopic.in_array($(this).val().toString())) {
                $(this).prop('checked', true);
            }

            $(this).on('click', function() {
                if ($(this).is(':checked')) {
                    _this.arrTopic.push($(this).val().toString());
                } else {
                    _this.arrTopic.splice(_this.arrTopic.indexOf($(this).val().toString()), 1);
                }

                $('#article_topic').val(_this.arrTopic.join(','));
            });
        });
    },
    init: function(settings) {
        var _this = this;
        _this.settings = $.extend(_this.settings, settings || {});

        Backend.createCode();
        Backend.multiSelect();
        _this.languageSelector();

        $('.list-folder').on('click', 'input:checkbox', function() {
            _this.listOnCategory($(this));
        });
        $('.list-folder').on('click', 'span', function() {
            _this.setOnCategory($(this));
        });
        if (!$('[data-for="category_id"]').html().blank() && $('span.seton').size() > 0) {
            $('[data-for="category_id"]').scrollTop($('span.seton').position().top - 49);
        }

        _this.validate();

        //get list article reference
        if ($('#article_reference').size() > 0) {
            _this.arrReference = $('#article_reference').val().blank() ? [] : $('#article_reference').val().split(',');
        }

        //get list article topic
        if ($('#article_topic').size() > 0) {
            _this.arrTopic = $('#article_topic').val().blank() ? [] : $('#article_topic').val().split(',');
        }
    }
};

var Buildtop = {
    init: function() {
        var _this = this;

        _this.getListSearch();

        // click search
        $('#frmSearch').on('submit', function(evt) {
            evt.preventDefault();

            _this.getListBuildtop();
            _this.getListSearch();
        });

        $('#tblListBuildtop tbody').sortable({
            cursor: 'move',
            forceHelperSize: true
        }).disableSelection();

        // click button add
        $('#btnAdd').on('click', function() {
            var $table = $('#tblListBuildtop tbody'), sHTML = '', arrTypeId = _this.getTypeId();

            $.each($('#tblListSearch tbody input:checkbox:checked'), function(i, o) {
                if (!arrTypeId.in_array(parseInt($(o).val()))) {
                    sHTML = '<tr data-id="new_' + $(o).val() + '">'
                        + '<td class="text-center">'
                            + '<input type="checkbox"  class="checkbox" data-for="chkAll" value="new_' + $(o).val() + '" data-type_id="' + $(o).val() + '" />'
                        + '</td>'
                        + '<td class="text-center order"></td>'
                        + '<td>' + $(o).data('title') + '</td>'
                        + '<td class="text-center"></td>'
                    + '</tr>';

                    //remove and append data
                    $table.find('.nodata').remove();
                    $table.prepend(sHTML);
                } else {
                    $(':checkbox[data-type_id="' + $(o).val() + '"]').parents('tr').addClass('bg-warning');
                    setTimeout(function() {
                        $(':checkbox[data-type_id="' + $(o).val() + '"]').parents('tr').removeClass('bg-warning');
                    }, 1000);
                }

                $(o).attr('checked', false);
            });

            $('#tblListSearch input#chkAll').prop('checked', false);
            _this.reSortTable();
        });

        // click button save
        $('#btnSave').click(function() {
            var self = $(this), arrHotId = _this.getHotId();

            if (!arrHotId.blank()) {
                $.ajax({
                    url: self.data('link'),
                    method: 'post',
                    data: $.extend($('#frmSearch').serializeHash(), { id: arrHotId }),
                    dataType: 'json',
                    beforeSend: function() {
                        self.prop('disabled', true).find('.fa').removeClass('fa-save').addClass('fa-spin fa-spinner');
                    },
                    success: function(response) {
                        self.prop('disabled', false).find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-save');

                        Backend.showMessage(response.message, {
                            className: response.error == 1 ? 'alert-danger' : 'alert-info'
                        });

                        $('#tblListBuildtop input#chkAll').prop('checked', false);
                        _this.getListBuildtop();
                    },
                    error: function(response) {
                        $('#tblListBuildtop input#chkAll').prop('checked', false);
                        self.prop('disabled', false).find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-save');
                    }
                });
            }
        });

        // click button delete
        $('#btnDelete').on('click', function() {
            var self = $(this), arrUnsetId = [];

            $('#tblListBuildtop tbody input:checkbox').each(function() {
                if ($(this).is(':checked')) {
                    arrUnsetId.push($(this).val());
                }
            });

            if (!arrUnsetId.blank()) {
                $.ajax({
                    url: self.data('link'),
                    method: 'delete',
                    data: {
                        id: arrUnsetId
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        self.prop('disabled', true).find('.fa').removeClass('fa-trash').addClass('fa-spin fa-spinner');
                    },
                    success: function(response) {
                        self.prop('disabled', false).find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-trash');

                        Backend.showMessage(response.message, {
                            className: response.error == 1 ? 'alert-danger' : 'alert-info'
                        });

                        $('#tblListBuildtop input#chkAll').prop('checked', false);
                        _this.getListBuildtop();
                    },
                    error: function(response) {
                        $('#tblListBuildtop input#chkAll').prop('checked', false);
                        self.prop('disabled', false).find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-trash');
                    }
                });
            }
        });
    },
    getListBuildtop: function () {
        $.ajax({
            url: $('#tblListBuildtop').data('link'),
            method: 'get',
            data: $('#frmSearch').serialize(),
            beforeSend: function() {
                $('#tblListBuildtop tbody').html('<tr><td colspan="' + $('#tblListBuildtop thead th').size() + '" class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></td></tr>');
            },
            success: function(response) {
                $('#tblListBuildtop tbody').html(response).sortable({
                    cursor: 'move',
                    forceHelperSize: true
                }).disableSelection();
            }
        });
    },
    getListSearch: function () {
        $.ajax({
            url: $('#tblListSearch').data('link'),
            method: 'get',
            data: $('#frmSearch').serialize(),
            beforeSend: function() {
                $('#tblListSearch tbody').html('<tr><td colspan="' + $('#tblListSearch thead th').size() + '" class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></td></tr>');
            },
            success: function(response) {
                $('#tblListSearch tbody').html(response);
            }
        });
    },
    getTypeId: function() {
        var arrTypeId = [];

        $.each($('#tblListBuildtop tbody tr:not(".nodata")'), function(i, o) {
            arrTypeId.push($(o).find('input:checkbox').data('type_id'));
        });

        return arrTypeId;
    },
    getHotId: function() {
        var arrHotId = [];

        $.each($('#tblListBuildtop tbody tr:not(".nodata")'), function(i, o) {
            arrHotId.push($(o).find('input:checkbox').val());
        });

        return arrHotId;
    },
    reSortTable: function(arrId) {
        var $table = $('#tblListBuildtop tbody');

        $.each($table.find('tr'), function(i, o) {
            if (typeof(arrId) !== 'undefined') {
                $(o)
                    .attr('data-id', arrId[i])
                    .find('input:checkbox').val(arrId[i]);
            }
            $(o).find('.order').html(i + 1);
        });
    }
};

var Slide = {
    messages: {},
    tmp_html: '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="position: relative;">'
        + '<div class="row">'
            + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">'
                + '<img src="{SRC}" class="img-responsive">'
                + '<input type="hidden" class="form-control" id="slide_image_{ID}" name="slide_image[{ID}]" value="{SRC}">'
            + '</div>'
            + '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">'
                + '<div class="form-group">'
                    + '<input type="text" class="form-control" id="slide_title_{ID}" name="slide_title[{ID}]" placeholder="Title">'
                + '</div>'
                + '<div class="form-group">'
                    + '<textarea class="form-control" rows="3" id="slide_description_{ID}" name="slide_description[{ID}]" placeholder="Description"></textarea>'
                + '</div>'
                + '<div class="row">'
                    + '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 form-group">'
                        + '<input type="text" class="form-control mb05" id="slide_link_{ID}" name="slide_link[{ID}]" placeholder="Link">'
                    + '</div>'
                    + '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">'
                        + '<select class="form-control" id="slide_target_{ID}" name="slide_target[{ID}]">'
                            + '<option value="_blank">Blank</option>'
                            + '<option value="_parent">Parent</option>'
                            + '<option value="_self">Self</option>'
                            + '<option value="_top">Top</option>'
                        + '</select>'
                    + '</div>'
                + '</div>'
            + '</div>'
            + '<button type="button" class="btn btn-sm btn-primary r0" style="position: absolute; left: 15px; top: 0;"><i class="fa fa-close"></i></button>'
        + '</div>'
    + '</div>',
    init: function (messages) {
        var _this = this;
        _this.messages = $.extend(_this.messages, messages || {});

        var uploadConfig = $('#frmSlide #fileUploader').data('config');
        $('#frmSlide #fileUploader').removeAttr('data-config');

        $('#frmSlide #fileUploader').uploadFile({
            url: uploadConfig.url,
            maxFileAllowed: uploadConfig.maxFileAllowed,
            allowedTypes: uploadConfig.allowedTypes, //seperate with ','
            maxFileSize: uploadConfig.maxFileSize, //in byte
            maxFileAllowedErrorStr: uploadConfig.maxFileAllowedErrorStr,
            dragDropStr: uploadConfig.dragDropStr,
            dragDropErrorStr: uploadConfig.dragDropErrorStr,
            uploadErrorStr: uploadConfig.uploadErrorStr,
            extErrorStr: uploadConfig.extErrorStr,
            sizeErrorStr: uploadConfig.sizeErrorStr,
            onSuccess: function(instance, panel, files, data, xhr) {
                if (instance.fileCounter > 0) {
                    instance.fileCounter--;
                }

                _this.displayData(data, true);
            }
        });

        $('#frmSlide').on('click', '#slide_panel button', function() {
            $(this).parents('.form-group').remove();
        });
    },
    displayData: function(data, upload) {
        var _this = this;
        var upload = typeof(upload) == 'undefined' ? false : upload;

        if (!upload) {
            $.each(data, function (i, o) {
                var src = $(o).data('filename').replace('/original/', '/medium/');
                var id = Backend.rndStr(10);
                var sHTML = _this.tmp_html.replace(/\{SRC\}/g, src).replace(/\{ID\}/g, id);

                $('#slide_panel').append(sHTML);
            });
        } else {
            var src = data.info.url.replace('/original/', '/medium/');
            var id = Backend.rndStr(10);
            var sHTML = _this.tmp_html.replace(/\{SRC\}/g, src).replace(/\{ID\}/g, id);

            $('#slide_panel').append(sHTML);
        }
    }
};

var Location = {
    init: function () {
        $(document).on('click', 'a[data-panel]', function(evt) {
            evt.preventDefault();

            var self = $(this);
            var link = $(self).attr('href');
            var panel = $(self).data('panel');

            $.ajax({
                url: link,
                beforeSend: function () {
                    $(self).parents('.form-group').parent().find('.form-group').removeClass('bdr-lr1 bdr-lw3 active');
                    $(panel).html('<i class="fa fa-2x fa-spin fa-spinner"></i>');
                },
                success: function (response) {
                    $(self).parents('.form-group').addClass('bdr-lr1 bdr-lw3 active');
                    $(panel).html(response);
                }
            });
        });

        $('#country_panel a[data-panel]:first').trigger('click');

        $('#country_panel').sortable({
            items: '> div',
            cursor: 'move',
            forceHelperSize: true,
            update: function(event, ui) {
                var arrId = $.map($('#country_panel .form-group.row'), function(o, i) {
                    return $(o).data('id');
                });

                $.ajax({
                    url: $('#country_panel').data('link_sort'),
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: arrId
                    },
                    beforeSend: function () {

                    },
                    success: function (response) {
                        Backend.showMessage(response.message, {
                            className: response.error == 0 ? 'alert-info' : 'alert-danger'
                        });
                    }
                });
            }
        }).disableSelection();

        $('#city_panel').sortable({
            items: '> div',
            cursor: 'move',
            forceHelperSize: true,
            update: function(event, ui) {
                var arrId = $.map($('#city_panel .form-group.row'), function(o, i) {
                    return $(o).data('id');
                });

                $.ajax({
                    url: $('#city_panel').data('link_sort'),
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: arrId
                    },
                    beforeSend: function () {

                    },
                    success: function (response) {
                        Backend.showMessage(response.message, {
                            className: response.error == 0 ? 'alert-info' : 'alert-danger'
                        });
                    }
                });
            }
        }).disableSelection();

        $('#district_panel').sortable({
            items: '> div',
            cursor: 'move',
            forceHelperSize: true,
            update: function(event, ui) {
                var arrId = $.map($('#district_panel .form-group.row'), function(o, i) {
                    return $(o).data('id');
                });

                $.ajax({
                    url: $('#district_panel').data('link_sort'),
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: arrId
                    },
                    beforeSend: function () {

                    },
                    success: function (response) {
                        Backend.showMessage(response.message, {
                            className: response.error == 0 ? 'alert-info' : 'alert-danger'
                        });
                    }
                });
            }
        }).disableSelection();

        $('#ward_panel').sortable({
            items: '> div',
            cursor: 'move',
            forceHelperSize: true,
            update: function(event, ui) {
                var arrId = $.map($('#ward_panel .form-group.row'), function(o, i) {
                    return $(o).data('id');
                });

                $.ajax({
                    url: $('#ward_panel').data('link_sort'),
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: arrId
                    },
                    beforeSend: function () {

                    },
                    success: function (response) {
                        Backend.showMessage(response.message, {
                            className: response.error == 0 ? 'alert-info' : 'alert-danger'
                        });
                    }
                });
            }
        }).disableSelection();
    },
    validateForm: function (form, settings) {
        $(form).validate({
            rules: settings.rules,
            messages: settings.messages,
            submitHandler: function (form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                    },
                    success: function (response) {
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                        $('.sidebar-option .icon-close').trigger('click');

                        Backend.showMessage(response.message, {
                            className: 'alert-info',
                            callback: function () {
                                window.location.reload(true);
                            }
                        });
                    },
                    error: function (response) {
                        var errors = response.responseJSON;
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');

                        Backend.showError(errors);
                    }
                });
            }
        });
    },
    validateCountry: function (messages) {
        this.validateForm('#frmCountry', {
            rules: {
                country_name: {
                    required: true,
                    maxlength: 100
                },
                country_code: {
                    required: true,
                    maxlength: 15
                }
            },
            messages: {
                country_name: {
                    required: messages.country_name.required,
                    maxlength: messages.country_name.maxlength.replace(':max', 100)
                },
                country_code: {
                    required: messages.country_code.required,
                    maxlength: messages.country_code.maxlength.replace(':max', 100)
                }
            }
        });
    },
    validateCity: function (messages) {
        this.validateForm('#frmCity', {
            rules: {
                city_name: {
                    required: true,
                    maxlength: 100
                },
                country_id: {
                    required: true
                },
                city_zipcode: {
                    number: true
                },
                city_location: {
                    maxlength: 50
                }
            },
            messages: {
                city_name: {
                    required: messages.city_name.required,
                    maxlength: messages.city_name.maxlength.replace(':max', 100)
                },
                country_id: {
                    required: messages.country_id.required
                },
                city_zipcode: {
                    number: messages.city_zipcode.number
                },
                city_location: {
                    maxlength: messages.city_location.maxlength.replace(':max', 50)
                }
            }
        });
    },
    validateDistrict: function (messages) {
        this.validateForm('#frmDistrict', {
            rules: {
                district_name: {
                    required: true,
                    maxlength: 100
                },
                city_id: {
                    required: true
                },
                district_location: {
                    maxlength: 50
                }
            },
            messages: {
                district_name: {
                    required: messages.district_name.required,
                    maxlength: messages.district_name.maxlength.replace(':max', 100)
                },
                city_id: {
                    required: messages.city_id.required
                },
                district_location: {
                    maxlength: messages.district_location.maxlength.replace(':max', 50)
                }
            }
        });
    },
    validateWard: function (messages) {
        this.validateForm('#frmWard', {
            rules: {
                ward_name: {
                    required: true,
                    maxlength: 100
                },
                district_id: {
                    required: true
                },
                ward_location: {
                    maxlength: 50
                }
            },
            messages: {
                ward_name: {
                    required: messages.ward_name.required,
                    maxlength: messages.ward_name.maxlength.replace(':max', 100)
                },
                district_id: {
                    required: messages.district_id.required
                },
                ward_location: {
                    maxlength: messages.ward_location.maxlength.replace(':max', 50)
                }
            }
        });
    }
};

var Block = {
    validate: function (form, rules, messages) {
        $(form).validate({
            rules: rules,
            messages: messages,
            submitHandler: function (form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $(form).find('button:submit').prop('disabled', true).find('.fa').addClass('fa-spinner fa-spin');
                    },
                    success: function (response) {
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');
                        $('.sidebar-option .icon-close').trigger('click');

                        Backend.showMessage(response.message, {
                            className: 'alert-info',
                            callback: function () {
                                window.location.reload(true);
                            }
                        });
                    },
                    error: function (response) {
                        var errors = response.responseJSON;
                        $(form).find('button:submit').prop('disabled', false).find('.fa').removeClass('fa-spinner fa-spin');

                        Backend.showError(errors);
                    }
                });
            }
        });
    },
    uploadImage: function (setting) {
        $('#fileUploader').uploadFile({
            url: setting.url,
            uploadPanel: setting.uploadPanel,
            maxFileAllowed: setting.maxFileAllowed,
            allowedTypes: setting.allowedTypes, //seperate with ','
            maxFileSize: setting.maxFileSize, //in byte
            maxFileAllowedErrorStr: setting.maxFileAllowedErrorStr,
            dragDropStr: setting.dragDropStr,
            dragDropErrorStr: setting.dragDropErrorStr,
            uploadErrorStr: setting.uploadErrorStr,
            extErrorStr: setting.extErrorStr,
            sizeErrorStr: setting.sizeErrorStr,
            onSuccess: function(instance, panel, files, data, xhr) {
                if (instance.fileCounter > 0) {
                    instance.fileCounter--;
                }

                $('#template_thumbnail').val(data.info.filename);
                panel.html('<img src="' + setting.mediaUrl + data.info.filename + '" class="img-responsive" />');
            },
            onDelete: function(obj, instance, panel) {
                instance.fileCounter--;
            }
        });
    },
    initPage: function(messages) {
        this.validate('#frmBlockPage', {
            page_name: {
                required: true,
                maxlength: 250
            },
            page_code: {
                required: true,
                maxlength: 25
            },
            page_layout: {
                required: true
            },
            page_url: {
                maxlength: 250,
                url: true
            },
            language_id: {
                required: true
            }
        }, {
            page_name: {
                required: messages.page_name.required,
                maxlength: messages.page_name.maxlength.replace(':max', 250)
            },
            page_code: {
                required: messages.page_code.required,
                maxlength: messages.page_code.maxlength.replace(':max', 25)
            },
            page_layout: {
                required: messages.page_layout.required
            },
            page_url: {
                maxlength: messages.page_url.maxlength.replace(':max', 250),
                url: messages.page_url.invalid
            },
            language_id: {
                required: messages.language_id.required
            }
        });
    },
    initFunction: function(messages) {
        this.validate('#frmBlockFunction', {
            function_name: {
                required: true,
                maxlength: 100
            },
            function_params: {
                required: true
            }
        }, {
            function_name: {
                required: messages.function_name.required,
                maxlength: messages.function_name.maxlength.replace(':max', 100)
            },
            function_params: {
                required: messages.function_params.required
            }
        });
    },
    initTemplate: function(messages) {
        if ($('#template_content').size() > 0) {
            Backend.loadCKEditor('template_content', $.parseJSON($('#template_content').attr('data-editor')));
        }

        this.validate('#frmBlockTemplate', {
            template_name: {
                required: true,
                maxlength: 100
            },
            template_area: {
                required: true
            },
            template_view: {
                required: true,
                maxlength: 200
            },
            template_content: {
                required: true
            },
            template_thumbnail: {
                required: true
            }
        }, {
            template_name: {
                required: messages.template_name.required,
                maxlength: messages.template_name.maxlength.replace(':max', 100)
            },
            template_area: {
                required: messages.template_area.required
            },
            template_view: {
                required: messages.template_view.required,
                maxlength: messages.template_view.maxlength.replace(':max', 200)
            },
            template_content: {
                required: messages.template_content.required
            },
            template_thumbnail: {
                required: messages.template_thumbnail.required
            }
        });
    },
    instance: {
        sort: '.widget-list',
        drag: '#widget_type > [data-type]'
    },
    initLayout: function() {
        var _this = this;

        $(_this.instance.sort).sortable({
            cancel: '.no-sortable, a, :input, option',
            placeholder: 'empty-drop-item',
            revert: true,
            update: function (event, ui) {
                if (ui.item.is('.ui-draggable')) {
                    ui.item.removeClass('ui-draggable ui-draggable-handle')
                        .addClass('widget-item')
                        .css({ width: 'auto', height: 'auto' });

                    var type = ui.item.data('type');
                    var area = $(event.target).data('area');
                    var link = $(event.target).data('link_template');

                    $.ajax({
                        url: link,
                        data: {
                            type: type,
                            area: area
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            ui.item.html('<div class="text-center"><i class="fa fa-3x fa-spin fa-spinner"></i></div>');
                        },
                        success: function(response) {
                            if (response.error == 1) {
                                ui.item.remove();
                                Backend.showMessage(response.message);
                            } else {
                                $(_this.instance.drag).draggable('disable');
                                $(_this.instance.sort).sortable('disable');

                                $('.template-header').find('button').prop('disabled', true);

                                ui.item.html(response.data);
                                _this.handleSelectTemplate();
                                _this.handleForm(ui.item.find('.template-form'));
                            }
                        }
                    });
                } else {
                    var link = $(event.target).data('link_sort');
                    var arrId = $.map($(event.target).find('.widget-item'), function(o, i) {
                        return $(o).data('id');
                    });

                    $.ajax({
                        url: link,
                        method: 'post',
                        data: {
                            id: arrId
                        },
                        success: function(response) {
                            Backend.showMessage(response.message, {
                                className: 'alert-info'
                            });
                        }
                    });
                }
            }
        }).disableSelection();

        $(_this.instance.drag).draggable({
            connectToSortable: _this.instance.sort,
            helper: 'clone',
            revert: 'invalid',
            cursor: 'move',
            refreshPositions: true
        }).disableSelection();

        _this.handleWidget();
    },
    handleSelectTemplate: function() {
        $('.template-dropdown').on('click', '.template-dropdown-item', function(evt) {
            evt.preventDefault();

            if (!$(this).hasClass('selected')) {
                $(this).parent().children().removeClass('selected');
                $(this).addClass('selected');
                $(this).parents('.template-form').find('[name="template_id"]').val($(this).data('id'));
                $(this).parents('.template-form').find('button.btn-save').prop('disabled', false);

                if ($(this).data('type') == 'dynamic') {
                    var self = $(this);
                    var link = self.data('link');

                    $.ajax({
                        url: link,
                        method: 'get',
                        dataType: 'json',
                        success: function (response) {
                            self.parents('.template-form').find('.template-content').html(response.template);
                            self.parents('.template-form').find('.function-content').html(response.function);
                        }
                    });
                }
            }
        });
    },
    handleForm: function(form, action) {
        var _this = this;

        if (typeof (action) == 'undefined') {
            var action = 'add';
        }

        $(form).find('select[name="function_id"]').on('change', function() {
            var self = $(this);
            var function_id = self.val();

            console.log(self.parent(), self.parent().nextAll());
            self.parent().nextAll().remove();

            if (!function_id.blank()) {
                var link = self.data('link').replace('0', function_id);

                $.ajax({
                    url: link,
                    method: 'get',
                    success: function (data) {
                        self.parents('.function-content').append(data);
                    }
                });
            }
        });

        //click button save
        $(form).on('submit', function (evt) {
            evt.preventDefault();

            var form = $(this);
            var data = form.serializeHash();
            data.widget_order = form.parents('.widget-item').prevAll().size() + 1;

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: data,
                beforeSend: function() {
                    form.find('button').prop('disabled', true);
                    form.find('button.btn-save').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function (data) {
                    $(data).hide().insertAfter(form.parents('.widget-item')).fadeIn('slow');
                    form.parents('.widget-item').remove();

                    $('.template-header').find('button').prop('disabled', false);

                    $(_this.instance.drag).draggable('enable');
                    $(_this.instance.sort).sortable('enable');
                },
                error: function(response) {
                    if (response.responseJSON) {
                        form.find('button').prop('disabled', false);
                        form.find('button.btn-save').html('Save');

                        var errors = response.responseJSON;

                        Backend.showError(errors);
                    } else {
                        form.parents('.widget-item').remove();

                        if (form.parents('.widget-list').children().size() <= 0) {
                            form.parents('.widget-list').append('<li class="widget-item no-sortable ui-sortable-handle"></li>');
                        }

                        $(_this.instance.drag).draggable('enable');
                        $(_this.instance.sort).sortable('enable');
                    }
                }
            });
        });

        //click button cancel
        $(form).find('button.btn-cancel').on('click', function () {
            $(_this.instance.drag).draggable('enable');
            $(_this.instance.sort).sortable('enable');

            $('.template-header').find('button').prop('disabled', false);

            if (action == 'add') {
                $(this).parents('.widget-item').fadeOut('slow', function() {
                    if ($(this).parent().children().size() == 1) {
                        $(this).parent().append('<li class="widget-item no-sortable ui-sortable-handle"></li>');
                    }

                    $(this).remove();
                });
            } else {
                $(this).parents('.template-form').find('.template-content').html($(this).parents('.template-form').data('image'));
                $(this).parents('.template-form').find('.function-content').addClass('hidden');
                $(this).parents('.template-form').find('.template-footer').addClass('hidden');
            }
        });
    },
    handleWidget: function() {
        var _this = this;

        $('.widget-list').on('click', '.btn-status', function(evt) {
            evt.preventDefault();

            var self = $(this);
            var oldText = self.html();

            bootbox.confirm(self.data('message'), function(result) {
                if (result) {
                    $.ajax({
                        url: self.data('link'),
                        method: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            self.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
                            $('.template-header').find('button').prop('disabled', true);
                        },
                        success: function(response) {
                            self.prop('disabled', false).html(oldText);
                            $('.template-header').find('button').prop('disabled', false);

                            Backend.showMessage(response.message, {
                                className: response.error == 0 ? 'alert-info' : 'alert-danger'
                            });

                            if (response.error == 0) {
                                $(response.data).hide().insertAfter(self.parents('.widget-item')).fadeIn('slow');
                                self.parents('.widget-item').remove();
                            }
                        },
                        error: function () {
                            self.prop('disabled', false).html(oldText);
                            $('.template-header').find('button').prop('disabled', false);
                        }
                    });
                }
            });
        });

        $('.widget-list').on('click', '.btn-edit', function(evt) {
            evt.preventDefault();

            var self = $(this);
            var oldText = self.html();

            $.ajax({
                url: self.data('link'),
                method: 'get',
                dataType: 'json',
                beforeSend: function() {
                    self.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
                    $('.template-header').find('button').prop('disabled', true);
                },
                success: function(response) {
                    self.html(oldText);
                    self.parent().find('button').prop('disabled', true);

                    self.parents('.template-body').find('.template-form').data('image', self.parents('.template-body').find('.template-content').html());
                    self.parents('.template-body').find('.template-content').html(response.template);
                    self.parents('.template-body').find('.function-content').html(response.function).removeClass('hidden');
                    self.parents('.template-body').find('.template-footer').removeClass('hidden');

                    $('.template-header').find('button').prop('disabled', true);

                    $(_this.instance.drag).draggable('disable');
                    $(_this.instance.sort).sortable('disable');

                    _this.handleForm(self.parents('.template-body').find('.template-form'), 'edit');
                    self.parents('.template-body').find('.template-form select[name="function_id"]').trigger('change');
                },
                error: function () {
                    self.prop('disabled', false).html(oldText);
                }
            });
        });

        $('.widget-list').on('click', '.btn-delete', function(evt) {
            evt.preventDefault();

            var self = $(this);
            var oldText = self.html();

            bootbox.confirm(self.data('message'), function(result) {
                if (result) {
                    $.ajax({
                        url: self.data('link'),
                        method: 'delete',
                        dataType: 'json',
                        beforeSend: function() {
                            self.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
                            $('.template-header').find('button').prop('disabled', true);
                        },
                        success: function(response) {
                            self.prop('disabled', false).html(oldText);
                            $('.template-header').find('button').prop('disabled', false);

                            Backend.showMessage(response.message, {
                                className: response.error == 0 ? 'alert-info' : 'alert-danger'
                            });

                            if (response.error == 0) {
                                self.parents('.widget-item').fadeOut(function() {
                                    if ($(this).parent().children().size() == 1) {
                                        $(this).parent().append('<li class="widget-item no-sortable ui-sortable-handle"></li>');
                                    }

                                    $(this).remove();
                                });
                            }
                        },
                        error: function () {
                            self.prop('disabled', false).html(oldText);
                            $('.template-header').find('button').prop('disabled', false);
                        }
                    });
                }
            });
        });
    }
};

var Page = {
    settings: {},
    languageSelector: function () {
        var _this = this;

        if ($('select#language_id').is(':disabled')) {
            $('<input type="hidden" name="language_id" value="' + $('select#language_id').val() + '">').insertAfter($('select#language_id'));
            $('select#language_id').removeAttr('name');
        }

        $('select#language_id').on('change', function () {
            var self = $(this);

            if (_this.settings.default_language == self.val()) {
                $('#page_source').prop('disabled', true);
                $('#page_source').prev().removeClass('required');
            } else {
                $('#page_source').prop('disabled', false);
                $('#page_source').prev().addClass('required');
            }

            //load page parent
            $.ajax({
                url: self.data('linkpage'),
                data: {
                    language_id: self.val(),
                    selected: $('#parent_id').data('id')
                },
                success: function(data) {
                    $('#parent_id').html(data);
                }
            });

            //load page source
            $.ajax({
                url: self.data('linksource'),
                data: {
                    source: 'page',
                    language_id: self.val(),
                    selected: $('#page_source').data('id')
                },
                success: function(data) {
                    $('#page_source').html(data);
                }
            });
        }).trigger('change');
    },
    validate: function() {
        var _this = this;

        $('#frmPage').validate({
            rules: {
                page_title: {
                    required: true,
                    maxlength: 200
                },
                page_code: {
                    required: true,
                    code: true,
                    maxlength: 200
                },
                parent_id: {
                    required: true
                },
                language_id: {
                    required: true
                },
                page_source: {
                    required: function (element) {
                        return _this.settings.default_language != $('#language_id').val();
                    }
                },
                status: {
                    required: true
                },
                page_content: {
                    required: true
                }
            },
            messages: {
                page_title: {
                    required: _this.settings.messages.page_title.required,
                    maxlength: _this.settings.messages.page_title.maxlength.replace(':max', 200)
                },
                page_code: {
                    required: _this.settings.messages.page_code.required,
                    code: _this.settings.messages.page_code.code,
                    maxlength: _this.settings.messages.page_code.maxlength.replace(':max', 200)
                },
                parent_id: {
                    required: _this.settings.messages.parent_id.required
                },
                language_id: {
                    required: _this.settings.messages.language_id.required
                },
                page_source: {
                    required: _this.settings.messages.page_source.required
                },
                status: {
                    required: _this.settings.messages.status.required
                },
                page_content: {
                    required: _this.settings.messages.page_content.required
                }
            }
        });
    },
    init: function(options) {
        this.settings = $.extend(this.settings, options || {});

        this.languageSelector();
        this.validate();
    }
};

var Navigation = {
    getList: function() {
        var _this = this;

        $.ajax({
            url: $('#navigation_panel').data('link'),
            beforeSend: function() {
                $('#navigation_panel').html('<i class="fa fa-3x fa-spin fa-spinner"></i></i>');
            },
            success: function(data) {
                $('#navigation_panel').html(data);
                _this.checkSort();
            }
        });
    },
    resetListParent: function(data) {
        $('#menu_settings_column [name="parent_id"]').each(function() {
            $(this).html(data);
        });
    },
    updateSort: function() {
        var _this = this;

        var arrId = $.map($('#navigation_panel .navigation-item'), function(o, i) {
            return $(o).data('id');
        });

        if (arrId.blank()) {
            return false;
        }

        $.ajax({
            url: $('#navigation_panel').data('link_sort'),
            method: 'post',
            dataType: 'json',
            data: {
                id: arrId
            },
            beforeSend: function() {
                $('.btn-savesort').prop('disabled', true).prepend('<i class="fa fa-spin fa-spinner mr05"></i>');
                $('#navigation_panel .btn-up, #navigation_panel .btn-down').prop('disabled', true);
            },
            success: function (response) {
                $('.btn-savesort').prop('disabled', false).find('.fa').remove();

                Backend.showMessage(response.message, {
                    className: response.error == 0 ? 'alert-info' : 'alert-danger',
                    timeout: 1000,
                    callback: function() {
                        if (response.error == 0) {
                            _this.getList();
                            _this.resetListParent(response.parents);
                        } else {
                            _this.getList();
                        }
                    }
                });
            }
        });
    },
    checkSort: function() {
        $('#navigation_panel .navigation-item').each(function() {
            if ($(this).prev().size() == 0 || $(this).prev().data('id') == $(this).data('parent_id')) {
                $(this).find('.btn-up').hide();
            } else {
                $(this).find('.btn-up').show();
            }

            if ($(this).next().size() == 0 || ($(this).next().data('parent_id') != $(this).data('parent_id') && $(this).next().data('parent_id') != $(this).data('id'))) {
                $(this).find('.btn-down').hide();
            } else {
                $(this).find('.btn-down').show();
            }
        });
    },
    init: function () {
        var _this = this;

        // click button add
        $('#menu_settings_column').on('click', '.btn-add', function(evt) {
            evt.preventDefault();

            var button = $(this);
            var form = button.parents('form');
            var parent_id = form.find('[name="parent_id"]').val();

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function() {
                    button.prepend('<i class="fa fa-spinner fa-spin mr05"></i>').prop('disabled', true);

                    form.find('.has-error').removeClass('has-error');
                    form.find('label.error').remove();
                },
                success: function(response) {
                    button.prop('disabled', false).find('.fa').remove();

                    form.find('input:text').val('');
                    form.find('input:checkbox').prop('checked', false);

                    form.find('.has-error').removeClass('has-error');
                    form.find('label.error').remove();

                    Backend.showMessage(response.message, {
                        className: response.error == 0 ? 'alert-info' : 'alert-danger',
                        timeout: 1000,
                        callback: function() {
                            if (response.error == 0) {
                                _this.getList();
                                _this.resetListParent(response.parents);
                            }
                        }
                    });
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    button.prop('disabled', false).find('.fa').remove();

                    Backend.showError(errors, form);
                }
            });
        });

        // click select all
        $('#menu_settings_column').on('click', '.btn-select', function(evt) {
            evt.preventDefault();

            $(this).parents('.panel').find(':checkbox').prop('checked', true);
        });

        // click un select all
        $('#menu_settings_column').on('click', '.btn-unselect', function(evt) {
            evt.preventDefault();

            $(this).parents('.panel').find(':checkbox').prop('checked', false);
        });

        // click cancel
        $('#navigation_panel').on('click', '.btn-cancel', function() {
            $(this).parents('form').find(':input').each(function() {
                $(this).val($(this).data('old'));
            });
        });

        // click save
        $('#navigation_panel').on('click', '.btn-save', function(evt) {
            evt.preventDefault();

            var button = $(this);
            var form = button.parents('form');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function() {
                    button.find('.fa').removeClass('fa-save').addClass('fa-spinner fa-spin');
                    form.find('.btn').prop('disabled', true);
                },
                success: function(response) {
                    button.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-save');
                    form.find('.btn').prop('disabled', false);

                    Backend.showMessage(response.message, {
                        className: response.error == 0 ? 'alert-info' : 'alert-danger',
                        timeout: 1000,
                        callback: function() {
                            if (response.error == 0) {
                                _this.getList();
                                _this.resetListParent(response.parents);
                            }
                        }
                    });
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    button.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-save');
                    form.find('.btn').prop('disabled', false);

                    Backend.showError(errors, form);
                }
            });
        });

        // click delete
        $('#navigation_panel').on('click', '.btn-delete', function(evt) {
            evt.preventDefault();

            var button = $(this);
            bootbox.confirm(button.data('message'), function(result) {
                if (result) {
                    $.ajax({
                        url: button.data('link'),
                        method: 'delete',
                        dataType: 'json',
                        beforeSend: function() {
                            button.find('.fa').removeClass('fa-trash').addClass('fa-spinner fa-spin');
                            button.parents('form').find('.btn').prop('disabled', true);
                        },
                        success: function (response) {
                            Backend.showMessage(response.message, {
                                className: response.error == 0 ? 'alert-info' : 'alert-danger',
                                callback: function() {
                                    if (response.error == 0) {
                                        button.parents('.navigation-item').fadeOut('fast', function() {
                                            $(this).remove();
                                        });
                                    } else {
                                        button.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-trash');
                                        button.parents('form').find('.btn').prop('disabled', false);
                                    }
                                }
                            });
                        },
                        error: function(response) {
                            button.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-trash');
                            button.parents('form').find('.btn').prop('disabled', false);

                            Backend.showMessage(response.statusText);
                        }
                    });
                }
            });
        });

        // click button up
        $('#navigation_panel').on('click', '.btn-up', function(evt) {
            evt.preventDefault();

            var item = $(this).parents('.navigation-item');
            var id = item.data('id');
            var parent_id = item.data('parent_id');
            var child = parseInt(item.data('child'));
            var prev = item.prev();
            var child_prev = parseInt(prev.data('child'));

            if (prev.data('parent_id') != parent_id) {
                prev = $('.navigation-item[data-id="' + prev.data('parent_id') + '"]');
                child_prev = parseInt(prev.data('child'));
            }

            if (child > 0) {
                var new_prev = prev.clone().insertAfter(item.nextAll(':eq(' + (child - 1) + ')'));

                if (child_prev > 0) {
                    for (var i = child_prev - 1; i >= 0; i--) {
                        prev.nextAll(':eq(' + i + ')').clone().insertAfter(new_prev);
                    }

                    prev.nextAll(':lt(' + child_prev + ')').remove();
                }

                prev.remove();
            } else {
                item.clone().insertBefore(prev);
                item.remove();
            }

            _this.checkSort();
        });

        // click button down
        $('#navigation_panel').on('click', '.btn-down', function(evt) {
            evt.preventDefault();

            var item = $(this).parents('.navigation-item');
            var id = item.data('id');
            var parent_id = item.data('parent_id');
            var child = item.data('child');
            var next = item.next();
            var child_next = parseInt(next.data('child'));

            if (child > 0) {
                next = item.nextAll(':eq(' + child + ')');
                child_next = parseInt(next.data('child'));

                var new_next = next.clone().insertBefore(item);

                if (child_next > 0) {
                    for (var i = child_next - 1; i >= 0; i--) {
                        next.nextAll(':eq(' + i + ')').clone().insertAfter(new_next);
                    }

                    next.nextAll(':lt(' + child_next + ')').remove();
                }

                next.remove();
            } else {
                if (child_next > 0) {
                    next = next.nextAll(':eq(' + (child_next - 1) + ')');
                    item.clone().insertAfter(next);
                    item.remove();
                } else {
                    item.clone().insertAfter(next);
                    item.remove();
                }
            }

            _this.checkSort();
        });

        // click button save sort
        $('.btn-savesort').on('click', function() {
            _this.updateSort();
        });

        _this.checkSort();
    }
};

var Utils = {
    loadContent: function(url, data) {
        $.ajax({
            url: url,
            method: 'get',
            data: data,
            beforeSend: function() {
                $('#filemanager_modal').addClass('text-center').html('<i class="fa fa-spinner fa-spin fa-5x"></i>');
            },
            success: function(data) {
                $('#filemanager_modal').removeClass('text-center').html(data);
            }
        });
    },
    initGeneral: function() {
        var _this = this;

        // click search
        $('#frmSearch').on('submit', function(evt) {
            evt.preventDefault();

            _this.loadContent($(this).attr('action'), $(this).serialize());
        });

        // click paging
        $('.pagination a').on('click', function(evt) {
            evt.preventDefault();

            _this.loadContent($(this).attr('href'), {});
        });
    },
    insertPost: function(editorName) {
        var _this = this;

        _this.initGeneral();

        // click button select
        $('.btn-select').on('click', function() {
            if ($('#filemanager_modal :checkbox:checked').size() > 0) {
                var editor = CKEDITOR.instances[editorName];
                var wrap = $('<div></div>');
                var arrId = $.map($('#filemanager_modal :checkbox:checked'), function(o, i) {
                    return $(o).val();
                });

                wrap.append('<div data-component="true" data-type="article" data-id="' + arrId.join(',') + '">Article</div>');
                editor.insertHtml(wrap.html(), 'unfiltered_html');

                //close modal;
                bootbox.hideAll();
            }
        });
    },
    insertVideo: function(editorName) {
        var _this = this;

        _this.initGeneral();

        // click button select
        $('.btn-select').on('click', function() {
            if ($('#filemanager_modal :checkbox[name="media_id"]:checked').size() > 0) {
                var editor = CKEDITOR.instances[editorName];
                var wrap = $('<div></div>');

                $('#filemanager_modal :checkbox[name="media_id"]:checked').each(function() {
                    wrap.append('<div data-component="true" data-type="video" data-id="' + $(this).val() + '">'
                        + '<div class="embed-responsive embed-responsive-16by9">'
                            + '<iframe class="embed-responsive-item" src="' + $(this).data('filename') + '"></iframe>'
                        + '</div>'
                    + '</div>');
                });

                editor.insertHtml(wrap.html(), 'unfiltered_html');

                //close modal;
                bootbox.hideAll();
            }
        });
    },
    insertLink: function(editorName) {
        var _this = this;

        _this.initGeneral();

        // click button select
        $('.btn-select').on('click', function() {
            if ($(':checkbox[name="media_id"]:checked').size() > 0) {
                var editor = CKEDITOR.instances[editorName];
                var wrap = $('<div></div>');

                $(':checkbox[name="media_id"]:checked').each(function() {
                    wrap.append('<div data-component="true" data-type="video" data-id="' + $(this).val() + '">'
                        + '<div class="embed-responsive embed-responsive-16by9">'
                        + '<iframe class="embed-responsive-item" src="' + $(this).data('filename') + '"></iframe>'
                        + '</div>'
                        + '</div>');
                });

                editor.insertHtml(wrap.html(), 'unfiltered_html');

                //close modal;
                bootbox.hideAll();
            }
        });
    }
};

$(document).ready(function() {
    AdminLTE.init();
    Backend.init();
});