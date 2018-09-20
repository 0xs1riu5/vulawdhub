/* ========================================================================
 * ZUI: kanban.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function ($) {
    'use strict';

    if (!$.fn.droppable) throw new Error('droppable requires for kanbans');

    var Kanban = function (element, options) {
        this.$ = $(element);
        this.options = this.getOptions(options);

        this.getLang();
        this.init();
    };

    Kanban.DEFAULTS = {
        lang: 'zh-cn',
        langs: {
            'zh-cn': {
                appendToTheEnd: '移动到末尾'
            },
            'zh-tw': {
                appendToTheEnd: '移动到末尾'
            },
            'en': {
                appendToTheEnd: 'Move to the end.'
            }
        }
    }; // default options

    Kanban.prototype.getOptions = function (options) {
        options = $.extend(
            {}, Kanban.DEFAULTS, this.$.data(), options);
        return options;
    };

    Kanban.prototype.getLang = function () {
        var config = window.config;
        if (!this.options.lang) {
            if (typeof(config) != 'undefined' && config.clientLang) {
                this.options.lang = config.clientLang;
            }
            else {
                var hl = $('html').attr('lang');
                this.options.lang = hl ? hl : 'en';
            }
            this.options.lang = this.options.lang.replace(/-/, '_').toLowerCase();
        }
        this.lang = this.options.langs[this.options.lang] || this.options.langs[Kanban.DEFAULTS.lang];
    };

    Kanban.prototype.init = function () {
        var idSeed = 1;
        var lang = this.lang;
        this.$.find('.kanban-item:not(".disable-drop"), .kanban:not(".disable-drop")').each(function () {
            var $this = $(this);
            if ($this.attr('id')) {
                $this.attr('data-id', $this.attr('id'));
            }
            else if (!$this.attr('data-id')) {
                $this.attr('data-id', 'kanban' + (idSeed++));
            }

            if ($this.hasClass('kanban')) {
                $this.find('.kanban-list').append('<div class="kanban-item kanban-item-empty"><i class="icon-plus"></i> {appendToTheEnd}</div>'.format(lang))
                    .append('<div class="kanban-item kanban-item-shadow"></div>'.format(lang));
            }
        });

        this.bind();
    };

    Kanban.prototype.bind = function (items) {
        var $kanbans = this.$,
            setting = this.options;
        if (typeof(items) == 'undefined') {
            items = $kanbans.find('.kanban-item:not(".disable-drop, .kanban-item-shadow")');
        }

        items.droppable(
            {
                container: '.admin-main-container',
                target: '.kanban-item:not(".disable-drop, .kanban-item-shadow")',
                flex: true,
                start: function (e) {
                    $kanbans.addClass('dragging').find('.kanban-item-shadow').height(e.element.outerHeight());
                },
                drag: function (e) {
                    $kanbans.find('.kanban.drop-in-empty').removeClass('drop-in-empty');
                    if (e.isIn) {
                        var kanban = e.target.closest('.kanban').addClass('drop-in');
                        var shadow = kanban.find('.kanban-item-shadow');
                        var target = e.target;

                        $kanbans.addClass('drop-in').find('.kanban.drop-in').not(kanban).removeClass('drop-in');

                        shadow.insertBefore(target);

                        kanban.toggleClass('drop-in-empty', target.hasClass('kanban-item-empty'));
                    }

                },
                drop: function (e) {
                    if (e.isNew) {
                        var DROP = 'drop';

                        e.element.insertBefore(e.target);
                        if (setting.hasOwnProperty(DROP) && $.isFunction(setting[DROP])) {
                            setting[DROP](e);
                        }
                    }

                },
                finish: function () {
                    $kanbans.removeClass('dragging').removeClass('drop-in').find('.kanban.drop-in').removeClass('drop-in');

                }
            });
    };

    $.fn.kanbans = function (option) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('zui.kanban');
            var options = typeof option == 'object' && option;

            if (!data) $this.data('zui.kanban', (data = new Kanban(this, options)));

            if (typeof option == 'string') data[option]();
        });
    };

    $.fn.kanbans.Constructor = Kanban;

    $(function () {
        $('[data-toggle="kanbans"]').kanbans();
    });
}(jQuery));