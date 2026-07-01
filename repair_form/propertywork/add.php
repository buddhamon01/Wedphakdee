<?php
include("../../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มข้อมูลทรัพย์สิน</title>

    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            font-size: 22px;
            font-weight: bold;
        }

        label {
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="container mt-4">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                🏢 เพิ่มข้อมูลทรัพย์สิน

            </div>

            <div class="card-body">

                <form action="save_property.php" method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>เลขครุภัณฑ์</label>

                            <input
                                type="text"
                                name="asset_no"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ชื่อทรัพย์สิน</label>

                            <input
                                type="text"
                                name="property_name"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ประเภททรัพย์สิน</label>

                            <select
                                name="property_type"
                                class="form-select"
                                required>

                                <option value="">-- เลือกประเภท --</option>

                                <option>คอมพิวเตอร์</option>

                                <option>เครื่องพิมพ์</option>

                                <option>Notebook</option>

                                <option>เครื่องสแกน</option>

                                <option>UPS</option>

                                <option>Server</option>

                                <option>Switch</option>

                                <option>Router</option>

                                <option>เครื่องมือแพทย์</option>

                                <option>เฟอร์นิเจอร์</option>

                                <option>อื่นๆ</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>หมวดหมู่</label>

                            <input
                                type="text"
                                name="category"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ยี่ห้อ</label>

                            <input
                                type="text"
                                name="brand"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>รุ่น</label>

                            <input
                                type="text"
                                name="model"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Serial Number</label>

                            <input
                                type="text"
                                name="serial_no"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>หน่วยงาน</label>

                            <input
                                type="text"
                                name="department"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>สถานที่ใช้งาน</label>

                            <input
                                type="text"
                                name="location"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ผู้รับผิดชอบ</label>

                            <input
                                type="text"
                                name="responsible_person"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>ผู้จำหน่าย</label>

                            <input
                                type="text"
                                name="vendor"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>วันที่จัดซื้อ</label>

                            <input
                                type="date"
                                name="purchase_date"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>วันหมดประกัน</label>

                            <input
                                type="date"
                                name="warranty_date"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>ราคาซื้อ</label>

                            <input
                                type="number"
                                step="0.01"
                                name="price"
                                class="form-control"
                                value="0">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>สถานะ</label>

                            <select
                                name="status"
                                class="form-select">

                                <option value="ใช้งาน">ใช้งาน</option>

                                <option value="ชำรุด">ชำรุด</option>

                                <option value="ส่งซ่อม">ส่งซ่อม</option>

                                <option value="จำหน่าย">จำหน่าย</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>รูปภาพ</label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept="image/*">

                        </div>

                        <div class="col-md-12 mb-3">

                            <label>หมายเหตุ</label>

                            <textarea
                                name="note"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                    </div>

                    <hr>

                    <button
                        type="submit"
                        class="btn btn-success">

                        💾 บันทึกข้อมูล

                    </button>

                    <a
                        href="index.php"
                        class="btn btn-secondary">

                        ย้อนกลับ

                    </a>

                </form>

            </div>

        </div>

    </div>

</body>

</html>