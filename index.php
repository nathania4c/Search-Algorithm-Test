    
    <?php
    
    session_start();
    
	unset($_SESSION["index"]);
   	unset($_SESSION["array"]);

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
