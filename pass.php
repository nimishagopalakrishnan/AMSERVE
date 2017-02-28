<?php
session_start();
include_once 'dbconnect.php';

if(isset($_POST['submit']) && isset($_FILES['cv'])) {
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$middlename=$_POST['middlename'];
$fullname=filter_var($_POST['firstname'] . ' ' . $_POST['middlename'] . ' ' . $_POST['lastname'],FILTER_SANITIZE_STRING);
$gender=$_POST['gender'];
$age=$_POST['age'];
$telhome=$_POST['telhome'];
$telmob = $_POST['telmob'] ;
$address = $_POST['address'];
$x = $_POST['x'];
$x=$x+1;
$x1 = $_POST['x1'];
$x1=$x1+1;

for($i=0;$i<$x;$i++) {
    ${'degree' . $i}=$_POST["degree"][$i];
    ${'schoolname' . $i}=$_POST["schoolname"][$i];
    ${'years' . $i}=$_POST["years"][$i];
    ${'percentage' . $i}=$_POST["percentage"][$i];
}

for($i=0;$i<$x1;$i++) {
    ${'organisation' . $i}=$_POST["organisation"][$i];
    ${'designation' . $i}=$_POST["designation"][$i];
    ${'fromdate' . $i}=$_POST["fromdate"][$i];
    ${'todate' . $i}=$_POST["todate"][$i];
}


	$masterinsert="INSERT INTO `master`(`full_name`, `gender`, `age`, `telhome`, `telmob`, `address`,`cvname`) VALUES ('$fullname','$gender',$age,$telhome,$telmob,'$address','null')";
	if(mysqli_query($con,$masterinsert)) 
	{

	$res1=mysqli_query($con,"SELECT `id` FROM `master` WHERE telmob='$telmob'");
	$userRow1=mysqli_fetch_array($res1);
	$masterid = $userRow1['id'];
	

	for($i=0;$i<$x;$i++) 
	{
	
	$eduinsert="INSERT INTO `education`(`degree`, `schoolname`, `years`, `percentage`, `MasterId`) VALUES ('${'degree' . $i}','${'schoolname' . $i}',${'years' . $i},${'percentage' . $i},$masterid)";
	if(mysqli_query($con,$eduinsert)) {

		} else {
			?>
				<script>alert('Problem adding Details in Edu');</script>
			<?php
		}
	}
	
		
	for($i=0;$i<$x1;$i++) {
	
	$profinsert="INSERT INTO `professional`(`organisation`, `designation`, `fromdate`, `todate`,`MasterId`) VALUES ('${'organisation' . $i}','${'designation' . $i}','${'fromdate' . $i}','${'todate' . $i}',$masterid)";
	if(mysqli_query($con,$profinsert)) {
	} 
	else {
			?>
				<script>alert('Problem adding Details in Prof');</script>
			<?php
		}
	}
	
	
if (($_FILES['cv']['name']!=""))
{
//Where the file is going to be stored	
	$target_dir = "CV/";
	$file = $_FILES['cv']['name'];
	$path = pathinfo($file);
	$ext = $path['extension'];
	$temp_name = $_FILES['cv']['tmp_name'];
	$file_name1 = "$masterid" . "_" . "$fullname";
	$path_filename_ext = $target_dir.$file_name1.".".$ext;
	//Check if file already exists
	if (file_exists($path_filename_ext)) 
	{
			?>
			 <script>alert('Problem reading file.');</script>
			<?php
	}else
	{
		$query=mysqli_prepare($con,"UPDATE `master` SET `cvname` = '$file_name1' WHERE `id` = $masterid");
		mysqli_stmt_execute($query);
		move_uploaded_file($temp_name,$path_filename_ext);
		echo "Congratulations! Your Application has been Successfully Sent.";
	}
}
	else
	{
	?>
	 <script>alert('Problem adding Details in Master.');</script>
	<?php 
	
	} 
}
} 
?>