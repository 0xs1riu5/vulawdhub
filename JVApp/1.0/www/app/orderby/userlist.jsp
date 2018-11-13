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
<style>
#main{
width:500px;
height: 100%;
margin: 0px auto;
}
a{
color:#666;
}
table{
border-collapse:collapse;
width:100%;
}
table td{
border-bottom: 1px solid #f3f3f3;
line-height: 30px;
}
</style>
</head>
<body>
<div id="main">
<div>新闻列表</div>
<div>
<%
	try{
	
	String orderby=request.getParameter("order");
	
	Connection conn=DBUtil.getMySQLConn();
	
	String sql="select * from users";
	if(orderby!=null&&orderby.length()>0){
		sql+=" order by "+orderby;
	}

	PreparedStatement pstmt=conn.prepareStatement(sql);
	ResultSet rs=pstmt.executeQuery();
	%>
		<table>
		<tr>
		<td align="center"><a href="?order=id">编号</a></td><td align="center"align="center"><a href="?order=phone">电话</a></td><td align="center"><a href="?order=email">邮箱</a></td><td align="center"><a href="?order=nikename">用户名</a></td><td align="center"><a href="?order=address">地址</a></td>
		</tr>
	<%
	while(rs.next()){
		%>
	
		<tr><td><a href="user.jsp?id=<%=rs.getString("id")%>"><%=rs.getString("id")%></a></td><td><%=rs.getString("phone")+""%></td><td><%=rs.getString("email")+""%></td><td><%=rs.getString("nikename")+""%></td><td><%=rs.getString("address")+""%></td>
			<%
			
	}
	
	%>
	</table>
	<%
	conn.close();
	}catch(Exception e){
		
		throw e;
	}
%>
</div>
</div>
</body>
</html>