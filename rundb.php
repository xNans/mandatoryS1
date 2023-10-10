<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MovieDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $html = "<table border=1>";
    
    while (($row = $result->fetch_assoc()) !== null) {
        $html .= "<tr>";

        foreach ($row as $columnName => $columnValue) {
            $html .=  "<td>" . $columnValue . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    echo $html;
} 
else {
  echo "0 results";
}
$conn->close();

?>