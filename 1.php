<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['admin_id']) && $_SESSION['role'] != "admin") {
    echo "<script>alert('คุณไม่มีสิทธิ์'); window.location='login.php';</script>";
    exit();
}

// ตรวจสอบว่าได้รับ order_id จาก URL หรือไม่
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // ดึงข้อมูลคำสั่งซื้อตาม order_id
    $select_order = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $select_order->execute([$order_id]);

    // ตรวจสอบว่ามีคำสั่งซื้อหรือไม่
    if ($select_order->rowCount() > 0) {
        $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);

        // ดึงข้อมูลสินค้าที่เกี่ยวข้องกับคำสั่งซื้อนี้
        $select_products = $conn->prepare("SELECT op.*, p.name 
                                           FROM order_poduct op 
                                           JOIN products p ON op.op_product_id = p.id 
                                           WHERE op.op_order_id = ?");
        $select_products->execute([$order_id]);

        // แสดงข้อมูลใบเสร็จ
        ?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h2 class="text-center text-primary">ใบเสร็จรับเงิน</h2>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>ผู้ซื้อ:</strong> <?= $fetch_order['name']; ?></h5>
                                <p><strong>อีเมล:</strong> <?= $fetch_order['email']; ?></p>
                                <p><strong>เบอร์โทร:</strong> <?= $fetch_order['number']; ?></p>
                                <p><strong>ที่อยู่:</strong> <?= $fetch_order['address']; ?></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h5><strong>เลขที่คำสั่งซื้อ:</strong> #<?= $fetch_order['order_id']; ?></h5>
                                <p><strong>วันที่สั่งซื้อ:</strong> <?= date('d/m/Y', strtotime($fetch_order['placed_on'])); ?></p>
                                <p><strong>สถานะการชำระเงิน:</strong>
                                    <span class="badge text-dark ">
                                    <?= $fetch_order['payment_status']; ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <h5 class="text-center">รายละเอียดสินค้า</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>รายการ</th>
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-end">ราคาต่อหน่วย</th>
                                    <th class="text-end">รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_price = 0;
                                while ($product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                                    $subtotal = $product['op_product_qty'] * $product['op_product_price'];
                                    $total_price += $subtotal;
                                ?>
                                    <tr>
                                        <td><?= $product['name']; ?></td>
                                        <td class="text-center"><?= $product['op_product_qty']; ?></td>
                                        <td class="text-end"><?= number_format($product['op_product_price'], 2); ?> บาท</td>
                                        <td class="text-end"><?= number_format($subtotal, 2); ?> บาท</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">ราคารวม:</th>
                                    <th class="text-end"><?= number_format($total_price, 2); ?> บาท</th>
                                </tr>
                            </tfoot>
                        </table>

                        <hr>

                        <p class="text-center">
                            <strong>ชำระเงินผ่าน:</strong> <?= $fetch_order['method']; ?>
                        </p>

                        <div class="text-center no-print">
                            <button class="btn btn-primary" onclick="window.print();">
                                <i class="bi bi-printer"></i> พิมพ์ใบเสร็จ
                            </button>
                            <a href="./admin/dashboard.php" class="btn btn-secondary">กลับ</a>
                        </div>
                    </div>
                </div>

        

</body>

</html>

<?php
    } else {
        echo '<p class="text-center text-muted">ไม่พบคำสั่งซื้อ</p>';
    }
} else {
    echo '<p class="text-center text-muted">ไม่พบคำสั่งซื้อที่เลือก</p>';
}
?>