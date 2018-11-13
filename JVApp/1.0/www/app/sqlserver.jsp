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
<ul>
<%
	PreparedStatement pstmt=null;
	Connection conn=null;
	ResultSet rs=null;
	try{
	String id=request.getParameter("id");
	
	conn=DBUtil.getSQLServerConn();
	String sql="select * from news where id="+id.toLowerCase();
	System.out.println(sql);
	
	pstmt=conn.prepareStatement(sql);
	
	rs=pstmt.executeQuery();
	
	while(rs.next()){
		%>
		<li><%=rs.getString("title") %></li>
		<%
	}
	}
	finally{
	DBUtil.closeConn(rs, pstmt, conn);
	}
	
%>
</ul>
</body>
</html>