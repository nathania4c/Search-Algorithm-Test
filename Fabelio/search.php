    
    <?php
    
    session_start();
    
    if (isset($_SESSION["index"]) && isset($_SESSION["array"])){
        $index = $_SESSION["index"];
        $similars = $_SESSION["array"];
        if ($index >= count($similars))
            $index = 0;
    } else {
    
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Fabelio";
    
    $mysqli = new mysqli($host, $username,$password, $dbname);
    
    if($mysqli -> connect_errno){
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    if (isset($_GET["search"])){
        $_SESSION["keyword"] = "%".$_GET["search"]."%";
        $keyword = $_SESSION["keyword"];
    } else {
        $keyword = "%%";
    }
    
    $sql = "Select * From intern_test_data where `product name` like '$keyword' order by `product name`, `price` DESC";
    $result = $mysqli -> query($sql);
    $bestmatch = mysqli_fetch_assoc($result);
    
    $productname = $bestmatch["product name"];
    $image = $bestmatch["image"];
    $price = $bestmatch["price"];
    $dimension = $bestmatch["dimension"];
    $colours = $bestmatch["colours"];
    $colours = explode(", ", $colours);
    $material = $bestmatch["material"];
    
    $similars = array();
    $similars[$productname] = $bestmatch;
    mysqli_free_result($result);
    
    //match by material
    $sql = "Select * From intern_test_data where `material` = '$material' order by `product name`, `price` DESC";
    $result = $mysqli -> query($sql);
    while ($likematerial = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likematerial["product name"], $similars)){
            $similars[$likematerial["product name"]] = $likematerial;
        }
    }
    mysqli_free_result($result);
    
    //match by dimension
    $sql = "Select * From intern_test_data where `dimension` = '$dimension' order by `product name`, `price` DESC";
    $result = $mysqli -> query($sql);
    while ($likedimension = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likedimension["product name"], $similars)){
            $similars[$likedimension["product name"]] = $likedimension;
        }
    }
    mysqli_free_result($result);
    
    //match by price
    $price = substr($price, 0, 1);
    $price = $price."%";
    $sql = "Select * From intern_test_data where `price` like '$price' order by `product name`";
    $result = $mysqli -> query($sql);
    while ($likeprice = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likeprice["product name"], $similars)){
            $similars[$likeprice["product name"]] = $likeprice;
        }
    }
    
    //match by each colour
    foreach ($colours as $c){
        $c = "%".$c."%";
        $sql = "Select * From intern_test_data where `colours` like '$c' order by `product name`, `price` DESC";
        $result = $mysqli -> query($sql);
        while ($likecolours = mysqli_fetch_assoc($result)){
            if (!array_key_exists($likecolours["product name"], $similars)){
                $similars[$likecolours["product name"]] = $likecolours;
            }
        }
        mysqli_free_result($result);
    }
    
    $_SESSION["index"] = 0;
    $index = $_SESSION["index"];
    
    $_SESSION["array"] = $similars;
    
    mysqli_close($mysqli);
    
    }
    
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="ISO-8859-1">
    <title>Search</title>
    </head>
    <body style="padding-left: 1em">
    	<header>
    	</header>
    	<main>
    		<?php
    		
    		$count=0;
    		
    		foreach($similars as $key => $value) {
    		    
    		    if ($count != $index){
    		        $count++;
    		        continue;
    		    }
    		    
    		    echo "<h2>".$similars[$key]["product name"]."</h2>";
    		    echo "<img src='".$similars[$key]["image"]."' width=450px>";
    		    echo "<table>";
    		    echo "<tr><th style='text-align:left'>Material: </th><td>".$similars[$key]["material"]."</td></tr>";
    		    echo "<tr><th style='text-align:left'>Dimension: </th><td>".$similars[$key]["dimension"]."</td></tr>";
    		    echo "<tr><th style='text-align:left'>Price: </th><td>".$similars[$key]["price"]."</td></tr>";
    		    echo "<tr><th style='text-align:left'>Colours: </th><td>".$similars[$key]["colours"]."</td></tr>";
    		    echo "</table>";
    		    
    		    $index++;
    		    break;
    		}
    		
    		$_SESSION["index"] = $index;
    		
    		?>
        </main>
        <footer style="padding-top: 1em;">
        	<a href="index.php"><button>Back To Index</button></a>
        </footer>
    
    </body>
    </html>