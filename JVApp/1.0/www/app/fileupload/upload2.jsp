<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.*,java.io.*" %>
<%@ page import="java.text.SimpleDateFormat" %>
<%@ page import="org.apache.commons.fileupload.*" %>
<%@ page import="org.apache.commons.fileupload.disk.*" %>
<%@ page import="org.apache.commons.fileupload.servlet.*" %>
<%

//文件保存目录路径
String savePath = pageContext.getServletContext().getRealPath("/") + "upload/";

//文件保存目录URL
String saveUrl  = request.getContextPath() + "/upload/";
//最大文件大小
long maxSize = 20000000;

response.setContentType("text/html; charset=UTF-8");

if(!ServletFileUpload.isMultipartContent(request)){
	out.println("请选择文件。");
	return;
}
//检查目录
File uploadDir = new File(savePath);
if(!uploadDir.isDirectory()){
	out.println("上传目录不存在。");
	return;
}
//检查目录写权限
if(!uploadDir.canWrite()){
	out.println("上传目录没有写权限。");
	return;
}

File saveDirFile = new File(savePath);
if (!saveDirFile.exists()) {
	saveDirFile.mkdirs();
}

FileItemFactory factory = new DiskFileItemFactory();
ServletFileUpload upload = new ServletFileUpload(factory);
upload.setHeaderEncoding("UTF-8");
List items = upload.parseRequest(request);
Iterator itr = items.iterator();
while (itr.hasNext()) {
	FileItem item = (FileItem) itr.next();
	
	String fileName = item.getName();
	long fileSize = item.getSize();
	if (!item.isFormField()) {
		//检查文件大小
		
		if(item.getSize() > maxSize){
			out.println("上传文件大小超过限制。");
			return;
		}
		//检查扩展名
		String fileExt = fileName.substring(fileName.lastIndexOf(".") + 1).toLowerCase();
		if(!"image/jpg".equals(item.getContentType())&&!"image/jpeg".equals(item.getContentType())){
			out.println("上传文件类型错误。"+item.getContentType());
			return;
		}

		SimpleDateFormat df = new SimpleDateFormat("yyyyMMddHHmmss");
		String newFileName = df.format(new Date()) + "_" + new Random().nextInt(100000) + "." + fileExt;
		try{
			File uploadedFile = new File(savePath, newFileName);
			item.write(uploadedFile);
		}catch(Exception e){
			out.println("上传文件失败。");
			return;
		}
		out.println(saveUrl+"/"+newFileName+"上传成功，十分感谢|upload success,thanks...");
	}
}
%>