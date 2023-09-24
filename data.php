<?php 
include_once('./uploads/getID3-1.9.21/getID3-1.9.21/getid3/getid3.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webcam";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$loc = "uploads/" . $_REQUEST["loc"] . ".webm";
$getID3 = new getID3;

$sql = "INSERT INTO info (`location`, `title`) VALUES ('$loc', 'None')";

$result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM info";
$result = mysqli_query($conn, $sql);

echo("<table class=\"table table-dark\">
<thead>
  <tr>
    <th scope=\"col\">ID</th>
    <th scope=\"col\">Preview</th>
    <th scope=\"col\">Title</th>
    <th scope=\"col\">Edit</th>
  </tr>
</thead>
<tbody>");
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    $file = $getID3->analyze($row["location"]);
    echo("<tr>
    <th scope=\"row\">" . $row["id"] . "</th>
    <td><video controls>
    <source src=\" " . $row["location"] . "\">
  Your browser does not support the video tag.
  </video></td> 
  <form action=\"\">
  <td>
  <input type=\"text\" id=" . $row["id"] ." name=\"fname\" placeholder = " . $row["title"]  
  . "></td>
  <td> 
  <button type=\"button\" class=\"btn btn-primary\" onclick = \"edit(" . $row["id"] . ")\">Edit</button>
  </td>
</form>
  
  </tr>
  <tr>");
//  echo($file['playtime_seconds']);
// print_r($file);
} 
} else {
  echo "0 results";
}
echo("</tbody>
  </table>");
  
mysqli_close($conn);
?>
