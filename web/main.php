<html>
	<head>
		<title></title>
		<style>
			#mainFrame{
				width: 800px;
				margin-left: auto;
				margin-right: auto;
			}
			#preview{
				width: 400px;
				float: left;
			}
			#preview p{
				text-align: center;
			}
			#inputPanel{
				width: 400px;
				float: right;
			}
			#frame {
				font-size: 8px;
				width: 400px;
				height: 500px;
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
	</head>
	<body>
		<div id="mainFrame">
			<div id="preview">
				<a href="../">
				<p>Preview</p>
				<iframe id="frame" src="../"></iframe>
				</a>	
			</div>
			<div id="inputPanel">
				<form action="update.php">
					<input type="submit" value="Update webpage" />
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
				<form action="logout.php">
					<input type="submit" value="Logout" />
				</form>
			</div>
		</div>
	</body>
</html>
