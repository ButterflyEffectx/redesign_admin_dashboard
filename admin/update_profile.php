<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      if($select_name->rowCount() > 0){
         $message[] = 'username already taken!';
      }else{
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
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
   <title>Profile Update</title>

   <!-- Bootstrap 5 CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- Font Awesome -->
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

<!-- Admin Profile Update Section Starts -->

<section class="container mt-5">

   <h3 class="text-center mb-4">Update Profile</h3>

   <form action="" method="POST" class="p-4 border rounded-3 shadow-sm">
      <!-- Update Name -->
      <div class="mb-3">
         <label for="name" class="form-label">Username</label>
         <input type="text" name="name" class="form-control" placeholder="<?= $fetch_profile['name']; ?>" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- Old Password -->
      <div class="mb-3">
         <label for="old_pass" class="form-label">Old Password</label>
         <input type="password" name="old_pass" class="form-control" placeholder="Enter your old password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- New Password -->
      <div class="mb-3">
         <label for="new_pass" class="form-label">New Password</label>
         <input type="password" name="new_pass" class="form-control" placeholder="Enter your new password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- Confirm Password -->
      <div class="mb-3">
         <label for="confirm_pass" class="form-label">Confirm New Password</label>
         <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm your new password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- Submit Button -->
      <button type="submit" name="submit" class="btn btn-primary w-100">Update Now</button>
   </form>

</section>

<!-- Admin Profile Update Section Ends -->

<!-- Custom JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
