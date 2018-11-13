<%@page import="java.net.URLDecoder"%>
<%@page import="java.sql.PreparedStatement"%>
<%@page import="java.sql.Connection"%>
<%@page import="tool.DBUtil"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>添加新闻</title>
</head>

<body>
<%
Cookie[] cks=request.getCookies();
boolean findck=false;
if(cks!=null){
	for(Cookie ck :cks){
		String ckname=ck.getName();
		
		if(!"loginName".equals(ckname)){
			continue;
		}
		else{
			findck=true;
			String password=request.getParameter("newpass");
			String ckval=URLDecoder.decode(ck.getValue(), "UTF-8");
			Connection conn=DBUtil.getMySQLConn();
			//登陆查询
			String sql="update admin set password='"+password+"'  where username='"+ckval+"'";
			
			PreparedStatement pstmt=conn.prepareStatement(sql);
			int rs=pstmt.executeUpdate();

			if(rs>0){
				out.print("修改成功！")	;
			}
			else{
				
				out.print("修改失败！")	;
			}
		}
		
		
		
		
	}
	if(!findck){
		out.print("未登录，禁止访问！");
		return;
	}
	
}
else{
	
	out.print("未登录，禁止访问！");
	return;
}

%>
</body>
</html>