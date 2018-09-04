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
    var ucinstalloptions = document.getElementsByName("ucinstall");


    $("js-pre-step").onclick = function() {
        location.href="./index.php?step=readme";
    };

    $("js-submit").onclick = function () {
        setupUCenter();
    }
};

/**
 * 连接Ucenter
 */
function setupUCenter()
{
   var f = $("js-setup");
   var ucinstalloptions = document.forms['js-setup'].ucinstall;
   var uccheck = true;


   if(f["js-ucapi"].value.length < 1)
   {
       $("ucapinotice").innerHTML='请填写UCenter的URL';
        uccheck = false;
   }
   else
   {
        $("ucapinotice").innerHTML='';
   }

    if (f['js-ucfounderpw'].value.length < 1)
    {
        $("ucfounderpwnotice").innerHTML='请填写 UCenter 创始人的密码';
        uccheck = false;
    }
    else
    {
        $("ucfounderpwnotice").innerHTML='';
    }

    if(uccheck == false)
    {
        return uccheck;
    }

        var params="ucapi=" + f["js-ucapi"].value + "&" + "ucfounderpw=" + f["js-ucfounderpw"].value;
        Ajax.call("./index.php?step=setup_ucenter", params, displayres, 'POST', 'JSON');

}

function displayres(res)
{
    if (res.error !== 0)
    {
        $("ucfounderpwnotice").innerHTML= res.message;
    }
    else
    {
        location.href="index.php?step=usersmerge";
    }
}
