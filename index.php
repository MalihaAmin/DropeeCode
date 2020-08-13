<?php

session_start();
$_SESSION['pmessage'] = '';

$pos = [[]];
$color = [[]];
$bg_color = [[]];

for($c=0; $c<4; $c++){
    for($r=0; $r<4; $r++){
        $pos[$c][$r] = "";
        $color[$c][$r] = "#000000";
        $bg_color[$c][$r] = "#f4f4f4";
        $size[$c][$r]= 18;
    }
}


//create mysql connection
require('connectDB.php');

$sql ="SELECT * FROM management ORDER BY id DESC";
$result=mysqli_query($conn,$sql);
if (mysqli_num_rows($result) > 0)
  while ($row = mysqli_fetch_assoc($result)){
        $pos[$row['row']][$row['column']]=$row['text'];
        $color[$row['row']][$row['column']] = $row['color'];
        $bg_color[$row['row']][$row['column']] = $row['bg_color'];
        $size[$row['row']][$row['column']]= $row['size'];

  }




if ($_SERVER["REQUEST_METHOD"] == "POST") {

        
    
    //define other variables with submitted values from $_POST
    $text = $conn->real_escape_string($_POST['text']);
    $pcol = $conn->real_escape_string($_POST['column']);
    $prow = $conn->real_escape_string($_POST['row']);
    $tcolor = $conn->real_escape_string($_POST['tcolor']);
    $tsize = $conn->real_escape_string($_POST['size']);
    $bcolor = $conn->real_escape_string($_POST['bcolor']);

    $sql_string = "";
    if($pcol!='select' or $prow!='select'){
        if($pcol=='select' or $prow=='select'){
            $_SESSION['pmessage']="Must select both row and column";
        }
        else{
            $prow = $prow-1;
            $pcol = $pcol-1;
            $sql = "SELECT * FROM management WHERE `row`=? and `column`=?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_Select_logs";
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($result, "ss", $prow, $pcol);
                    mysqli_stmt_execute($result);
                    $resultl = mysqli_stmt_get_result($result);
                    if (!$row = mysqli_fetch_assoc($resultl)){
                        $sql_string = "`row` = \"".$prow."\",`column` = \"".$pcol."\"";
                    }
                    else{
                        $_SESSION['pmessage'].="Position Unavailable ";
                        
                    }
                }
        }
    }
    if($tsize!=""){
        if(!is_numeric($tsize)){
            $_SESSION['pmessage'].="Size must be numeric";
        }
        else{
            if($sql_string!=""){
                $sql_string.=",";
            }
            $sql_string .= "`size` = \"".$tsize."\"";

        }
    }
    if($tcolor!='select'){
        if($sql_string!=""){
            $sql_string.=",";
        }
        $sql_string .= "`color` = \"".$tcolor."\"";
    }
    if($bcolor!='select'){
        if($sql_string!=""){
            $sql_string.=",";
        }
        $sql_string .= "`bg_color` = \"".$bcolor."\"";
    }

    if($text=='select'){
        $_SESSION['pmessage']="Text must be selected first";
    }
    else{
        if($sql_string!=""){
            $sql = "UPDATE management SET ".$sql_string." where `text`=\"".$text."\"";
            if ($conn->query($sql) === true){
                header("location: index.php");
            }
            else {
                $_SESSION['pmessage'] = 'Edit Failed!';
                echo $sql;
            }
        }
        else{
            if($_SESSION['pmessage']=="")
                $_SESSION['pmessage'] = 'Must select atleast one change';
        }
    }
    
}

?>





<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
 <meta name="keywords" content="">
  <meta name="author" content="Maliha Binte Ruhul Amin">
<title>Dropee Code</title>

<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link href = "css/jquery-ui.css" rel = "stylesheet">
<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="//db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="css/form.css" type="text/css">
</head>
<body>
<header>
   <div class="container">
     <div id="branding">
      <h1><span class="highlight">Dropee</span> Code Test</h1>
    </div>
  </div>
</header>

<div class="container">
        <div class="row">
    		<div class="col-md-5">
                <form class="form" action="index.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <h1 style= 'text-align: center'>Manage Table</h1>
                <br><br>
                    <div class="alert alert-error"><?= $_SESSION['pmessage'] ?></div>
                        <label>Text:</label>
                        <select class="select-style" name="text" placeholder="Text">
                        <option value="select"> Select Text</option>
                        <option value="Dropee.com">Dropee.com</option>
                        <option value="B2B Marketplace">B2B Marketplace</option>
                        <option value="SaaS enabled marketplace">SaaS enabled marketplace</option>
                        <option value="Provide Transparency">Provide Transparency</option>
                        <option value="Build Trust">Build Trust</option>
                        </select><br><br>
                        <label>Column:</label>
                        <select class="select-style" name="column" placeholder="Column Position">
                        <option value="select">Select Column Position</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        </select><br><br>
                        <label>Row:</label>
                        <select class="select-style" name="row" placeholder="Row Position">
                        <option value="select">Select Row Position</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        </select><br><br>
                        <label>Text Color:</label>
                        <select class="select-style" name="tcolor" placeholder="Text Color">
                        <option value="select">Select Style</option>
                        <option value="#8B0000">Red</option>
                        <option value="#7CFC00">Green</option>
                        <option value="#FFFF00">Yellow</option>
                        <option value="#FFFFFF">White</option>
                        <option value="#000000">Black</option>
                        </select><br><br>
                        <label>Text Size:</label>
                        <input type="text" placeholder="Enter Text Size in Pixel" name="size"/>
                        <br>
                        <label>Back-Ground Color:</label>
                        <select class="select-style" name="bcolor" placeholder="Row Position">
                        <option value="select">Select Color</option>
                        <option value="#8B0000">Red</option>
                        <option value="#7CFC00">Green</option>
                        <option value="#FFFF00">Yellow</option>
                        <option value="#FFFFFF">White</option>
                        <option value="#000000">Black</option>
                        </select><br><br><br>
                <input type="submit" value="Make Changes" name="edit" class="btn btn-block btn-primary" />
                </form>
                <br>
            </div>
            <div class="col-md-7">
                <h2 style= 'text-align: center'>Table Output</h2>
                <br><br>
                <table class="table table-bordered">
                <col width="50px" min-height= 50px />
                <col width="50px" min-height= 50px/>
                <col width="50px" />
                <col width="50px" />
                    <tbody>
                    <?php 
                    for($c=0; $c<4; $c++){
                        echo "<tr>";
                        for($r=0; $r<4; $r++){
                            echo "<td style=\"color:".$color[$c][$r]."; font-size: ".$size[$c][$r]."px\"><div style=\"min-height: 60px; background-color: ".$bg_color[$c][$r]."\">";
                            echo $pos[$c][$r];
                            echo "</div></td>";
                        }
                        echo "</tr>";
                    }
                    
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>



</body>
</html>