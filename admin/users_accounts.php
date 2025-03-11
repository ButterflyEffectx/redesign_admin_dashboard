<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <style>
        @font-face {
            font-family: "NotoSansThaiLooped";
            src: url(../../font/NotoSansThaiLooped-Bold.ttf) format("truetype");
        }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- User Accounts Section Starts -->

<section class="container mt-5">

   <h1 class="text-center mb-4">รายชื่อสมาชิก</h1>

   <div class="row">
   
   <?php
$select_account = $conn->prepare("SELECT * FROM `users`");
$select_account->execute();
?>

<div class="container mt-4">
  <div class="card shadow-sm border">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">จัดการผู้ใช้</h5>
    </div>
    <div class="card-body">
      <?php if($select_account->rowCount() > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>ชื่อผู้ใช้</th>
                <th width="150">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <?php while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                  <td><?= $fetch_accounts['id']; ?></td>
                  <td><?= $fetch_accounts['name']; ?></td>
                  <td>
                    <a href="edit_user.php?id=<?= $fetch_accounts['id']; ?>" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i> แก้ไข
                    </a>
                    <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบบัญชีนี้หรือไม่?');">
                      <i class="fas fa-trash"></i> ลบ
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info text-center">ไม่มีข้อมูลผู้ใช้</div>
      <?php endif; ?>
    </div>
  </div>
</div>

   </div>

</section>

<!-- User Accounts Section Ends -->

<!-- Custom JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
