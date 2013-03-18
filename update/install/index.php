<?php
    
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	
	<title>Tefter Update Wizard</title>
	
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0" />
	
	<link rel="stylesheet" media="all" href="css/screen.css" />
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>

	<section id="content">
		<div class="group block">
			<h1>Tefter v1.1</h1>

			<section id="primary">
				<form action="update-actions.php" method="post">
					<fieldset>
						<div class="group">
							<label for="folder" class="required">Name of Tefter system folder</label>
							<input name="folder" id="folder" value="" type="text" />
							<p class="hint">Default name of system folder is <strong>system</strong>.</p>
						</div>
					</fieldset>
					
					<button type="submit">Update</button>
				</form>
			</section>
			
		</div>
	</section>

</body>
</html>