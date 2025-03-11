<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('location:home.php');
   exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {
      if (empty($address)) {
         $message[] = 'กรุณาเพิ่มที่อยู่ของคุณ!';
      } else {
         // เพิ่มข้อมูลคำสั่งซื้อในตาราง `orders`
         $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_price]);

         $last_order_id = $conn->lastInsertId(); // รับ order_id ล่าสุด

         // ดึงข้อมูลจากตะกร้าและเพิ่มเข้าไปใน `order_poduct`
         $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart_items->execute([$user_id]);

         while ($cart_item = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $insert_order_product = $conn->prepare("INSERT INTO `order_poduct` (op_order_id, op_product_id, op_product_qty, op_product_price) VALUES (?, ?, ?, ?)");
            $insert_order_product->execute([$last_order_id, $cart_item['pid'], $cart_item['quantity'], $cart_item['price']]);
         }

         // ลบสินค้าทั้งหมดออกจากตะกร้าหลังจากสั่งซื้อสำเร็จ
         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'สั่งซื้อสำเร็จ!';
      }
   } else {
      $message[] = 'ตะกร้าของคุณว่างเปล่า!';
   }
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ชำระเงิน</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

   <!-- ฟอนต์ NotoSansThai -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap">

   <style>
      body {
         font-family: 'Noto Sans Thai', sans-serif;
      }
   </style>
</head>

<body class="bg-light">

   <!-- Header -->
   <?php include 'components/user_header.php'; ?>

   <div class="container text-center my-5">
      <h3>ชำระเงิน</h3>
      <p><a href="home.php">หน้าหลัก</a> <span> / ชำระเงิน</span></p>
   </div>

   <section class="checkout container my-5">
      <h1 class="title text-center mb-4">สรุปคำสั่งซื้อ</h1>

      <form action="" method="post">

         <div class="cart-items bg-white p-4 rounded shadow-sm mb-4">
            <h3 class="mb-3">สินค้าในตะกร้า</h3>
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                  <p><span><?= $fetch_cart['name']; ?></span> <span class="text-success"><?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?> บาท</span></p>
            <?php
               }
            } else {
               echo '<p class="text-danger text-center">ไม่มีสินค้า!</p>';
            }
            ?>
            <p class="grand-total fw-bold"><span>รวมทั้งสิ้น :</span> <span class="text-danger"><?= $grand_total; ?> บาท</span></p>
            <a href="cart.php" class="btn btn-primary">ดูตะกร้า</a>
         </div>

         <input type="hidden" name="total_products" value="<?= implode($cart_items); ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
         <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
         <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
         <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

         <div class="user-info bg-white p-4 rounded shadow-sm">
            <h3 class="mb-3">ข้อมูลของคุณ</h3>
            <p><i class="bi bi-person"></i> <?= $fetch_profile['name'] ?></p>
            <p><i class="bi bi-phone"></i> <?= $fetch_profile['number'] ?></p>
            <p><i class="bi bi-envelope"></i> <?= $fetch_profile['email'] ?></p>
            <a href="update_profile.php" class="btn btn-success">อัพเดท</a>
            <h3 class="mt-4">สถานที่จัดส่ง</h3>
            <p><i class="bi bi-map"></i> <?= empty($fetch_profile['address']) ? 'กรุณาเพิ่มที่อยู่' : $fetch_profile['address']; ?></p>
            <a href="update_address.php" class="btn btn-success">อัพเดทที่อยู่</a>
            <select name="method" class="form-select my-3" required>
               <option value="" disabled selected>เลือกวิธีชำระเงิน --</option>
               <option value="เก็บปลายทาง">เก็บปลายทาง</option>
               <option value="โอนผ่านธนาคาร">โอนผ่านธนาคาร</option>
            </select>
            <input type="submit" value="ยืนยันคำสั่งซื้อ" class="btn btn-danger w-100 <?= empty($fetch_profile['address']) ? 'disabled' : ''; ?>" name="submit">
         </div>

      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>

</body>

</html>