<%@page import="java.net.URLDecoder"%>
<%@page import="java.net.URLEncoder"%>
<%@page import="tool.Base64"%>
<%@page import="java.sql.PreparedStatement"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="tool.DBUtil"%>
<%@page import="java.sql.Connection"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>index</title>
</head>
<body>
<div>新闻列表</div>
<div>新闻编号：${param.id}</div>
<%

	
	Connection conn=DBUtil.getMySQLConn();
	String id="0";
	Cookie[] cookies = request.getCookies();

	try{
	for(Cookie cookie : cookies){
		if(cookie.getName().equals("id")){
	    	id=URLDecoder.decode(cookie.getValue());
		}
	}
	}catch(Exception ee){
		
	}
	
	String sql="select * from news where id="+id;
	
	PreparedStatement pstmt=conn.prepareStatement(sql);
	ResultSet rs=pstmt.executeQuery();
	while(rs.next()){
		%>
		<div>标题:<%=rs.getString("title")+""%></div>
		<div>内容:content:<%=rs.getString("content")+""%></div>
		<%
	}

	conn.close();
%>
</body>
</html>