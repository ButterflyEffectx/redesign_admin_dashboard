<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick View</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

   <!-- ฟอนต์ NotoSansThaiLooped -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap">

   <style>
      @font-face {
         font-family: 'mai';
         src: url(font/Kanit-Regular.ttf);
      }
      * {
         font-family: 'mai';
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view container py-5">

   <h1 class="text-center mb-4 fw-bold">เพิ่มเติมเกี่ยวกับสินค้า</h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="bg-white p-4 shadow rounded">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      
      <!-- Product Image -->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="<?= $fetch_products['name']; ?>" class="img-fluid mb-4 rounded">
      
      <!-- Product Category -->
      <a href="category.php?category=<?= $fetch_products['category']; ?>" class="btn btn-primary btn-sm mb-3"><?= $fetch_products['category']; ?></a>
      
      <!-- Product Name -->
      <div class="h4 mb-3"><?= $fetch_products['name']; ?></div>
      
      <!-- Product Details -->
      <div class="text-muted mb-4"><?= $fetch_products['details']; ?></div>
      
      <!-- Price & Quantity -->
      <div class="d-flex justify-content-between align-items-center mb-4">
         <div class="h5"><?= $fetch_products['price']; ?> บาท</div>
         <input type="number" name="qty" class="form-control w-25" min="1" max="99" value="1">
      </div>
      
      <!-- Add to Cart Button -->
      <button type="submit" name="add_to_cart" class="btn btn-success w-100">เพิ่มใส่ตะกร้า</button>
   </form>
   <?php
         }
      }else{
         echo '<p class="text-center text-danger fs-4">ไม่มีสินค้า!</p>';
      }
   ?>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include './components/alers.php'; ?>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
