<?php
    session_start();
    include("conn.php");
    if(!$_SESSION['Uid']){
        header("location: Login.php");
    }
    if(isset($_GET['pid'])){
        if($_GET['pid']!=""){
            $pid=$_GET['pid'];
            $inid=$_GET['inid'];
            // echo "<script>alert($pid)</script>";
            $upsel=mysqli_query($conn,"SELECT*from products as p inner join stock_out as s on s.product_id=p.productid where So_id=$pid");
            $fez=mysqli_fetch_array($upsel);
        }else{
            header("location: stockin.php");
        }
    }
?>

<script>
        $(document).ready(function(){
            $("#up").hide();
        });
    </script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="bootstrap.css">
    <script src="jquery-3.6.3.js"></script>
    <title>Document</title>
</head>
<body style="background: whitesmoke;">
    <div class="w3-panel w3-blue-grey"><center><h1 class="w3-jumbo w3-text-white">STOCK MANAGEMENT SYSTERM</h1></center></div>
    <div class="row" id="nav">
        <div class="col-1"></div>
        <div class="col-1"><div class="w3-card w3-blue-grey w3-round" style="height: 40px;"><a href="product.php" class="w3-text-white w3-large" style="text-decoration: none;">Products</a></div></div>
        <div class="col-1"></div>
        <div class="col-1"><div class="w3-card w3-blue-grey w3-round" style="height: 40px;"><a href="stockin.php" class="w3-text-white w3-large" style="text-decoration: none;">Stock in</a></div></div>
        <div class="col-1"></div>
        <div class="col-1"><div class="w3-card w3-blue-grey w3-round" style="height: 40px;"><a href="stockout.php" class="w3-text-white w3-large" style="text-decoration: none;">Stock out</a></div></div>
        <div class="col-1"></div>
        <div class="col-1"><div class="w3-card w3-blue-grey w3-round" style="height: 40px;"><a href="report.php" class="w3-text-white w3-large" style="text-decoration: none;">Report</a></div></div>
        <div class="col-1"></div>
        <div class="col-1"><div class="w3-card w3-blue-grey w3-round" style="height: 40px;"><a href="logout.php" class="w3-text-white w3-large" style="text-decoration: none;">Logout</a></div></div>
    </div>
    <br><br>
    <table class="w3-table" id="outtable">
        <h1 class="w3-xxxlarge"  id="o"><center>Stockout report</center></h1>
        <tr>
            <th><center><b>N<sup><u>o</u></sup></b></center></th>
            <th><center>Product Name</center></th>
            <th><center>Date</center></th>
            <th><center>Quantity</center></th>
            <th colspan="2"><center>More</center></th>
        </tr>
        <?php
            $sel=mysqli_query($conn,"SELECT*from products as p inner join stock_out as s on s.product_id=p.productid");
            $x=1;
            while($row=mysqli_fetch_array($sel)){
                $ppid=$row['So_id'];
                $inid=$row['inid'];
                $q=$row['Quantity'];
                ?>
                <tr>
                    <td><center><?=$x?></center></td>
                    <td><center><?=$row['Product_name']?></center></td>
                    <td><center><?=$row['Date']?></center></td>
                    <td><center><?=$row['Quantity']?></center></td>
                    <td><center><a href="stockout.php?pid=<?=$row['So_id']?>&&inid=<?=$row['inid']?>"><i class="fas fa-pen w3-text-blue"></i>Update</a></center></td>
                    <td><center><button onclick="del(<?=$ppid?>,<?=$inid?>,<?=$q?>)">Delete</button></center></td>
                    <script>
                        function del(x,z,y){
                            var c=confirm("Are you sure you want to delete this product");
                            if(c){
                                window.location.replace("soutdelete.php?pid="+x+"&&inid="+z+"&&q="+y);
                            }
                        }
                    </script>
                </tr>
                <?php
                $x++;
            }
        ?>
    </table>
    
    <div class="w3-display-left w3-round-xlarge w3-blue-grey w-25" style="margin-top: 5%;" id="up">
        <center>
            <form action="#" class="w-75" method="post">
                <!-- <div class="w3-display-topright"><span onclick="$('#modal').hide()" class="w3-jumbo w3-text-red">&times;</span></div> -->
                <center>
                    <h1 class="w3-xxlarge">Remove some <?=$fez['Product_name']?></h1>
                    <div class="">
                        <label for="">Product name</label><br>
                        <select name="pname" class="form-control" required>
                            <option value="<?=$fez['productid']?>"><?=$fez['Product_name']?></option>
                        </select>
                    </div><br>
                    <div class="">
                        <label for="">Date</label><br>
                        <input type="date" value="<?=$fez['Date']?>" name="date" class="form-control" required>
                    </div><br>
                    <div class="">
                        <label for="">Quantity</label><br>
                        <input type="number" value="<?=$fez['Quantity']?>" min="0" max="<?=$fezz['Quantity']?>" name="qty" class="form-control" required>
                    </div><br>
                    <div class="">
                    <div class="">
                    <input type="submit" name="outt" value="Update" class="btn btn-success w-50">
                    <a href="product.php" class="btn btn-danger w-25">Cancel</a>
                    <br>
                </center>
            </form>
            
            <?php
                if(isset($_POST['outt'])){
                    $pidd=$_POST['pname'];
                    $date=$_POST['date'];
                    $qty=$_POST['qty'];
                    $oqty=$fez['Quantity'];
                    $cdate=date('Y-m-d');
                    if($date>=$cdate){
                        $up=mysqli_query($conn,$v="UPDATE stock_in set Quantity=Quantity+$oqty-$qty,Total_price=$qty*Unit_price where St_id=$inid");
                        $uu=mysqli_query($conn,"UPDATE stock_out set `Date`='$date',Quantity=$qty where So_id=$pid");
                        if($up&&$uu){
                            // echo $v;
                            ?><script>window.location.replace("stockout.php");</script><?php
                        }else{
                            ?>
                            <script>
                                alert("Productname select valid inputs");
                            </script>
                            <?php
                        }
                    }else{
                            ?>
                        <script>
                            alert("date has passed");
                        </script>
                        <?php
                    }
                }
            ?>
            <?php
                if(isset($_POST['up'])){
                    $pidd=$_POST['pname'];
                    $date=$_POST['date'];
                    $qty=$_POST['qty'];
                    $uprice=$_POST['uprice'];
                    $cdate=date('Y-m-d');
                    if($date<=$cdate){
                        $up=mysqli_query($conn,$v="UPDATE stock_in set product_id=$pidd,`Date`='$date',Quantity='$qty',Unit_price='$uprice',Total_price=$qty*$uprice where St_id=$pid");
                        if($up){
                            // echo $v;
                            ?><script>window.location.replace("stockin.php");</script><?php
                        }else{
                            ?>
                            <script>
                                alert("Productname select valid inputs");
                            </script>
                            <?php
                        }
                    }else{
                            ?>
                        <script>
                            alert("date hasn't reached yet");
                        </script>
                        <?php
                    }
                }
            ?>
        </center>  
    </div>
    <script>
        $(document).ready(function(){
            $("#up").hide();
        });
    </script>
    <?php
        if(isset($_GET['pid'])){
            if($_GET['pid']!=""){
                ?>
                <script>
                    $(document).ready(function(){
                        $("table").css("width","75%");
                        $("table").css("margin-left","25%");
                        $("#up").show();
                        $("#addd").hide();
                    });
                </script>
                <?php
            }else{
                header("location: products.php");
            }
        }
    ?>
</body>
</html>