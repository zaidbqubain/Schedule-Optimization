<?php
require('../../includes/config.php');

$json = file_get_contents('php://input');
$params = json_decode($json);

$stmt = $dbh->prepare("UPDATE events SET event_start = :start, event_end = :end WHERE id = :id");
$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':start', $params->newStart);
$stmt->bindParam(':end', $params->newEnd);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Successful';

echo json_encode($response);

