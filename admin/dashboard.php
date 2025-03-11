<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="container mt-4">

   <h1 class="text-center mb-4">ตารางแอดมิน</h1>

   <div class="row row-cols-1 row-cols-md-3 g-4">

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <h3 class="card-title">ยินดีต้อนรับ!</h3>
               <p><?= $fetch_profile['name']; ?></p>
               <a href="update_profile.php" class="btn btn-primary">อัพเดทสถานะ</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $total_pendings = 0;
                  $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                  $select_pendings->execute(['pending']);
                  while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                     $total_pendings += $fetch_pendings['total_price'];
                  }
               ?>
               <h3><?= $total_pendings; ?> บาท</h3>
               <p>คำสั่งซื้อรอดำเนินการ</p>
               <a href="placed_orders.php" class="btn btn-warning">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $total_completes = 0;
                  $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                  $select_completes->execute(['completed']);
                  while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                     $total_completes += $fetch_completes['total_price'];
                  }
               ?>
               <h3><?= $total_completes; ?> บาท</h3>
               <p>คำสั่งซื้อที่สำเร็จ</p>
               <a href="placed_orders.php" class="btn btn-success">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $select_orders = $conn->prepare("SELECT * FROM `orders`");
                  $select_orders->execute();
                  $numbers_of_orders = $select_orders->rowCount();
               ?>
               <h3><?= $numbers_of_orders; ?> ครั้ง</h3>
               <p>คำสั่งซื้อทั้งหมด</p>
               <a href="placed_orders.php" class="btn btn-info">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $select_products = $conn->prepare("SELECT * FROM `products`");
                  $select_products->execute();
                  $numbers_of_products = $select_products->rowCount();
               ?>
               <h3><?= $numbers_of_products; ?></h3>
               <p>เพิ่มสินค้า</p>
               <a href="products.php" class="btn btn-primary">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $select_users = $conn->prepare("SELECT * FROM `users`");
                  $select_users->execute();
                  $numbers_of_users = $select_users->rowCount();
               ?>
               <h3><?= $numbers_of_users; ?></h3>
               <p>รายชื่อสมาชิก</p>
               <a href="users_accounts.php" class="btn btn-secondary">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

      <div class="col">
         <div class="card shadow-sm">
            <div class="card-body text-center">
               <?php
                  $select_admins = $conn->prepare("SELECT * FROM `admin`");
                  $select_admins->execute();
                  $numbers_of_admins = $select_admins->rowCount();
               ?>
               <h3><?= $numbers_of_admins; ?></h3>
               <p>รายชื่อแอดมิน</p>
               <a href="admin_accounts.php" class="btn btn-dark">ดูเพิ่มเติม</a>
            </div>
         </div>
      </div>

   </div>

</section>

<!-- admin dashboard section ends -->

<script src="../js/admin_script.js"></script>

</body>
</html>
