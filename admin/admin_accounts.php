<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM admin WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admins accounts</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

   <!-- ฟอนต์ NotoSansThaiLooped -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap">

   <style>
      body {
         font-family: 'Noto Sans Thai', sans-serif;
      }
   </style>

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- admins accounts section starts  -->
   <section class="container mt-4">

      <h1 class="text-center mb-4">รายชื่อแอดมิน</h1>

      <div class="row">

         <!-- Add new admin button -->
         <div class="col-12 mb-4">
            <div class="card p-3 text-center">
               <p>เพิ่มแอดมินคนใหม่</p>
               <a href="register_admin.php" class="btn btn-primary">เพิ่มแอดมิน</a>
            </div>
         </div>

         <?php
         $select_account = $conn->prepare("SELECT * FROM admin");
         $select_account->execute();
         ?>

         <div class="container mt-4">
            <div class="card shadow-sm border">
               <div class="card-header bg-primary text-white">
                  <h5 class="mb-0">จัดการแอดมิน</h5>
               </div>
               <div class="card-body">
                  <?php if ($select_account->rowCount() > 0): ?>
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                           <thead class="table-light">
                              <tr>
                                 <th>แอดมิน ID</th>
                                 <th>ชื่อแอดมิน</th>
                                 <th width="150">จัดการ</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)): ?>
                                 <tr>
                                    <td><?= $fetch_accounts['id']; ?></td>
                                    <td><?= $fetch_accounts['name']; ?></td>
                                    <td>
                                       <?php if ($fetch_accounts['id'] == $admin_id): ?>
                                          <a href="update_profile.php" class="btn btn-warning btn-sm">
                                             <i class="fas fa-edit"></i> แก้ไข
                                          </a>
                                       <?php endif; ?>
                                       <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>"
                                          class="btn btn-danger btn-sm"
                                          onclick="return confirm('คุณต้องการลบบัญชีนี้ใช่หรือไม่?');">
                                          <i class="fas fa-trash"></i> ลบ
                                       </a>
                                    </td>
                                 </tr>
                              <?php endwhile; ?>
                           </tbody>
                        </table>
                     </div>
                  <?php else: ?>
                     <div class="alert alert-info text-center">ไม่มีบัญชีแอดมิน</div>
                  <?php endif; ?>
               </div>
            </div>
         </div>

      </div>

   </section>
   <!-- admins accounts section ends -->

   <script src="../js/admin_script.js"></script>

</body>

</html>