<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <style>
      @font-face {
         font-family: "NotoSansThaiLooped";
         src: url(../font/NotoSansThaiLooped-Bold.ttf) format("truetype");
      }
      body {
         font-family: "NotoSansThaiLooped";
         background-color: #f8f9fa;
      }
      .form-container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
      }
      .card {
         width: 100%;
         max-width: 400px;
         padding: 20px;
         box-shadow: 0 4px 8px rgba(0,0,0,0.1);
         border-radius: 10px;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>


<section class="form-container">
   <div class="card p-4">
      <h3 class="text-center mb-4">เข้าสู่ระบบ</h3>
      <?php if(isset($message)) { 
         foreach($message as $msg) {
            echo '<div class="alert alert-danger" role="alert">'.$msg.'</div>'; 
         }
      } ?>
      <form action="" method="post">
         <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" id="email" required class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <div class="mb-3">
            <label for="pass" class="form-label">รหัสผ่าน</label>
            <input type="password" name="pass" id="pass" required class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <button type="submit" name="submit" class="btn btn-primary w-100">ยืนยัน</button>
         <div class="text-center mt-3">
            <p>ยังไม่ได้สมัครสมาชิก? <a href="register.php">สมัครสมาชิกเลย</a></p>
            <p>เป็นแอดมินหรอ? <a href="./admin/admin_login.php">เข้าหน้าแอดมินเลย</a></p>
           
            

         </div>
      </form>
   </div>
</section>

</body>
</html>
