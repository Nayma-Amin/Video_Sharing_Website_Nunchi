<?php
require_once 'nunchi_database.php';

                if(isset($_POST['update_user'])){
                    
                    $user_id = $_POST['id'];
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname'];
                    $email = $_POST['email'];
                    $payment_status = $_POST['payment_status'];
                    $password = $_POST['password'];
                    $profile_picture = $_FILES['profile_picture']['name'];
                    $profile_picture_type = $_FILES['profile_picture']['type'];
                    $profile_picture_size = $_FILES['profile_picture']['size'];
                    $profile_picture_temp_loc = $_FILES['profile_picture']['tmp_name'];
                    $profile_picture_store = "profile_pictures/". $profile_picture;
                    move_uploaded_file($profile_picture_temp_loc, $profile_picture_store);
                    
                    $sql4 = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', email = '$email', 
                    payment_status ='$payment_status', password ='$password', profile_picture = '$profile_picture' WHERE id = '$user_id' ";
                    $query4 = mysqli_query($con, $sql4);
                    
                    if($query4){
                        echo "<script>alert('User updated successfully!')</script>";
                        header("Location: registered_users.php");
                    }
                    else{
                        echo "<script>alert('User not updated!')</script>";
                    }

                }
?>