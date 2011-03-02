function DeleteItem(register, source)
{
	if(confirm('Do you wanna delete this register?'))
	{
		document.location = 'service.php?function=delete&register=' + register + "&source=" + source;	
	}
}

function MoveToList()
{
	document.location = 'list.php';
}

function ChangeCheckBox(control)
{
	if(control.checked)
	{
		control.value = "1";
	}
	else
	{
		control.value = "0";
	}
}