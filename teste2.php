<?php 
	
	if(isset($_POST['txtName']))
	{
		echo 'CHEGOU O VALOR: ' . $_POST['txtName'];
	}
	else
	{
		echo 'NÌO CHEGOU';
	}
?>