<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>admin</title>
</head>
<body>
<%
String loginName="";
boolean isAdmin=false;
for(Cookie ck :request.getCookies()){
	if(ck!=null){
		if("isadmin".equals(ck.getName())&&"admin".equals(ck.getValue())){
			isAdmin=true;
			break;
		}
	}
}
if(!isAdmin){
response.sendRedirect("index.jsp");
}
%>
登陆<%=loginName %>
<div>功能列表：</div>

<div><a href="addNew.jsp">添加新闻</a></div>
<div><a href="updatepassPage.jsp">修改密码</a></div>
<div><a href="logout.jsp">退出登录</a></div>
</body>
</html>