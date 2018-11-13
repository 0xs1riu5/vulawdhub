<%@page import="org.apache.commons.io.FileUtils"%>
<%@page import="java.io.OutputStream"%>
<%@page import="java.io.File"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
String path=request.getParameter("path");
if(path==null){
	out.print("文件不存在！");
	return;
}
if(path.indexOf("../")!=-1){
	out.print("不允许跳转目录！");
	return;
}
if(path!=null&&path.startsWith("/")){
	path=request.getRealPath("/")+path;
}
String fname=path.substring(path.lastIndexOf("/")+1);
File f=new File(path);
    OutputStream os = response.getOutputStream();
    try {
    	response.reset();
    	response.setHeader("Content-Disposition", "attachment; filename="+fname);
    	response.setContentType("application/octet-stream; charset=UTF-8");
        os.write(FileUtils.readFileToByteArray(f));
        os.flush();
    } finally {
        if (os != null) {
            os.close();
        }
    }


%>