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
	//获取客户端提交的id值
	String id=request.getParameter("id");
	
	if(id.toLowerCase().indexOf("select")!=-1){
		out.print("发现非法字符！");
		return;
	}
	
	//取得数据库连接
	conn=DBUtil.getMySQLConn();
	//组合数据库查询SQL语句
	String sql="select * from users where id="+id;
	//创建查询
	pstmt=conn.prepareStatement(sql);
	//执行查询
	rs=pstmt.executeQuery();
	//输出数据
	while(rs.next()){
		%>
<div><li><%=rs.getString("phone")+""%>-----<%=rs.getString("email")+""%>-----<%=rs.getString("nikename")+""%>-----<%=rs.getString("address")+""%></li></div>
		<%
	}
}catch(Exception e){
	out.print("error");
}
finally{
DBUtil.closeConn(rs, pstmt, conn);
}
	
	
%>
</ul>
</body>
</html>