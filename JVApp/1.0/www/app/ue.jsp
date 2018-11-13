<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	String path = request.getContextPath();
	String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>添加内容</title>
<!--  ueditor -->
<script type="text/javascript" charset="utf-8">window.UEDITOR_HOME_URL = "<%=path%>/static/js/ueditor/";</script>
<script type="text/javascript" charset="utf-8" src="<%=basePath %>static/js/ueditor/editor_config.js"></script>
<script type="text/javascript" charset="utf-8" src="<%=basePath %>static/js/ueditor/editor_all_min.js"></script>
<link rel="stylesheet" type="text/css" href="<%=basePath %>static/js/ueditor/themes/default/ueditor.css" />
</head>

<body>
<div>
<script type="text/plain" id="myEditor" class="myEditor" style="width:100%">${content.content}</script>
<script type="text/javascript">
			 var editor = new baidu.editor.ui.Editor({
				 textarea:'content.content'
				 });
			 editor.render('myEditor');
			 
</script>
</div>
</body>
</html>