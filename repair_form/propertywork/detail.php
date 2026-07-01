<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

$keyword = "";

$sql = "SELECT * FROM properties";

if (isset($_GET['keyword']) && $_GET['keyword'] != "") {

    $keyword = trim($_GET['keyword']);

    $sql .= " WHERE
        asset_no LIKE '%$keyword%'
        OR property_name LIKE '%$keyword%'
        OR department LIKE '%$keyword%'
        OR property_type LIKE '%$keyword%'
        OR serial_no LIKE '%$keyword%'
    ";
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!doctype html>

<html lang="th">

<head>

    <meta charset="utf-8">

    <title>รายการทรัพย์สิน</title>

    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {

            background: #f4f6f9;

        }

        .card {

            border: none;

            border-radius: 12px;

        }

        .table th {

            background: #0051ff;

            color: white;

            text-align: center;

        }

        .table td {

            vertical-align: middle;

        }

        img {

            border-radius: 8px;

        }
    </style>

</head>

<body>

    <div class="container mt-4">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">

                    📋 รายการทรัพย์สิน

                </h3>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-8">

                        <form method="GET">

                            <div class="input-group">

                                <input

                                    type="text"

                                    name="keyword"

                                    class="form-control"

                                    placeholder="ค้นหาเลขครุภัณฑ์ / ชื่อ / แผนก"

                                    value="<?= htmlspecialchars($keyword) ?>">

                                <button class="btn btn-primary">

                                    ค้นหา

                                </button>

                            </div>

                        </form>

                    </div>

                    <div class="col-md-4 text-end">

                        <a href="add.php" class="btn btn-success">

                            ➕ เพิ่มทรัพย์สิน

                        </a>

                        <a href="index.php" class="btn btn-secondary">

                            ย้อนกลับ

                        </a>

                    </div>

                </div>

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>รูป</th>

                            <th>เลขครุภัณฑ์</th>

                            <th>ชื่อทรัพย์สิน</th>

                            <th>ประเภท</th>

                            <th>แผนก</th>

                            <th>สถานที่</th>

                            <th>ราคา</th>

                            <th>สถานะ</th>

                            <th width="230">

                                จัดการ

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {

                        ?>

                                <tr>

                                    <td align="center">

                                        <?= $row['id']; ?>

                                    </td>

                                    <td align="center">

                                        <?php

                                        if ($row['image'] != "") {

                                        ?>

                                            <img

                                                src="../../uploads/property/<?= $row['image']; ?>"

                                                width="70">

                                        <?php

                                        } else {

                                            echo "-";
                                        }

                                        ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['asset_no']); ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['property_name']); ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['property_type']); ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['department']); ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['location']); ?>

                                    </td>

                                    <td align="right">

                                        <?= number_format($row['price'], 2); ?>

                                    </td>

                                    <td align="center">

                                        <?php

                                        switch ($row['status']) {

                                            case "ใช้งาน":

                                                echo '<span class="badge bg-success">ใช้งาน</span>';

                                                break;

                                            case "ชำรุด":

                                                echo '<span class="badge bg-danger">ชำรุด</span>';

                                                break;

                                            case "ส่งซ่อม":

                                                echo '<span class="badge bg-warning text-dark">ส่งซ่อม</span>';

                                                break;

                                            case "จำหน่าย":

                                                echo '<span class="badge bg-secondary">จำหน่าย</span>';

                                                break;
                                        }

                                        ?>

                                    </td>

                                    <td>

                                        <a

                                            href="edit.php?id=<?= $row['id']; ?>"

                                            class="btn btn-warning btn-sm">

                                            แก้ไข

                                        </a>

                                        <a

                                            href="delete.php?id=<?= $row['id']; ?>"

                                            onclick="return confirm('ยืนยันการลบ ?')"

                                            class="btn btn-danger btn-sm">

                                            ลบ

                                        </a>

                                        <a

                                            href="print_property.php?id=<?= $row['id']; ?>"

                                            target="_blank"

                                            class="btn btn-info btn-sm">

                                            พิมพ์

                                        </a>

                                    </td>

                                </tr>

                            <?php

                            }
                        } else {

                            ?>

                            <tr>

                                <td colspan="10" align="center">

                                    ยังไม่มีข้อมูล

                                </td>

                            </tr>

                        <?php

                        }

                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>