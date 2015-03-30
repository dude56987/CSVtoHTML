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
		}
		function writeFile($filePath,$stringToWrite){
			 $myFileObject = fopen($filePath, "w") or die("Unable to open file!");
			 fwrite($myFileObject, $stringToWrite);
			 fclose($myFileObject);
		}
		$settings= array(
			"location" => "location",
			"stylesheetLocation" => "stylesheetLocation",
			"outputLocation" => "outputLocation",
			"breakfastStartTime" => "breakfastStartTime",
			"lunchStartTime" => "lunchStartTime",
			"dinnerStartTime" => "dinnerStartTime",
			"lateMealStartTime" => "lateMealStartTime"
		);
		//for($index=0;$index<count($settings);$index++){
		foreach($settings as $item){
			print htmlspecialchars($item)."<br />";//DEBUG
			$temp=grabInput($item);
			print htmlspecialchars($temp)."<br />";//DEBUG
			if(count($temp)){
				// concat the name and the value given to look like
				// the config file so the same code can read the 
				// config file into the program
				$settings[$item]=$settings[$item].'='.$temp;
			}
		}
		// if the length of the sourcelocation variable is longer than
		// the string source variable itself, a new setting is being
		// saved and it needs to be updated
		print (strlen($settings['location']).'>'.(strlen("location")));
		if (strlen($settings['location']) > (strlen("location"))){
			print "<h3 style='color:green'>Updated Savefile</h3>";
			$configFile=$settings;
		}else{
			//else show the form
			// readfile /etc/glue.cfg and input current values into below
			// form so user can update and rewrite the file
			//
			//Read the file to insert it below
			$configFile=file("/etc/glue.cfg");
			// remove line endings
			$tempArray=array();
			foreach($configFile as $entry){
				// replace line endings
				$entry = str_replace(array("\r\n", "\r","\n"), "", $entry);
				// append the array
				array_push($tempArray, $entry);
			}
			$configFile=$tempArray;
		}
		// begin building the html to be shown on the webpage
		print "<form action='systemSettings.php'>";
		foreach($configFile as $entry){
			if($entry[0] != "#"){
				// split up lines based on '='
				$entry = explode('=',$entry);
				print "<h2>".$entry[0]."</h2>";
				print "<input name='".$entry[0]."' type='text' value='".$entry[1]."' name='".$entry[0]."' />";
			}
		}
		print "<p><input type='submit' action='systemSettings.php' value='Save Changes' /></p></form>";
		?>
	</body>
</html>
