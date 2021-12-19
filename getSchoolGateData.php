<?php include "includes/db.php" ?>
<?php
date_default_timezone_set('Asia/Kolkata');
$d = date("Y-m-d"); //yyyy-mm-dd
$t = date("H:i:sa"); //hour(24):min:sec am/pm 

if (isset($_GET['card_uid']) && isset($_GET['device_token'])) 
{
    
    $card_uid = $_GET['card_uid'];
    $device_uid = $_GET['device_token'];
    
    $query = "Select * from school_gates where gate_device_id = '{$device_uid}' ";
    $selected_device = mysqli_query($conn, $query);
    if(!$selected_device)
    {
        die("Query Failed " . mysqli_error($conn));
    }
    
    while($row = mysqli_fetch_assoc($selected_device))
    {
        $gate_id = $row['gate_id'];
        $gate_device_mode = $row['gate_device_mode'];
    }
    
    if($gate_device_mode == 0)//Registration Mode
    {
        //Write code to register student with card
    }
    
    else if($gate_device_mode == 1)
    {
        $query = "Select * from students where student_card_id = {$card_uid}" ;
        $selected_card = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($selected_card) > 0)
        {
            while($row = mysqli_fetch_assoc($selected_card))
            {
                $student_id = $row['student_id'];
                $student_name = $row['student_name'];
                
                
                $enterance_log_query = "Select * from student_entrance_log where student_id = {$student_id} order by entrance_log_datetime desc";
                $selected_student = mysqli_query($conn, $enterance_log_query);
                if(!$selected_student)
                {
                    die("Query Failed " . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($selected_student) > 0)
                {
                    $firstRow = mysqli_fetch_assoc($selected_student);
                    
                    //In
                    if($firstRow['entrance_log_type'] == "OUT")
                    {
                        $entance_log_insert_query = "Insert into student_entrance_log (student_id, entrance_log_type, entrance_log_datetime, entrance_gate_id) ";
                        $entance_log_insert_query .= "Values ('{$student_id}', 'IN', now(), '{$gate_id}')";
                        $create_log = mysqli_query($conn, $entance_log_insert_query);
                        
                        echo $student_name . " IN";
                        exit();
                    }
                    
                    //OUT
                    else if($firstRow['entrance_log_type'] == "IN")
                    {
                        $entance_log_insert_query = "Insert into student_entrance_log (student_id, entrance_log_type, entrance_log_datetime,entrance_gate_id) ";
                        $entance_log_insert_query .= "Values ('{$student_id}', 'OUT', now(), '{$gate_id}')";
                        $create_log = mysqli_query($conn, $entance_log_insert_query);
                        
                        echo $student_name . " OUT";
                        exit();
                    }
                }
                
                else
                {
                    $entance_log_insert_query = "Insert into student_entrance_log (student_id, entrance_log_type, entrance_log_datetime, entrance_gate_id) ";
                    $entance_log_insert_query .= "Values ('{$student_id}', 'IN', now(), '{$gate_id}')";
                    $create_log = mysqli_query($conn, $entance_log_insert_query);
                        
                    echo $student_name . " IN";
                    exit();
                }
                
            
                //Change Student Status
                
            }
            
        }
        
        else
        {
            echo "Not Registered";
            exit();
        }
        
    }
    
}
    
?>