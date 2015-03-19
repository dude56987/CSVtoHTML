<html>
	<head>
		<title></title>
	</head>
	<body>
		<?PHP
		// if the user has submitted the form to this page do work
		function grabInput($varName){
			$input=filter_input(INPUT_GET, $varName);
			return $input;
		}
		$settings= array(
			"sourceLocation" => "",
			"styleLocation" => "",
			"outputLocation" => "",
			"breakfastStart" => "",
			"lunchStart" => "",
			"dinnerStart" => "",
			"lateMealStart" => "",
		)
		for($index=0;$index>count($settings);$index++){
			$settings[$index]=grabInput($settings[$index]);
		}
		//else show the form
		// readfile /etc/glue.cfg and input current values into below
		// form so user can update and rewrite the file
		print "
		<form action='systemSettings.php'>
			<h2>SourceData Location</h2>
			<input name='sourceLocation' type='text' value='' />
			<h2>StyleSheet Location</h2>
			<input name='styleLocation' type='text' value='' />
			<h2>Output Location</h2>
			<input name='outputLocation' type='text' value='' />
			<h2>Breakfast Start Time</h2>
			<input name='breakfastStart' type='number' value='' />
			<h2>Lunch Start Time</h2>
			<input name='lunchStart' type='number' value='' />
			<h2>Dinner Start Time</h2>
			<input name='dinnerStart' type='number' value='' />
			<h2>Late Meal Start Time</h2>
			<input name='lateMealStart' type='number' value='' />
			<p>	
			<input type='submit' value='Save Changes' />
			</p>
		</form>";
		?>
	</body>
</html>
