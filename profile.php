<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

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
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="user-details container py-5">

   <div class="user bg-white p-4 rounded shadow-sm mx-auto" style="max-width: 600px;">
      <?php
         // Assuming you fetch the user profile from the database here
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <p class="h5 mb-3"><i class="bi bi-person"></i> <span><?= $fetch_profile['name']; ?></span></p>
      <p class="mb-3"><i class="bi bi-phone"></i> <span><?= $fetch_profile['number']; ?></span></p>
      <p class="mb-3"><i class="bi bi-envelope"></i> <span><?= $fetch_profile['email']; ?></span></p>
      <a href="update_profile.php" class="btn btn-success mb-3">อัพเดทข้อมูลส่วนตัว</a>
      <p class="address mb-3"><i class="bi bi-geo-alt"></i> <span><?php if($fetch_profile['address'] == ''){echo 'กรุณากรอกที่อยู่ของคุณ';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn btn-warning">อัพเดทที่อยู่</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include './components/alers.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
