<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'product updated!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'image updated!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <style>
        @font-face {
            font-family: "NotoSansThaiLooped";
            src: url(../../font/NotoSansThaiLooped-Bold.ttf) format("truetype");
        }
   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Update Product Section Starts -->

<section class="container mt-5">

   <h1 class="text-center mb-4">แก้ไขสินค้า</h1>
   
   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   
   <form action="" method="POST" enctype="multipart/form-data" class="p-4 border rounded-3 shadow-sm">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">

      <div class="mb-3 text-center">
         <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="" class="img-fluid" width="150">
      </div>

      <div class="mb-3">
         <label for="name" class="form-label">แก้ไขชื่อสินค้า</label>
         <input type="text" name="name" class="form-control" placeholder="Enter product name" required maxlength="100" value="<?= $fetch_products['name']; ?>">
      </div>

      <div class="mb-3">
         <label for="price" class="form-label">แก้ไขราคา</label>
         <input type="number" name="price" class="form-control" min="0" max="9999999999" required placeholder="Enter product price" value="<?= $fetch_products['price']; ?>" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10)">
      </div>

      <div class="mb-3">
         <label for="category" class="form-label">แก้ไขประเภทสินค้า</label>
         <select name="category" class="form-select" required>
            <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
            <option value="อาหรคาว">โทรศัพท์มือถือ</option>
            <option value="ของหวาน">แล็บท็อป</option>
            <option value="อาหารว่าง">นาฬิกาข้อมือ</option>
            <option value="อาหารเพื่อสุขภาพ">โทรทัศน์</option>
         </select>
      </div>

      <div class="mb-3">
         <label for="image" class="form-label">แก้ไขรูปสินค้า</label>
         <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp">
      </div>

      <div class="d-flex justify-content-between">
         <button type="submit" name="update" class="btn btn-primary">ตกลง</button>
         <a href="products.php" class="btn btn-secondary">ย้อนกลับ</a>
      </div>
   </form>

   <?php
         }
      }else{
         echo '<p class="text-center text-danger">ยังไม่มีสินค้าที่เพิ่มเข้ามา!</p>';
      }
   ?>

</section>

<!-- Update Product Section Ends -->

<script src="../js/admin_script.js"></script>

</body>
</html>
