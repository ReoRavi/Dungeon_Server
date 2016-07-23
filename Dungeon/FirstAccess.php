<?php

error_reporting(E_ALL&~E_NOTICE&~E_WARNING); 

require ("REST.php");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$jsonData = json_decode(file_get_contents('php://input'), true);

$table = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));
$key = array_shift($request);

	// mysql의 특수화 함수를 사용하는 쿼리를 구별한다.
	if ($key === "Count")
	{
		$count = mysql_query("select count(*) from $table;");
			
		if (!$count)
   		{
			echo $error;
	   	}
		else
		{
			$userCode = mysql_fetch_row($count);
			
			echo $userCode[0] + 1;
		}
	}
	else
	{
		echo RESTProcess($method, $table, $key, $jsonData);
	}
	
?>
