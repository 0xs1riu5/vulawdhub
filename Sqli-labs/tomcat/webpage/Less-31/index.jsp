 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" > 

<%@ page import="java.io.*" %>
<%@ page import="java.net.*" %>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <TITLE>Less-31 WAF PROTECT</TITLE>
</HEAD>
<body bgcolor="#000000">

<%
	String id = request.getParameter("id");
	String qs = request.getQueryString();
		
	if(id!=null)
	{
		if(id!="")
		{
			try
			{
				String rex = "^\\d+$";
				Boolean match=id.matches(rex);
				if(match == true)
				{
					URL sqli_labs = new URL("http://index_url/Less-31/index.php?"+ qs);
			        URLConnection sqli_labs_connection = sqli_labs.openConnection();
			        BufferedReader in = new BufferedReader(
			                                new InputStreamReader(
			                                sqli_labs_connection.getInputStream()));
			        String inputLine;
			        while ((inputLine = in.readLine()) != null) 
			            out.print(inputLine);
			        in.close();
				}
				else
				{
					response.sendRedirect("hacked.jsp");
				}
			} 
			catch (Exception ex)
			{
				out.print("<font color= '#FFFF00'>");
				out.println(ex);
				out.print("</font>");				
			}
			finally
			{
				
			}
		}
		
	}
	else
	{
		URL sqli_labs = new URL("http://index_url/Less-31/index.php");
        URLConnection sqli_labs_connection = sqli_labs.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                sqli_labs_connection.getInputStream()));
        String inputLine;
        while ((inputLine = in.readLine()) != null) 
            out.print(inputLine);
        in.close();
	}
%>
</font> </div><center>

<font size='4' color= "#33FFFF">
<br>
<br>
Thanks to "int3rf3r3nc3" 
<a href="http://sectree.wordpress.com/">sectree.wordpress.com</a> for coding this page
<br><br>
</font>
<font size='3' color= '#99FF00'>
Refrences:
<a href="https://www.owasp.org/images/b/ba/AppsecEU09_CarettoniDiPaola_v0.8.pdf">AppsecEU09_CarettoniDiPaola_v0.8.pdf</a><br>
<a href="https://community.qualys.com/servlet/JiveServlet/download/38-10665/Protocol-Level Evasion of Web Application Firewalls v1.1 (18 July 2012).pdf">https://community.qualys.com/servlet/JiveServlet/download/38-10665/Protocol-Level Evasion of Web Application Firewalls v1.1 (18 July 2012).pdf</a>

</font>
</center>
 </BODY> 
</html>
