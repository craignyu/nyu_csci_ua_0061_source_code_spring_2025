<?php

  // file path
  $path = getcwd() . '/databases';

  // open database
  $db = new SQLite3($path.'/pokemon_message_board.db');

  // database contains a single table named 'messages' with the following properties:
  // id      INTEGER PRIMARY KEY AUTOINCREMENT
  // avatar  TEXT
  // message TEXT

  // see if we are looking for only recent images
  if (isset($_GET['id'])) {
    $sql = "SELECT * FROM messages WHERE id > :id ORDER BY id";
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $_GET['id']);
    $result = $statement->execute();
  }

  // otherwise extract all messages from the database
  else {
    $sql = "SELECT * FROM messages ORDER BY id";
    $statement = $db->prepare($sql);
    $result = $statement->execute();  
  }

  // store all results in an associative array that we will send back to the HTML page
  $send_back = array();
  $send_back['records'] = array();
  $send_back['last_id'] = false;
  if (array_key_exists('id', $_GET)) {
    $send_back['last_id'] = intval($_GET['id']);
  }

  // visit all records
  while ($row = $result->fetchArray()) {

    // create a mini-array to hold this record
    $record = array();
    $record['id'] = $row['id'];
    $record['avatar'] = $row['avatar'];
    $record['message'] = $row['message'];

    // store this as our last ID
    $send_back['last_id'] = $row['id'];

    // add record to the main array
    array_push($send_back['records'], $record);
  }

  // close the database
  $db->close();
  unset($db);

  // turn the array into a JSON object and print it to the browser
  print json_encode($send_back);
  exit();

 ?>
