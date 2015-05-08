<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include 'stored.php';
session_start();

$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
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
			<h2 id="subtitle">(PHP-MySQL)</h2>
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
				<p><input type="submit" name="add" value="Add Video"></p>
			</form>
		</section>

    
    <section id="inventoryTable">
		  <?php
      if(isset($_GET['add'])){
        if(empty($_GET['videoLength'])){
          $_GET['videoLength'] = 0;
        }
        if(empty($_GET['videoName']) || ctype_space($_GET['videoName']) || !is_numeric($_GET['videoLength']) || $_GET['videoLength']<0) {
          if(empty($_GET['videoName']) || ctype_space($_GET['videoName'])) {
            echo '<p class="error">ERROR: You must enter a Video Name.</p>';
          }
          if(!is_numeric($_GET['videoLength'])){ 
            echo '<p class="error">ERROR: Video length must be numeric.</p>';
          }
          if($_GET['videoLength']<0){ 
            echo '<p class="error">ERROR: Video length must be 0 or greater.</p>';
          }
        } else {
          if (!($stmt = $mysqli->prepare("INSERT INTO VideoInventory(name, category, length, rented) VALUES (?,?,?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          $vName = $_GET['videoName'];
          $vCat = $_GET['videoCategory'];
          $vLen = $_GET['videoLength'];
          $vRented = 0;
          if (!$stmt->bind_param("ssii", $vName, $vCat, $vLen, $vRented)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          $stmt->close();
        }
      }

      if(isset($_GET['deleteAll'])){
        if (!($stmt = $mysqli->prepare("DELETE FROM VideoInventory"))) {
          echo "Prepare for delete all failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->execute()) {
          echo "Delete all execution failed: (" . $stmt->errno . ") " . $stmt->error;
        }
      }
      ?>
      <h2>Video Inventory</h2>
      <section id="inventoryOptions">
        <form action="#" method="get">
          <fieldset>
            <legend>Filter Videos</legend>
              <?php  
                if (!($stmt = $mysqli->prepare("SELECT DISTINCT category FROM VideoInventory WHERE (category IS NOT NULL and category != '')"))) {
                  echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                $vCat = null;
                if (!$stmt->execute()) {
                  echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->bind_result($vCat)) {
                  echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                echo '<span class="filterText">Select Category</span>';
                echo '<select name="filter" class="filterText">';
                echo "<option value=\"allMovies\">All Movies</option>";
                while ($stmt->fetch()) {
                  echo "<option value=\"$vCat\">$vCat</option>";
                }
                echo '</select>';
                echo "<button type=\"submit\" class=\"filterText\" >Filter</button>";
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
        $colHeaders = array(
          'vI'  => 'ID',
          'vN'  => 'Name',
          'vC'  => 'Category',
          'vL'  => 'Length',
          'vR'  => 'Status',
          'vIO' => 'Check In/Check Out',
          'vD'  => 'Delete'
        );
        if(isset($_GET['checkIn'])){
          $cID = $_GET['checkIn'];
          if (!($stmt = $mysqli->prepare("UPDATE VideoInventory SET rented=? WHERE id=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          $a=0;
          if (!$stmt->bind_param("ii", $a, $cID)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          $stmt->close();
        }
        if(isset($_GET['checkOut'])){
          $cID = $_GET['checkOut'];
          if (!($stmt = $mysqli->prepare("UPDATE VideoInventory SET rented=? WHERE id=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          $a=1;
          if (!$stmt->bind_param("ii", $a,$cID)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          $stmt->close();
        }

        if(isset($_GET['deleteButton'])){
          $dID = $_GET['deleteButton'];
          if (!($stmt = $mysqli->prepare("DELETE FROM VideoInventory WHERE id=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          if (!$stmt->bind_param("i", $dID)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          $stmt->close();
        }

        if(isset($_GET['filter']) && $_GET['filter'] !== 'allMovies'){
          if (!($stmt = $mysqli->prepare("SELECT * FROM VideoInventory WHERE category = ?"))) {
            echo "Prepare for table printing failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          if (!$stmt->bind_param("s", $_GET['filter'])) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
        }
        else{
          if (!($stmt = $mysqli->prepare("SELECT * FROM VideoInventory"))) {
            echo "Prepare for table printing failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
        }  
          
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $vID = $vName = $vCat = $vLen = $vRented = null;
        if (!$stmt->bind_result($vID, $vName, $vCat, $vLen, $vRented)) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        echo '<p><table border="1"><thead><tr>';
      
        foreach ($colHeaders as $key => $colName){
          echo "<th>$colName</th>";
        }
        echo '</tr>';

        while($stmt->fetch()){
          echo '<tr>';
            echo "<td>$vID</td><td>$vName</td><td>$vCat</td><td>$vLen</td>";
            if($vRented==0){
              echo '<td>Available (Checked In)</td>';
            } else{
              echo '<td>Checked Out</td>';
            }
            echo '<form action="#" method="get">';
              if($vRented==0){
                echo "<td><button type=\"submit\" name=\"checkOut\" value=\"$vID\">Check Out</button></td>";
              } else {
                echo "<td><button type=\"submit\" name=\"checkIn\" value=\"$vID\">Check In</button></td>";;
              }
            echo '</form>';
            echo "<form action=\"#\" method=\"get\"><td><button type=\"submit\" name=\"deleteButton\" value=\"$vID\">Delete</button></td></form>";
          echo '</tr>';
        }
        $stmt->close();


      ?>
      </table>
      
    </section>

	</body>
</html>