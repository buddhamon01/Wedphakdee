<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM properties WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("ไม่พบข้อมูล");
}

/* =========================
   บันทึกข้อมูล
========================= */

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $asset_no = $_POST['asset_no'];
    $property_name = $_POST['property_name'];
    $property_type = $_POST['property_type'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $serial_no = $_POST['serial_no'];
    $department = $_POST['department'];
    $location = $_POST['location'];
    $responsible_person = $_POST['responsible_person'];
    $purchase_date = $_POST['purchase_date'];
    $warranty_date = $_POST['warranty_date'];
    $vendor = $_POST['vendor'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $note = $_POST['note'];

    $image = $row['image'];

    if (!empty($_FILES['image']['name'])) {

        $path = "../../uploads/property/";

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        $filename = time() . rand(1000, 9999) . "." . $ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);

        $image = $filename;
    }

    $sql = "UPDATE properties SET

    asset_no=?,
    property_name=?,
    property_type=?,
    category=?,
    brand=?,
    model=?,
    serial_no=?,
    department=?,
    location=?,
    responsible_person=?,
    purchase_date=?,
    warranty_date=?,
    vendor=?,
    price=?,
    status=?,
    image=?,
    note=?

    WHERE id=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(

        "sssssssssssssdsssi",

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
        $note,
        $id

    );

    if ($stmt->execute()) {

        echo "<script>

        alert('แก้ไขข้อมูลสำเร็จ');

        location='detail.php';

        </script>";

        exit();
    }
}
?>

<!doctype html>

<html lang="th">

<head>

    <meta charset="utf-8">

    <title>แก้ไขทรัพย์สิน</title>

    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-4">

        <div class="card shadow">

            <div class="card-header bg-warning">

                <h3>✏ แก้ไขทรัพย์สิน</h3>

            </div>

            <div class="card-body">

                <form method="post" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>เลขครุภัณฑ์</label>

                            <input
                                type="text"
                                name="asset_no"
                                class="form-control"
                                value="<?= htmlspecialchars($row['asset_no']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ชื่อทรัพย์สิน</label>

                            <input
                                type="text"
                                name="property_name"
                                class="form-control"
                                value="<?= htmlspecialchars($row['property_name']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ประเภท</label>

                            <input
                                type="text"
                                name="property_type"
                                class="form-control"
                                value="<?= htmlspecialchars($row['property_type']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>หมวดหมู่</label>

                            <input
                                type="text"
                                name="category"
                                class="form-control"
                                value="<?= htmlspecialchars($row['category']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ยี่ห้อ</label>

                            <input
                                type="text"
                                name="brand"
                                class="form-control"
                                value="<?= htmlspecialchars($row['brand']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>รุ่น</label>

                            <input
                                type="text"
                                name="model"
                                class="form-control"
                                value="<?= htmlspecialchars($row['model']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Serial Number</label>

                            <input
                                type="text"
                                name="serial_no"
                                class="form-control"
                                value="<?= htmlspecialchars($row['serial_no']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>แผนก</label>

                            <input
                                type="text"
                                name="department"
                                class="form-control"
                                value="<?= htmlspecialchars($row['department']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>สถานที่</label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?= htmlspecialchars($row['location']) ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ผู้รับผิดชอบ</label>

                            <input
                                type="text"
                                name="responsible_person"
                                class="form-control"
                                value="<?= htmlspecialchars($row['responsible_person']) ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>วันที่จัดซื้อ</label>

                            <input
                                type="date"
                                name="purchase_date"
                                class="form-control"
                                value="<?= $row['purchase_date'] ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>หมดประกัน</label>

                            <input
                                type="date"
                                name="warranty_date"
                                class="form-control"
                                value="<?= $row['warranty_date'] ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>ราคา</label>

                            <input
                                type="number"
                                step="0.01"
                                name="price"
                                class="form-control"
                                value="<?= $row['price'] ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>สถานะ</label>

                            <select
                                name="status"
                                class="form-select">

                                <option <?= ($row['status'] == "ใช้งาน") ? "selected" : "" ?>>ใช้งาน</option>

                                <option <?= ($row['status'] == "ชำรุด") ? "selected" : "" ?>>ชำรุด</option>

                                <option <?= ($row['status'] == "ส่งซ่อม") ? "selected" : "" ?>>ส่งซ่อม</option>

                                <option <?= ($row['status'] == "จำหน่าย") ? "selected" : "" ?>>จำหน่าย</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>รูปภาพ</label>

                            <input
                                type="file"
                                name="image"
                                class="form-control">

                            <?php if ($row['image'] != "") { ?>

                                <img src="../../uploads/property/<?= $row['image'] ?>" width="120" class="mt-2">

                            <?php } ?>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label>หมายเหตุ</label>

                            <textarea
                                name="note"
                                class="form-control"
                                rows="4"><?= htmlspecialchars($row['note']) ?></textarea>

                        </div>

                    </div>

                    <button class="btn btn-success">

                        💾 บันทึก

                    </button>

                    <a href="detail.php" class="btn btn-secondary">

                        ย้อนกลับ

                    </a>

                </form>

            </div>

        </div>

    </div>

</body>

</html>