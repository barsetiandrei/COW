<?php 
	include 'db_connect.php';
	session_start();

	$conn = OpenCon();
	$uid = $_SESSION['uid'];

	header('Content-disposition: attachment; filename=report.xml');
	header('Content-type: text/xml');

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><metrics></metrics>');
	$xml->addAttribute('version', '1.0');

	if ($result = $conn->query("SELECT * FROM metrics WHERE uid='". $uid ."' ORDER BY date ASC")) {
		if ($rows = $result->fetch_all(MYSQLI_ASSOC)) {
			foreach ($rows as $row) {
				$xmlRow = $xml->AddChild("day");
				$xmlRow -> addChild('uid',$row['uid']);
				$xmlRow -> addChild('Date',$row['date']);
				$xmlRow -> addChild('Calories',$row['calories']);
				$xmlRow -> addChild('Carbs',$row['carbs']);
				$xmlRow -> addChild('Fat',$row['fat']);
				$xmlRow -> addChild('Protein',$row['protein']);
				$xmlRow -> addChild('Sodium',$row['sodium']);
				$xmlRow -> addChild('Sugar',$row['sugar']);
			}

			$dom = dom_import_simplexml($xml)->ownerDocument;
			$dom->formatOutput = true;
			echo $dom->saveXML();
		}
	}
?>