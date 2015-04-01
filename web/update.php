<html>
	<head>
	    <title>Update Screen</title>
	    <link rel="stylesheet" type="text/css" href="style.css" />
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
		<?PHP
			exec('touch /tmp/refreshGlue');
		?>
        <div id="timer">
		    The screen will update in <input id="minutes" type="text" style="width: 14px; border: none; background-color:white; font-size: 16px; font-weight: bold;"> minutes and <input id="seconds" type="text" style="width: 26px; border: none; background-color:white; font-size: 16px; font-weight: bold;"> seconds.
		</div>
		<script>
			countdown();
        </script>
	</body>
</html>
