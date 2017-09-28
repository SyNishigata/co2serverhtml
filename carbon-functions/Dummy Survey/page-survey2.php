<?php

	function display_survey(){
		$hostname = "localhost";
		$database = "co2functions";
		$username = "root";
		$password = "";
		$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
		mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

		/* Query the table and store it in a variable */
		$result = mysqli_query($conn, "SELECT * FROM trees");


		/* If you require you may print and view the contents of $result object */
		echo "<pre>"; print_r($result); echo "</pre>";


		/* Print the contents of $result looping through each row returned in the result */
		echo "All rows: <br>";
		echo "id,"." "."name,"."species,"."diameter,"."<br><br>";
		
		while ($row = mysqli_fetch_assoc($result))
		{
			print_r($row);
			echo("<br>");
		}
		
	}
	
	display_survey();
	
?>
