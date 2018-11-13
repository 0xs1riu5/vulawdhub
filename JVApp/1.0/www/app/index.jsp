<%@page import="java.io.File"%>
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
<style type="text/css">
.main{

margin-top:20px;
font-size:18px;
}
.main div{
line-height:30px;
}
a{
color:green;
}
</style>
</head>
<body>
<div class="main">
<div>1.<a href="mysql.jsp?id=1">mysql union,boolean注入数字型</a></div>
<div>2.<a href="mysqlString.jsp?type=1">mysql union,boolean注入字符型</a></div>
<div>3.<a href="mysql/1.html">urlRewrite,mysql,伪静态注入</a></div>
<div>4.<a href="sqlserver.jsp?id=1">sqlserver union,报错，boolean注入</a></div>
<div>5.<a href="search.jsp?key=a">搜索</a></div>
<div>6.<a href="test.action">struts2漏洞(s-16)</a></div>
<div>7.<a href="newslist.jsp">新闻列表</a></div>
<div>8.<a href="readFile.jsp">路径泄露</a></div>
<div>9.<a href="oracle.jsp?id=1">oracle union,报错，boolean注入</a></div>
<div>10.<a href="admin/index.jsp">管理后台</a></div>
<div>11.<a href="csrf.jsp">csrf</a></div>
<div>12.<a href="fileupload/index.html">upload File</a></div>
<div>13.<a href="foward.jsp?url=index.jsp">foward URL</a></div>
<div>14.<a href="stoken.jsp">Token 注入</a></div>
<div>15.<a href="admin/logincheck.jsp">XFF and Referer insert 注入</a></div>
<div>16.<a href="orderby/user.jsp?id=1">orderby注入</a></div>
<div>17.<a href="orderby/userlist.jsp?id=id">order by注入绕过空格</a></div>
<div>18.<a href="admin/updatepassPage.jsp">Cookie注入</a></div>
<div>19.<a href="ue.jsp">UEditor多个文件上传漏洞</a></div>
<div>20.<a href="injectInCookie.jsp">CookieSQL注入</a></div>
</div>
</body>
</html>