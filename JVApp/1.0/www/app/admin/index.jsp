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
</head>
<body>
<div style="width:300px;margin: 0px auto">
<form method="post" action="logincheck.jsp">
账号：<input type="text" name="username"></input></br>
密码：<input type="text" name="password"></input></br>
<input type="hidden" name="from" value="test">
<input type="submit">登陆</input>
</form>
</div>
</body>
</html>