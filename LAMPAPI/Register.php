<?php
$inData = getRequestInfo();

$firstName = $inData["FirstName"];
$lastName = $inData["LastName"];
$login = $inData["Login"];
$password = $inData["Password"];

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if ($conn->connect_error)
{
    returnWithError( $conn->connect_error );
}
else
{
	$stmtValidation = $conn->prepare("Select Login from Users where Login = ?");
	$stmtValidation->bind_param("s", $login);
	$stmtValidation->execute();
	$result = $stmtValidation->get_result();
	
	if($row = $result->fetch_assoc())
	{
		$stmtValidation->close();
		$conn->close();
		existenceError("");
	}
	else
	{
		$stmtValidation->close();
		$stmt = $conn->prepare("INSERT into Users (FirstName,LastName,Login,Password) VALUES(?,?,?,?)");
		$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}
		
}

function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson( $obj )
{
    header('Content-type: application/json');
    echo $obj;
}

function existenceError( $err )
{
    $retValue = '{"exists":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
}

function returnWithError( $err )
{
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
}

?>