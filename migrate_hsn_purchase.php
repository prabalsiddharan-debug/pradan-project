<?php
$mysqli = new mysqli('localhost', 'root', '', 'pradan');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$tables = ['tblpur_order_detail', 'tblpur_invoice_details', 'tblpur_estimate_detail'];

foreach ($tables as $table) {
    echo "Processing $table...\n";
    $result = $mysqli->query("DESCRIBE $table 'hsn_code'");
    if ($result->num_rows == 0) {
        $mysqli->query("ALTER TABLE $table ADD COLUMN hsn_code VARCHAR(255) NULL AFTER item_code");
        echo "Added hsn_code to $table\n";
    } else {
        echo "hsn_code already exists in $table\n";
    }
}

$mysqli->close();
echo "Done.\n";
