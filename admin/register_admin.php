<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'username already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered!';
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
   <title>register</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ไอคอน Bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- register admin section starts  -->

<section class="container mt-5">

   <form action="" method="POST" class="p-4 border rounded-3 shadow-sm">
      <h3 class="text-center mb-4">Register New Admin</h3>
      
      <?php 
         if(isset($message)){
            foreach($message as $msg){
               echo '<p class="alert alert-warning">'.$msg.'</p>';
            }
         }
      ?>

      <div class="mb-3">
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>
      
      <div class="mb-3">
         <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>
      
      <div class="mb-3">
         <input type="password" name="cpass" maxlength="20" required placeholder="Confirm your password" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <div class="text-center">
         <input type="submit" value="Register Now" name="submit" class="btn btn-primary w-100">
      </div>
   </form>

</section>

<!-- register admin section ends -->

<script src="../js/admin_script.js"></script>

</body>
</html>
