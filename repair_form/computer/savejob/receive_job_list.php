<?php
include("../../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../login.php");
    exit();
}

$keyword = $_GET['keyword'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT
rr.*,
r.sender_name,
r.department,
r.repair_system,
r.location,
r.priority
FROM repair_receive_jobs rr
LEFT JOIN repair_jobs r
ON rr.repair_job_id=r.id
WHERE 1 ";

if ($keyword != "") {
    $sql .= " AND (
        r.sender_name LIKE '%$keyword%'
        OR rr.technician_name LIKE '%$keyword%'
        OR rr.device_name LIKE '%$keyword%'
    )";
}

if ($status != "") {
    $sql .= " AND rr.status='$status'";
}

$sql .= " ORDER BY rr.id DESC";

$result = $conn->query($sql);
?>

<!doctype html>

<html lang="th">

<head>

    <meta charset="UTF-8">

    <title>รายละเอียดงานรับซ่อม</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container-fluid mt-4">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h4>📋 รายละเอียดงานรับซ่อม</h4>

            </div>

            <div class="card-body">

                <form method="get">

                    <div class="row mb-3">

                        <div class="col-md-4">

                            <input type="text" name="keyword" class="form-control"
                                placeholder="ค้นหาผู้แจ้ง / ช่าง / อุปกรณ์" value="<?= htmlspecialchars($keyword) ?>">

                        </div>

                        <div class="col-md-3">

                            <select name="status" class="form-select">

                                <option value="">ทุกสถานะ</option>

                                <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>

                                <option value="เสร็จสิ้น">เสร็จสิ้น</option>

                            </select>

                        </div>

                        <div class="col-md-2">

                            <button class="btn btn-primary">

                                ค้นหา

                            </button>

                        </div>

                        <div class="col-md-3 text-end">

                            <a href="../indexrepairlist.php" class="btn btn-secondary">

                                กลับ

                            </a>

                        </div>

                    </div>

                </form>

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>ผู้แจ้ง</th>

                            <th>ช่าง</th>

                            <th>สถานะ</th>

                            <th>ความเร่งด่วน</th>

                            <th>อุปกรณ์</th>

                            <th>รวมเงิน</th>

                            <th>วันที่</th>

                            <th>จัดการ</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>

                                <td><?= $row['repair_job_id'] ?></td>

                                <td><?= $row['sender_name'] ?></td>

                                <td><?= $row['technician_name'] ?></td>

                                <td>

                                    <?php

                                    if ($row['status'] == "เสร็จสิ้น")

                                        echo '<span class="badge bg-success">เสร็จสิ้น</span>';
                                    else

                                        echo '<span class="badge bg-warning text-dark">กำลังดำเนินการ</span>';

                                    ?>

                                </td>

                                <td>

                                    <?php

                                    switch ($row['priority']) {

                                        case 'normal':

                                            echo '<span class="badge bg-success">ปกติ</span>';

                                            break;

                                        case 'urgent':

                                            echo '<span class="badge bg-warning text-dark">ด่วน</span>';

                                            break;

                                        case 'emergency':

                                            echo '<span class="badge bg-danger">ด่วนมาก</span>';

                                            break;

                                    }

                                    ?>

                                </td>

                                <td><?= $row['device_name'] ?></td>

                                <td><?= number_format($row['total_price'], 2) ?></td>

                                <td><?= $row['created_at'] ?></td>

                                <td>

                                    <a href="receive_job_detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">

                                        รายละเอียด

                                    </a>

                                    <a href="print_receive_job.php?id=<?= $row['id'] ?>" target="_blank"
                                        class="btn btn-success btn-sm">

                                        พิมพ์

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html> 