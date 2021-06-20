<?php
require('../../includes/config.php');

$json = file_get_contents('php://input');
$params = json_decode($json);

$stmt = $dbh->prepare("DELETE FROM events WHERE id = :id");
$stmt->bindParam(':id', $params->id);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Delete successful';
$response->id = $params->id;

header('Content-Type: application/json');
echo json_encode($response);
