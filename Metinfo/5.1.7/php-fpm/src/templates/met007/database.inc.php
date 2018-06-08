<?php
/*
《公共数据调用配置文件》
此配置文件主要用于提高程序效率，在不清楚系统运行机制的情况下请不要随意修改；
模板制作时请将此文件放置于模板文件夹下面，如met001/database.inc.php，并根据模板实际调用的数组进行参数优化；
所有参数值0为不调用，1为调用；
如果模板参数没有定义，则调用系统默认参数，模板参数命名规则：$dataoptimize[模块标识][具体参数标识]
*/
//全站默认配置(模板标识为10000)
$dataoptimize_html='html';//模板文件类型，可以为htm,html,php
$dataoptimize[10000][otherinfo]=1;//是否调用备用字段
$dataoptimize[10000][parameter]=0;//是否调用产品、图片、下载模块参数
$dataoptimize[10000][news]=0;//是否调用按更新时间排序的公共文章列表
$dataoptimize[10000][hitsnews]=0;//是否调用按点击次数排序的公共文章列表
$dataoptimize[10000][product]=0;//是否调用按更新时间排序的公共产品列表
$dataoptimize[10000][hitsproduct]=0;//是否调用按点击次数排序的公共产品列表
$dataoptimize[10000][download]=0;//是否调用按更新时间排序的公共下载列表
$dataoptimize[10000][hitsdownload]=0;//是否调用按点击次数排序的公共下载列表
$dataoptimize[10000][img]=0;//是否调用按更新时间排序的公共图片列表
$dataoptimize[10000][hitsimg]=0;//是否调用按点击次数排序的公共图片列表
$dataoptimize[10000][link]=0;//是否调用公共友情链接列表
$dataoptimize[10000][categoryname]=0;//是否调用公共信息列表对应的栏目名称
$dataoptimize[10000][para][3]=0;//是否调用产品模块信息列表参数内容
$dataoptimize[10000][para][4]=0;//是否调用下载模块信息列表参数内容
$dataoptimize[10000][para][5]=0;//是否调用图片模块信息列表参数内容

//首页调用参数配置(模板标识为10001)
$dataoptimize[10001][parameter]=1;//是否调用产品、图片、下载模块参数
$dataoptimize[10001][para][4]=1;//是否调用下载模块信息列表参数内容
$dataoptimize[10001][news]=1;//是否调用按更新时间排序的公共文章列表
$dataoptimize[10001][hitsnews]=0;//是否调用按点击次数排序的公共文章列表
$dataoptimize[10001][product]=1;//是否调用按更新时间排序的公共产品列表
$dataoptimize[10001][hitsproduct]=0;//是否调用按点击次数排序的公共产品列表
$dataoptimize[10001][download]=1;//是否调用按更新时间排序的公共下载列表
$dataoptimize[10001][hitsdownload]=0;//是否调用按点击次数排序的公共下载列表
$dataoptimize[10001][img]=1;//是否调用按更新时间排序的公共图片列表
$dataoptimize[10001][hitsimg]=0;//是否调用按点击次数排序的公共图片列表
$dataoptimize[10001][link]=1;//是否调用公共友情链接列表
$dataoptimize[10001][job]=0;//是否调用首页招聘信息列表

//简介模块调用参数配置(模板标识为1)


//文章模块调用参数配置(模板标识为2)
$dataoptimize[2][otherlist]=1;//文章内容页是否调用相关文章信息列表
$dataoptimize[2][classname]=0;//是否调用文字信息列表对应的栏目名称
$dataoptimize[2][nextlist]=1;//是否调用文章模块上一条下一条信息

//产品模块调用参数配置(模板标识为3)
$dataoptimize[3][parameter]=1;//是否调用产品、图片、下载模块参数
$dataoptimize[3][otherlist]=1;//产品内容页是否调用相关产品信息列表
$dataoptimize[3][classname]=0;//是否调用产品信息列表对应的栏目名称
$dataoptimize[3][nextlist]=1;//是否调用产品模块上一条下一条信息
$dataoptimize[3][para][3]=1;//是否调用产品模块信息列表参数内容

//下载模块调用参数配置(模板标识为4)
$dataoptimize[4][parameter]=1;//是否调用产品、图片、下载模块参数
$dataoptimize[4][otherlist]=0;//下载内容页是否调用相关下载信息列表
$dataoptimize[4][classname]=0;//是否调用下载信息列表对应的栏目名称
$dataoptimize[4][nextlist]=1;//是否调用下载模块上一条下一条信息
$dataoptimize[4][para][4]=1;//是否调用下载模块信息列表参数内容

//图片模块调用参数配置(模板标识为5)
$dataoptimize[5][parameter]=1;//是否调用产品、图片、下载模块参数
$dataoptimize[5][otherlist]=0;//图片内容页是否调用相关图片信息列表
$dataoptimize[5][classname]=0;//是否调用图片信息列表对应的栏目名称
$dataoptimize[5][nextlist]=1;//是否调用图片模块上一条下一条信息
$dataoptimize[5][para][5]=1;//是否调用图片模块信息列表参数内容

//招聘模块调用参数配置(模板标识为6)
$dataoptimize[6][nextlist]=1;//是否调用招聘上一条下一条信息

//留言模块调用参数配置(模板标识为7)

//反馈模块调用参数配置(模板标识为8)

//友情链接模块调用参数配置(模板标识为9)
$dataoptimize[9][link]=1;//是否调用公共友情链接列表

//会员模块调用参数配置(模板标识为10)

//搜索模块调用参数配置(模板标识为11)

//网站地图调用参数配置(模板标识为12)

//产品列表调用参数配置(模板标识为100)
$dataoptimize[100][parameter]=1;//是否调用产品、图片、下载模块参数

//图片列表调用参数配置(模板标识为101)
$dataoptimize[101][parameter]=1;//是否调用产品、图片、下载模块参数
?>