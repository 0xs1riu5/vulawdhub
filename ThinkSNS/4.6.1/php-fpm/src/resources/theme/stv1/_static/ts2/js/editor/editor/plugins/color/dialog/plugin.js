KISSY.Editor.add("color/dialog", function(editor) {
    var S = KISSY,
        KE = S.Editor;
    KE.use("colorsupport/dialog/colorpicker", function() {
        var colorPicker = new KE.ColorSupport.ColorPicker();
        editor.addDialog("color/dialog", {
            //动态更新cmd，可能有前景色与背景色两种
            show:function(cmd) {
                colorPicker.show(cmd);
            },
            hide:function() {
                colorPicker.hide();
            },
            destroy:function() {
                colorPicker.destroy();
            }
        });
    });
}, {
    attach:false
});