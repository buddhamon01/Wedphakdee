<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

/*=========================
Pagination
=========================*/

$limit = 20;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

/*=========================
จำนวนข้อมูลทั้งหมด
=========================*/

$totalQuery = $conn->query("SELECT COUNT(*) total FROM properties");
$totalData = $totalQuery->fetch_assoc();

// ===========================
// รวมมูลค่าครุภัณฑ์
// ===========================

$sqlTotal = $conn->query("
SELECT
    SUM(price) AS total_price
FROM properties
");

$rowTotal = $sqlTotal->fetch_assoc();

$total_price = $rowTotal['total_price'] ?? 0;


// ===========================
// มูลค่าสิ่งก่อสร้าง
// ===========================

$sqlBuilding = $conn->query("
SELECT
    SUM(price) AS total_building
FROM properties
WHERE property_name='สิ่งก่อสร้าง'
");

$rowBuilding = $sqlBuilding->fetch_assoc();

$total_building = $rowBuilding['total_building'] ?? 0;

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM properties");
$totalData = $totalQuery->fetch_assoc();

$total_records = $totalData['total'];
$total_pages = ceil($total_records / $limit);
// ===========================
// รวมทั้งหมด
// ===========================

$total_all = $total_price + $total_building;

$total_pages = ceil($total_records / $limit);

/*=========================
ดึงข้อมูล
=========================*/

$sql = "
SELECT *
FROM properties
ORDER BY id DESC
LIMIT $start,$limit
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <title>งานทรัพย์สิน</title>

    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet"
        href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/property.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .table th {
            background: #0d6efd;
            color: #fff;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
        }

        .btn {
            border-radius: 8px;
        }

        .pagination {
            margin-top: 20px;
        }

        .container-fluid {
            max-width: 100%;
        }

        .table {

            white-space: nowrap;

            font-size: 14px;

        }

        .table thead th {

            position: sticky;

            top: 0;

            background: #1565C0;

            color: #fff;

            z-index: 99;

            text-align: center;

            vertical-align: middle;

            padding: 14px;

        }

        .table td {

            white-space: nowrap;

            vertical-align: middle;

            padding: 10px;

        }

        .table tbody tr:hover {

            background: #edf6ff;

        }

        .table-responsive {

            max-height: 700px;

            overflow: auto;

            border-radius: 10px;

        }

        .table td:nth-child(4) {

            min-width: 220px;

        }

        .table td:nth-child(5) {

            min-width: 260px;

        }

        .table td:nth-child(3) {

            min-width: 170px;

        }

        .table td:nth-child(11) {

            text-align: right;

        }

        .table td:last-child {

            min-width: 220px;

        }

        .table-responsive {

            overflow: auto;

        }

        .table {

            white-space: nowrap;

        }

        .table td {

            white-space: nowrap;

        }

        .card {

            border: none;

        }

        .summary-card {

            border-radius: 15px;

        }

        .input-group-text {

            border-right: none;

        }

        .form-control {

            border-left: none;

        }

        .btn {

            border-radius: 10px;

        }
    </style>

</head>

<body>

    <div class="container-fluid px-3 py-3">

        <div class="card shadow">

            <div class="card-header border-0 text-white"
                style="background:linear-gradient(90deg,#1565C0,#1976D2);">

                <div class="row align-items-center">

                    <div class="col-md-7">

                        <h2 class="fw-bold mb-1">

                            <i class="fas fa-building"></i>

                            ทะเบียนครุภัณฑ์

                        </h2>

                        <div style="opacity:.9">

                            ระบบบริหารครุภัณฑ์ โรงพยาบาลภักดีชุมพล

                        </div>

                    </div>

                    <div class="col-md-5 text-end">

                        <h4 class="mb-0">

                            <?= number_format($total_records) ?>

                        </h4>

                        <small>

                            รายการทั้งหมด

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">
                <?php

                $total = $total_records;

                $normal = $conn->query("SELECT COUNT(*) c FROM properties WHERE location='ปกติ'")->fetch_assoc()['c'];

                $repair = $conn->query("SELECT COUNT(*) c FROM properties WHERE location='ชำรุด'")->fetch_assoc()['c'];

                $borrow = $conn->query("SELECT COUNT(*) c FROM properties WHERE borrow_department<>''")->fetch_assoc()['c'];

                ?>

                <div class="row mb-4">

                    <div class="col-lg-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="text-muted">

                                            ข้อมูลทั้งหมด

                                        </div>

                                        <h2 class="fw-bold">

                                            <?= number_format($total) ?>

                                        </h2>

                                    </div>

                                    <div>

                                        <i class="fas fa-layer-group text-primary fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="text-muted">

                                            ปกติ

                                        </div>

                                        <h2 class="fw-bold text-success">

                                            <?= $normal ?>

                                        </h2>

                                    </div>

                                    <div>

                                        <i class="fas fa-check-circle text-success fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="text-muted">

                                            ชำรุด

                                        </div>

                                        <h2 class="fw-bold text-warning">

                                            <?= $repair ?>

                                        </h2>

                                    </div>

                                    <div>

                                        <i class="fas fa-tools text-warning fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="text-muted">

                                            ยืมใช้งาน

                                        </div>

                                        <h2 class="fw-bold text-danger">

                                            <?= $borrow ?>

                                        </h2>

                                    </div>

                                    <div>

                                        <i class="fas fa-users text-danger fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="row mb-4">

                    <!-- Card 1 -->

                    <div class="col-lg-4">

                        <div class="card shadow border-0">

                            <div class="card-body text-white"

                                style="background:#d97b43;border-radius:8px;">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <h5>

                                            มูลค่าครุภัณฑ์

                                            <?= number_format($total_records) ?>

                                            รายการ

                                        </h5>

                                        <h1 class="fw-bold">

                                            <?= number_format($total_price, 2) ?>

                                            <small>บาท</small>

                                        </h1>

                                    </div>

                                    <div>

                                        <i class="fas fa-laptop-medical fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Card 2 -->

                    <div class="col-lg-4">

                        <div class="card shadow border-0">

                            <div class="card-body text-white"

                                style="background:#88b04b;border-radius:8px;">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <h5>

                                            มูลค่าสิ่งก่อสร้าง

                                        </h5>

                                        <h1 class="fw-bold">

                                            <?= number_format($total_building, 2) ?>

                                            <small>บาท</small>

                                        </h1>

                                    </div>

                                    <div>

                                        <i class="fas fa-building fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Card 3 -->

                    <div class="col-lg-4">

                        <div class="card shadow border-0">

                            <div class="card-body text-white"

                                style="background:#4e7f99;border-radius:8px;">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <h5>

                                            มูลค่าครุภัณฑ์และสิ่งก่อสร้างทั้งหมด

                                            <?= number_format($total_records) ?>

                                            รายการ

                                        </h5>

                                        <h1 class="fw-bold">

                                            <?= number_format($total_all, 2) ?>

                                            <small>บาท</small>

                                        </h1>

                                    </div>

                                    <div>

                                        <i class="fas fa-money-check-dollar fa-3x"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Toolbar -->

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="row g-3 align-items-center">

                            <div class="col-lg-5">

                                <div class="input-group">

                                    <span class="input-group-text bg-white">

                                        <i class="fas fa-search"></i>

                                    </span>

                                    <input

                                        type="text"

                                        id="searchInput"

                                        class="form-control"

                                        placeholder="ค้นหาเลขครุภัณฑ์ / ชื่อทรัพย์สิน">

                                </div>

                            </div>

                            <div class="col-lg-2">

                                <select class="form-select">

                                    <option>ทุกประเภท</option>

                                    <option>คอมพิวเตอร์</option>

                                    <option>การแพทย์</option>

                                    <option>ยานพาหนะ</option>

                                    <option>สำนักงาน</option>

                                </select>

                            </div>

                            <div class="col-lg-2">

                                <select class="form-select">

                                    <option>ทุกหน่วยงาน</option>

                                    <?php

                                    $dept = $conn->query("SELECT DISTINCT department FROM properties ORDER BY department");

                                    while ($d = $dept->fetch_assoc()) {

                                        echo "<option>" . $d['department'] . "</option>";
                                    }

                                    ?>

                                </select>

                            </div>

                            <div class="col-lg-3 text-end">

                                <button class="btn btn-primary">

                                    <i class="fas fa-search"></i>

                                    ค้นหา

                                </button>

                                <button

                                    onclick="location.reload()"

                                    class="btn btn-secondary">

                                    <i class="fas fa-rotate-right"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="mb-4">

                    <a href="add.php"

                        class="btn btn-success">

                        <i class="fas fa-plus"></i>

                        เพิ่มครุภัณฑ์

                    </a>

                    <a href="import_excel.php"

                        class="btn btn-primary">

                        <i class="fas fa-file-import"></i>

                        Import Excel

                    </a>

                    <a href="export_excel.php"

                        class="btn btn-success">

                        <i class="fas fa-file-excel"></i>

                        Export Excel

                    </a>

                    <button

                        onclick="window.print()"

                        class="btn btn-dark">

                        <i class="fas fa-print"></i>

                        พิมพ์

                    </button>

                    <button

                        onclick="location.reload()"

                        class="btn btn-warning">

                        <i class="fas fa-arrows-rotate"></i>

                        รีเฟรช

                    </button>

                    <a href="../../repair_form/home_repair.php"

                        class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>

                        กลับ

                    </a>

                </div>

                <div class="alert alert-info">

                    <b>

                        ข้อมูลทั้งหมด

                        <?= number_format($total_records) ?>

                        รายการ

                    </b>
                    ๆ
                </div>

                <div class="table-responsive shadow rounded">

                    <table
                        class="table
table-bordered
table-striped
table-hover
align-middle
mb-0"
                        id="propertyTable">

                        <thead>

                            <tr>

                                <th width="60">ลำดับ</th>
                                <th>ปีงบ</th>
                                <th>เลขครุภัณฑ์</th>

                                <th>ประเภทครุภัณฑ์</th>

                                <th>ชื่อทรัพย์สิน</th>
                                <th>ประจำอยู่หน่วยงาน</th>
                                <th>ความเสี่ยง</th>
                                <th>สถานที่</th>
                                <th>การเบิกใช้</th>
                                <th>วันที่รับเข้า</th>

                                <th>ราคา</th>
                                <th>หน่วยงานขอยืม</th>
                                <th>สถานะ</th>

                                <th width="260">จัดการ</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if ($result->num_rows > 0) {

                                $no = $start + 1;

                                while ($row = $result->fetch_assoc()) {

                            ?>

                                    <tr>

                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['budget_year']) ?></td>
                                        <td><?= htmlspecialchars($row['asset_no']) ?></td>

                                        <td><?= htmlspecialchars($row['property_name']) ?></td>

                                        <td><?= htmlspecialchars($row['property_type']) ?></td>



                                        <td><?= htmlspecialchars($row['department']) ?></td>
                                        <td><?= htmlspecialchars($row['department_unit']) ?></td>
                                        <td><?= htmlspecialchars($row['risk_level']) ?></td>
                                        <td><?= htmlspecialchars($row['withdraw_status']) ?></td>

                                        <td><?= htmlspecialchars($row['purchase_date']) ?></td>

                                        <td><span class="fw-bold text-success">

                                                <?= number_format($row['price'], 2) ?>

                                            </span></td>
                                        <td><?= htmlspecialchars($row['borrow_department']) ?></td>
                                        <td>

                                            <?php

                                            if ($row['location'] == "ปกติ") {

                                                echo '<span class="badge bg-success">ปกติ</span>';
                                            } elseif ($row['location'] == "ชำรุด") {

                                                echo '<span class="badge bg-danger">ชำรุด</span>';
                                            } else {

                                                echo '<span class="badge bg-warning text-dark">' . $row['location'] . '</span>';
                                            }

                                            ?>

                                        </td>
                                        <td class="text-center">

                                            <a
                                                href="detail.php?id=<?= $row['id'] ?>"
                                                class="btn btn-info btn-sm"
                                                title="รายละเอียด">

                                                <i class="fas fa-eye"></i>

                                            </a>

                                            <a
                                                href="edit.php?id=<?= $row['id'] ?>"
                                                class="btn btn-warning btn-sm"
                                                title="แก้ไข">

                                                <i class="fas fa-pen"></i>

                                            </a>

                                            <a
                                                href="delete.php?id=<?= $row['id'] ?>"
                                                onclick="return confirm('ยืนยันการลบ ?')"
                                                class="btn btn-danger btn-sm">

                                                <i class="fas fa-trash"></i>

                                            </a>

                                            <a
                                                href="print_property.php?id=<?= $row['id'] ?>"
                                                target="_blank"
                                                class="btn btn-primary btn-sm">

                                                <i class="fas fa-print"></i>

                                            </a>

                                        </td>
                                    </tr>

                                <?php

                                }
                            } else {

                                ?>

                                <tr>

                                    <td colspan="9" class="text-center">

                                        ไม่มีข้อมูล

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>
                </div>
                <!-- Pagination -->

                <nav>

                    <ul class="pagination justify-content-center">

                        <?php if ($page > 1) { ?>

                            <li class="page-item">

                                <a class="page-link"

                                    href="?page=<?= $page - 1 ?>">

                                    « ก่อนหน้า

                                </a>

                            </li>

                        <?php } ?>

                        <?php

                        for ($i = 1; $i <= $total_pages; $i++) {

                        ?>

                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">

                                <a class="page-link"

                                    href="?page=<?= $i ?>">

                                    <?= $i ?>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if ($page < $total_pages) { ?>

                            <li class="page-item">

                                <a class="page-link"

                                    href="?page=<?= $page + 1 ?>">

                                    ถัดไป »

                                </a>

                            </li>

                        <?php } ?>

                    </ul>

                </nav>

            </div>

        </div>

    </div>

    <script src="../../assets/js/property.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

</body>

</html>