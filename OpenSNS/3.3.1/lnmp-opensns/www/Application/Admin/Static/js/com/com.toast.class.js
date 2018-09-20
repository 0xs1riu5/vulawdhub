/**
 * 操纵toastor的便捷类
 * @type {{success: Function, error: Function, info: Function, warning: Function, show: Function, showLoading: Function, hideLoading: Function}}
 * change by zzl@ourstu.com
 */
var toast = {
    /**
     * 成功提示
     * @param text 内容
     * @param title 标题
     */
    success: function (text) {
        toast.show(text, 'success');
    },
    /**
     * 失败提示
     * @param text 内容
     * @param title 标题
     */
    error: function (text) {
        toast.show(text, 'error');
    },
    /**
     * 信息提示
     * @param text 内容
     * @param title 标题
     */
    info: function (text) {
        toast.show(text, 'info');
    },
    /**
     * 警告提示
     * @param text 内容
     * @param title 标题
     */
    warning: function (text, title) {
        toast.show(text, 'warning');
    },

    show: function (text, type) {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-bottom-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        switch (type){
            case 'warning':
                toastr.warning(text);
                break;
            case 'success':
                toastr.success(text);
                break;
            case 'info':
                toastr.info(text);
                break;
            case 'error':
                toastr.error(text);
                break;
            default :
                toastr.success(text);
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