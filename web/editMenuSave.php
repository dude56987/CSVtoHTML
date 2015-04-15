<?PHP
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
if(count($_POST)!=0){
	//print floor(count($_POST)/30)."<br>\n";// DEBUG
	for($row=0;$row<floor(count($_POST)/30);$row++){
		// create an array to store row items temporararly
		$rowArray=array();
		// go though all columns
		for($col=0;$col<=30;$col++){
			// skip blank columns
			if(empty($_POST[$row."_".$col])){
				// empty post
			}else{
				// append nonempty items to the array
				array_push($rowArray,$_POST[$row."_".$col]);
			}

		}
		if (count($rowArray) >= 3){
			// if the array is at least 3 in length write it out
			foreach($rowArray as $item){
				// write out each item on the row
				$outputString=$outputString.$item.",";
			}
			// add a line ending
			$outputString=$outputString."\n";
		}
	}
}
writeFile('/usr/share/signage/menu.csv',$outputString);
print $outputString;// DEBUG
?>
<script>
	window.location="editMenu.php";
</script>
