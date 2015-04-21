<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style>
			#mainFrame{
				width: 800px;
				margin-left: auto;
				margin-right: auto;
			}
			#preview{
				width: 600px;
				float: left;
			}
			#preview p{
				text-align: center;
			}
			#inputPanel{
				margin-top: 100px;
				width: 200px;
				float: right;
			}
			#frame {
				font-size: 8px;
				width: 600px;
				height: 800px;
				border: 0;
				/*
			    -ms-transform: scale(0.25);
			    -moz-transform: scale(0.25);
			    -o-transform: scale(0.25);
			    -webkit-transform: scale(0.25);
			    transform: scale(0.25);
			
			    -ms-transform-origin: 0 0;
			    -moz-transform-origin: 0 0;
			    -o-transform-origin: 0 0;
			    -webkit-transform-origin: 0 0;
			    transform-origin: 0 0;
			    */
		    }
		</style>
		    <script type="text/javascript">
			// set minutes
			var mins = 3;
			// calculate the seconds (don't change this! unless time progresses at a different speed for you...)
			var secs = mins * 60;
			function countdown() {
				setTimeout('Decrement()',1000);
			}
			function Decrement() {
			if (document.getElementById) {
				minutes = document.getElementById("minutes");
				seconds = document.getElementById("seconds");
				// if less than a minute remaining
				if (seconds < 59) {
					seconds.value = secs;
				} else {
					minutes.value = getminutes();
					seconds.value = getseconds();
				}
		        secs--;
		        if (minutes.value == 0){
				if (seconds.value == 1){
					// when minutes value is 0 and seconds value reaches 1 redirect the page
					window.location = "main.php";
				}
			}
			setTimeout('Decrement()',1000);
			}
			}
			function getminutes() {
				// minutes is seconds divided by 60, rounded down
				mins = Math.floor(secs / 60);
				return mins;
			}
			function getseconds() {
				// take mins remaining (as seconds) away from total seconds remaining
				return secs-Math.round(mins *60);
			}
	    </script>
	</head>
	<body>
		<div id="mainFrame">
		<?PHP
		$input=filter_input(INPUT_GET, "updateGlue");
		if( $input == 'Update webpage' ){
			exec('touch /tmp/refreshGlue');
			print '<div id="timer">
			The screen will update in <input id="minutes" type="text" style="width: 14px; border: none; background-color:white; font-size: 16px; font-weight: bold;"> minutes and <input id="seconds" type="text" style="width: 26px; border: none; background-color:white; font-size: 16px; font-weight: bold;"> seconds.
			</div>
			<script>
				countdown();
			</script>';
		}
		?>
			<div id="preview">
				<a href="../">
				<p>Fullscreen Preview</p>
				<iframe id="frame" src="../"></iframe>
				</a>	
			</div>
			<div id="inputPanel">
				<form action="main.php">
					<input type="submit" name='updateGlue' value="Update webpage" />
				</form>
				<form action="editMenu.php">
					<input type="submit" value="Edit Menu" />
				</form>
				<form action="editBackgrounds.php">
					<input type="submit" value="Edit Backgrounds" />
				</form>
				<form action="systemSettings.php">
					<input type="submit" value="System Settings" />
				</form>
				<form action="help.php">
					<input type="submit" value="Help" />
				</form>
				<form action="../">
					<input type="submit" value="Logout" />
				</form>
			</div>
		</div>
	</body>
</html>
