<?php 
	
	if(isset($_POST['txtName']))
	{
		echo 'CHEGOU O VALOR: ' . $_POST['txtName'];
	}
	else
	{
		echo 'N�O CHEGOU';
	}
?>