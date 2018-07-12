/**
 * Created by boooo on 14-2-6.
 */
var post = function(){$.ajax({
    url:'http://localhost/ts/index.php?app=public&mod=Feed&act=PostFeed',
    type:'POST',
    data:{
        body:'纳德拉的上位与微软渴求一位工程师背景的高管有直接关系。这家公司曾经成功预见到了科技发展的重大变化，比如智能手机和平板电脑的兴起。但在产品研发和资本化的执行层面，公司却频频失手。'+new Date(),
        type:'post',
        app_name:'public',
        content:'',
        attach_id:'',
        videourl:'',
        channel_id:''
    }
})};
var doPostId;
var doPost = function(){
    doPostId = setInterval(function(){
    post();
},500)};

var stop = function(){
    clearInterval(doPostId);
}

var start = function(){
    doPost();
}