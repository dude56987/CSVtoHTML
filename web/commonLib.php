<?PHP
///////////////////////////////////////////////
function openFile($filePath){
	$myfile = fopen($filePath, "r") or die("Unable to open file!");
	//Output one line until end-of-file
	$output = '';
	while(!feof($myfile)) {
	   $output=$output.fgets($myfile);
	}
	fclose($myfile);
	return $output;
}
///////////////////////////////////////////////
function writeFile($filePath,$contentToWrite){
	$fileObject=fopen($filePath,"w");
	fwrite($fileObject,$contentToWrite);	
	fclose($fileObject);
}
?>
