<?php
$conn = new mysqli("localhost", "root","1234","linebot");
/* check connection */
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
if(!$conn->set_charset("utf8mb4")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
}
?>
