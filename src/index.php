<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include 'stored.php';
$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $myPassword, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else{
  echo 'Connection to database successful!<br>';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Kelvin Watson - Assignment 2, HTML and CSS</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<header>
			<h1 id="title">CS 290 Assignment 4, Part 2</h1>
			<h2 id="subtitle">PHP-MySQL Assignment</h2>
			<p id="author">Programmed by: Kelvin Watson (OSU ID 932540242)</p>
		</header>
		<section id="addVideoForm">
			<h2>Add Video</h2>
			<form action="#" method="get">
				<fieldset>
					<legend>Enter Video Information</legend>
					<label for="name">Video Name</label>
					<input type="text" name="videoName" placeholder="Video Name"><br>
					<label for="name">Video Category</label>
					<input type="text" name="videoCategory" placeholder="Video Category"><br>
					<label for="name">Video Length</label>
					<input type="text" name="length" placeholder="Enter Video Length">
				</fieldset>
				<input type="submit" name="add" value="Add Video">
			</form>
		</section>

    <section id="SQLCheckAdds">
      <h2>Status of Adds</h2>
      <?php
      if(isset($_GET['add'])){
        echo 'You pressed the add button!<br>';
        if(empty($_GET['videoName'])){
          echo 'Sorry, video name form field cannot be empty<br>';
        } else{
          echo 'Great, you filled in a video name. Time to process it';
          //INSERT this into the sql table?
        }
      }

      ?>
    </section>
	  
    <section>
		  <h2>Video Inventory (Table)</h2>
		</section>
	</div>

	</body>
</html>
