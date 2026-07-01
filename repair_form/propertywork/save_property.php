<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

/* ============================
   รับค่าจากฟอร์ม
============================ */

$asset_no           = trim($_POST['asset_no']);
$property_name      = trim($_POST['property_name']);
$property_type      = trim($_POST['property_type']);
$category           = trim($_POST['category']);
$brand              = trim($_POST['brand']);
$model              = trim($_POST['model']);
$serial_no          = trim($_POST['serial_no']);
$department         = trim($_POST['department']);
$location           = trim($_POST['location']);
$responsible_person = trim($_POST['responsible_person']);
$purchase_date      = $_POST['purchase_date'];
$warranty_date      = $_POST['warranty_date'];
$vendor             = trim($_POST['vendor']);
$price              = $_POST['price'];
$status             = $_POST['status'];
$note               = trim($_POST['note']);

$image = "";

/* ============================
   Upload รูปภาพ
============================ */

$uploadDir = "../../uploads/property/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES["image"]["name"])) {

    $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    $allow = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($ext, $allow)) {

        $image = time() . "_" . rand(1000, 9999) . "." . $ext;

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $uploadDir . $image
        );
    }
}

/* ============================
   บันทึกข้อมูล
============================ */

$sql = "
INSERT INTO properties
(
asset_no,
property_name,
property_type,
category,
brand,
model,
serial_no,
department,
location,
responsible_person,
purchase_date,
warranty_date,
vendor,
price,
status,
image,
note
)

VALUES
(
?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssssssssdsss",

    $asset_no,
    $property_name,
    $property_type,
    $category,
    $brand,
    $model,
    $serial_no,
    $department,
    $location,
    $responsible_person,
    $purchase_date,
    $warranty_date,
    $vendor,
    $price,
    $status,
    $image,
    $note

);

if ($stmt->execute()) {

    echo "

<script>

alert('บันทึกข้อมูลสำเร็จ');

window.location='index.php';

</script>

";
} else {

    echo "

<script>

alert('เกิดข้อผิดพลาด : " . $conn->error . "');

history.back();

</script>

";
}

$stmt->close();

$conn->close();
