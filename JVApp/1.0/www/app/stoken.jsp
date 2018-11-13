<%@page import="java.util.UUID"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.PreparedStatement"%>
<%@page import="tool.DBUtil"%>
<%@page import="java.sql.Connection"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>搜索</title>
</head>
<body>
<form action="token.jsp" method="post">

<%
	String uuid=UUID.randomUUID().toString();
	request.getSession().setAttribute("token", uuid);
	%>
	<input type="hidden" name="token" value="<%=uuid %>" ></input>
	<%
%><input type="submit">查询</input>
搜索关键字：<input type="text" name="key" value="" ></input><input type="submit">查询</input>
</form>
<div>

</div>
</body>
</html>