<html>
	<head>
		<title></title>
	<style>
		input{
			width:100%;
		}	
	</style>
	</head>
	<body>
		<?PHP
		// if the user has submitted the form to this page do work
		function grabInput($varName){
			$input=filter_input(INPUT_GET, $varName);
			return $input;
		};
		$settings= array(
			"sourceLocation" => "",
			"styleLocation" => "",
			"outputLocation" => "",
			"breakfastStart" => "",
			"lunchStart" => "",
			"dinnerStart" => "",
			"lateMealStart" => ""
		);
		for($index=0;$index>count($settings);$index++){
			$settings[$index]=grabInput($settings[$index]);
		};
		//else show the form
		// readfile /etc/glue.cfg and input current values into below
		// form so user can update and rewrite the file
		//
		//Read the file to insert it below
		$configFile=file("/etc/glue.cfg");
		print "<form action='systemSettings.php'>";
		foreach($configFile as $entry){
			$entry = explode('=',$entry);
			print "<h2>".$entry[0]."</h2>";
			print "<input name='outputLocation' type='text' value='".$entry[1]."' />";
		}
		print "<p><input type='submit' value='Save Changes' /></p></form>";
		?>
	</body>
</html>
