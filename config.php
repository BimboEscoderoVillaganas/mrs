<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session if session not started
}
require_once('database/Database.php'); // Include the database class
?>
