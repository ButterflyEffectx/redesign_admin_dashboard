<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <span>'.$message.'</span>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
   }
}
?>

<header class="header bg-light shadow-sm">
   <section class="container d-flex justify-content-between align-items-center py-3">
      <a href="home.php" class="logo h3 text-primary text-decoration-none">M Shop</a>
      
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                     <a class="nav-link active" href="home.php">หน้าหลัก</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="product.php">สินค้า</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="orders.php">คำสั่งซื้อ</a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>

      <div class="d-flex align-items-center">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php" class="me-3 text-dark"><i class="bi bi-search"></i></a>
         <a href="cart.php" class="me-3 text-dark position-relative">
            <i class="bi bi-cart"></i>
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?= $total_cart_items; ?></span>
         </a>
         
         <!-- เปลี่ยน dropdown เป็นปุ่มปกติ -->
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
             $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- แสดงโปรไฟล์และปุ่ม logout -->
         <div class="d-flex align-items-center">
            <p class="fw-bold m-2"><?= $fetch_profile['name']; ?>/User</p>
            <a href="profile.php" class="btn btn-warning text-white me-2"><i class="bi bi-person"></i> โปรไฟล์</a>
            <a href="components/user_logout.php" class="btn btn-danger text-white" onclick="return confirm('ต้องการออกจากระบบหรือไม่?');"><i class="bi bi-box-arrow-right"></i> Logout</a>
         </div>
         <?php
         } else {
         ?>
         <a href="login.php" class="btn btn-success text-white"><i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ</a>
         <?php
         }
         ?>



</div>



   </section>
</header>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
   @font-face {
      font-family: "NotoSansThaiLooped";
      src: url(../font/NotoSansThaiLooped-Bold.ttf) format("truetype");
   }
   body {
      font-family: "NotoSansThaiLooped", sans-serif;
      background-color: #f8f9fa;
   }
</style>
