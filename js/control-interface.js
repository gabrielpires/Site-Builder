
var columnRemovalCharsList = new Array("/","id_","_id","dt_","_dt");

var allTablesObject = null;

var templateTable = '';
var templateColumn = '';



function ShowDatabases()
{
	getByID('ctnDatabases').style.display = 'block';
}


function ShowTablesArea()
{
	getByID('ctnTables').style.display = 'block';
}


function ShowGenerateButton()
{
	var btnGenerate = getByID('btnGenerate');
	btnGenerate.className = 'ShowGenerateCRUDButton';	
}

function HideGenerateButton()
{
	var btnGenerate = getByID('btnGenerate');
	btnGenerate.className = 'HideGenerateCRUDButton';
}

function ShowLoading()
{
	var container = getByID('ctnLoading');
	container.style.display = 'block';
	
	window.location.hash = "";
}

function HideLoading()
{
	var container = getByID('ctnLoading');
	container.style.display = 'none';
	
	window.location.hash = "";
}

function ToggleIsObjectArea(control, pTableIndex, pRowIndex)
{
	
	var container = getByID('ctnIsObject_' + pTableIndex + '_' + pRowIndex);
	
	if(control.checked)
	{
		container.className = 'ShowIsObjectContainer';
	}
	else
	{
		container.className = 'HideIsObjectContainer';
	}
}

function CreateTablesInterface(result)
{
	allTablesObject = result;
	GetTableTemplate();
}

function GetTableTemplate()
{
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result) { templateTable = xmlhttp.responseText; GetColumnTemplate(); };
	r.onfail = function(xmlhttp, obj){ alert('System fail trying get the Table Template');};
	r.get('controls/table.html');
}

function GetColumnTemplate()
{
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result) { templateColumn = xmlhttp.responseText; MountTables(); };
	r.onfail = function(xmlhttp, obj){ alert('System fail trying get the Column Template');};
	r.get('controls/column.html');
}

function MountTables()
{
	ShowTablesArea();
	var masterContainer = getByID('ctnTablesList');
	masterContainer.innerHTML = '';
	getByID('hdnTotalTables').value = allTablesObject.length;
	for(var i = 0; i < allTablesObject.length; i++)
	{
		
		table = allTablesObject[i];
		table.template = templateTable
		table.className = CreateClassName(table.name);
		table.template = table.template.replace(/#TABLE_INDEX#/g, i);
		table.template = table.template.replace(/#TABLE_NAME#/g,table.name); 
		table.template = table.template.replace(/#CLASS_NAME#/g,table.className);
		table.template = table.template.replace(/#TOTAL_COLUMNS#/g, table.columns.length);
		
		/*COLUMNS INSERT*/
		var columnsList = '';
		for(var c = 0; c < table.columns.length; c++)
		{
			column = table.columns[c];
			column.template = templateColumn;
			column.property = CreatePropertyName(column.name);
			column.template = column.template.replace(/#TABLE_INDEX#/g, i);
			column.template = column.template.replace(/#COLUMN_INDEX#/g, c);
			column.template = column.template.replace(/#COLUMN_NAME#/g, column.name);
			column.template = column.template.replace(/#COLUMN_TYPE#/g, ClearTypeValue(column.type));
			column.template = column.template.replace(/#COLUMN_KEY#/g, column.key);			
			column.template = column.template.replace(/#PROPERTY_NAME#/g, column.property);					
			columnsList += column.template;

		}
		
		table.template = table.template.replace(/#COLUMNS#/g, columnsList);
		masterContainer.innerHTML += table.template;
	}
	
	for(var i = 0; i < allTablesObject.length; i++)
	{
		table = allTablesObject[i];
		for(var c = 0; c < table.columns.length; c++)
		{
			var ddlTables = getByID('ddlTables_' + i + '_' + c);
			var binder = new bindList(ddlTables);
			binder.fill(
			{
			"source": allTablesObject, 
			"success": function(){},
			"fail": function(){alert('here comes my fail function');},
			"load": "Loading databases",
			"empty": "No databases",
			"first": {"text":"Select a database", "value":0}
			});
		}
	}
	
	HideLoading();
}

function CreateClassName(pTableName)
{
	var acronym = getByID('txtAcronym').value;
	var className = pTableName;
	className = className.replace(acronym, '');
	className = className.replace(/_/g,' ');
	className = className.toLowerCase();
	className = className.capitalize();
	className = className.replace(/ /g, '');

	return className;
}

function CreatePropertyName(pColumnName)
{
	var propertyName = pColumnName;
	propertyName = propertyName.toLowerCase();
	
	for(var i = 0; i < columnRemovalCharsList.length; i++)
	{
		var value = columnRemovalCharsList[i];
		propertyName = propertyName.replace(value,"");
	}

	return propertyName;
}

function ClearTypeValue(pValue)
{
	return pValue.replace(/\([^)]*\)/g,"");
}

function FillIsObjectTextAndValue(pTableControl)
{
	var idValueControl = pTableControl.id.replace('ddlTables','ddlValueField');
	var idTextControl = pTableControl.id.replace('ddlTables','ddlTextField');
	
	if(pTableControl.value != '0')
	{
		var table = pTableControl.value;
		for(var i = 0; i < allTablesObject.length; i++)
		{
			if(allTablesObject[i].name == table)
			{
				var binder = new bindList(getByID(idValueControl));
				binder.fill(
				{
				"source": allTablesObject[i].columns, 
				"success": function(){},
				"fail": function(){alert('here comes my fail function');},
				"load": "Loading properties...",
				"empty": "No databases",
				"first": {"text":"Select a property", "value":0}
				});
				
				var binder = new bindList(getByID(idTextControl));
				binder.fill(
				{
				"source": allTablesObject[i].columns, 
				"success": function(){},
				"fail": function(){alert('here comes my fail function');},
				"load": "Loading properties...",
				"empty": "No databases",
				"first": {"text":"Select a property", "value":0}
				});
			}
		}
	}
	else
	{
	
	}
	
}