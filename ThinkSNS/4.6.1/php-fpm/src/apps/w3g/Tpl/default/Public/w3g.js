(function($){
    $.fn.extend({
        insertAtCaret: function(myValue){
            var $t=$(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else 
                if ($t.selectionStart || $t.selectionStart == '0') {
                    var startPos = $t.selectionStart;
                    var endPos = $t.selectionEnd;
                    var scrollTop = $t.scrollTop;
                    $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                    this.focus();
                    $t.selectionStart = startPos + myValue.length;
                    $t.selectionEnd = startPos + myValue.length;
                    $t.scrollTop = scrollTop;
                }
                else {
                    this.value += myValue;
                    this.focus();
                }
        }
    })  
})(jQuery);

function addtheme(){
    var text = '#请在这里输入自定义话题#';
    var   patt   =   new   RegExp(text,"g");  
    var content_publish = $('#topic_content');
    var result;
    if( content_publish.val().search(patt) == '-1' ){
        content_publish.insertAtCaret(text);

        var textArea = document.getElementById('topic_content');
        result = patt.exec( content_publish.val() );
        var end = patt.lastIndex-1 ;
        var start = patt.lastIndex - text.length +1;
        // if (document.selection) { //IE
        //      var rng = textArea.createTextRange();
        //      rng.collapse(true);
        //      rng.moveEnd("character",end)
        //      rng.moveStart("character",start)
        //      rng.select();
        // }else 
        if (textArea.selectionStart || (textArea.selectionStart == '0')) { // Mozilla/Netscape…
            textArea.selectionStart = start;
            textArea.selectionEnd = end;
        }
        textArea.focus();
        return ;
    }
}