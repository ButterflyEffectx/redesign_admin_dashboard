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
   <title>Search Page</title>

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

<!-- search form section starts  -->

<section class="search-form py-5">
   <div class="container d-flex justify-content-center align-items-center">
      <form method="post" action="" class="w-100 w-md-50">
         <input type="text" name="search_box" class="form-control form-control-lg mb-3" placeholder="Search for products..." required>
         <button type="submit" name="search_btn" class="btn btn-primary btn-lg w-100"><i class="fas fa-search"></i> Search</button>
      </form>
   </div>
</section>

<!-- search form section ends -->

<!-- products section starts  -->

<section class="products py-5">
   <div class="container">
      <div class="row">
         <?php
            if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
               $search_box = $_POST['search_box'];
               $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
               $select_products->execute(['%' . $search_box . '%']);
               if($select_products->rowCount() > 0){
                  while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="col-md-4 mb-4">
            <div class="card">
               <img src="uploaded_img/<?= $fetch_products['image']; ?>" class="card-img-top" alt="Product Image">
               <div class="card-body text-center">
                  <h5 class="card-title"><?= $fetch_products['name']; ?></h5>
                  <p class="card-text"><?= $fetch_products['category']; ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                     <span class="text-muted"><?= $fetch_products['price']; ?> บาท</span>
                     <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                  </div>
                  <form action="" method="post">
                     <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                     <div class="input-group mt-3">
                        <input type="number" name="qty" class="form-control" min="1" max="99" value="1" required>
                        <button type="submit" class="btn btn-success" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <?php
                  }
               } else {
                  echo '<p class="col-12 text-center h4 text-muted">ไม่พบสินค้า!</p>';
               }
            }
         ?>
      </div>
   </div>
</section>

<!-- products section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include './components/alers.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
