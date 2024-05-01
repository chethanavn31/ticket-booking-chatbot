<?php
session_start();
if (empty($_SESSION['adminID'])) {
   header("Location: vlogin.php"); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Bookings</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    
    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="vendor/vector-map/jqvmap.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css?v=1" rel="stylesheet" media="all">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"> 
    </script>

    <script type="text/javascript">
    function setModaltxt(btnID,bookingId) {
        if (btnID == 'btnCancel') {
            $('#txtDisplay').html('Are you sure to cancel this booked request ?');
            $('#bkId').val(bookingId);
            $('#bkStatus').val('Cancelled');
        }
        else if (btnID == 'btnConfirm') {
            $('#txtDisplay').html('Are you sure to confirm this booked request ?');
            $('#bkId').val(bookingId);
            $('#bkStatus').val('Confirmed');
        }
    }

    function killSession() {
        $.ajax({
            url : 'adminSignout.php',
            type : 'POST', 
            data: { },
            success : function(data) {              
                    console.log('Success');
                    toast('Signed out','S');
                    setTimeout(function(){ window.location.href = 'vlogin.php';  }, 500);                                                   
                },
                error : function(request,error){                       
                    toast('ERROR !!','E');
                }                           
        });       
    }

    function updateDBWithAdminPassword() {
        var newpwd = $('#txtPwd').val();
        var confpwd = $('#txtconfPwd').val();
        var adId = $('#adminId').val();
        if(newpwd==null || newpwd == "" || newpwd==undefined){
            console.log('bkID id cannot be null');
            toast('Password cannot be empty','C');
        }
        else if (newpwd.length <= 8) {
            $('#txtPwd').val('');
            $('#txtconfPwd').val('');
            toast('Password should be above 8 characters','C'); 
        }
        else if(confpwd==null || confpwd == "" || confpwd==undefined){
            console.log('bkStatus cannot be null');
            toast('Confirm Password cannot be empty','C');
        }        
        else if (newpwd != confpwd) {
            $('#txtPwd').val('');
            $('#txtconfPwd').val('');
            toast('Password does not match','C');            
        }
        else {
            $.ajax({
                url : 'updateAdmin.php',
                type : 'POST',
                data: { adminID : adId, password : confpwd },
                success : function(data) {              
                    console.log('Success');
                    $('#txtPwd').val('');
                    $('#txtconfPwd').val('');
                    toast(' update success','S');
                                                                           
                },
                error : function(request,error){
                    console.log('Error '+JSON.stringify(request));
                    toast('ERROR !!','E');
                }
            });  
        }                  
    }    

    function toast(text,status) {
        var x = document.getElementById("snackbar");
        x.innerHTML = text;
        if (status == 'S') {
            x.className = "show success";
        }
        else if (status == 'E') {
            x.className = "show error";
        }       
        else if (status == 'C') {
            x.className = "show check";
        } 
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);
    }

    function updateDBWithBookingStatus() {
        var bkID = $('#bkId').val();
        var bkStatus = $('#bkStatus').val();
        if(bkID==null || bkID == "" || bkID==undefined){
            console.log('bkID id cannot be null');
        }
        else if(bkStatus==null || bkStatus == "" || bkStatus==undefined){
            console.log('bkStatus cannot be null');
            toast('booking status not sleccted','C');
        }
        else {
            $.ajax({
                url : 'updateBooking.php',
                type : 'POST',
                data: { bkID:bkID, bkStatus:bkStatus },
                success : function(data) {              
                    console.log('Success');
                    toast(' update success','S');
                    setTimeout(function(){ window.location.href = 'vbookings.php'; }, 2000);  
                                                                                
                },
                error : function(request,error){
                    console.log('Error '+JSON.stringify(request));
                    toast('ERROR !!','E');
                }
            });     
        }
        // console.log(bkID);
        // console.log(bkStatus);           
               
    }    
    </script>    
