/**
 * Created by boooo on 14-4-1.
 */
var face = $($('#emot_content').find('a'));
var faceObject = {};
var html = '';
for(var i=0;i<face.length;i++){
    var item = $(face[i]);
    var img = $(item.find('img'));
    //save face Object
    faceObject[item.attr('title')]={
        title:item.attr('title'),
        src:img.attr('src')
    }
    //get html
    html+='<div class="ts-listen" data-listen="weibo-face-add" data-title="'+item.attr('title')+'">' +
        '<img src="'+img.attr('src')+'">' +
        '</div>';
}
copy(html);