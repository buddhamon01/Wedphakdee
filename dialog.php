<?php
include("config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// ตัวแปรเก็บข้อความ
$message = "";
$message_type = ""; // success, warning, error

// ตัวอย่างการแสดง message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "✅ ส่งข้อมูลสำเร็จแล้ว!";
    $message_type = "success";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เทมเพลท</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container-wrapper {
            display: flex;
            width: 100%;
            margin-top: 60px;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #003d99, #0051ff);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
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
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #48cae4;
            padding-left: 25px;
        }

        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            border-left-color: #48cae4;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 10px 0;
        }

        .sidebar-title {
            color: rgba(255, 255, 255, 0.7);
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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .msg-box {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .msg-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .msg-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .msg-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 600px) {
            .container-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        🏢 ระบบแจ้งซ่อมและบริหารงาน
    </div>

    <div class="container-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-title">📋 เมนูหลัก</li>
                <li><a href="dashboard.php">🏠 หน้าแรก</a></li>
                <li><a href="leave.php">📅 วันลา</a></li>
                <li><a href="e_document.php">📄 หนังสือราชการ</a></li>
                <li><a href="vehicle/index.php">🚗 ยานพาหนะ</a></li>
                <li><a href="repair_form.php">🔧 แจ้งซ่อม</a></li>
                <li><a href="template.php" class="active">📋 เทมเพลท</a></li>

                <li class="menu-divider"></li>
                <li class="sidebar-title">⚙️ ตั้งค่า</li>
                <li><a href="logout.php" class="logout-link">🚪 ออกจากระบบ</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="card">
                <h2>📋 หน้าเทมเพลท</h2>

                <?php if (!empty($message)): ?>
                    <div class="msg-box msg-<?php echo $message_type; ?>">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <p>ยินดีต้อนรับสู่หน้าเทมเพลท สามารถใช้เป็นแบบอย่างสำหรับสร้างหน้าใหม่ๆได้</p>

                <form action="template.php" method="POST">
                    <button type="submit" class="btn btn-primary">ทดสอบ Message</button>
                </form>
            </div>
        </main>
    </div>

    <!-- Modal Dialog Example -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ข้อมูลจากระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ยินดีต้อนรับเข้าสู่ระบบแจ้งซ่อมและบริหารงาน
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // เปิด Dialog เมื่อเปิดหน้า
        window.addEventListener('load', function() {
            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            messageModal.show();
        });
    </script>

    <style>
        #logoutModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #logoutModal.active {
            display: flex;
        }

        #logoutModal .logout-modal-dialog {
            width: min(480px, calc(100% - 40px));
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
        }

        #logoutModal .logout-modal-header {
            padding: 18px 20px;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #logoutModal .logout-modal-body {
            padding: 20px;
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }

        #logoutModal .logout-modal-footer {
            padding: 16px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        #logoutModal .logout-modal-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        #logoutModal .logout-modal-btn.cancel {
            background: #6c757d;
            color: #fff;
        }

        #logoutModal .logout-modal-btn.confirm {
            background: #dc3545;
            color: #fff;
        }

        #logoutModal .logout-modal-close {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
        }
    </style>

    <div id="logoutModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">ยืนยันออกจากระบบ</div>
                <div><button class="modal-close" type="button" id="logoutModalClose">&times;</button></div>
            </div>
            <div class="modal-body">คุณต้องการออกจากระบบหรือไม่?</div>
            <div class="modal-footer">
                <button class="btn-cancel" type="button" id="logoutModalCancel">ยกเลิก</button>
                <button class="btn-confirm" type="button" id="logoutModalConfirm">ออกจากระบบ</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var logoutModal = document.getElementById("logoutModal");
            var logoutHref = "";

            document.querySelectorAll(".logout-link").forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    logoutHref = this.href;
                    logoutModal.classList.add("active");
                });
            });

            var hideModal = function() {
                logoutModal.classList.remove("active");
            };

            document.getElementById("logoutCancel").addEventListener("click", hideModal);
            document.getElementById("logoutModalClose").addEventListener("click", hideModal);
            document.getElementById("logoutConfirm").addEventListener("click", function() {
                window.location.href = logoutHref;
            });
            logoutModal.addEventListener("click", function(event) {
                if (event.target === logoutModal) {
                    hideModal();
                }
            });
        });
    </script>
</body>

</html>