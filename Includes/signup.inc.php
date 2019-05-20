<?php

if(isset($_POST['submit'])){
    include_once 'dbh.inc.php';

    $first = mysqli_real_escape_string($conn, $_POST['name']);
    $last = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $uid = mysqli_real_escape_string($conn, $_POST['password']);
    $pwd = mysqli_real_escape_string($conn, $_POST['uid']);

    //error handlers
    //check for empty fields

    if(empty($first) || empty($last) || empty($email) || empty(uid) || empty($pwd)){
       header("Location: ../Signup.html?signup=empty");
	   
    }else{
       //check if input char are valid
    	if(!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last) ){
          header("Location: ../Signup.html?signup=invalid")
	      exit();  
    	}else{
    		//check if email is valid
    		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    			header("Location: ../Signup.html?signup=email")
	            exit();
    		}else{
    			$sql = "SELECT * FROM users WHERE user_uid='$uid'";
    			$result = mysqli_query($conn, $sql);
    			$resultcheck = mysqli_num_rows($result);

    			if($resultcheck > 0){
    				header("Location: ../Signup.html?signup=usertaken")
	                exit();
    			}else{
    				//hashing the password
    				$hashedPwd = password_hash($pwd, PASSWORD_DEFUALT);
    				//inset the user into the database
    				$sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd,) VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd' );";

    				 mysqli_query($conn, $sql);

    				 header("Location: ../Signup.html?signup=success")
	                exit();
    			}
    		}
    	}
    }

}else {
	header("Location: ../Signup.html")
	exit();
}