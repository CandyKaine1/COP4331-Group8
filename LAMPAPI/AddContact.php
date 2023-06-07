<?php
$inData = getRequestInfo();

$name = $inData["Name"];
$phone = $inData["Phone"];
$email = $inData["Email"];
$userID = $inData["UserID"];


$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if ($conn->connect_error)
{
    returnWithError( $conn->connect_error );
}
else
{
	$stmtValidation = $conn->prepare("SELECT * FROM Contacts WHERE (Name = ? AND Email = ? AND Phone = ? AND UserID = ?)");
	$stmtValidation->bind_param("ssss", $name, $email, $phone, $userID);
	$stmtValidation->execute();
	$result = $stmtValidation->get_result();
	
	if($row = $result->fetch_assoc())
	{
		$stmtValidation->close();
		existenceError("true");
	}
	else
	{
		$stmtValidation->close();
		$stmt = $conn->prepare("INSERT into Contacts (Name,Phone,Email,UserID) VALUES(?,?,?,?)");
		$stmt->bind_param("ssss", $name, $phone, $email, $userID);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("false");
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
    $retValue = '{"exists":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
}

?>
