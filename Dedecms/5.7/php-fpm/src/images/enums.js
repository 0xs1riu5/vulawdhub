<!--

//选择地区的二级分类(非通用调用)
function selNext(oj, v)
{
    var newobj = oj.options;
    var selv = parseInt(v);
    var maxv = parseInt(v) + 500;
    while(newobj.length > 0) {
        oj.remove(0);
    }
    clear(oj);
    if(selv==0)
    {
        aOption = document.createElement('OPTION');
        aOption.text = '具体地区';
        aOption.value = '0';
        oj.options.add(aOption);
        return;
    }
    else
    {
        aOption = document.createElement('OPTION');
        aOption.text = '具体地区';
        aOption.value = '0';
        oj.options.add(aOption);
    }
    var str = '';
    for(i=selv+1; i < maxv; i++)
    {
        if(!em_nativeplaces[i]) continue;
        aOption = document.createElement('OPTION');
        aOption.text = em_nativeplaces[i];
        aOption.value = i;
        oj.options.add(aOption);
    }
}


//子类改变事件
function ChangeSon()
{
    /*
    var emname = this.name.replace('_son', '');
    var topSelObj = document.getElementById(emname+'_top');
    if(this.options[this.selectedIndex].value==0) {
        document.getElementById('hidden_'+emname).value = topSelObj.options[topSelObj.selectedIndex].value;
    }
    else {
        document.getElementById('hidden_'+emname).value = this.options[this.selectedIndex].value;
    }
    */
    // 由于支持3级联动,所以这里需要对自己改变进行重构
    var emname = this.name.replace('_son', '');
    // alert(emname);
    if( document.getElementById(emname+'_sec') )
    {
        var oj = document.getElementById(emname + '_sec');
    }
    else
    {
        var oj  = document.createElement('select');
        oj.name = emname + '_sec';
        oj.id   = emname + '_sec';
        oj.onchange = ChangeSec;
    }
    var v = this.options[this.selectedIndex].value;
    document.getElementById('hidden_'+emname).value = v;
    var newobj = oj.options;
    var selarr = eval('em_'+emname+'s');

    var selv = parseInt(v);
    var maxv = parseInt(v) + 0.5;
    // alert(maxv);
    i = 0;
    while(newobj && newobj.length > 0) oj.remove(0);
    clear(oj);
    if(selv == 0)
    {
        aOption = document.createElement('OPTION');
        aOption.text = '请选择..';
        aOption.value = '0';
        oj.options.add(aOption);
        return;
    }
    else
    {
        aOption = document.createElement('OPTION');
        aOption.text = '请选择..';
        aOption.value = '0';
        oj.options.add(aOption);
    }
    var str = '';
    var j=0;
    for(i = selv + 0.001; i < maxv; i = FloatAdd(i, 0.001))
    {
        if(!selarr[i]) continue;
        aOption = document.createElement('OPTION');
        aOption.text = selarr[i];
        aOption.value = i;
        oj.options.add(aOption);
        j++;
    }
    if(j > 0) document.getElementById('span_'+emname+'_sec').appendChild(oj);
    else document.getElementById('span_'+emname+'_sec').innerHTML = "";
}

// 改变第三级的事件
function ChangeSec()
{
    var emname = this.name.replace('_sec', '');
    var topSelObj = document.getElementById(emname+'_top');
    if(this.options[this.selectedIndex].value==0) {
        document.getElementById('hidden_'+emname).value = topSelObj.options[topSelObj.selectedIndex].value;
    }
    else {
        document.getElementById('hidden_'+emname).value = this.options[this.selectedIndex].value;
    }
}

//顶级类改变事件
function selNextSon()
{
    var emname = this.name.replace('_top', '');
    if( document.getElementById(emname+'_son') )
    {
        var oj = document.getElementById(emname + '_son');
    }
    else
    {
        var oj  = document.createElement('select');
        oj.name = emname + '_son';
        oj.id   = emname + '_son';
        oj.onchange = ChangeSon;
    }
    var v = this.options[this.selectedIndex].value;
    document.getElementById('hidden_'+emname).value = v;
    var newobj = oj.options;
    var selarr = eval('em_'+emname+'s');
    var selv = parseInt(v);
    var maxv = parseInt(v) + 500;
    while(newobj && newobj.length > 0) oj.remove(0);
    clear(oj);
    if(selv==0)
    {
        aOption = document.createElement('OPTION');
        aOption.text = '请选择..';
        aOption.value = '0';
        oj.options.add(aOption);
        return;
    }
    else
    {
        aOption = document.createElement('OPTION');
        aOption.text = '请选择..';
        aOption.value = '0';
        oj.options.add(aOption);
    }
    var str = '';
    for(i=selv+1; i < maxv; i++)
    {
        if(!selarr[i]) continue;
        aOption = document.createElement('OPTION');
        aOption.text = selarr[i];
        aOption.value = i;
        oj.options.add(aOption);
    }
    document.getElementById('span_'+emname+'_son').appendChild(oj);
}


