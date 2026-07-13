<?php
$mysqli = new mysqli('localhost', 'root', '', 'pradan');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

function check_table($mysqli, $table) {
    echo "--- $table ---\n";
    $result = $mysqli->query("DESCRIBE $table");
    if ($result) {
        $cols = [];
        while($row = $result->fetch_assoc()) {
            $cols[] = $row['Field'];
        }
        echo implode(", ", $cols) . "\n\n";
    } else {
        echo "Table $table does not exist.\n\n";
    }
}

check_table($mysqli, 'tblpur_request_detail');
check_table($mysqli, 'tblpur_order_detail');
check_table($mysqli, 'tblpur_invoice_details');
check_table($mysqli, 'tblpur_estimate_detail');

$mysqli->close();
