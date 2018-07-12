var i=0;

function timedCount()
{
i=i+1;
postMessage("消息轮询中( "+i+" )");
setTimeout("timedCount()",500);
}

timedCount();