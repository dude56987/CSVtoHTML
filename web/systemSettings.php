<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	<style>
		input{
			width:100%;
		}	
		button{
			width:100%;
		}
	</style>
	<script>
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
		<?PHP
		// if the user has submitted the form to this page do work
		function writeFile($filePath,$stringToWrite){
			 $myFileObject = fopen($filePath, "w") or die("Unable to open file!");
			 fwrite($myFileObject, $stringToWrite);
			 fclose($myFileObject);
		}
		function cleanLineEnds($string){
			// converts all line ends to \n line ends
			return str_replace(array("\r\n", "\r"), "\n", $string);
		}
		$fileContent="";
		$configFile= array();
		// read glue.cfg and write back changes if there are any
		foreach(file("/etc/glue.cfg") as $item){
			// replace line endings with \n
			$item = str_replace(array("\r\n", "\r","\n"), "\n", $item);
			// readfile /etc/glue.cfg and input current values into below
			// form so user can update and edit the file
			if($item[0]=="#"){
				// re-add comments back to the file
				$fileContent=$fileContent.$item;
			}elseif(stristr($item,"=")){
				$item=explode("=",$item);
				// build a new config file
				// add each element to fileContent
				$fileContent=$fileContent.$item[0]."=".$item[1];
				// add each config option element to an array
				$configFile[$item[0]]=$item[1];
			}
		}
		// begin building the html to be shown on the webpage
		print "<form method='POST' action='writeSystemSettings.php'>";
		foreach(explode("\n",$fileContent) as $entry){
			if(! empty($entry)){
				if($entry == "##"){
					print "<hr />";
				}elseif($entry[0] == "#"){
					print "<p>".$entry."</p>";
				}else{
					// split up lines based on '='
					$entry = explode('=',$entry);
					print "<h2 style='color:green;'>".$entry[0]."</h2>";
					print "<input name='".$entry[0]."' type='text' value='".$entry[1]."' /><br />";
				}
			}
		}
		?>
		<p><button style="background-color:green;color:white;" type='submit'>Save Changes and Return to Main Menu</button></p></form>
		<button style="background-color:darkred;color:white;" onclick="verifyLeave('main.php')">Discard Changes and Return to Main Menu</button>
	</body>
</html>
