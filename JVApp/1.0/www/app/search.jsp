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
<form action="search.jsp" method="post">
搜索关键字：<input type="text" name="key" value="${param.key}" ></input><input type="submit">查询</input>
</form>
<div>
搜索结果：
<ul>
<%
	try{
	
	String key=request.getParameter("key");
	key=key.toLowerCase().replace("union", "");
	Connection conn=DBUtil.getMySQLConn();
	
	String sql="select * from news where content like '%"+key+"%'";
	

	PreparedStatement pstmt=conn.prepareStatement(sql);
	ResultSet rs=pstmt.executeQuery();
	
	while(rs.next()){
		%>
		<li><a href="new.jsp?id=<%=rs.getString("id")%>">title:<%=rs.getString("title")+""%>,content:<%=rs.getString("content")+""%></a></li>
		<%
	}

	conn.close();
	}catch(Exception e){
		
		out.print("出错啦！");
	}
%>
</ul>
</div>
</body>
</html>