<?php 
function requestDb()
{
	$dbRequest = "";
	$dbUser = "";
	$dbPassword = "";
	$openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST');

	$dbName = "reading_groups_app";
	if ($openShiftVar === null || $openShiftVar == "")
	{
  		//Not in the openshift environment
		require('setLocalDbCredentials.php');
		$dbRequest = "mysql:host=$dbHost;dbname=$dbName";
	}
	else 
	{
     	// In the openshift environment 
     	$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
		$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
		$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
		$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
		$dbRequest = "mysql:host=$dbHost:$dbPort;dbname=$dbName";
	}

	try 
	{
		$db = new PDO($dbRequest, $dbUser, $dbPassword);
	}
	catch (PDOEXCEPTION $ex) 
	{

	}

	return $db;
}
?>