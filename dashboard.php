
<?php
include("config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if just logged in
$show_login_success = isset($_SESSION["login_success"]) ? $_SESSION["login_success"] : false;
if ($show_login_success) {
    unset($_SESSION["login_success"]);
}

// ฟั่งชี่นแจ้งเตือนไลน์ 


// บันทึกข้อมูลแจ้งซ่อม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_repair"])) {
    $topic = trim($_POST["topic"]);
    $reporter_name = trim($_POST["reporter_name"]);
    $detail = trim($_POST["detail"]);

    if (empty($topic) || empty($reporter_name) || empty($detail)) {
        $message = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } else {
        $technician_id = $_POST["technician_id"];

$stmt = $conn->prepare("
    INSERT INTO repairs 
    (user_id, topic, reporter_name, detail, technician_id) 
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("isssi", $user_id, $topic, $reporter_name, $detail, $technician_id);
        $stmt->bind_param("isss", $user_id, $topic, $reporter_name, $detail);

        if ($stmt->execute()) {
            $message = "บันทึกการแจ้งซ่อมเรียบร้อยแล้ว";
        } else {
            $message = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }

        $stmt->close();
    }
}
// ดึงประเภทงานซ่อมจากฐานข้อมูล
$categories = [];

$sql = "SELECT id, category_name FROM repair_categories WHERE status='ใช้งาน' ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
// ดึงรายชื่อช่าง
$technicians = [];

$sqlTech = "SELECT id, name FROM technicians WHERE status='ใช้งาน'";
$resultTech = $conn->query($sqlTech);

if ($resultTech->num_rows > 0) {
    while ($row = $resultTech->fetch_assoc()) {
        $technicians[] = $row;
    }
}
// อัปเดตสถานะการแจ้งซ่อม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $repair_id = intval($_POST["repair_id"]);
    $status = trim($_POST["status"]);

    $allowed_status = ["รอดำเนินการ", "ซ่อมเสร็จแล้ว", "ยกเลิก"];

    if (in_array($status, $allowed_status)) {
        $stmt = $conn->prepare("UPDATE repairs SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $status, $repair_id, $user_id);

        if ($stmt->execute()) {
            $message = "อัปเดตสถานะเรียบร้อยแล้ว";
        } else {
            $message = "ไม่สามารถอัปเดตสถานะได้";
        }

        $stmt->close();
    } else {
        $message = "สถานะไม่ถูกต้อง";
    }
}

// ดึงรายการแจ้งซ่อมของผู้ใช้
$stmt = $conn->prepare("
    SELECT r.*, t.name AS technician_name
    FROM repairs r
    LEFT JOIN technicians t ON r.technician_id = t.id
    WHERE r.user_id = ?
    ORDER BY r.id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$repairs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เมนูหลัก - ระบบแจ้งซ่อม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Tahoma, sans-serif;
            background: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }

        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #0051ffff;
            padding: 15px 20px;
            color: white;
            font-size: 22px;
            font-weight: bold;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            display: flex;
            width: 100%;
            margin-top: 60px;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #003d99, #0051ff);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #48cae4;
            padding-left: 25px;
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            border-left-color: #48cae4;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 10px 0;
        }

        .sidebar-title {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 15px 20px 8px;
            letter-spacing: 1px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .card h2 {
            margin-bottom: 15px;
            color: #333;
        }

        textarea {
            width: 100%;
            min-height: 120px;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            resize: vertical;
            font-family: Tahoma, sans-serif;
        }

        input, button, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            background: #4a67ff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #3349c9;
        }

        .msg-box {
            background: #e8fff0;
            color: #0f7a36;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .table-box {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background: #4a67ff;
            color: white;
        }

        .status-form {
            display: flex;
            gap: 8px;
            align-items: center;
            min-width: 220px;
        }

        .status-form select {
            margin: 0;
            min-width: 150px;
        }

        .status-form button {
            margin: 0;
            width: auto;
            white-space: nowrap;
            padding: 10px 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            color: white;
        }

        .pending {
            background: linear-gradient(135deg, #d8002bff, #48cae4);
        }

        .done {
           background: linear-gradient(135deg, #00b4d8, #48cae4);
        }

        .cancel {
            background: #dc3545;
        }

        .welcome-text {
            margin-bottom: 10px;
            color: #666;
        }

        .user-info {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }

        .user-info strong {
            color: #0051ff;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .top-bar {
                font-size: 18px;
            }
        }

        @media (max-width: 600px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                margin-top: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .top-bar {
                font-size: 16px;
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        🏢 ระบบแจ้งซ่อมและบริหารงาน
    </div>

    <div class="container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-title">📋 เมนูหลัก</li>
                <li><a href="dashboard.php">🏠 หน้าแรก</a></li>
                <li><a href="leave.php">📅 วันลา</a></li>
                <li><a href="e_document.php">📄 หนังสือราชการ</a></li>
                <li><a href="vehicle/index.php">🚗 ยานพาหนะ</a></li>
                <li><a href="repair_form.php">🔧 แจ้งซ่อม</a></li>

                <li class="menu-divider"></li>
                <li class="sidebar-title">⚙️ ตั้งค่า</li>
                <li><a href="logout.php">🚪 ออกจากระบบ</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="card">
                <h2>👋 ยินดีต้อนรับ</h2>
                <div class="user-info">
                    <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($_SESSION["fullname"]); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Login Success -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✅ ยินดีต้อนรับ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>สวัสดี <strong><?php echo htmlspecialchars($_SESSION["fullname"]); ?></strong></p>
                    <p>ยินดีต้อนรับเข้าสู่ระบบแจ้งซ่อมและบริหารงาน</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">เข้าใช้งาน</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($show_login_success) { ?>
            var loginModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
            loginModal.show();
        <?php } ?>
    </script>
</body>
</html>