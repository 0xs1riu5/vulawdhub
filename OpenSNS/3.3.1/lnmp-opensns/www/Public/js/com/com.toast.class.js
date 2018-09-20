/**
 * 操纵toastor的便捷类
 * @type {{success: success, error: error, info: info, warning: warning}}
 */
var toast = {
    /**
     * 成功提示
     * @param text 内容
     * @param title 标题
     */
    success: function (text) {
        toast.show(text, {placement: 'bottom', type: 'success'});
    },
    /**
     * 失败提示
     * @param text 内容
     * @param title 标题
     */
    error: function (text) {
        toast.show(text, {placement: 'bottom', type: 'error'});
    },
    /**
     * 信息提示
     * @param text 内容
     * @param title 标题
     */
    info: function (text) {
        toast.show(text, {placement: 'bottom', type: 'info'});
    },
    /**
     * 警告提示
     * @param text 内容
     * @param title 标题
     */
    warning: function (text, title) {
        toast.show(text, {placement: 'bottom',type:'warning'});
    },

    show: function (text, option) {
        var zui = $.zui;
        if (zui) {
            $.zui.messager.show(text, option);
        }else{
            $.messager.show(text, option);
        }
    },
    /**
     *  显示loading
     * @param text
     */
    showLoading: function () {
        $('body').append('<div class="big_loading"><img src="' + ThinkPHP.PUBLIC + '/images/big_loading.gif"/></div>');
    },
    /**
     * 隐藏loading
     * @param text
     */
    hideLoading: function () {
        $('div').remove('.big_loading');
    }
}