</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar2 js-scrollbar1">
            <div class="account2">
                <div class="image img-cir img-120">
                    <img src="images/icon/admin.jpg" alt="John Doe" />
                </div>
                <input type="hidden" id="adminId" value="<?php echo $_SESSION["adminID"];?>">
                <h4 class="name"> <?php echo $_SESSION["name"];?> </h4>
                <h1 class="email"> <?php echo $_SESSION["email"];?> </h1>
                <button id="btnKill" type="button" onclick="killSession()">Sign out</button>
            </div>
            <nav class="navbar-sidebar2">
                <ul class="list-unstyled navbar__list">
                    <li class="active has-sub">
                        <a class="js-arrow" href="#">
                            <i class="zmdi zmdi-lock"></i>
                            Update Password
                            <span class="arrow">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <div class="form-group m-t-20">
                                <input class="au-input au-input--upd" type="password" name="password"
                                    placeholder="New password" id="txtPwd">
                            </div>
                            <div class="form-group m-b-20">
                                <input class="au-input au-input--upd" type="password" name="password"
                                    placeholder="Confirm new password" id="txtconfPwd">
                            </div>
                            <button class="au-btn-upd" type="button" id="btnPwdUpd" onclick="updateDBWithAdminPassword()">Update</button>
                        </ul>
                    </li>
                    <li>
                        <a href="vdashboard.php">
                            <i class="zmdi zmdi-home"></i>Dashboard</a>
                    </li>
                    <li>
                        <a class="aSelected" href="vbookings.php">
                            <i class="zmdi zmdi-book"></i>View Bookings</a>
                    </li>
        
                    <li>
                        <a href="vlocation.php">
                            <i class="zmdi zmdi-city"></i>Location</a>
                    </li>
                    <li>
                        <a href="vtheatre.php">
                            <i class="zmdi zmdi-city-alt"></i>Theatre</a>
                    </li>
                    <li>
                        <a href="vmovie.php">
                            <i class="zmdi zmdi-movie"></i>Movie</a>
                    </li>
                    <li>
                        <a href="vshowtimings.php">
                            <i class="zmdi zmdi-timer"></i>Show Timings</a>
                    </li>
                    <li>
                        <a href="vseats.php">
                            <i class="zmdi zmdi-tag"></i>Seats</a>
                    </li>
                    
                </ul>
            </nav>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop2">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <div class="overview-wrap">
                                <h2 class="title-1">
                                    <i class="zmdi zmdi-book m-r-10"></i> Bookings </h2>
                            </div>
                            <div class="header-button2">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row ">
                            <?php
                                include 'dbFunctions.php';
                                $bookingData = getAllBookings();                                                
                        
                                foreach ($bookingData as $key => $value) {
                                    $thNameReplaced = str_replace ("'","\'",$value['theatre']);
                                    $d = $value['date'];
                                    $dt = new DateTime($d);

                                    $date = $dt->format('d-m-Y');
                            ?>                      
                            <!-- BOOKING CARD-->
                            <!-- booking_id, date, seatNumber, amount, status, movie, theatre, showname, ftime, location, user -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <img class="card-img-top" src="<?php echo $value['movie'];?>" alt="Card image cap" style="width:fit-content;height:200px;">
                                    <div class="card-body cb-bg">
                                        <div class="cb-content">
                                            <input type="hidden" id="bookingId<?php echo $key;?>" value="<?php echo $value['booking_id'];?>"> 
                                            <label class="" id=""> <?php echo $value['theatre'];?> , <?php echo $value['location'];?> </label>
                                            <div style="border-bottom: 2px solid white;" > </div>
                                            <label class="p-t-5" id=""> <?php echo $value['showname'];?> @ <?php echo $value['ftime'];?></label> </br>
                                            <label> BOOKED ON : <?php echo $date;?> </label> </br>
                                            <label>  <?php echo $value['seatNumber'];?> </label> </br>
                                            <label> TOTAL :  ₹ <?php echo $value['amount'];?> </label> </br>                                            
                                            <?php
                                                if ($value['status'] == 'Booked') {
                                            ?>  
                                                <div class="labelStatus" style="background:darkorange;"> <?php echo $value['status'];?> </div> 
                                            <div class="cb-footer">
                                                <button id="btnCancel" type="button" class="btn btn-danger btn-reset" data-toggle="modal" data-target="#staticModal" onclick="setModaltxt(this.id,<?php echo $value['booking_id'];?>)">Cancel</button>
                                                <button id="btnConfirm" type="button" class="btn btn-confirm" data-toggle="modal" data-target="#staticModal" onclick="setModaltxt(this.id,<?php echo $value['booking_id'];?>)">Confirm</button>
                                            </div> 
                                            <?php
                                                }
                                                else if ($value['status'] == 'Confirmed') {
                                            ?>  
                                                <div class="labelStatus" style="background:green;"> <?php echo $value['status'];?> </div> 
                                            <?php
                                                }
                                                else if ($value['status'] == 'Cancelled') {
                                            ?>
                                                <div class="labelStatus" style="background:red;"> <?php echo $value['status'];?> </div> 
                                            <?php
                                                }
                                            ?>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  END BOOKING CARD--> 
                            <?php
                                }
                            ?>  
                        </div>                   
                    </div>
                </div>              
            </div>
            <!-- END MAIN CONTENT -->

            <div id="snackbar"></div>

            <!-- MODAL  -->
			<div class="modal fade" id="staticModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- <h5 class="modal-title" id="staticModalLabel">Static Modal</h5> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="color:black; padding:10px;">
                            <p id="txtDisplay"> </p>
                            <input type="hidden" id="bkStatus"> </br>
                            <input type="hidden" id="bkId"> 
                            
                        </div>
                        <div class="modal-footer" style="padding:0px 100px;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button id="modalYes" type="button" class="btn btn-primary" onclick=updateDBWithBookingStatus()>Yes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL  -->            
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE WRAPPER-->

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>
    <script src="vendor/vector-map/jquery.vmap.js"></script>
    <script src="vendor/vector-map/jquery.vmap.min.js"></script>
    <script src="vendor/vector-map/jquery.vmap.sampledata.js"></script>
    <script src="vendor/vector-map/jquery.vmap.world.js"></script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->