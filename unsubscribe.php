<?php  
$servername = "us-cdbr-iron-east-04.cleardb.net";
$username = "b8f2edea134d15";
$password = "da33cd89";
$dbname = "heroku_12ab324116b7af6";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>

<html>
<head>
<title>Unsubscribe</title>
<head>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
.form{
    width: 500px;
    margin: 30px auto 0;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #333;
}


</style>
</head>


<body>
<?php if(isset($_POST['submit'])){
	$email = $_POST['email'];

$counter = 0;

$result = mysqli_query($conn,"SELECT * FROM pr_psf WHERE Email='$email'");
while($row = mysqli_fetch_array($result))
  {
     $counter++;
  }	
if($counter == 1){
	$sql = "UPDATE pr_psf SET unsubscribe=1 WHERE Email='$email'";
	if ($conn->query($sql) === TRUE) {
    	echo "<div class='alert alert-success'>You Unsubscribed successfully.</div>" ;
	} else {
		echo "<div class='alert alert-danger'>Something Went wrong please Try Again.</div>";
	}
}  
else{
	echo "<div class='alert alert-info'>Record does not exist.</div>";
}
} 
?>
<form method="post" action="#" class="form">
<div class="form-group">
    <label for="email">Email address:</label>
<input type="email" class="form-control" name="email" required="required">
</div>

<button class="btn btn-success" name="submit" type="submit">Unsubscribe</button>
</form>

</body>
</html>