<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include 'stored.php';
session_start();


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
					<input type="text" name="videoLength" placeholder="Enter Video Length">
				</fieldset>
				<input type="submit" name="add" value="Add Video">
			</form>
		</section>

    <section id="SQLCheckAdds">
      <h2>Status of Adds (Testing Only)</h2>
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
		  <?php
      if(isset($_GET['add'])){
        echo 'You pressed the add button!<br>';
        if(empty($_GET['videoName'])){
          echo 'Sorry, video name form field cannot be empty<br>';
        } else{
          if (!($stmt = $mysqli->prepare("INSERT INTO VideoInventory(name, category, length, rented) VALUES (?,?,?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          } else {
            echo 'prepare successful! YAY!<br>';
          }
          $vName = $_GET['videoName'];
          $vCat = $_GET['videoCategory'];
          $vLen = $_GET['videoLength'];
          $vRented = 0;
          if (!$stmt->bind_param("ssii", $vName, $vCat, $vLen, $vRented)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          } else {
            echo 'Bind successful, yay!<br>';
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }else {
            echo "$vName successfully added!<br>";
          }
          $stmt->close();
        }
      }
      ?>
      <h2>Video Inventory</h2>
      
        <section id="inventoryOptions">
          <form action="#" method="get">
            <fieldset>
              <legend>Filter Videos</legend>
                  <?php  
                    if (!($stmt = $mysqli->prepare("SELECT category FROM VideoInventory"))) {
                      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    } else {
                      //echo 'Prepare for dropdown successful<br>';
                    }
                    $vCat = null;
                    if (!$stmt->execute()) {
                      echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    } else {
                      //echo 'Execution drop down success <br>';
                    }
                    if (!$stmt->bind_result($vCat)) {
                      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                      //echo 'binding category successful';
                    }
                    echo 'Select Category';
                    echo '<select name="filter">';
                    echo "<option value=\"All Movies\">All Movies</option>";
                    while ($stmt->fetch()) {
                      echo "<option value=\"$vCat\">$vCat</option>";
                    }
                    echo '</select>';
                    echo "<button type=\"submit\">Filter</button>";
                    $stmt->close();
                    ?>
            </fieldset>
          </form>
          <form action="#" method="get">
            <fieldset>
              <legend>Delete All Movies</legend>
              <button type="submit" name="deleteAll">Delete All</button>      
            </fieldset>
          </form>
        </section>
      <?php
      if(isset($_GET['deleteAll'])){
        if (!($stmt = $mysqli->prepare("DELETE FROM VideoInventory"))) {
          echo "Prepare for delete all failed: (" . $mysqli->errno . ") " . $mysqli->error;
        } else {
          echo 'Prepare for delete all successful!<br>';
        }
        if (!$stmt->execute()) {
            echo "Delete all execution failed: (" . $stmt->errno . ") " . $stmt->error;
        }else {
            echo "Delete all execution successful<br>";
        }
      }
      
      ?>
      <?php
        $colHeaders = array(
          'vI' => 'ID',
          'vN' => 'Name',
          'vC' => 'Category',
          'vL' => 'Length',
          'vR' => 'Rented',
          'vD' => 'Delete'
        );
        if(isset($_GET['filter'])){
         if (!($stmt = $mysqli->prepare("SELECT * FROM VideoInventory WHERE category='$_GET[filter]"))) {
            echo "Prepare for table printing failed: (" . $mysqli->errno . ") " . $mysqli->error;
          } else {
            echo '<br>Prepare for table printing successful! YAY!<br>';
          } 
        }
        else{
          if (!($stmt = $mysqli->prepare("SELECT * FROM VideoInventory"))) {
            echo "Prepare for table printing failed: (" . $mysqli->errno . ") " . $mysqli->error;
          } else {
            echo '<br>Prepare for table printing successful! YAY!<br>';
          }
        }  
          

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }else {
            echo "Execution successful<br>";
        }
        $vID = $vName = $vCat = $vLen = $vRented = null;
        if (!$stmt->bind_result($vID, $vName, $vCat, $vLen, $vRented)) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
          echo 'Bind result success, yay!<br>';
        }
        echo '<table border="1"><thead><tr>';
      
        foreach ($colHeaders as $key => $colName){
          echo "<th>$colName</th>";
        }
        echo '</tr>';

        while($stmt->fetch()){
          echo '<tr>';
            echo "<td>$vID</td><td>$vName</td><td>$vCat</td><td>$vLen</td><td>$vRented</td>";
            echo '<td><button name="deleteButton">Delete</button></td>';
          echo '</tr>';
        }
        $stmt->close();


      ?>
      </table>
      
    </section>
	</div>

	</body>
</html>
