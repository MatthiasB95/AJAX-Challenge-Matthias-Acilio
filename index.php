<?php 

$localhost = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "ajax_challenge"; 

// create connection 
$conn = new  mysqli($localhost, $username, $password, $dbname); 

// check connection 
if($conn->connect_error) {
    die("connection failed: " . $conn->connect_error);
} else {
    //echo "Successfully Connected";
}


$error = false;
$nameError = "";
$emailError = "";
$passError = "";
if ( isset($_POST['btn-signup']) ) {
 

  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

 $email = trim($_POST[ 'email']);
 $email = strip_tags($email);
 $email = htmlspecialchars($email);

 $pass = trim($_POST['pass']);
 $pass = strip_tags($pass);
 $pass = htmlspecialchars($pass);


 if (empty($name)) {
  $error = true;
  $nameError = "Please enter your full name.";
 } else if (strlen($name) < 3) {
  $error = true;
  $nameError = "Name must have at least 3 characters.";
 } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
  $error = true ;
  $nameError = "Name must contain alphabets and space.";
 }else  {
 	$query = "SELECT user_name FROM users WHERE user_name='$name'";
 	$result = mysqli_query($conn, $query);
  	$count = mysqli_num_rows($result);
  	if ($count!=0) {
  		$error = true;
    $nameError = "Name is already taken";
  	}
 	
 }

 //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
  $error = true;
  $emailError = "Please enter valid email address." ;
 } else {
  // checks whether the email exists or not
  $query = "SELECT user_email FROM users WHERE user_email='$email'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if($count!=0){
   $error = true;
   $emailError = "Provided Email is already in use.";
  }
 }
 // password validation
  if (empty($pass)){
  $error = true;
  $passError = "Please enter password.";
 } else if(strlen($pass) < 6) {
  $error = true;
  $passError = "Password must have at least 6 characters." ;
 }

 // password hashing for security
$password = hash('sha256' , $pass);


 // if there's no error, continue to signup
 if( !$error ) {
  
  $query = "INSERT INTO users(user_name,user_email,user_pass) VALUES('$name','$email','$password')";
  $res = mysqli_query($conn, $query);
  
  if ($res) {
   $errTyp = "success";
   $errMSG = "Successfully registered, you may login now";
   unset($name);
   unset($email);
   unset($pass);
  } else  {
   $errTyp = "danger";
   $errMSG = "Something went wrong, try again later..." ;
  }
  
 }


}
?>
<!DOCTYPE html> 
<html>
<head>
<title>Login & Registration System</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<style type="text/css">
    #search-box1, #search-box2, #search-box3{
        width: 300px;
        position: relative;
        display: inline-block;
        font-size: 14px;
        margin-top: 15px;
    }
    #search-box1 input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }

    #search-box2 input[type="email"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }

    #search-box3 input[type="password"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    #result1, #result2, #result3{
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;

    }
    #search-box1 input[type="text"] #result1{
        width: 100%;
        box-sizing: border-box;
    }
    #search-box2, input[type="email"] #result2{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    #result1, #result2, #result3 p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }

   </style>
      <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#search-box1 input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings("#result1");
        if(inputVal.length){
            $.get("backend_name.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        }else{
            resultDropdown.empty();
        }
    });
});

$(document).ready(function(){
    $('#search-box2 input[type="email"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings("#result2");
        if(inputVal.length){
            $.get("backend_email.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        }else{
            resultDropdown.empty();
        }
    });
});



</script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<body>

   <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  autocomplete="off" >
  
      
            <h2>Sign Up.</h2>
            <hr />
          
            <?php
   if ( isset($errMSG) ) {
  
   ?> 
            <div  class="alert alert-<?php echo $errTyp ?>" >
                         <?php echo  $errMSG; ?>
       		</div>

<?php
mysqli_close($conn); 
  }
  ?> 
          
      
          
			<div id="search-box1">
            <input type ="text"  name="name"  class ="form-control"  placeholder ="Enter Name" maxlength ="50"/>
             <div id="result1"></div>
            </div>
      
            <span class = "text-danger"> <?php echo $nameError;?> </span>
          
    
			<div id="search-box2">
            <input type = "email" name = "email" class ="form-control" placeholder = "Enter Your Email"   maxlength = "40"/>
             <div id="result2"></div>
    		</div>

               <span class = "text-danger"> <?php echo $emailError; ?> </span>
      
          
      
            
        	<div id="search-box3">
            <input type = "password"   name = "pass"   class = "form-control"   placeholder = "Enter Password"   maxlength = "15"  />
             <div id="result3"></div>
            </div>
               <span class = "text-danger"> <?php echo $passError; ?> </span>
      
            <hr/>

          
            <button   type = "submit"   class = "btn btn-block btn-primary"   name = "btn-signup" >Sign Up</button >
            <hr/>
          
            
    
  
   </form >
</body >
</html >