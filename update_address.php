<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $address = $_POST['flat'] .', '.$_POST['building'].', '.$_POST['area'].', '.$_POST['town'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE users set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'address saved!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>อัพเดทที่อยู่</title>

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
   
<?php include 'components/user_header.php' ?>

<section class="container my-5 p-4 border rounded shadow bg-white" style="max-width: 600px;">

   <form action="" method="post">
      <h3 class="text-center mb-4">ที่อยู่ของคุณ</h3>
      
      <input type="text" class="form-control mb-3" placeholder="เบอร์โทรศัพท์ที่ติดต่อได้" required maxlength="50" name="flat">
      <input type="text" class="form-control mb-3" placeholder="บ้านเลขที่" required maxlength="50" name="building">
      <input type="text" class="form-control mb-3" placeholder="แขวง" required maxlength="50" name="area">
      <input type="text" class="form-control mb-3" placeholder="อำเภอ" required maxlength="50" name="town">
      <input type="text" class="form-control mb-3" placeholder="จังหวัด" required maxlength="50" name="city">
      <input type="text" class="form-control mb-3" placeholder="ถนน" required maxlength="50" name="state">
      <input type="text" class="form-control mb-3" placeholder="ประเทศ" required maxlength="50" name="country">
      <input type="number" class="form-control mb-3" placeholder="ไปรษณีย์" required max="999999" min="0" maxlength="6" name="pin_code">
      <button type="submit" name="submit" class="btn btn-primary w-100">ยืนยัน</button>
   </form>

</section>

<?php include 'components/footer.php' ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include './components/alers.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
