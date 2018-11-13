<%@page import="org.owasp.esapi.codecs.OracleCodec"%>
<%@page import="org.owasp.esapi.codecs.MySQLCodec"%>
<%@page import="org.owasp.esapi.codecs.Codec"%>

<%@page import="org.owasp.esapi.ESAPI"%>
<%@page import="java.net.HttpCookie"%>
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
	Codec mysql = new OracleCodec();
	String sql="select * from news where id=?";

	PreparedStatement pstmt=conn.prepareStatement(sql);
	pstmt.setString(1, request.getParameter("id"));
	ResultSet rs=pstmt.executeQuery();
	
	while(rs.next()){
		%>
		<div>标题:<%=rs.getString("title")+""%></div>
		<div>内容:content:<%=rs.getString("content")+""%></div>
		<%
	}

	conn.close();
	/*
    Cookie ck=new Cookie("id",request.getParameter("id"));
    ck.setHttpOnly(true);
    Cookie ck2=new Cookie("id2","xxxx");
    ck2.setHttpOnly(true);
    response.addCookie(ck2); */

%>
</body>
</html>