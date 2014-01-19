<?php 

include_once 'root/includes/db_connect.php';



$pvalue = json_decode(stripslashes($_POST["json_obj"]), true);

//echo $pvalue["mobileno"]." ".$pvalue["id_proof"]." ".$pvalue["rating"];

$query = "select * from worker where mobile_no='".$pvalue["mobileno"]."' AND id_proof_no='".$pvalue["id_proof"]."' ;" ;

//echo $query;


$result = mysqli_query($con,$query);

if(mysqli_num_rows($result))
{
	$insert_query = "insert into rating(mobile_no, rating) values( '".$pvalue["mobileno"]."', ".$pvalue["rating"].");" ;
	//echo $insert_query;
	if(mysqli_query($con,$insert_query))
	{
		
		$get_query = "select AVG(rating) from rating where mobile_no='".$pvalue["mobileno"]."' ;";
		$avgresult = mysqli_query($con,$get_query);
		//echo $get_query;
		if(mysqli_num_rows($avgresult))
		{
			$row1 = mysqli_fetch_array($avgresult);
			//echo $row1['AVG(rating)'];
			$update_avg = $row1['AVG(rating)'];
			$update_query = "update worker set rating=".$update_avg." where mobile_no='".$pvalue["mobileno"]."'; ";
			if(mysqli_query($con,$update_query))
			{
				echo "Success";
			}
			else
				echo "Fail";
		}
		else
			echo "Fail";
	}
	else
		echo "Fail"; 
}
else
	echo "Fail";  
/*
$loc_final = $pvalue["mark"]."_".$pvalue["series"];

$array = explode(',', $pvalue["language"]);
//echo $pvalue["worker"]." ".$pvalue["mark"]." ".$pvalue["series"]." ".$pvalue["language"] ;

if($pvalue["language"]!='' && $pvalue["language"]!='null'){
	if($pvalue["series"] == '' )
		$query = "select * from worker where occupation='".$pvalue["worker"]."' AND location like '%".$pvalue["mark"]."%' AND ( ";
	else
		$query = "select * from worker where occupation='".$pvalue["worker"]."' AND location='".$loc_final."' AND ( ";

	for ($i = 0; $i < count($array)-1; ++$i) {
		$query .= " language like '%".$array[$i]."%' OR  " ;
	}
	$query .= " language like '%".$array[$i]."%') ;";
}
else{
	if($pvalue["series"] == '' )
		$query = "select * from worker where occupation='".$pvalue["worker"]."' AND location like '%".$pvalue["mark"]."%' ;";
	else
		$query = "select * from worker where occupation='".$pvalue["worker"]."' AND location='".$loc_final."' ;";
}

//echo $query;

$result = mysqli_query($con,$query);

if(mysqli_num_rows($result))
{
	echo "Results: <br /><br />";
        echo "<table align='center' style='margin: 0px auto; width:80%'>";
        echo "<tr><th align='left' style='font-weight: bold; '> Name </th> <th align='left' style='font-weight: bold; '> Mobile No. </th>";
	echo "<th align='left' style='font-weight: bold; '> City </th><th align='left' style='font-weight: bold; '> Area </th><th align='left' style='font-weight: bold; '> Language </th>  <th align='center' style='font-weight: bold; '> Rating </th> </tr>";
        while($row = mysqli_fetch_array($result))
        {
		list($city_name, $area_name) = explode("_", $row['location']);
                echo "<tr>";
                echo "<td align='left'>". $row['name']. "</td><td align='left'> <a href='tel:".$row['mobile_no']."'>" . $row['mobile_no']. "</a></td> <td align='left'>$city_name</td><td align='left' >$area_name</td><td align='left' >".$row['language']."</td><td align='center'>". $row['rating']. "</td>" ;
                echo "</tr>";
        }
        echo "</table>";
//        echo "employee is there";
}
else
        echo "No employee found in this area";

*/

/*

include_once 'root/includes/db_connect.php';

$occupation = $_POST["worker"];
$loc1 = $_POST["mark"];
$loc2 = $_POST["series"];
$location = $loc1."_".$loc2;

$query = "select * from worker where location="."'$location'"." and occupation="."'$occupation' ; ";

$result = mysqli_query($con,$query);

if(mysqli_num_rows($result))
{
	echo "<table align='center' style='margin: 0px auto; width:80%'>";
	echo "<tr><th align='left'  style='font-weight: bold;' >S.No.</th> <th align='left' style='font-weight: bold; '> Name </th> <th align='left' style='font-weight: bold; '> Mobile No. </th> <th align='center' style='font-weight: bold; '> Rating </th> </tr>";
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td align='left'>". $row['SNo']. "<td align='left'>". $row['name']. "</td><td align='left'> <a href='tel:".$row['mobile_no']."'>" . $row['mobile_no']. "</a> </td><td align='center'>". $row['rating']. "</td>" ;
		echo "</tr>";
	}
	echo "</table>";
//        echo "employee is there";
}
else
	echo "employee is not there";

*/

//echo $occupation."<br />".$location;

?>
