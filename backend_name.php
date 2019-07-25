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

if(isset($_REQUEST["term"])){
    // Prepare a select statement
    $sql = "SELECT user_name FROM users WHERE user_name LIKE ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        $param_term = '%'. $_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo "<p>" . $row["user_name"] . "</p>";
                    
                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
}
 
// close connection
mysqli_close($conn);
?>