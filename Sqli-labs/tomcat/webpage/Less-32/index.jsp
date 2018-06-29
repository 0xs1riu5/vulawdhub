 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" > 

<%@ page import="java.sql.*" %> 
<%@ page import="java.io.*" %>
<%@ page errorPage="error.html" %>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <TITLE>Less-32 Fun with JSP</TITLE>
</HEAD>
<body bgcolor="#000000">
<div style=" margin-top:50px;color:#FFF; font-size:40px; text-align:center">Welcome&nbsp;&nbsp;<font color="#FF0000"> Dhakkan </font><br>
<font size="3" color="#FFFF00">


<%
	String id = request.getParameter("id");
	String hint = id;
	
	
	String connectionURL = "jdbc:mysql://db:3306/security";
	Connection connection = null;
	Statement pstatement = null;
	Class.forName("com.mysql.jdbc.Driver").newInstance();
	int updateQuery = 0;
	if(id!=null)
	{
		if(id!="")
		{
			try
			{
				connection = DriverManager.getConnection(connectionURL, "root", "toor");
				String queryString = "SELECT * FROM users where id=('"+id+"') LIMIT 0,1";
				pstatement = connection.createStatement();
				ResultSet rs = pstatement.executeQuery(queryString);
				while(rs.next())
				{
					out.print("<font size='5' color= '#99FF00'>");
					out.print("Username: "+rs.getString(2));
					out.print("<br>");
					out.print("Password: "+rs.getString(3));
					out.print("</font>");
					
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
				pstatement.close();
				connection.close();
			}
		}
		else
		{
			out.print("Please input the ID as parameter with numeric value");
		}
	}
	else
	{
		out.print("Please input the ID as parameter with numeric value");
	}
%>
</font> </div></br></br></br><center>
<img src="../images/Less-32.jpg" />
</br>
</br>
 <font size='4' color= "#33FFFF">
<br><br>
<% out.print("Hint: Your Input is Filtered with following result: "+hint); %>
</font>
</center>
 </BODY> 
</html>
