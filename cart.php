<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['delete'])) {
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $success_msg[] = 'เอาสินค้าออกแล้ว!';
}

if (isset($_POST['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   $success_msg[] = 'ลบสิ้นค้าออกหมดแล้ว!';
}

if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $success_msg[] = 'อัพเดทตะกร้าเรียบร้อย';
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ตะกร้า</title>

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

   <div class="heading text-center bg-white py-4 mb-4">
      <h3 class="mb-2">ตะกร้า</h3>
      <p><a href="home.php">หน้าหลัก</a> <span> / ตะกร้า</span></p>
   </div>

   <section class="products container py-5">

      <h1 class="title text-center mb-4">ตะกร้าของคุณ</h1>

      <div class="row row-cols-1 row-cols-md-3 g-4">

         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="col">
                  <form action="" method="post" class="card shadow-sm h-100">
                     <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                     <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="position-absolute top-0 end-0 p-2 text-light" style="background-color: rgba(0, 0, 0, 0.5);"><i class="fas fa-eye"></i></a>
                     <button type="submit" class="position-absolute top-0 start-50 translate-middle-x mb-3 btn btn-danger" name="delete" onclick="return confirm('Are you sure you want to remove this item?');"><i class="fas fa-times"></i></button>

                     <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="" class="card-img-top">
                     <div class="card-body">
                        <h5 class="card-title"><?= $fetch_cart['name']; ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                           <span class="text-success"><?= $fetch_cart['price']; ?> บาท</span>
                           <input type="number" name="qty" class="form-control w-auto" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>">
                           <button type="submit" name="update_qty" class="btn btn-primary">ยืนยัน</button>
                        </div>
                        <div class="sub-total mt-2"> ราคา : <span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> บาท</span> </div>
                     </div>
                  </form>
               </div>
         <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty text-center text-danger fs-4">ว่างเปล่า !</p>';
         }
         ?>

      </div>

      <div class="cart-total bg-white p-4 shadow-sm text-center mt-4">
         <p>ราคารวม : <span><?= $grand_total; ?> บาท</span></p>
         <a href="checkout.php" class="btn btn-success <?= ($grand_total > 1) ? '' : 'disabled'; ?>">ดำเนินการต่อ</a>

      </div>

   </section>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <?php include './components/alers.php'; ?>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>