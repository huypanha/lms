<?php
    // if have data
    if(isset($_POST['id'])){
        // import db
        require '../../config/db.php';

        // start sesstion to get current user info
        session_start();

        $user = json_decode($_SESSION['user']);

        $gender = $_POST['gender'];
        $id = $_POST['id'];

        try {
            // connect to db
            $db = DB::Connect();

            // create query
            $sql = "UPDATE user SET gender='$gender', updated_by=".$user->userId.", updated_date=NOW() WHERE user_id=$id";
            $stmt = $db->prepare($sql);
            $stmt->execute();

            // update user info
            $newInfo = json_decode($_SESSION['user'], true); // decode JSON to array
            $newInfo['gender'] = $gender; // update to new profile image
            $newInfo['updatedDate'] = date("Y-m-d H:i:s"); // update the updated date
            $_SESSION['user'] = json_encode($newInfo); // save to session

            // return result
            echo json_encode(array(
                "status"=>1,
                "data"=>"Updated gender successfully",
            ));
        } catch(PDOException $ex){
            // return error
            echo json_encode(array(
                "status"=>0,
                "data"=> $ex->getMessage(),
            ));
        }
    }
?>