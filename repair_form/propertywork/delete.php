<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

if ($id == 0) {

    header("Location: detail.php");
    exit();
}

/* ==========================================
   ค้นหารูปภาพ
========================================== */

$stmt = $conn->prepare("
SELECT image
FROM properties
WHERE id=?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {

    echo "<script>

    alert('ไม่พบข้อมูล');

    window.location='detail.php';

    </script>";

    exit();
}

/* ==========================================
   ลบรูปภาพ
========================================== */

if (!empty($row['image'])) {

    $imageFile = "../../uploads/property/" . $row['image'];

    if (file_exists($imageFile)) {

        unlink($imageFile);
    }
}

/* ==========================================
   ลบข้อมูล
========================================== */

$delete = $conn->prepare("
DELETE FROM properties
WHERE id=?
");

$delete->bind_param("i", $id);

if ($delete->execute()) {

    echo "

    <script>

    alert('ลบข้อมูลเรียบร้อย');

    window.location='detail.php';

    </script>

    ";
} else {

    echo "

    <script>

    alert('ไม่สามารถลบข้อมูลได้');

    history.back();

    </script>

    ";
}

$delete->close();
$conn->close();
