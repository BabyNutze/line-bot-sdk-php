<?php

$db = parse_url(getenv("postgres://qlalwjlsbccpqs:633bc9695e4fb4a66bdd23140ea8f4f10f10056b24d2297b83b591cc6e932a08@ec2-54-83-205-27.compute-1.amazonaws.com:5432/da3pmg91gstk4k"));
$db["path"] = ltrim($db["path"], "/");

/*--$conn = new mysqli("localhost", "root","1234","linebot");

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
if(!$conn->set_charset("utf8mb4")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
}
*/
?>
