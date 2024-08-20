<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   
   $email = $_POST['email'];
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   $image = $_FILES['image']['name'];
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, email, password, image) VALUES(?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'new tutor registered! please login now';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
   header('Location:login.php');
}
?>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>register new</h3>
      <div class="flex">
         <div class="col">
            <p>your name</p>
            <input type="text" name="name" placeholder="Your name"  required class="box">
            <p>your email</p>
            <input type="email" name="email" placeholder="Your email"  required class="box">
         </div>
         <div class="col">
            <p>your password</p>
            <input type="password" name="pass" placeholder="Your password" required class="box">
            <p>confirm password</p>
            <input type="password" name="cpass" placeholder="Confirm your password" required class="box">
            <p>select pic</p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">already have an account? <a href="login.php">login now</a></p>
      <input type="submit" name="submit" value="register now" class="btn">
   </form>

</section>











<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>