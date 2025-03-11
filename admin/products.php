<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}
;

if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'product name already exists!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'image size is too large';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image,details) VALUES(?,?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image, $details]);

         $message[] = 'new product added!';
      }

   }

}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- add products section starts  -->

   <section class="container mt-4">

      <form action="" method="POST" enctype="multipart/form-data" class="card shadow-sm border p-4">
         <div class="d-flex align-items-center mb-4">
            <i class="fas fa-box-open text-primary fs-3 me-2"></i>
            <h3 class="mb-0">เพิ่มสินค้า</h3>
         </div>

         <div class="row g-3">
            <!-- ชื่อสินค้า -->
            <div class="col-md-6">
               <label for="product_name" class="form-label">ชื่อสินค้า</label>
               <div class="input-group">
                  
                  <input type="text" id="product_name" required placeholder="กรอกชื่อสินค้า" name="name" maxlength="100"
                     class="form-control border bg-light">
               </div>
            </div>

            <!-- ราคา -->
            <div class="col-md-6">
               <label for="product_price" class="form-label">ราคา (บาท)</label>
               <div class="input-group">
                  
                  <input type="number" id="product_price" min="0" max="9999999999" required placeholder="กรอกราคา"
                     name="price" onkeypress="if(this.value.length == 10) return false;"
                     class="form-control border bg-light">
               </div>
            </div>

            <!-- ประเภทสินค้า -->
            <div class="col-md-6">
               <label for="product_category" class="form-label">ประเภทสินค้า</label>
               <div class="input-group">
                  
                  <select id="product_category" name="category" class="form-select border bg-light" required>
                     <option value="" disabled selected>เลือกประเภทสินค้า</option>
                     <option value="โทรศัพท์มือถือ">โทรศัพท์มือถือ</option>
                     <option value="แล็บท็อป">คอมพิวเตอร์</option>
                     <option value="นาฬิกาข้อมือ">นาฬิกาข้อมือ</option>
                     <option value="โทรทัศน์">โทรทัศน์</option>
                  </select>
               </div>
            </div>

            <!-- รูปภาพ -->
            <div class="col-md-6">
               <label for="product_image" class="form-label">รูปภาพสินค้า</label>
               <div class="input-group">
                  
                  <input type="file" id="product_image" name="image" class="form-control border bg-light"
                     accept="image/jpg, image/jpeg, image/png, image/webp" required>
               </div>
               <div class="form-text">รองรับไฟล์ JPG, JPEG, PNG และ WEBP</div>
            </div>

            <!-- รายละเอียด -->
            <div class="col-12">
               <label for="product_details" class="form-label">รายละเอียดสินค้า</label>
               <div class="input-group">
                  
                  <textarea id="product_details" name="details" placeholder="กรอกรายละเอียดสินค้า"
                     class="form-control border bg-light" required maxlength="500" rows="4"></textarea>
               </div>
               <div id="charCount" class="form-text text-end">0/500 ตัวอักษร</div>
            </div>
         </div>

         <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="reset" class="btn btn-light px-4">
               <i class="fas fa-undo me-1"></i> รีเซ็ต
            </button>
            <button type="submit" name="add_product" class="btn btn-primary px-4">
               <i class="fas fa-save me-1"></i> บันทึกข้อมูล
            </button>
         </div>
      </form>

      <script>
         document.addEventListener('DOMContentLoaded', function () {
            // นับจำนวนตัวอักษรในช่องรายละเอียด
            const detailsTextarea = document.getElementById('product_details');
            const charCount = document.getElementById('charCount');

            detailsTextarea.addEventListener('input', function () {
               const currentLength = this.value.length;
               charCount.textContent = `${currentLength}/500 ตัวอักษร`;

               // เปลี่ยนสีเมื่อเข้าใกล้ขีดจำกัด
               if (currentLength > 450) {
                  charCount.classList.add('text-warning');
                  if (currentLength > 480) {
                     charCount.classList.remove('text-warning');
                     charCount.classList.add('text-danger');
                  }
               } else {
                  charCount.classList.remove('text-warning', 'text-danger');
               }
            });

            // แสดงตัวอย่างรูปภาพก่อนอัปโหลด
            const imageInput = document.getElementById('product_image');

            imageInput.addEventListener('change', function () {
               // สร้างพื้นที่แสดงตัวอย่างถ้ายังไม่มี
               let previewContainer = document.getElementById('image-preview');

               if (!previewContainer) {
                  previewContainer = document.createElement('div');
                  previewContainer.id = 'image-preview';
                  previewContainer.className = 'mt-2 text-center';
                  this.parentNode.parentNode.appendChild(previewContainer);
               }

               // ล้างตัวอย่างเก่า
               previewContainer.innerHTML = '';

               if (this.files && this.files[0]) {
                  const reader = new FileReader();

                  reader.onload = function (e) {
                     const img = document.createElement('img');
                     img.src = e.target.result;
                     img.className = 'img-thumbnail mt-2';
                     img.style.maxHeight = '150px';
                     previewContainer.appendChild(img);
                  }

                  reader.readAsDataURL(this.files[0]);
               }
            });
         });
      </script>

   </section>

   <!-- add products section ends -->

   <!-- show products section starts  -->

   <section class="container mt-5">

      <h3 class="text-center mb-4">แสดงสินค้า</h3>

      <div class="row row-cols-1 row-cols-md-3 g-4">

         <?php
         $show_products = $conn->prepare("SELECT * FROM `products`");
         $show_products->execute();
         if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="col">
                  <div class="card h-100 shadow-sm">
                     <img src="../uploaded_img/<?= $fetch_products['image']; ?>" class="card-img-top" alt="">
                     <div class="card-body">
                        <h5 class="card-title"><?= $fetch_products['name']; ?></h5>
                        <p class="card-text"><?= $fetch_products['details']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                           <div class="price"><?= $fetch_products['price']; ?> บาท</div>
                           <div class="category"><?= $fetch_products['category']; ?></div>
                        </div>
                     </div>
                     <div class="card-footer d-flex justify-content-between">
                        <a href="update_product.php?update=<?= $fetch_products['id']; ?>"
                           class="btn btn-warning btn-sm">แก้ไข</a>
                        <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('ลบสินค้านี้?');">ลบ</a>
                     </div>
                  </div>
               </div>
               <?php
            }
         } else {
            echo '<p class="text-center w-100">ไม่มีสินค้า!</p>';
         }
         ?>

      </div>

   </section>

   <!-- show products section ends -->

   <script src="../js/admin_script.js"></script>

</body>

</html>