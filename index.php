<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="pt-BR">
<head>
	<title>Robot Gang - PHP Crud Builder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<link href="css/interface.css" rel="stylesheet" type="text/css"/>
	<script src="js/mylib.js" type="text/javascript"></script>
	<script src="js/commom.js" type="text/javascript"></script>
	<script src="js/control-interface.js" type="text/javascript"></script>
	<script src="js/bind-min.js" type="text/javascript"></script>
	<script src="js/connection.js" type="text/javascript"></script>
</head>

<body>
<div id="container">
	<div id="divHeader">
	<h1>Robot Gang - PHP Crud Builder</h1>
</div>
		  
  <div id="divContent">
	<h5>Setup Connection:</h5>
	<form id="frmConnection" name="frmConnection" onsubmit="return DoConnection();">
		<label for="txtServer">Server:</label>
		<input type="text" id="txtServer" name="txtServer" />
		<label for="txtUser">User:</label>
		<input type="text" id="txtUser" name="txtUser" />
		<label for="txtPass">Pass:</label>
		<input type="password" id="txtPass" name="txtPass" />
		<input type="submit" id="btnSubmit" value="Connect">
	</form>
	<div id="ctnDatabases">
	<h5>Project:</h5>
	<form id="frmProject" name="frmProject">
		<label for="txtName">Name:</label>
		<input type="text" id="txtProjectName" name="txtProjectName" value=""/>
		<label for="txtAcronym">Table Acronym:</label>
		<input type="text" id="txtAcronym" name="txtAcronym" value="" />
	</form>
	<h5>Databases:</h5>
	<form id="frmDatabase" name="frmDatabase" onsubmit="return GetTables();">
	<select id="ddlDatabase" name="ddlDatabase">
		<option value="0">Waiting a connection</option>
	</select>
		<input type="submit" id="btnSubmit" value="Get Tables" />
	</form>
	</div>
	<div id="ctnLoading" class="loading">
		<a name="loading"/>
		Loading...<br/>
		<img src="images/loading.gif" alt="Loading content"/>
	</div>
	<form id="frmTables" name="frmTables" onsubmit="return GenerateCRUD();">
	
		<div id="ctnTables">
			<h5>Tables:</h5>
			<input type="hidden" id="hdnTotalTables" name="hdnTotalTables" value="0" />
			<div id="ctnTablesList">
				
			</div>
		</div>
		<input type="submit" id="btnGenerate" name="btnGenerate" value="Generate CRUD" class="HideGenerateCRUDButton" />		
	</form>




</div>

</body>
</html>