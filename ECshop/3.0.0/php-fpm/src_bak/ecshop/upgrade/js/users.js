/* 初始化一些全局变量 */
var lf = "<br />";
var iframe = null;
var notice = null;
var oriDisabledInputs = [];

/* Ajax设置 */
Ajax.onRunning = null;
Ajax.onComplete = null;

/* 页面加载完毕，执行一些操作 */
window.onload = function () {
    var f = $("js-setup");


    $("js-pre-step").onclick = function() {
        location.href="./index.php?step=uccheck";
    };

    $("js-submit").onclick = function () {
        var params="maxuid=" + f["js-maxuid"].value;
        Ajax.call("./index.php?step=userimporttouc", params, displayres, 'POST', 'JSON');
    }
};

function displayres(res)
{
    if (res.error !== 0)
    {
        $("notice").innerHTML= res.message;
    }
    else
    {
		location.href="index.php?step=check";
    }
}