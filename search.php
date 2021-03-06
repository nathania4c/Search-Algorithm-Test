    
    <?php
    
    session_start();
    
    if (isset($_SESSION["index"]) && isset($_SESSION["array"])){
        $index = $_SESSION["index"];
        $similars = $_SESSION["array"];
        if ($index >= count($similars))
            $index = 0;
    } else {
    
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);
    $hostname = $dbparts['host'];
    $username = $dbparts['user'];
    $password = $dbparts['pass'];
    $database = ltrim($dbparts['path'],'/');

    $mysqli = new mysqli($hostname, $username, $password, $database);
    
    if($mysqli -> connect_errno){
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    if (isset($_GET["search"])){
        $_SESSION["keyword"] = "%".$_GET["search"]."%";
        $keyword = $_SESSION["keyword"];
    } else {
        $keyword = "%%";
    }
    
    $sql = "Select * From MyTable where `productname` like '$keyword' order by `productname`, `price` DESC";
    $result = $mysqli -> query($sql);
    
    $similars = array();
    
if ($bestmatch = mysqli_fetch_assoc($result)){
    
    $productname = $bestmatch["productname"];
    $image = $bestmatch["image"];
    $price = $bestmatch["price"];
    $dimension = $bestmatch["dimension"];
    $colours = $bestmatch["colours"];
    $colours = explode(", ", $colours);
    $material = $bestmatch["material"];
    
    $similars[$productname] = $bestmatch;
    mysqli_free_result($result);
    
    //match by material
    $sql = "Select * From MyTable where `material` = '$material' order by `productname`, `price` DESC";
    $result = $mysqli -> query($sql);
    while ($likematerial = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likematerial["productname"], $similars)){
            $similars[$likematerial["productname"]] = $likematerial;
        }
    }
    mysqli_free_result($result);
    
    //match by dimension
    $sql = "Select * From MyTable where `dimension` = '$dimension' order by `productname`, `price` DESC";
    $result = $mysqli -> query($sql);
    while ($likedimension = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likedimension["productname"], $similars)){
            $similars[$likedimension["productname"]] = $likedimension;
        }
    }
    mysqli_free_result($result);
    
    //match by price
    $price = substr($price, 0, 1);
    $price = $price."%";
    $sql = "Select * From MyTable where `price` like '$price' order by `productname`";
    $result = $mysqli -> query($sql);
    while ($likeprice = mysqli_fetch_assoc($result)){
        if (!array_key_exists($likeprice["productname"], $similars)){
            $similars[$likeprice["productname"]] = $likeprice;
        }
    }
    
    //match by each colour
    foreach ($colours as $c){
        $c = "%".$c."%";
        $sql = "Select * From MyTable where `colours` like '$c' order by `productname`, `price` DESC";
        $result = $mysqli -> query($sql);
        while ($likecolours = mysqli_fetch_assoc($result)){
            if (!array_key_exists($likecolours["productname"], $similars)){
                $similars[$likecolours["productname"]] = $likecolours;
            }
        }
        mysqli_free_result($result);
    }
    
    $_SESSION["index"] = 0;
    $index = $_SESSION["index"];
    
    $_SESSION["array"] = $similars;
    
}
    
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
            
            if (count($similars) == 0){
    		    echo "<p> sorry, no matches. Go back to index and search a different keyword</p>";
    		} else {
    		
    		$count=0;
    		
    		foreach($similars as $key => $value) {
    		    
    		    if ($count != $index){
    		        $count++;
    		        continue;
    		    }
    		    
    		    echo "<h2>".$similars[$key]["productname"]."</h2>";
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
            }
    		?>
        </main>
        <footer style="padding-top: 1em;">
        	<a href="index.php"><button>Back To Index</button></a>
            <p>Refresh the page to see the next best match</p>
        </footer>
    
    </body>
    </html>
