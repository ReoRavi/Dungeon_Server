<?php

error_reporting(E_ALL&~E_NOTICE&~E_WARNING); 

require ("REST.php");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$jsonData = json_decode(file_get_contents('php://input'), true);

$table = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));
$key = array_shift($request);

	if ($key === "Sort")
	{
		$sort = mysql_query("SELECT * FROM Dungeon_Rank ORDER BY 'Score' DESC;");
		
		$sortResult = "";		
						
		while($row = mysql_fetch_array($sort))
		{
			$sortResult .= json_encode($row);
		}
			
		echo $sortResult;	
	}
	else
	{
		echo RESTProcess($method, $table, $key, $jsonData);
	}


?>
