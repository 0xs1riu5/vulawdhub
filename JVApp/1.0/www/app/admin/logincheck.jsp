<%@page import="java.net.URLEncoder"%>
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
	try{
	String username=request.getParameter("username");
	String password=request.getParameter("password");
	
	Connection conn=DBUtil.getMySQLConn();
	//登陆查询
	String sql="select * from admin where username='"+username+"' and password='"+password+"'";
	String referer=request.getHeader("Referer");
	//添加登陆记录
	//X-Forwarded-For这里也可能导致注入额
	String ip=request.getHeader("X-Forwarded-For");
	if(ip==null||"".equals(ip)){
		ip=request.getRemoteAddr();
	}
	String addSql="insert into logs values(null,'"+ip+"','"+referer+"',NOW())";
	
	PreparedStatement pstmt_log=conn.prepareStatement(addSql);
	pstmt_log.executeUpdate();
	
	//执行查询
	PreparedStatement pstmt=conn.prepareStatement(sql);
	
	ResultSet rs=pstmt.executeQuery();
	//查询数据不为空，账户密码正确---过滤过滤-预编译预编译
	if(rs.next()){
		
		Cookie ck=new Cookie("loginName",URLEncoder.encode(rs.getString("username"), "UTF-8"));
		ck.setMaxAge(3600);
		Cookie pass=new Cookie("pass",URLEncoder.encode(rs.getString("password"), "UTF-8"));
		response.addCookie(ck);
		response.addCookie(pass);
		response.sendRedirect("admin.jsp");
	}
	out.print("登陆失败了no！");
	conn.close();
	}catch(Exception e){
		
		throw e;
	}
%>
</ul>
</body>
</html>