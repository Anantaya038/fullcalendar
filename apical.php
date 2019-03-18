<?php
include ("connect.php");
mysqli_set_charset($conn , "utf8");

if ($_GET[mode] == "select") { 

$sqldata="SELECT
appointment.*,
users_patient.firstname AS patient_firstname,
users_patient.lastname AS patient_lastname,
users_pt.firstname AS pt_firstname,
users_pt.lastname AS pt_lastname
FROM
appointment
JOIN
patient
ON
patient.patient_id = appointment.patient_id
JOIN
users AS users_patient
ON
users_patient.users_id = patient.users_id
JOIN
pt
ON
pt.pt_id = appointment.pt_id
JOIN
users AS users_pt
ON
users_pt.users_id = pt.users_id
WHERE
1"; 
if($_GET['patient_id']) {
        $sqldata .= " AND patient.patient_id = ".$_GET['patient_id'];
    }
    if($_GET['pt_id']) {
        $sqldata .= " AND pt.pt_id = ".$_GET['pt_id'];
    }
    
       
        $query = mysqli_query ($conn, $sqldata);
        
        if (mysqli_num_rows($query) > 0) {
        while ($rec = mysqli_fetch_assoc ($query)) { 
            $result[] = $rec;
        }
        }else {
            $result = [];
        }
        echo json_encode ($result);

    }
    
    


if ($_GET[mode] == "search") { 

    $sqldata = "SELECT * FROM patient left join users on patient.users_id = users.users_id WHERE firstname LIKE '".$_GET['q']."%'";
    //$sqldata= "SELECT  * FROM users  WHERE (firstname LIKE ".$_GET['name']."  )";
             
    $query = mysqli_query ($conn, $sqldata);	
    if (mysqli_num_rows($query) > 0) {
        while ($rec = mysqli_fetch_assoc ($query)) { 
            $data_value = $rec["firstname"] . " " . $rec["lastname"];
			$result[] =  array(
				"id"=> $rec["patient_id"],
				"value" => $data_value
			);
		}
        }else {
            echo "0 results";
        }

        $result = array(
			"total"=>"",
			"items"=>$result	
        );
        
        echo json_encode ($result);
        
}


if ($_GET[mode] == "search-pt") { 

    $sqldata = "SELECT * FROM pt left join users on pt.users_id = users.users_id WHERE firstname LIKE '".$_GET['q']."%'";
    //$sqldata= "SELECT  * FROM users  WHERE (firstname LIKE ".$_GET['name']."  )";
             
    $query = mysqli_query ($conn, $sqldata);	
    if (mysqli_num_rows($query) > 0) {
        while ($rec = mysqli_fetch_assoc ($query)) { 
            $data_value = $rec["firstname"] . " " . $rec["lastname"];
			$result[] =  array(
				"id"=> $rec["pt_id"],
				"value" => $data_value
			);
		}
        }else {
            echo "0 results";
        }

        $result = array(
			"total"=>"",
			"items"=>$result	
        );
        
        echo json_encode ($result);
        
}





?>