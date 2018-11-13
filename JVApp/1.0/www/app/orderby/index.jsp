<%@page import="tool.DBUtil"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.PreparedStatement"%>
<%@page import="java.sql.Connection"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>index</title>
<style type="text/css">
.main{

margin-top:20px;
font-size:18px;
}
.main div{
line-height:30px;
}
a{
color:green;
}
</style>
</head>
<body>
<div class="main">
<div>1.<a href="userlist.jsp?order=id">新闻列表</a></div>
<div>2.<a href="userlist2.jsp?order=id">新闻列表，绕过空格</a></div>
</div>
</body>
</html>