<?php
$conn = new mysqli('localhost', 'root', '', 'pradan');
if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }

// Check tblitemable columns
$result = $conn->query('SHOW COLUMNS FROM tblitemable');
$cols = [];
while ($row = $result->fetch_assoc()) { $cols[] = $row['Field']; }
echo 'tblitemable columns: ' . implode(', ', $cols) . PHP_EOL;

$added = [];

if (!in_array('hsn_code', $cols)) {
    $conn->query("ALTER TABLE tblitemable ADD COLUMN hsn_code VARCHAR(50) NOT NULL DEFAULT '' AFTER unit");
    $added[] = 'hsn_code to tblitemable';
}

if (!in_array('mrp', $cols)) {
    $conn->query("ALTER TABLE tblitemable ADD COLUMN mrp DECIMAL(15,6) NOT NULL DEFAULT '0.000000' AFTER hsn_code");
    $added[] = 'mrp to tblitemable';
}

if (!in_array('discount_item', $cols)) {
    $conn->query("ALTER TABLE tblitemable ADD COLUMN discount_item DECIMAL(15,6) NOT NULL DEFAULT '0.000000' AFTER mrp");
    $added[] = 'discount_item to tblitemable';
}

// Also check tblitems for hsn_code
$result2 = $conn->query('SHOW COLUMNS FROM tblitems');
$cols2 = [];
while ($row = $result2->fetch_assoc()) { $cols2[] = $row['Field']; }
echo 'tblitems columns: ' . implode(', ', $cols2) . PHP_EOL;

if (!in_array('hsn_code', $cols2)) {
    $conn->query("ALTER TABLE tblitems ADD COLUMN hsn_code VARCHAR(50) NOT NULL DEFAULT '' AFTER unit");
    $added[] = 'hsn_code to tblitems';
}

if (empty($added)) {
    echo 'All columns already exist. Nothing to add.' . PHP_EOL;
} else {
    echo 'Added: ' . implode(', ', $added) . PHP_EOL;
}

$conn->close();
echo 'Done!' . PHP_EOL;
?>
