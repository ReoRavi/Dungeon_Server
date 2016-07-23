<?php

error_reporting(E_ALL&~E_NOTICE&~E_WARNING); 

$error = "error";

$db_host = "db.ravi1237.com";
$db_user = "accounts1237";
$db_pass = "ravi794200";
$db_name = "dbaccounts1237";
	
$conn = mysql_connect($db_host, $db_user, $db_pass) or die("NO");
	
$db = mysql_select_db($db_name);


$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'), true);

$table = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));
$key = array_shift($request);

switch ($method) {
		// 값 받기
  case 'GET':
		if ($key === "FirstAccess")
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
			$data = mysql_query(CreateGETQuery($key, $table));
			
			if (!$data)
   			{
				echo $error;
	   		}
			
			while($row = mysql_fetch_array($data))
			{
				echo json_encode($row);
    		}
		}
			
		break;

		// 수정
  case 'PUT':
		$data = mysql_query(CreatePUTQuery($input, count($input), $key, $table));
   
   		if (!$data)
   		{
			echo $error;
	   	}
		
		break;
		
		// 생성
  case 'POST':
		$data = mysql_query(CreatePOSTQuery($input, count($input), $table));
   
   		if (!$data)
   		{
			echo CreatePOSTQuery($input, count($input), $table);
	   	}
		
   		break;
	
		// 삭제
  case 'DELETE':
		$data = mysql_query(CreateDELETEQuery($key, $table));
   
   		if (!$data)
   		{
			echo $error;
	   	}
		
		break;
}

function CreateGETQuery($key, $tableName)
{
	$query = "select * from $tableName";
	
	if ($key)
	{
		$query .= " WHERE UserCode = $key;";
	}
	
	return $query;	
}

function CreatePUTQuery($jsonData, $count, $key, $tableName)
{
	$set = " ";
	
	for ($i = 0; $i < $count; ++$i) 
	{
		$set .= (string)key($jsonData);
		$set .= " = ";
		$set .= (string)array_shift($jsonData);
	
		if (!($i == $count - 1))
		{
			$set .= ", ";
		}
	}

	$query = "UPDATE $tableName SET $set WHERE UserCode = $key";
	
	return $query;	
}

function CreatePOSTQuery($jsonData, $count, $tableName)
{
	$columns = "(";
	$values = "(";
	
	for ($i = 0; $i < $count; ++$i) 
	{
		$columns .= (string)key($jsonData);
		$values .= (string)array_shift($jsonData);
	
		if ($i == $count - 1)
		{
			$columns .= ")";
			$values .= ")";
		}
		else
		{
			$columns .= ", ";
			$values .= ", ";
		}
	}
	
	$query = "INSERT INTO $tableName $columns VALUES $values;";
	
	return $query;
}

function CreateDELETEQuery($key, $tableName)
{
	$query = "DELETE FROM $tableName WHERE UserCode = $key";
	
	return $query;	
}

// close mysql connection
mysqli_close($conn);

?>