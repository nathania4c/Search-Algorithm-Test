<?php

session_start();

$mysqli = new mysqli("localhost", "root","", "Fabelio");

if($mysqli -> connect_errno){
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "Select * From intern_test_data where `product name` like '$keyword' ";
$result = $mysqli -> query($sql);

$countsql = "Select count(*) From intern_test_data where `product name` like '$keyword' ";
$countresult = $mysqli -> query($countsql);
$countfieldinfo = mysqli_fetch_assoc($countresult);
$totalcount = $countfieldinfo["count(*)"];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Search Page</title>
</head>
<body>
	<header>
	</header>
	<main>
		<form id="searchbar" method="get" action="search.php">
			<button type="submit">Search</button><input type="text" id="searching" name="search" placeholder="type in product name">
		</form>
		
		<table>
			<tr>
				<th>Product Name</th>
				<th>Image</th>
				<th>Price</th>
				<th>Dimensions</th>
				<th>Colours</th>
				<th>Material</th>
			</tr>
		
		<?php
		
		$index = $_SESSION["index"];
		$count = 0;
		
		while($fieldinfo = mysqli_fetch_assoc($result)){
		    
		    if ($count != $index){
		        $count++;
		        continue;
		    }
		    
		    $productname = $fieldinfo["product name"];
		    $image = $fieldinfo["image"];
		    $price = $fieldinfo["price"];
		    $dimension = $fieldinfo["dimension"];
		    $colours = $fieldinfo["colours"];
		    $material = $fieldinfo["material"];
		    
		    echo "<tr>";
		    echo "<td>".$productname."</td>";
		    echo "<td><img src='".$image."' alt='none' width='100'></td>";
		    echo "<td>Rp ".$price."</td>";
		    echo "<td>".$dimension."</td>";
		    echo "<td>".$colours."</td>";
		    echo "<td>".$material."</td>";
		    echo "</tr>";
		    
		    $index++;
		    break;
		
		}
	
		$result -> free_result(); 
		$mysqli -> close();
		
		$_SESSION["index"] = $index;
		    
	   ?>
	   
	   </table>
	   
	   <?php
	   
	   if ($index == $totalcount){
		    echo "<p>Would you like to view the options again?</p>";
		}
	   ?>
	   
</main>
	
<footer>
</footer>

</body>
</html>