<%@page import="java.sql.Connection"%>
<%@page import="tool.DBUtil"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.PreparedStatement"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	String title=request.getParameter("title");
	String content=request.getParameter("content");
	Connection conn=DBUtil.getMySQLConn();
	
	String sql="insert into news values(null,'"+title+"','"+content+"')";
	
	PreparedStatement pstmt=conn.prepareStatement(sql);
	
	int rs=pstmt.executeUpdate();
	conn.close();
	String msg="add failed";
	if(rs>0){
		msg="add ok";
	}
	request.setAttribute("msg", msg);
	response.sendRedirect("addNew.jsp");
%>