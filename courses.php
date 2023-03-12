<?php

  // Get the assignments from the database
  $query = "SELECT * FROM assignments WHERE user_id = $userId";
  $result = $db->query($query);
  $assignments = array();
  while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
  }

  // Return the assignments as JSON
  header("Content-Type: application/json");
  echo json_encode($assignments);
?>