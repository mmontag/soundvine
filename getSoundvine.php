<?php

require(".db.config.php");

$id = $_GET['id'];

if (preg_match('/^[\w\d-]{1,30}$/', $id) !== 1) {
  header(':', true, 400);
  die("Invalid id.");
}

$query = "SELECT * FROM vines WHERE id = '$id' LIMIT 1";
$result = $mysqli->query($query);
if ($result->num_rows < 1) {
  header(':', true, 404);
  echo json_encode(array(
    "error" => "No records matched the query"
  ));
  exit;
}
$row = $result->fetch_object();

echo json_encode($row);

$result->close();
$mysqli->close();