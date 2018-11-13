<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<%
String orderBy="";
String orderByStr=request.getParameter("orderByStr");
int order=Integer.parseInt(orderBy);
switch(order){
case 1:
	orderBy="id";
	break;
case 2:
	orderBy="name";
	break;
case 3:
	orderBy="sex";
	break;

}
//根据orderBy去进行排序
%>