<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);

   if ($select_admin->rowCount() > 0) {
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      $_SESSION['role'] = "admin";
      header('location:dashboard.php');
   } else {
      $message[] = 'incorrect username or password!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

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

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <span>' . $message . '</span>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
      }
   }
   ?>

   <!-- admin login form section starts  -->
   <section class="d-flex justify-content-center align-items-center vh-100">

      <form action="" method="POST" class="p-4 border rounded-3 shadow-lg" style="max-width: 400px; width: 100%; background-color: #fff;">
         <h3 class="text-center mb-4">ล็อกอินแอดมิน</h3>
         <p class="text-center mb-4">ชื่อใช้แอดมินเริ่มต้น = <span>admin</span> & รหัสผ่าน = <span>111</span></p>
         <div class="mb-3">
            <input type="text" name="name" maxlength="20" required placeholder="ชื่อแอดมิน" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <div class="mb-3">
            <input type="password" name="pass" maxlength="20" required placeholder="รหัสผ่าน" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <div class="d-grid mb-3">
            <input type="submit" value="เข้าสู่ระบบ" name="submit" class="btn btn-primary">
         </div>
         <p class="text-center">ยังไม่ได้สมัครสมาชิก? <a href="../login.php">กลับหน้าสมัคร</a></p>
      </form>

   </section>
   <!-- admin login form section ends -->

</body>

</html>