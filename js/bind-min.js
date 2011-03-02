//HTML SELECT BINDER
//Author: Gabriel Pires - gabriel@sintese.info
//Version: 1.0
//Release Date: 2010-09-01
//The ajax handler is Ajax Requester - MyLib JS - Developed by David Mark - <a href="http://www.cinsoft.net/">http://www.cinsoft.net/

function bindList(pControl)
{
	this._control = pControl;
	this._source = null;
	this._type = null;
	this._load = 'Wait, loading...';	
 	this._nodeLoad = null;
	this._empty = 'No item(s)';
	this._first = {"text":"Select an item","value":"0"};
	
	this._success = this.Success;
	this._fail = this.Fail;
}


bindList.prototype.fill = function(pData)
{
	this._type = typeof(pData.source);
	this._source = pData.source;
	
	if(typeof(pData.success) == "function")
	{
		this._success = pData.success;
	}
	
	if(typeof(pData.fail) == "function")
	{
		this._fail = pData.fail;
	}
	
	if(pData.first != null)
	{
		this._first = pData.first;
	}
		
	try
	{

		if(this.verifyValue(pData.load))
		{
			this._load = pData.load;	
		}
		
		if(this.verifyValue(pData.empty))
		{
			this._empty = pData.empty;
		}
		
		this.removeAll();
		this._nodeLoad = this.addItem({"value":"0","text":this._load, "seleted":"selected"});	
		
		if(this._type == "string")
		{
			this.request();
		}
		else
		{
		
			this.applyResult(pData.source);
		}
   }
   catch(err)
   {
   		console.log(err);
		this._fail();
   }
}


bindList.prototype.verifyValue = function(pValue){
	if(pValue != undefined && pValue != null && pValue != "")
	{
		return true;
	}
	else
	{
		return false;
	}
}

bindList.prototype.request = function()
{
	var that = this;
	var r = new API.Requester();
	r.onsuccess = function(xmlhttp, result){ that.applyResult(result); };
	r.onfail = function(xmlhttp, obj){this._fail();};
	r.get(this._source,true);
}

bindList.prototype.applyResult = function(pData)
{                               
	this.addItem(this._first);
	for(var i=0; i < pData.length; i++)
	{
		var item = pData[i];
		this.addItem(item);
	}
	
	this._control.removeChild(this._nodeLoad);
	if(pData.length == 0)
	{
		this.additem({"value":"0", "text": this._empty, "selected":"selected"})
	}
	
	this._success();
}

bindList.prototype.removeAll = function(){
	
	while(this._control.firstChild)
	{
		this._control.removeChild(this._control.firstChild);
	}
	
}

bindList.prototype.addItem = function(pObject)
{
	var option = document.createElement('option');
	option.setAttribute('value', pObject.value);
	
	if(this.verifyValue(pObject.selected))
	{
		option.setAttribute('selected','selected');
	}
	
	option.innerHTML = pObject.text;
	
	this._control.appendChild(option);
	
	return option;
}

bindList.prototype.Success = function()
{
	alert('The Load has been completed');
}

bindList.prototype.Fail = function()
{
	alert('An error occour in the process, verify your data or webservice');
}


