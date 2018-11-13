<%@page import="java.io.PrintWriter"%>
<%@page import="tool.DBUtil"%>
<%@page import="java.sql.ResultSet"%>
<%@page import="java.sql.PreparedStatement"%>
<%@page import="java.sql.Connection"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%!
public int checkStr(String str){
	
	if(str!=null&&(str.indexOf("-")!=-1||str.indexOf("#")!=-1)){
		
		return 0;
	}
	return 1;
}
public String replaceStr(String str){
	if(str!=null){
		
		return str.replace("\'", "\\'");
	}
	return str;
}
%>
<%
String op=request.getParameter("op");

if("add".equals(op)){
	
	PreparedStatement pstmt=null;
	Connection conn=null;
	ResultSet rs=null;
	try{
		//获取客户端提交的值
		
		String phone=request.getParameter("phone");
		if(phone==null){
			phone="";
		}
		if(checkStr(phone)!=1){
			throw new Exception("发现注释");
		}
		phone=replaceStr(phone);
		String email=request.getParameter("email");
		if(email==null){
			phone="";
		}
		if(checkStr(email)!=1){
			throw new Exception("发现注释");
		}
		email=replaceStr(email);
		String nikename=request.getParameter("nikename");
		if(nikename==null){
			phone="";
		}
		if(checkStr(nikename)!=1){
			throw new Exception("发现注释");
		}
		nikename=replaceStr(nikename);
		String column=request.getParameter("column");
		if(column==null){
			phone="";
		}
		if(checkStr(column)!=1){
			throw new Exception("发现注释");
		}
		column=replaceStr(column);
		String value=request.getParameter("value");
		if(value==null){
			phone="";
		}
		if(checkStr(value)!=1){
			throw new Exception("发现注释");
		}
		value=replaceStr(value);
		//取得数据库连接
		conn=DBUtil.getMySQLConn();
		//组合数据库查询SQL语句
		String sql="insert into inserttest(phone,email,nikename,"+column+",time) values('"+phone+"','"+email+"','"+nikename+"','"+value+"','"+System.currentTimeMillis()+"')";
		System.out.print(sql);
		//创建查询
		pstmt=conn.prepareStatement(sql);
		//执行查询
		int r=pstmt.executeUpdate();
		if(r!=0){
			
			out.print("insert ok");
		}
	}
	finally{
	DBUtil.closeConn(rs, pstmt, conn);
	}
}

	
	
%>
<html><head>
   <title>sql injection</title>
   <link href="static/bootstrap.min.css" rel="stylesheet">
   <script src="static/jquery.min.js"></script>
   <script src="static/bootstrap.min.js"></script>
<script id="gjzonedword20150522" src="http://s.pc.qq.com/pcmgr/zonedword/gjzonedword20150522.js" charset="UTF-8" gjguid="0bb2a985e7d1e9e246f54ae5b8d3f2b3" bid="1" sename="百度搜索" seurl="https://www.baidu.com/s?wd=%s&amp;tn=98012088_5_dg&amp;ch=11"></script></head>
<body>
	<div class="container" style="margin-top: 100px;">  
		<form class="well" id="send" style="margin: 0px auto; width: 220px;" action="?op=add" method="post"> 
			<img class="img-memeda " style="margin: 0px auto; width: 180px;" src="static/papapa.png">
			<h3>Please complete Your Profile</h3>
			<label>Phone:</label>
			<input name="phone" class="span3" style="height: 30px;" type="text">
			<label>Email:</label>
			<input name="email" class="span3" style="height: 30px;" type="text">
			<label>Nickname:</label>
			<input name="nickname" class="span3" style="height: 30px;" type="text">
            <select name="column">
                <option value="birth">birth</option>
                <option value="address">address</option>
            </select>
			<input name="value" class="span3" style="height: 30px;" type="text">
			<button class="btn btn-primary" type="submit">submit</button>
            
		</form>
	</div>


</body></html>