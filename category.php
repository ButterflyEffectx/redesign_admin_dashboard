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
   <title>ประเภทสินค้า</title>

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

<section class="products container py-5">

   <h1 class="title text-center mb-4">ประเภทสินค้า</h1>

   <div class="row row-cols-1 row-cols-md-3 g-4">

      <?php
         $category = $_GET['category'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
         $select_products->execute([$category]);
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col">
         <form action="" method="post" class="card shadow-sm h-100">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
            <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
            <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="position-absolute top-0 end-0 p-2 text-light" style="background-color: rgba(0, 0, 0, 0.5);"><i class="fas fa-eye"></i></a>
            <button type="submit" class="position-absolute bottom-0 start-50 translate-middle-x mb-3 btn btn-primary" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button>
            
            <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" class="card-img-top">
            <div class="card-body">
               <h5 class="card-title"><?= $fetch_products['name']; ?></h5>
               <div class="d-flex justify-content-between align-items-center">
                  <span class="text-success"><?= $fetch_products['price']; ?> บาท</span>
                  <input type="number" name="qty" class="form-control w-auto" min="1" max="99" value="1">
               </div>
            </div>
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="text-center text-danger fs-4">ว่างเปล่า !</p>';
         }
      ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include './components/alers.php'; ?>

</body>
</html>
