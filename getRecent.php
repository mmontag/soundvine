<?php

require(".db.config.php");

$query = "SELECT * FROM vines ORDER BY time_stamp DESC LIMIT 5";
$result = $mysqli->query($query);
echo $mysqli->error;
if ($result->num_rows < 1) {
  header(':', true, 404);
  echo json_encode(array(
    "error" => "No records matched the query"
  ));
  exit;
}

$rows = array();
while($row = $result->fetch_object()) {
  $rows[] = $row;
}

echo json_encode($rows);

$result->close();
$mysqli->close();