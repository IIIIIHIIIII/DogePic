<?php
class func {
public  function uploadFile($name,$tmp){
    $func = new func();
    $dir = "pics/";
    $current_file = $dir . basename($name);
    $filetor = $func->fileRandName() .".". pathinfo($current_file,PATHINFO_EXTENSION);

         $filename = $dir . $filetor;
         if (move_uploaded_file($tmp, $filename)) { 
            return $filetor;
         }
}

public  function fileRandName() {
    $func = new func();
    
    while(true) {
    $tmp = "";
    $data = array(array(48,57),array(65,90),array(97,122));
        for($i = 0; $i <= 5; $i++) {
         $value = rand(0,2);
         $tmp = $tmp . chr(rand($data[$value][0],$data[$value][1]));
        }
        
        if(!$func->getFileBase($tmp)) {
            return $tmp;   
    }
       
       }
}
    
public  function getFileBase($name){
    $base = array("png","gif","jpg","jpeg");
    for($i = 0;$i < count($base) - 1;$i++) {
        if(file_exists("pics/".$name.".".$base[$i])) {
           return  true;
        }
    }
    return false;
}
    
public  function addTodb($address,$file) {
    $func = new func();
    $con = mysqli_connect($func->dbaddress,$func->dbuser,$func->dbpass,$func->db);
    if($con->connect_error) {
     echo "Something went wrong."; 
    }
    mysqli_query($con,"insert into picdata(name,address) values('".$file."','".$address."')");
    $con->close();
}
    
public function proxy() {
    // http://stackoverflow.com/questions/858357/detect-clients-with-proxy-servers-via-php
    $proxy_headers = array(
        'HTTP_VIA',
        'HTTP_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED_FOR_IP',
        'VIA',
        'X_FORWARDED_FOR',
        'FORWARDED_FOR',
        'X_FORWARDED',
        'FORWARDED',
        'CLIENT_IP',
        'FORWARDED_FOR_IP',
        'HTTP_PROXY_CONNECTION'
    );
    foreach($proxy_headers as $x){
        if (isset($_SERVER[$x])) return true;
    }
        return false;
}
    
public function viewedBefore($file,$ip) {
    $func = new func();
    $con = mysqli_connect($func->dbaddress,$func->dbuser,$func->dbpass,$func->db);
    if($con->connect_error) {
     echo "Something went wrong."; 
    }
    
    $result = mysqli_query($con,"select * from picip where name='".$file."' and aes_decrypt(ip,'".$func->aeskey."') = '".$ip."'");
    if($result->fetch_array()){
        $con->close();
        return true;   
    }
    
    $con->close();
    return false;
}
    
    
public function addip($file,$ip) {
    $func = new func();
    $con = mysqli_connect($func->dbaddress,$func->dbuser,$func->dbpass,$func->db);
    if($con->connect_error) {
     echo "Something went wrong."; 
    }
        
    mysqli_query($con,"insert into picip(name,ip) values('".$file."',aes_encrypt('".$ip."','".$func->aeskey."'))") or die("fff");
     $con->close();
}
    
public function upview($file) {
    $func = new func();
    $con = mysqli_connect($func->dbaddress,$func->dbuser,$func->dbpass,$func->db);
    if($con->connect_error) {
     echo "Something went wrong."; 
    } 
    mysqli_query($con,"update picdata set hits = hits + 1 where name ='".$file."'");
     $con->close();
}    
    
private $dbaddress = "";
private $dbuser = "";
private $dbpass = "";
private $db = "";
private $aeskey = "";

}
?>