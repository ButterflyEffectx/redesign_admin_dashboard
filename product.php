<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>สินค้า</title>

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




<div class="container py-5">
   <div class="heading mb-4">
      <h3 class="text-center">สินค้า</h3>
      <p class="text-center"><a href="home.php">หน้าหลัก</a> <span> / สินค้า</span></p>
   </div>

<!-- Category Section -->
<section class="container my-4">
   <h1 class="text-center">ประเภทสินค้า</h1>
   <div class="row text-center">
      <div class="col-md-3">
         <a href="category.php?category=โทรศัพท์มือถือ" class="text-decoration-none">
            <img src="images/icon-7.png" class="img-fluid" alt="">
            <h5>โทรศัพท์มือถือ</h5>
         </a>
      </div>
      <div class="col-md-3">
         <a href="category.php?category=แล็บท็อป" class="text-decoration-none">
            <img src="images/icon-1.png" class="img-fluid" alt="">
            <h5>คอมพิวเตอร์</h5>
         </a>
      </div>
      <div class="col-md-3">
         <a href="category.php?category=นาฬิกาข้อมือ" class="text-decoration-none">
            <img src="images/icon-8.png" class="img-fluid" alt="">
            <h5>นาฬิกาข้อมือ</h5>
         </a>
      </div>
      <div class="col-md-3">
         <a href="category.php?category=โทรทัศน์" class="text-decoration-none">
            <img src="images/icon-2.png" class="img-fluid" alt="">
            <h5>จอ</h5>
         </a>
      </div>
   </div>
</section>


   <!-- menu section starts  -->
   <section class="products">
      <h1 class="title text-center mb-4">สินค้าทั้งหมด</h1>

      <div class="row row-cols-1 row-cols-md-3 g-4">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="col">
            <div class="card h-100">
               <img src="uploaded_img/<?= $fetch_products['image']; ?>" class="card-img-top" alt="<?= $fetch_products['name']; ?>">
               <div class="card-body">
                  <a href="category.php?category=<?= $fetch_products['category']; ?>" class="badge bg-primary text-white"><?= $fetch_products['category']; ?></a>
                  <h5 class="card-title"><?= $fetch_products['name']; ?></h5>
                  <div class="d-flex justify-content-between align-items-center">
                     <span class="price"><?= $fetch_products['price']; ?> บาท</span>
                     <form action="" method="post">
                        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                        <input type="number" name="qty" class="form-control w-auto" min="1" max="99" value="1" maxlength="2">
                        <button type="submit" class="btn btn-primary w-100 mt-2" name="add_to_cart">
                           <i class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า
                        </button>
                     </form>
                  </div>
               </div>
               <div class="card-footer text-center">
                  <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="btn btn-outline-info w-100">
                     <i class="bi bi-eye"></i> ดูรายละเอียด
                  </a>
               </div>
            </div>
         </div>
         <?php
            }
         }else{
            echo '<p class="empty text-center">ไม่มีสินค้า!</p>';
         }
         ?>

      </div>
   </section>
   <!-- menu section ends -->

</div>

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include './components/alers.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
