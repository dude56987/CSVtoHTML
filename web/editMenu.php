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
		.row1 input{
			color: black;
			background-color: white;
		}
		.row2 input{
			color: black;
			background-color: lightgray;
		}
	</style>
	<script>
	// clear a row of its values
	function clearRow(rowNumber){
		for(index=0;index<=30;index++){
			document.getElementById(rowNumber+'_'+index).value = '';
		}
		return false;
	}
	// prompt the user that they want to follow a link
	function verifyLeave(dest){
		// shows ok/cancel
		if(confirm("If you leave this page any unsaved changes will be lost. Click OK if you would you like to leave?")){
			window.location=dest;
		}

	}
	</script>
	</head>
	<body>
		<div>
			<h1>Syntax Help</h1>
			<ul>
				<li>#Header = Add a # to the start of a line to make the
					  text a header
				</li>
				<li>
				# = put a # on a line by itself to draw 2 blank lines
				</li>
				<li>
				#BACKGROUND=filename.png = Write #BACKGROUND= then 
					    filename to use a custom background for
					    a date and time
				</li>
				<li>
				Any other text will be shown as a item on the list
				</li>
			</ul>
		</div>
		<hr />
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
		// create a array of dates for the dropdown
		$datesArray= array();
		for($temp=0;$temp<60;$temp++){
			$dateVar = date_create('NOW');
			date_modify($dateVar, '+'.$temp.' day');
			$dateVar = date_format($dateVar, 'm/d/Y');
			array_push($datesArray,$dateVar);
		}
		$timesArray=array(
			"BREAKFAST",
			"LUNCH",
			"DINNER",
			"LATEMEAL"
		);
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
		$rowFlip=False;
		// begin building the html to be shown on the webpage
		print "<form action='editMenuSave.php' method='post'><table>";
		foreach($configFile as $entry){
			if($rowFlip==False){
				// alternate colors on the rows to make it easier to read
				print "<tr class='row1'>\n";
				$rowFlip=True;
			}else{
				print "<tr class='row2'>\n";
				$rowFlip=False;
			}
			if($entry[0] != "#"){
				// split up lines based on '='
				$entry = explode(',',$entry);
				// for each line add a delete button
				print "<td><button onclick=\"return clearRow(".$row.")\" >Delete</button></td>";
				foreach($entry as $cell){
					if($column == 0){
						print "\t<td>";
						print"\t<select id=\"".$row."_".$column."\"  name='".$row."_".$column."' selected='selected' >\n";
						print "\t<option value=\"".$cell."\" />".$cell."</option>\n";
						foreach($datesArray as $dateItem){
							print "\t\t<option value=\"".$dateItem."\">".$dateItem."</option>\n";
						}
						print "</select>";
						print "</td>";
					}elseif($column == 1){
						print "\t<td>";
						print"\t<select id=\"".$row."_".$column."\"  name='".$row."_".$column."'>\n";
						print "<option value=\"".$cell."\" selected='selected' />".$cell."</option>\n";
						foreach($timesArray as $timeItem){
							print "\t\t<option value=\"".$timeItem."\">".$timeItem."</option>\n";
						}
						print "</select>";
						print "</td>";

					}else{
						print "\t<td><input id=\"".$row."_".$column."\" name=\"".$row."_".$column."\" type=\"text\" value=\"".$cell."\" /></td>\n";
					}
				$column++;
				}
				// fill in the remaining cells for if the user wants to
				// add anything to that row
				for($temp2=0;$temp2<=(30-count($entry));$temp2++){
					print "\t<td><input id=\"".$row."_".$column."\"  name=\"".$row."_".$column."\" type=\"text\" value=\"\" /></td>\n";
					$column++;
				}
			$column=0;
			$row++;
			}
			print "</tr>\n";
		}
		// build aditional input areas for user to add items
		$dateIncrement=0;
		for($temp=0;$temp<20;$temp++){
			if($rowFlip==False){
				// alternate colors on the rows to make it easier to read
				print "<tr class='row1'>\n";
				$rowFlip=True;
			}else{
				print "<tr class='row2'>\n";
				$rowFlip=False;
			}
			print "<td><button onclick=\"return clearRow(".$row.")\" >Delete</button></td>";
			for($temp2=0;$temp2<=30;$temp2++){
				if ($column==0){
					print"\t<td><select id=\"".$row."_".$column."\" name='".$row."_".$column."'>\n";
					// generate the date for the field
					$dateVar = date_create('NOW');
					date_modify($dateVar, '+'.$dateIncrement.' day');
					$dateVar = date_format($dateVar, 'm/d/Y');
					print "\t\t<option value=\"".$dateVar."\" selected='selected' >".$dateVar."</option>\n";
					foreach($datesArray as $dateItem){
						print "\t\t<option value=\"".$dateItem."\">".$dateItem."</option>\n";
					}
					print"\t</select></td>\n";
				} elseif ($column == 1){
					print "\t<td>";
					print"\t<select id=\"".$row."_".$column."\" name='".$row."_".$column."'>\n";
					// show rows as breakfast,lunch,dinner,latemeal
					if($row%4==0){
						print "\t\t<option value=\"".$timesArray[0]."\">".$timesArray[0]."</option>\n";
					}elseif($row%4==1){
						print "\t\t<option value=\"".$timesArray[1]."\">".$timesArray[1]."</option>\n";
					}elseif($row%4==2){
						print "\t\t<option value=\"".$timesArray[2]."\">".$timesArray[2]."</option>\n";
					}elseif($row%4==3){
						print "\t\t<option value=\"".$timesArray[3]."\">".$timesArray[3]."</option>\n";
						// increse date for next entry
						$dateIncrement++;
					}
					foreach($timesArray as $timeItem){
						print "\t\t<option value=\"".$timeItem."\">".$timeItem."</option>\n";
					}
					print "</select>";

				} else{
					print "\t<td><input id=\"".$row."_".$column."\"  name=\"".$row."_".$column."\" type=\"text\" value=\"\" /></td>\n";
				}
				$column++;
			}
			print "</tr>\n";
			$column=0;
			$row++;
		}
		?>
		</table>
		<button style='background-color:green;color:white;' type='submit' >Save Changes and Keep Editing</button> 
		<button style="background-color:darkred;color:white;" onclick="verifyLeave('main.php');return false;">Discard Changes and Return to Main Menu</button>
		</form>
	</body>
</html>
