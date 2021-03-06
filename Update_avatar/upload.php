
<?php 
// Include the database configuration file 
include_once 'dbConfig.php'; 
     
if(isset($_POST['submit'])){ 
    // File upload configuration 
    $targetDir = "uploads/"; 
    $allowTypes = array('jpg','png','jpeg','gif','PNG','JPG'); 
     
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
    if(!empty($fileNames))
    { 
        foreach($_FILES['files']['name'] as $key=>$val){ 
            // File upload path 
            $fileName = basename($_FILES['files']['name'][$key]); 
            $targetFilePath = $targetDir . $fileName; 
             
            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if(in_array($fileType, $allowTypes)){ 
                // Upload file to server 
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                    // Image db insert sql 
                    $insertValuesSQL .= "('".$fileName."', NOW()),"; 
                }else{ 
                    $errorUpload .= $_FILES['files']['name'][$key].' | '; 
                } 
            }else{ 
                $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
            } 
        } 
         
        // Error message 
        $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
        $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
        $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
         
        if(!empty($insertValuesSQL))
        { 
            $insertValuesSQL = trim($insertValuesSQL, ','); 
            // Insert image file name into database 

            $insert1 = $db->query("DELETE  FROM avatar"); 
            if($insert1)
            {
                $insert = $db->query("INSERT INTO avatar (file_name, upload_on) VALUES $insertValuesSQL"); 
                if($insert)
                { 
                    $statusMsg = "C???p nh???t ???nh th??nh c??ng .".$errorMsg; 
                    header("location: changeAvater.php?showTB= $statusMsg"); 
                }else{ 
                    $statusMsg = "???? x???y ra l???i khi t???i file - Vui l??ng ki???m tra l???i"; 
                    header("location: changeAvater.php?showTB= $statusMsg"); 
                } 
            }            
        }else{ 
            $statusMsg = "T???i l??n th???t b???i ! L???i : ".$errorMsg; 
            header("location: changeAvater.php?showTB= $statusMsg"); 
        } 
    }else{ 
        $statusMsg = 'B???n ch??a ch???n file n??o !'; 
        header("location: changeAvater.php?showTB= $statusMsg"); 
    } 
} 
 

?>

<?php

