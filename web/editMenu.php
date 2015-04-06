<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	<style>
		input{
			width:125px;
		}
		html{
			width:3000px;
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
		// - Cheat sheet at top of spreadsheet
		
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
		// THIS SECTION ONLY HANDLES ADDITIONS TO THE DATA
		// Build the data from user submited changes
		// Write new datafile to disk with changes
		$outputString="";
		// use foreach to account for however many rows the list has
		// columns are static at 30 so we can use this to build the
		// config file from the post data
		// This needs put into a array to pull out error data
		//foreach($_POST as $item){
		print floor(count($_POST)/30)."<br>\n";// DEBUG
		for($row=0;$row<floor(count($_POST)/30);$row++){
			for($col=0;$col<=30;$col++){
				// skip blank rows
				if(empty($_POST[$row."_".$col])){
					// empty post
				}else{
					$outputString=$outputString.$_POST[$row."_".$col].",";
				}
			}
			$outputString=$outputString."<br/>\n";
		}
		print $outputString;// DEBUG
		// clean up the empty rows inside the data spreadsheet
		$tempString=explode($outputString, "\n");
		$outputString="";
		foreach($tempString as $row){
			$tempRow=explode($row, ",");
			// if first user assigned variable is missing
			// then the line is ignored
			if(empty($tempRow[2])){
				// do nothing			
			}else{
				// append non empty rows
				$outputString=$outputString.$row."\n";
			}
		}
		print $outputString;// DEBUG
		// if the length of the sourcelocation variable is longer than
		// the string source variable itself, a new setting is being
		// saved and it needs to be updated
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
		$row=0;
		$column=0;
		// begin building the html to be shown on the webpage
		print "<form action='editMenu.php' method='post'><table>";
		foreach($configFile as $entry){
			print "<tr>\n";
			if($entry[0] != "#"){
				// split up lines based on '='
				$entry = explode(',',$entry);
				foreach($entry as $cell){
					print "\t<td><input name=\"".$row."_".$column."\" type=\"text\" value=\"".$cell."\" /></td>\n";
				$column++;
				}
				// fill in the remaining cells for if the user wants to
				// add anything to that row
				for($temp2=0;$temp2<=(30-count($entry));$temp2++){
					print "\t<td><input name=\"".$row."_".$column."\" type=\"text\" value=\"\" /></td>\n";
					$column++;
				}
			$column=0;
			$row++;
			}
			print "</tr>\n";
		}
		// create a array of dates for the dropdown
		$datesArray= array();
		for($temp=0;$temp<60;$temp++){
			$dateVar = date_create('NOW');
			date_modify($dateVar, '+'.$temp.' day');
			$dateVar = date_format($dateVar, 'm/d/Y');
			array_push($datesArray,$dateVar);
		}
		// build aditional input areas for user to add items
		for($temp=0;$temp<20;$temp++){
			print "<tr>\n";
			for($temp2=0;$temp2<=30;$temp2++){
				if ($column==0){
					print"\t<td><select name='".$row."_".$column."'>\n";
					foreach($datesArray as $dateItem){
						print "\t\t<option value=\"".$dateItem."\">".$dateItem."</option>\n";
					}
					print"\t</select></td>\n";
				} elseif ($column==1){
					print "\t<td><input name='".$row."_".$column."' type='text' value='' /></td>\n";
				} else{
					print "\t<td><input name='".$row."_".$column."' type='text' value='' /></td>\n";
				}
				$column++;
			}
			print "</tr>\n";
			$column=0;
			$row++;
		}
		print "</table><p><input style='width:400px' type='submit' value='Save Changes' /></p></form>";
		?>
		<a href='main.php'>Return to Main Menu</a>
	</body>
</html>
