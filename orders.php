<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>คำสั่งซื้อ</title>

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

<div class="container my-5">
   <div class="text-center">
      <h3>คำสั่งซื้อ</h3>
      <p><a href="home.php">หน้าหลัก</a> <span> / คำสั่งซื้อ</span></p>
   </div>

   <section class="orders">
      <h1 class="text-center mb-4">คำสั่งซื้อของคุณ</h1>

      <div class="row">

      <?php
         if($user_id == ''){
            echo '<p class="text-center text-muted">ไม่มีคำสั่งซื้อ</p>';
         }else{
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->execute([$user_id]);
            if($select_orders->rowCount() > 0){
               while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-4 mb-4">
         <div class="card shadow-sm danger ">
            <div class="card-body ">
               <p>วันที่ซื้อ: <strong><?= $fetch_orders['placed_on']; ?></strong></p>
               <p>ชื่อ: <strong><?= $fetch_orders['name']; ?></strong></p>
               <p>อีเมล: <strong><?= $fetch_orders['email']; ?></strong></p>
               <p>เบอร์โทรศัพท์: <strong><?= $fetch_orders['number']; ?></strong></p>
               <p>ที่อยู่: <strong><?= $fetch_orders['address']; ?></strong></p>
               <p>ชำระเงินผ่าน: <strong><?= $fetch_orders['method']; ?></strong></p>
               <p>ราคารวม: <strong><?= $fetch_orders['total_price']; ?> บาท</strong></p>
               <p>สถานะ: <strong class="<?= ($fetch_orders['payment_status'] == 'ยังไม่จ่ายตัง') ? 'text-danger' : 'text-success'; ?>">
                  <?= $fetch_orders['payment_status']; ?></strong>
               </p>
            </div>
         </div>
      </div>
      <?php
               }
            }else{
               echo '<p class="text-center text-muted">ไม่มีคำสั่งซื้อ!</p>';
            }
         }
      ?>

      </div>

   </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include './components/alers.php'; ?>

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<script src="js/script.js"></script>

</body>
</html>
