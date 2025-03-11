<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="alert alert-info alert-dismissible fade show" role="alert">
         <span>'.$message.'</span>
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="container-fluid d-flex justify-content-between align-items-center">

      <a href="dashboard.php" class="logo fs-3 fw-bold">Admin</a>

      <nav class="navbar d-flex gap-3">
         <a href="products.php" class="nav-link">สินค้า</a>
         <a href="placed_orders.php" class="nav-link">คำสั่งซื้อ</a>
         <a href="admin_accounts.php" class="nav-link">แอดมิน</a>
         <a href="users_accounts.php" class="nav-link">ผู้ใช้</a>
         <!-- <a href="../1.php" class="nav-link">ใบเสร็จ</a> -->
      </nav>

      <div class="icons d-flex gap-3">
         <div id="menu-btn" class="fas fa-bars fs-4"></div>
         <div id="user-btn" class="fas fa-user fs-4"></div>
      </div>

      <div class="profile dropdown">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="d-inline"><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn btn-primary btn-sm ms-2">แก้ไขโปรไฟล์</a>
         <a href="../components/admin_logout.php" class="btn btn-danger btn-sm ms-2" onclick="return confirm('ออกจากระบบ?');">ออกจากระบบ</a>
      </div>

   </section>

</header>

