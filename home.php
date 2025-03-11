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
   <title>home</title>

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

<?php include 'components/user_header.php'; ?>




<!-- Products Section -->
<section class="container my-4">
   <h1 class="text-center">สินค้า</h1>
   <div class="row">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-4 mb-4">
         <div class="card h-100">
            <img src="uploaded_img/<?= $fetch_products['image']; ?>" class="card-img-top" alt="">
            <div class="card-body">
               <h5 class="card-title"><?= $fetch_products['name']; ?></h5>
               <p class="card-text">หมวดหมู่: <a href="category.php?category=<?= $fetch_products['category']; ?>" class="text-decoration-none"> <?= $fetch_products['category']; ?></a></p>
               <p class="card-text text-danger fw-bold"><?= $fetch_products['price']; ?> บาท</p>
               <form action="" method="post">
                  <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                  <input type="number" name="qty" class="form-control mb-2" min="1" max="99" value="1">
                  <button type="submit" class="btn btn-primary w-100" name="add_to_cart">เพิ่มลงตะกร้า <i class="bi bi-cart"></i></button>
               </form>
            </div>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="text-center">ไม่มีสินค้า!</p>';
         }
      ?>
   </div>
   <div class="text-center mt-3">
      <a href="product.php" class="btn btn-secondary">ดูสินค้าทั้งหมด</a>
   </div>
</section>

<?php include 'components/footer.php'; ?>


</body>
</html>