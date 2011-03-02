
var resultTable = null;



function SetupPage(){
	document.getElementById('frmConnection').onsubmit = function(e){
		alert('teste');
		e = e || window.event;
		e.preventDefault();

		
		return false;
	}
}

function DoConnection(e){
	
	console.log('Builder.Service.php?f=connection' + GetConnectionParameters());
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result) { FillDatabase(result); };
	r.onfail = function(xmlhttp, obj){ FailConnection();};
	r.post('Builder.Service.php?f=connection',GetConnectionParameters(), null, true);
	
	return false;
}

function FailConnection()
{
	alert('fail');
}



function GetTables()
{
	ShowLoading();
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result) { FillTables(result); };
	r.onfail = function(xmlhttp, obj){ FailConnection();};
	r.post('Builder.Service.php?f=get-tables',GetConnectionParameters(), null, true);


	return false;
}

function GetConnectionParameters()
{
		var parameters = "server=" + getByID('txtServer').value + "&user=" + getByID('txtUser').value + "&pass=" + getByID('txtPass').value;
	var database = getByID('ddlDatabase').value
	
	if(database != '0')
	{
		parameters += "&database=" + database;
	}
	
	return parameters;
}

 
function FillDatabase(result)
{
	var binder = new bindList(getByID('ddlDatabase'));
	binder.fill(
	{
	"source": result, //THE ARRAY OBJECT
	"success": function()
		{ 	
			
		},//THE SUCCESS FUNCTION
	"fail": function(){alert('here comes my fail function');},//THE FAIL FUNCTION
	"load": "Loading databases",//THE MESSAGE THAT WILL BE SHOWN WHILE THE SCRIPT FILL THE LIST
	"empty": "No databases" // THE MESSAGE THAT WILL BE SHOWN WHEN THE SERVICE OR THE LIST HAS NO ITEM(S)
	});
	
	ShowDatabases();
}

function FillTables(result)
{	
	resultTable = result;
	ShowGenerateButton();
	CreateTablesInterface(result);
}


function GenerateCRUD()
{
	var paramTablesPost = API.serializeFormUrlencoded(document.forms.frmTables);
	paramTablesPost += "&txtProjectName=" + getByID('txtProjectName').value;
	paramTablesPost += "&txtServer=" + getByID('txtServer').value;
	paramTablesPost += "&txtDatabase=" + getByID('ddlDatabase').value;				
	paramTablesPost += "&txtUser=" + getByID('txtUser').value;
	paramTablesPost += "&txtPass=" + getByID('txtPass').value;
				
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result) { CRUDResult(result); };
	r.onfail = function(xmlhttp, obj){ CRUDResult(result);};
	r.post('Builder.Service.php?f=generate-crud',paramTablesPost, null, true);


	return false;
}

function CRUDResult(result)
{
	if(result.length > 0)
	{
		window.location.hash = "";
		alert('Your project has been created');		
	}
	else
	{
		alert('An error occur when the system try create your project.');
	}
}
