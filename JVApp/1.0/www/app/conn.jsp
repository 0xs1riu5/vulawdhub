<%@page import="com.mysql.jdbc.PreparedStatement"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.DriverManager"%>
<%@page import="java.sql.SQLException"%>
<%@page import="java.sql.Connection"%>
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="UTF-8"%>
<%!

//数据库连接字符串
private static String url="jdbc:mysql://127.0.0.1:3306/test";
private static String name="root";
private static String pwd="lihang";

private static String surl="jdbc:sqlserver://127.0.0.1:1433;databaseName=Test";
private static String sname="sa";
private static String spwd="lihang";


//静态加载驱动
static {
	
	try {
		
		Class.forName("com.microsoft.sqlserver.jdbc.SQLServerDriver");
		Class.forName("com.mysql.jdbc.Driver");
	
	} catch (ClassNotFoundException e) {
		
		e.printStackTrace();
	}
}

//获得数据库连接
public static Connection getConn(){
	
	Connection conn=null;
	
	try {
		//得到数据库连接
		conn=DriverManager.getConnection(url,name,pwd);
		
	} catch (SQLException e) {
		
		e.printStackTrace();
	}
	
	return conn;
}

//获得数据库连接
	public static Connection getSqlServerConn(){
		
		Connection conn=null;
		
		try {
			//得到数据库连接
			conn=DriverManager.getConnection(surl,sname,spwd);
			
		} catch (SQLException e) {
			
			e.printStackTrace();
		}
		
		return conn;
	}


public static void closeConn(ResultSet rs,PreparedStatement pstmt,Connection conn){
	
	if(rs!=null){
		
	try {
			
			rs.close();
				
		} catch (SQLException e) {
			
			System.out.println("pstmt对象关闭失败..");
			e.printStackTrace();
			
		}
	}

	if(pstmt!=null){
			
		try {
				
			pstmt.close();
				
			
		} catch (SQLException e) {
			
			System.out.println("pstmt对象关闭失败..");
			e.printStackTrace();
			
		}
	}
	
	
		
	if(conn!=null){
			
		try {
				
			conn.close();
				
		
		} catch (SQLException e) {
			
			System.out.println("conn对象关闭失败..");
			e.printStackTrace();
			
		}
	}

}
%>