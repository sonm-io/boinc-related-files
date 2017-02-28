<?php
// This file is part of DrugDiscovery@Home BOINC project.
// http://boinc.drugdiscoveryathome.com
// Copyright (C) 2017 by Krzysztof 'krzyszp' Piszczek
// This file is available under GNU GPL3 licence

include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$appid = mysqli_real_escape_string($conn, $_GET['appid']);
$key = $_GET['key'];

if ($key == 'XXXXXXX') { 
    if (! filter_var($appid, FILTER_VALIDATE_INT)) {
        echo "Your variable is not an integer";
    }else {
    	header("Access-Control-Allow-Origin: *");
    	$sql = "SELECT id, workunitid, userid, hostid, received_time, name, flops_estimate, granted_credit, address FROM result RIGHT JOIN eth_wallets ON result.userid = eth_wallets.user_id WHERE result.appid = $appid AND server_state = 5 AND validate_state = 1 and result.id not in (select taskid from eth_payments where eth_payments.status != 0) limit 1000";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $rows = array();
            while ($r = mysqli_fetch_assoc($result)) {
                $rows['object_name'][] = $r;
            }
            $data = array('Jobs' => $rows);
            print json_encode($data);
        }else {
            echo "<br> No results";
        }
    }
}
$conn->close();

?>
