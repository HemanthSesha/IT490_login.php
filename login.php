<?php
	include("config.php");
	session_start();
	if(isset($_POST['submit']))
	{
		//Grab Session ID's Username + Password
		$_SESSION['id'] = mysqli_real_escape_string($db,$_POST['PatronName']);
		$_SESSION['pass'] = mysqli_real_escape_string($db,$_POST['PatronCardNumber']);
		//IDs from the user input textfields
		$id = $_SESSION['id'];
		$pass = $_SESSION['pass'];
		$sql = "SELECT * FROM assign3 WHERE PatronName= '$id' AND PatronCardNumber= '$pass'";
		$result = mysqli_query($db, $sql);
    //If Login can't be found
		if($result->num_rows == 0)
		{
			$error_msg = "No Such Patron Login";
		}
		else
		{
			//If Login was Successful go here
			header('Location: https://web.njit.edu/~ky77/Main.php');
			exit;
		}
	}
	if(isset($_POST['register']))
	{
		//Make new vaules for new users
		$id = mysqli_real_escape_string($db,$_POST['PatronName']);
		$pass = mysqli_real_escape_string($db,$_POST['PatronCardNumber']);
		$email = mysqli_real_escape_string($db,$_POST['PatronEmailAddress']);
		
		//Check if Patron ID is taken
		$sql = "SELECT * FROM assign3 WHERE PatronCardNumber = '$pass'";
		$result = mysqli_query($db,$sql);
		//Check Email Field for data only triggers if blank
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			echo "Email address '$email' is considered invalid. Enter a new email address and try again.\n";
			exit;
		}
		if($result->num_rows == 1)
		{
			$error_msg = "Patron ID taken";
		}
		//If everything is good 
		if($result->num_rows == 0)
		{
			$sql = "INSERT INTO assign3 (PatronName, PatronCardNumber, PatronEmailAddress ) VALUES ('$id', '$pass', '$email')";
			if (mysqli_query($db, $sql))
			{
				header('Location: https://web.njit.edu/~ky77/registrationsuccess.php');
				exit;
			}
			else
			{
				//For debugging (This is hard to fix if something goes wrong)
				$error_msg4 = "Oops! Something Went Wrong";
			}
		}
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<title>Kenny - Assignment 4 Login Page</title>
	</head>
	<!-- Begin Body -->
	<body>
			<form action="" method="post">
				<table>
					<tr>
						<th>Libary Login Page</th>
					</tr>
					<tr>
						<td class = "labels">Patron Name:</td>
						<!-- Patron Name min length set to one to validate they have info -->
						<td><input type="text" name="PatronName" id="PatronName" minlength="1" required></td>
					</tr>
					<tr>
						<td class="labels">Patron ID:</td>
						<td><input type="text" name="PatronCardNumber" id="PatronCardNumber" minlength="8" maxlength="8" required></td>
					</tr>
					<tr>
						<td class="labels">Email: (Enter only if you want to register)</td>
						<td><input type="email" name="PatronEmailAddress" id="PatronEmailAddress"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" name = "submit" value="Login" />
						</td>
						<td>
							<input type="submit" name = "register" value="register" />
						</td>
					</tr>
				</table>
			</form>
			<!-- Throw Error Message -->
			<div style = "font-size:24px; color:red;"><?php echo $error_msg; ?></div>
			
	</body>
</html>