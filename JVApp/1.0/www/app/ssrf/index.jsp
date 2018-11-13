<%@page import="java.io.InputStreamReader"%>
<%@page import="java.net.URL"%>
<%@page import="java.net.HttpURLConnection"%>
<%@page import="java.io.InputStream"%>
<%@page import="java.io.BufferedReader"%>
<%@page import="javax.imageio.ImageIO"%>
<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>我的头像</title>
</head>
<body>


<form action="" method="get">
<input type="text" name="url" />
<input type="submit" value="上传" />
</form>
<%
String url=request.getParameter("url");
if(url!=null&&!"".equals(url)){
	try{
    URL curl = new URL(url);
    HttpURLConnection connection=(HttpURLConnection)curl.openConnection();
  
    connection.setRequestProperty("User-Agent","Mozilla/4.0");
    connection.setConnectTimeout(10000);
   
   connection.connect(); 
   
   InputStream in= connection.getInputStream();
  
   BufferedReader reader=new BufferedReader(new InputStreamReader(in,"UTF-8"));
    
   String tempstr;
   String result="";
   
   while((tempstr=reader.readLine())!=null){ 
    	 
	   result+=tempstr;
	  
   }
   
  	out.print("<textarea rows=10 cols=100>"+result+"</textarea>");
   
   }catch (Exception e) {
	   throw e;
   }
	   
}
%>
</body>
</html>