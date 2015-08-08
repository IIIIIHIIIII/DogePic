<?php
    require_once('func.php');
    $func = new func();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>DogePic</title>
        
        <script>
            function unhide() {
                document.getElementById("outimage").hidden = false;
                document.getElementById("show").hidden = true;
            }
        </script>
        <style>
            .image {
                width:310px;
                height:30px;
                background-image:url("image.png");
                background-repeat:no-repeat;
            }
            
            .image input {
                width:310px;
                height:30px;
                overflow:hidden;
                opacity:0;
                display:block;
            }
            
            .address input {
                width:300px;
                height:30px;
            }
            
            .container {
                padding-left:25%;
                padding-top:10%;
                width:500px;
            }
        </style>
    </head>

    <body>
    
        <div class="container">
            <?php if(!isset($_GET["image"]) and !isset($_POST["address"])) { ?>
            <form method="post" enctype="multipart/form-data" action="<?php $PHP_SELF ?>">      
                <dd><div class="address"><input type="text" id="address" name="address" placeholder="Your doge address goes here..." required></div><br>
                <dd><div class="image"><input type="file" id="image" name="image"></div></dd><br>
                <dd style="padding-left:17%;"><input type="radio" name="tag" value="SFW" checked>SFW <input type="radio" name="tag" value="NSFW">NSFW</dd><br>
                <dd style="padding-left:23%;"><input type="submit" value="Upload"></dd>
            </form>
            <?php } else if(isset($_GET["image"]) and !isset($_POST["address"])) {
        if($func->checktag($_GET["image"])) { ?>
                <input id = "show" type="button" value="Click here to see NSFW image." onclick="unhide()"/>
                <img id = "outimage" src="<?php echo "pics/".$_GET["image"]?>" width="700px" hidden/>
            <?php }else { ?>
                <img id = "outimage" src="<?php echo "pics/".$_GET["image"]?>" width="700px"/>
            <?php 
        }
        if(!$func->proxy()) {
        if(!$func->viewedBefore($_GET["image"],$_SERVER["REMOTE_ADDR"])) {
            $func->upview($_GET["image"]);
            $func->addip($_GET["image"],$_SERVER["REMOTE_ADDR"]);
        }
    }                                                   
                } else {
                $address = $_POST["address"];
                $tag = $_POST["tag"];
                $randomFilename = $func->uploadFile($_FILES["image"]["name"],$_FILES["image"]["tmp_name"]);
                $func->addTOdb($address,$randomFilename,$tag);
            ?>
                <label>Image url :</label>
                <input style="width:400px;height:30px;" type="text" value="<?php echo "index.php?image=" . $randomFilename?>"/>
                       <?php unset($_POST["address"]);} ?>
            
        </div>
    
    </body>


</html>