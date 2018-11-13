<%@page import="java.io.File"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<%
String path=request.getRealPath("/test.txt");
File f=new File(path);
if(!f.exists()){
	out.println("Fatal error:"+path);
}
//throw new Exception("文件不存在："+path);
%>
</body>
</html>