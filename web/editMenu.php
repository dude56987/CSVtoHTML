<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	<style>
		input{
			width:100%;
		}	
		html{
			width:100%;
		}
	</style>
	</head>
	<body>
		<?PHP
		print '';
		// This webpage needs to be able to...
		// - View and edit spreadsheets in comma delimited format .csv
		// - Delete rows in existing spreadsheets
		// - Save changes to the spreadsheet
		
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
			$temp=grabInput($item);
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
		if (strlen($settings['location']) > (strlen("location"))){
			print "<h3 style='color:green'>Updated Savefile</h3>";
			$configFile=$settings;
			// pull the configfile into a string
			$tempArray = ''; 
			foreach($configFile as $item){
				$tempArray=$tempArray.$item."\n";
			}
			// write the new version of the configfile
			writeFile('/usr/share/signage/menu.csv',$tempArray);
		}else{
			// readfile /etc/glue.cfg and input current values into below
			// form so user can update and edit the file
			$configFile=file("/usr/share/signage/menu.csv");
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
		print "<form action='systemSettings.php'><table>";
		foreach($configFile as $entry){
			print "<tr>";
			if($entry[0] != "#"){
				// split up lines based on '='
				$entry = explode(',',$entry);
				foreach($entry as $cell){
					print "<td><input name='".$cell."' type='text' value='".$cell."' /></td>";
				}
			}
			print "</tr>";
		}
		# build aditional input areas for user to add items
		for($row=0;$row<12;$row++){
			print "<tr>";
			for($column=0;$column<10;$column++){
				print "<td><input name='".$row."_".$column."_NEW' type='text' value='".$row.'_'.$column."_NEW' /></td>";
			}
			print "</tr>";
		}
		print "</table><p><input type='submit' action='editMenu.php' value='Save Changes' /></p></form>";
		?>
		<a href='main.php'>Return to Main Menu</a>
	</body>
</html>
