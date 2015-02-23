<?PHP
include("commonLib.php");
////////////////////////////////////////
writeFile("test.txt","The world is full of wires...");
print openFile("test.txt");
?>
