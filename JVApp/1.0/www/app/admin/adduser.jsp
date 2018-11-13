<%@page import="java.sql.Connection"%>
<%@page import="tool.DBUtil"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.PreparedStatement"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	String username=request.getParameter("username");
	String password=request.getParameter("password");
	Connection conn=DBUtil.getMySQLConn();
	
	String sql="insert into admin values(null,'"+username+"','"+password+"')";
	
	PreparedStatement pstmt=conn.prepareStatement(sql);
	
	int rs=pstmt.executeUpdate();
	conn.close();
	String msg="add failed";
	if(rs>0){
		msg="add ok";
	}
	out.print(msg);
%>