// 根据数组生成多级联动菜单
function MakeTopSelect(emname, selvalue)
{
    var selectFormHtml = '';
    var aOption = null;
    var selObj = document.createElement("select");
    selObj.name = emname + '_top';
    selObj.id   = emname + '_top';
    selObj.onchange = selNextSon;
    var selarr = eval('em_'+emname+'s');
    // alert(selarr[1002.2]);
    var topvalue = 0;
    var sonvalue = 0;
    var secvalue = 0;
    
    aOption = document.createElement('OPTION');
    aOption.text = '请选择..';
    aOption.value = 0;
    selObj.options.add(aOption);
    
    // alert(selvalue);
    if(selvalue % 500 == 0 ) 
    {
        topvalue = selvalue;
    }
    // 如果是小数,则依次取出顶级数值,二级数值以及三级数值
    else if(!!(selvalue % 1))
    {
        secvalue = selvalue;
        sonvalue = Math.floor(selvalue);
        topvalue = sonvalue - (sonvalue % 500);
        // alert(secvalue);
    }
    else {
        sonvalue = selvalue;
        topvalue = selvalue - (selvalue % 500);
    }
    
    for(i = 500; i <= selarr.length; i += 500)
    {
        if(!selarr[i]) continue;
        aOption = document.createElement('OPTION');
        if(i == topvalue) {
            aOption = document.createElement('OPTION');
            aOption.text = selarr[i];
            aOption.value = i;
            selObj.options.add(aOption);            
            aOption.selected = true;
        }
        else {
            aOption = document.createElement('OPTION');
            aOption.text = selarr[i];
            aOption.value = i;
            selObj.options.add(aOption);
        }
    }
    document.getElementById('span_'+emname).appendChild(selObj);
    
    //如果子类存在值，创建子类
    //if(sonvalue > 0 || topvalue > 0) {
    selObj = document.createElement("select");
    selObj.name = emname + '_son';
    selObj.id   = emname + '_son';
    selObj.onchange = ChangeSon;
    aOption = document.createElement('OPTION');
    aOption.text = '请选择..';
    aOption.value = 0;
    selObj.options.add(aOption);
    
    //当大类有值输出子类
    if(topvalue > 0)
    {
        var selv = topvalue;
        var maxv = parseInt(topvalue) + 500;
        for(i = selv + 1; i < maxv; i++)
        {
            if(!selarr[i]) continue;
            aOption = document.createElement('OPTION');
            if(i == sonvalue) {
                aOption = document.createElement('OPTION');
                aOption.text = selarr[i];
                aOption.value = i;
                selObj.options.add(aOption);
                aOption.selected = true;
            }
            else {
                aOption = document.createElement('OPTION');
                aOption.text = selarr[i];
                aOption.value = i;
                selObj.options.add(aOption);
            }
        }
    }
    document.getElementById('span_'+emname+'_son').appendChild(selObj);
    
    // 若存在第三级则创建
    if(secvalue > 0)
    {
        selObj = document.createElement("select");
        selObj.name = emname + '_sec';
        selObj.id   = emname + '_sec';
        selObj.onchange = ChangeSec;
        aOption = document.createElement('OPTION');
        aOption.text = '请选择..';
        aOption.value = 0;
        selObj.options.add(aOption);
        
        var selv = sonvalue;
        var maxv = parseInt(sonvalue) + 0.5;
        // alert(maxv);
        i = 0;
        for(i = selv + 0.001; i < maxv; i = FloatAdd(i, 0.001))
        {
            if(!selarr[i]) continue;
            aOption = document.createElement('OPTION');
            if(i == secvalue) {
                aOption = document.createElement('OPTION');
                aOption.text = selarr[i];
                aOption.value = i;
                selObj.options.add(aOption);
                aOption.selected = true;
            }
            else {
                aOption = document.createElement('OPTION');
                aOption.text = selarr[i];
                aOption.value = i;
                selObj.options.add(aOption);
            }
        }
    }
    document.getElementById('span_'+emname+'_sec').appendChild(selObj);
}

// 两个小数相加进度计算
function FloatAdd(arg1, arg2)
{
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2))
    return (arg1*m+arg2*m)/m
}

//清除旧对象
function clear(o)
{
    l=o.length;
    for (i = 0; i< l; i++){
        o.options[1]=null;
    }
}

-->