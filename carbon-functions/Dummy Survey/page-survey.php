<?php

	/* Establish the connection with SQL database */
	$hostname = "localhost";
	$database = "co2functions";
	$username = "root";
	$password = "";
	$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
	mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

	
	/* Declare formSubmit if document is just loading */
	if(empty($_POST['formSubmit'])){
		$formSubmit = '';
	} else {
		$formSubmit = $_POST['formSubmit'];
	}
	
	if($formSubmit == "Submit")
	{
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		/* If statements for basic data verification */
		$errorMessage = "";
		
		if(empty($_POST['name']))
		{
			$errorMessage .= "<li>You forgot to enter your name!</li>";
		}
		if(empty($_POST['species']))
		{
			$errorMessage .= "<li>You forgot to enter your tree's species!</li>";
		}
		if(empty($_POST['diameter'])) 
		{
			$errorMessage .= "<li>You forgot to enter your tree's diameter!</li>";
		}

		if(empty($errorMessage)) {
			/* Generate an id for the new tree */
			$result = mysqli_query($conn, "SELECT * FROM trees");
			$id = mysqli_num_rows($result) + 1;

			/* Get the data and store them in variables */
			$name = $_POST["name"];
			$species = $_POST["species"];
			$diameter = $_POST["diameter"];
			
			/* Modify the variables here */
			$diameter = $diameter * 3.3;
			
			/* Create the INSERT query to transfer php data to mysql database */
			$sql = "INSERT INTO trees (treeid, custname, treespecies, treediameter) 
					VALUES ($id, '$name', '$species', $diameter)";
			
			/* Check if query processed correctly */
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			/* Redirection to the success page (homepage for carbon) */
			header("Location: http://localhost/co2serverfunctions/page-survey2.php");
		}
	}
?>

<html>
	<head>
		<title>Enter your Tree</title>
	</head>

	<body>
		<!-- Display an error message if one exists from the data verification process -->
		<?php
			if(!empty($errorMessage)) 
			{
				echo("<p>There was an error with your form:</p>\n");
				echo("<ul>" . $errorMessage . "</ul>\n");
			} 
		?>
		
		<!-- Simple form for general customer information -->
		<form action="http://localhost/co2serverfunctions/page-survey.php" method="post">
			Your Name: <input type="text" id="name" name="name" /><br />
			Tree Species: <input type="text" id="species" name="species" /><br />
			Diameter: <input type="floatval" name="diameter" /><br />
			<input type="submit" id="formSubmit" name="formSubmit" value="Submit" />
		</form>
	</body>
</html>