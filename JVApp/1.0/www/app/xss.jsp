<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>测试</title>
<script type="text/javascript">
var id=<%=request.getParameter("id")%>;
document.getElementById("show").innerHTML="id值为："+id;
</script>
</head>
<body>
<div id="show"></div>
<div>
<%=request.getParameter("name")%>
</div>
</body>
</html>