<?php
// Database Connection Parameters
$dsn = 'mysql:host=localhost;dbname=blog; charset=UTF8';
$user = 'prasiddha';
$pass = '1234';
// Try To Connect
try {
  // Create Connection
  $db = new PDO($dsn, $user, $pass);
  // Set Fetch Method Globally
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Failed To Connect Message
  die('Failed to connect with database. Error:  ' . $e->getMessage());
}