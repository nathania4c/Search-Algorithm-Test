    
    <?php
    
    session_start();
    
    if (isset($_GET["search"])){
        $_SESSION["keyword"] = $_GET["search"];
    }
   
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="ISO-8859-1">
    <link rel="stylesheet" href="css/index.css"/>
    <title>Index</title>
    </head>
    
    <body>
    	<main>
    		<div id="searchbar">
        		<form method="get" action="search.php">
        			<input type="text" id="searching" name="search" placeholder="What're You Looking For?">
        			<button id="searchbutton" type="submit">GO!</button>
        		</form>
    		</div>
    	   
        </main>
        <footer>
        </footer>
    </body>
	</html>

<?php

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
	$sql = "drop table if exists intern-test-data;
	create table intern-test-data (
		product name varchar(100) NOT NULL,
		price int(10) NOT NULL,
		dimension varchar(100) NOT NULL,
		colours varchar(100) NOT NULL,
		material varchar(100) NOT NULL,
		image varchar(500) NOT NULL
	);

	LOAD DATA INFILE 'intern-test-data.csv' 
	INTO TABLE intern-test-data 
	FIELDS TERMINATED BY ',' 
	ENCLOSED BY '"'
	LINES TERMINATED BY '\n'
	IGNORE 1 ROWS;
	";
	mysqli_query($mysqli, $sql);
?>
