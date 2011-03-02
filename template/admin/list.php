<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administravive Panel - Dashboard - List</title>
<?php include_once('controls/assets.php'); ?>
</head>

<body>
	<div id="wrapper">
   		
		<!-- mainNav -->
		<?php include_once('controls/header.php'); ?>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
                <!--  #sidebar -->
				<?php include_once('controls/sidebar.php'); ?>
				<!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#">Dashboard</a> &raquo; <a href="#" class="active">Print resources</a></h2>
                
                <div id="main">

					
					<h3>Sample section</h3>
                    	<table cellpadding="0" cellspacing="0">
							<tr class="table_header">
								<td>Value</td>
								<td>Commands</td>
							</tr>
							<tr>
                                <td>Vivamus rutrum nibh in felis tristique vulputate</td>
                                <td class="action"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a></td>
                            </tr>                        
							<tr class="odd">
                                <td>Duis adipiscing lorem iaculis nunc</td>
                                <td class="action"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a></td>
                            </tr>                        
							<tr>
                                <td>Donec sit amet nisi ac magna varius tempus</td>
                                <td class="action"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a></td>
                            </tr>                        
							<tr class="odd">
                                <td>Duis ultricies laoreet felis</td>
                                <td class="action"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a></td>
                            </tr>                        
							<tr>
                                <td>Vivamus rutrum nibh in felis tristique vulputate</td>
                                <td class="action"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a></td>
                            </tr>                        
                        </table>

                </div>
                <!-- // #main -->
                
                <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
        
	<?php include_once('controls/footer.php'); ?>
		
    </div>
    <!-- // #wrapper -->
</body>
</html>
