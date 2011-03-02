function getByID(pControl)
{
	return document.getElementById(pControl);
}

function newElement(pType,pId)
{
	var element = document.createElement(pType);
	if(pId != null)
	{
		element.setAttribute('id',pId);
	}
	
	return element;
}

function newInput(pType,pId,pValue)
{
	var element = document.createElement('input');
	element.setAttribute('type',pType);
	element.setAttribute('id',pId);
	element.setAttribute('name',pId);
	element.setAttribute('value',pValue);
	
	return element;
}

function newText(pValue)
{
	return document.createTextNode(pValue);
}

function ChangeSelected(control)
{
	if(control.checked)
	{
		control.value = 1;
	}
	else
	{
		control.value = 0;
	}
}

String.prototype.capitalize = function(){
   return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
  };