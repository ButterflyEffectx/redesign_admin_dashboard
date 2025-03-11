<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $warning_msg[] = 'อีเมลหรือเบอร์โทรใช้ไปแล้ว';
   }else{
      if($pass != $cpass){
         $warning_msg[] = 'รหัสผ่านไม่ตรงกัน';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }
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
   <title>Register</title>

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

<section class="container mt-5">

   <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
         <div class="card p-4 shadow-sm">
            <h3 class="text-center mb-4">สมัครสมาชิก</h3>
            <form action="" method="post">
               <div class="mb-3">
                  <input type="text" name="name" class="form-control" required placeholder="ชื่อผู้ใช้" maxlength="50">
               </div>
               <div class="mb-3">
                  <input type="email" name="email" class="form-control" required placeholder="อีเมล" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
               </div>
               <div class="mb-3">
                  <input type="number" name="number" class="form-control" required placeholder="เบอร์โทรศัพท์" min="0" max="9999999999" maxlength="10">
               </div>
               <div class="mb-3">
                  <input type="password" name="pass" class="form-control" required placeholder="ใส่รหัสผ่าน" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
               </div>
               <div class="mb-3">
                  <input type="password" name="cpass" class="form-control" required placeholder="ยืนยันรหัสผ่าน" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
               </div>
               <button type="submit" name="submit" class="btn btn-primary w-100">ยืนยัน</button>
            </form>
            <p class="text-center mt-3">สมัครสมาชิกเรียบร้อยแล้วใช่ไหม? <a href="login.php">เข้าสู่ระบบเลย</a></p>
         </div>
      </div>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include './components/alers.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
