<html>
	<head>
		<title></title>
	</head>
	<body>
		<form>
			<input id="dateMonth" type="number" name="dateMonth" />
			<input id="dateDay" type="number" name="dateDay" />
			<input id="dateYear" type="number" name="dateYear" />
		</form>
		<script type="text/javascript">
			var dateValue = new Date();
			var dateDayValue = dateValue.getDate();
			var dateMonthValue = dateValue.getMonth()+1;
			var dateYearValue = dateValue.getFullYear();

			document.getElementById('dateMonth').value = dateMonthValue;
			document.getElementById('dateDay').value = dateDayValue;
			document.getElementById('dateYear').value = dateYearValue;
		</script>
	</body>
</html>
