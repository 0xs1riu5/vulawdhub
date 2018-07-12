功能描述：

v1.0

1.实现首页分享置顶效果

2.支持多条分享置顶

3.后台可以设置，取消置顶分享

v1.3

1.新增用户可以删除当前置顶的分享，直至有新的分享置顶。

2.新增聚合展示历史置顶的分享，在首页右侧做推荐

3.调增了“置顶分享”的位置，放到了右上角。

4.优化了后台管理列表排序规则，后台新增删除功能


安装说明：

1.将分享置顶的插件包放到/addons/plugin/ 目录下。

2.自定义一个插件钩子：修改/apps/public/Tpl/default/Index/index.html，约在65行左右，添加下面钩子。

{:Addons::hook('home_index_left_feedtop')}   //分享置顶的钩子
  //此行一下不用添加，只是为了定位做参考
{:W('FeedList',array('type'=>$type,'feed_type'=>$feed_type,'feed_key'=>$feed_key,'fgid'=>$_GET['fgid']))}

3.然后进入后台->插件安装分享置顶，然后就可以使用分享置顶的功能了。

版本替换方法：

从v1.0升级到v1.3，下载新包直接覆盖即可（切记不要，如果之前正在使用，不要停止插件）。

技术支持：

欢迎大家给这个插件提出宝贵意见
联系作者：http://yunmai.me/index.php?app=public&mod=Profile&act=index&uid=feebas