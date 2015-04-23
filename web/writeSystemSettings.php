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
if(count($_POST)){ 
	$fileContent="";
	$configFile= array();
	// read glue.cfg and write back changes if there are any
	foreach(file("/etc/glue.cfg") as $item){
		// replace line endings with \n
		$item = str_replace(array("\n\n","\r\n", "\r"), "\n", $item);
		// readfile /etc/glue.cfg and input current values into below
		// form so user can update and edit the file
		if($item[0]=="#"){
			// re-add comments back to the file
			$fileContent=$fileContent.$item;
		}elseif(stristr($item,"=")){
			$item=explode("=",$item);
			// build a new config file
			// add each element to fileContent
			$fileContent=$fileContent.$item[0]."=".$_POST[$item[0]]."\n";
		}
	}
	print $fileContent."<br />";//DEBUG
	// if the length of the sourcelocation variable is longer than
	// the string source variable itself, a new setting is being
	// saved and it needs to be updated
	// if the user has submited anything write the new stuff to the file
	print "<h3 style='color:green'>Updated Savefile</h3>";
	// write the new version of the configfile
	writeFile('/etc/glue.cfg',$fileContent);
	// update the glue config
	exec('touch /tmp/refreshGlue');
}
?>
<script>
	// return to main menu after writing new settings
	window.location="main.php";
</script>
<a href="systemSettings.php">Keep Going</a>
