<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<title>PHP Class Builder</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" language="javascript"></script>
	<script src="js/core.js" language="javascript" type="text/javascript"></script>
</head>

<body>

	<h1>PHP Class Builder</h1>
	

	
	
	<form method="post" action="class.php" id="fields">
		<input type="hidden" id="hdnCurrentIndex" name="hdnCurrentIndex" value="0" />
		Project Name:
		<input type="text" id="txtProject" name="txtProject"> | 
		Table Name:
		<input type="text" id="txtTableName" name="txtTableName" onblur="javascript:CreateClassName(this,'#txtClassName');"> | 
		Class Name:
		<input type="text" id="txtClassName" name="txtClassName"> | 
		<input type="button" id="btnCreateClass" onclick="javascript:SaveClass();"  value="Create Class" />

		<br/><br/>
		<input type="button" id="btnAddField" onclick="javascript:AddField();" value="Add Field" />
		
	</form>

</body>
</html>
