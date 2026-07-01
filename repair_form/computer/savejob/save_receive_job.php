<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../login.php");
    exit();
}

$repair_job_id = $_POST['id'] ?? 0;

$status = $_POST['status'] ?? '';
$repair_type = $_POST['repair_type'] ?? '';

$device_name = $_POST['device_name'] ?? '';
$price = $_POST['price'] ?? 0;
$qty = $_POST['qty'] ?? 0;
$total_price = $_POST['total_price'] ?? 0;

$technician_note = $_POST['technician_note'] ?? '';

$technician_id = $_SESSION['user_id'];
$technician_name = $_SESSION['fullname'];

/*
|--------------------------------------------------------------------------
| บันทึกข้อมูล
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO repair_receive_jobs
(
    repair_job_id,
    technician_id,
    technician_name,
    status,
    repair_type,
    device_name,
    price,
    qty,
    total_price,
    technician_note
)
VALUES
(
    ?,?,?,?,?,?,?,?,?,?
)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error : " . $conn->error);
}

$stmt->bind_param(
    "iissssddds",
    $repair_job_id,
    $technician_id,
    $technician_name,
    $status,
    $repair_type,
    $device_name,
    $price,
    $qty,
    $total_price,
    $technician_note
);

$stmt->execute();

/*
|--------------------------------------------------------------------------
| อัปเดตสถานะงาน
|--------------------------------------------------------------------------
*/

$update = $conn->prepare("
    UPDATE repair_jobs
    SET status = ?
    WHERE id = ?
");

if ($update) {

    $update->bind_param(
        "si",
        $status,
        $repair_job_id
    );

    $update->execute();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>บันทึกรับงานสำเร็จ</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            บันทึกรับงานเรียบร้อย
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">เลขที่งาน</th>
                    <td><?= $repair_job_id ?></td>
                </tr>

                <tr>
                    <th>ช่างผู้รับงาน</th>
                    <td><?= htmlspecialchars($technician_name) ?></td>
                </tr>

                <tr>
                    <th>สถานะงาน</th>
                    <td><?= htmlspecialchars($status) ?></td>
                </tr>

                <tr>
                    <th>ประเภทการซ่อม</th>
                    <td><?= htmlspecialchars($repair_type) ?></td>
                </tr>

                <tr>
                    <th>อุปกรณ์ที่เปลี่ยน</th>
                    <td><?= htmlspecialchars($device_name) ?></td>
                </tr>

                <tr>
                    <th>ราคา/หน่วย</th>
                    <td><?= number_format($price, 2) ?></td>
                </tr>

                <tr>
                    <th>จำนวน</th>
                    <td><?= $qty ?></td>
                </tr>

                <tr>
                    <th>รวมเงิน</th>
                    <td><?= number_format($total_price, 2) ?></td>
                </tr>

                <tr>
                    <th>หมายเหตุช่าง</th>
                    <td><?= nl2br(htmlspecialchars($technician_note)) ?></td>
                </tr>

            </table>

            <a href="../indexrepairlist.php" class="btn btn-primary">
                กลับหน้ารายการแจ้งซ่อม
            </a>

        </div>

    </div>

</div>

</body>
</html>