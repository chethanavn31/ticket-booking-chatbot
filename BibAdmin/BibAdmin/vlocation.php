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
    <title>Location</title>

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
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>

    <script type="text/javascript">
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

    function modalclear() {
        $('#edLocId').val('');
        $('#edlocName').val('');
        $('#edselectStatus').val('');
        $('#selectedRowId').val('na');
    }
    
    function updateLocationDetail(locId,locName,locStatus,rowId,rowSize) {
        console.log(locId);
        console.log(locName);
        console.log(locStatus);
        console.log(rowId);
        $('#selectedRowId').val(rowId);
        $('#addUpdateLoc').html('Update Location');
        $('#edlocName').val(locName);
        $('#edLocId').val(locId);
        $('#totalRowsId').val(rowSize);
        if(locStatus.toLowerCase()=='active'){
            $("#edselectStatus").val('0'); 
        }
        else if(locStatus.toLowerCase()=='inactive'){
            $("#edselectStatus").val('1'); 
        }
    }

    function updateDBWithNewLoc() {
        var selRowindex = $('#selectedRowId').val();
        console.log(selRowindex);
        var locName = $('#edlocName').val();
        var locId = $('#edLocId').val();
        var locStatus = $('#edselectStatus option:selected').text();
        console.log(locId);
        console.log(locName);
        console.log(locStatus);
        if(selRowindex=='na') {
            console.log('Add row');
            if(locName==null || locName == "" || locStatus==undefined) {
                console.log('Name not entered');
                toast('empty location name','C');
                $('#dbResponse').html('Empty location');
            }
            else{
                $.ajax({
                        url : 'addLocation.php',
                        type : 'POST',
                        data: { locName: locName,locStatus:locStatus },
                        success : function(data) {              
                            //alert('Data: '+data);
                            console.log('Success');
                            var resData = JSON.parse(data);
                            var lastInsertedId = resData.id;

                            console.log($('#edlocName').val());
                        //var selRowindex = $('#selectedRowId').val();

                            $('#lName'+selRowindex).html($('#edlocName').val());
                            // $('#locStatus'+selRowindex).html($('#edselectStatus option:selected').text());
                            $('#selectedRowId').val('na');
                            $('#addUpdateLoc').html('Add new location');
                            $('#edlocName').val('');
                            $('#edLocId').val('');
                            toast(' add success','S');
                            setTimeout(function(){ window.location.href = 'vlocation.php'; }, 2000);  
                        },
                        error : function(request,error)
                        {
                            console.log('Error '+JSON.stringify(request))
                            //alert("Request: "+JSON.stringify(request));
                            toast('ERROR !!','E');
                        }
                    });
                }
        }
        else{
            if(locName=='') {
                console.log('Name not entered');
                toast('empty location name','C');
            }
            else{
                $.ajax({

                        url : 'updateLocation.php',
                        type : 'POST',
                        data: { locId: locId, locName: locName,locStatus:locStatus },
                        success : function(data) {              
                            //alert('Data: '+data);
                            console.log('Success');
                            toast(' update success','S');

                            console.log($('#edlocName').val());
                            //var selRowindex = $('#selectedRowId').val();

                            $('#lName'+selRowindex).html($('#edlocName').val());
                            // $('#locStatus'+selRowindex).html($('#edselectStatus option:selected').text());
                            $('#selectedRowId').val('na');
                            $('#addUpdateLoc').html('Add new location');
                            $('#edlocName').val('');
                            $('#edLocId').val('');
                            setTimeout(function(){ window.location.href = 'vlocation.php'; }, 2000);  
                        },
                        error : function(request,error)
                        {
                            console.log('Error '+JSON.stringify(request))
                            //alert("Request: "+JSON.stringify(request));
                            toast('ERROR !!','E');

                        }
                    });
                }
            }

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
                        <a href="vbookings.php">
                            <i class="zmdi zmdi-book"></i>View Bookings</a>
                    </li>
                   
                    <li>
                        <a class="aSelected" href="vlocation.php">
                            <i class="zmdi zmdi-city-alt"></i>Location</a>
                    </li>
                    <li>
                        <a href="vtheatre.php">
                            <i class="zmdi zmdi-city"></i>Theatre</a>
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
                                    <i class="zmdi zmdi-city-alt m-r-10"></i> Location</h2>
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
                        <div class="row">
                            <div class="col-lg-7">
                                <!-- TOP CAMPAIGN-->
                                <div class="top-campaign">
                                    <div class="table-responsive">
                                        <table class="table table-top-campaign">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th hidden>dbID </th>
                                                    <th> Name </th>
                                                    <th> Status </th>
                                                    <th> </th>
                                                </tr>
                                            </thead>
                                            <tbody id="locbody">
                                                <?php
                                                include 'dbFunctions.php';

                                                $locationData = getAllLocationsData();
                                            //echo json_encode($movieData);

                                            foreach ($locationData as $key => $value) {

                                                ?>
                                                <tr>
                                                <td  id="locID<?php echo $key;?>"><?php echo ($key+1);?></td>
                                                    <td hidden id="dbID<?php echo $key;?>"><?php echo $value['location_id'];?></td>
                                                    <td id="lName<?php echo $key;?>"> <?php echo $value['name'];?> </td>
                                                    <td id="locStatus<?php echo $key;?>"> <?php echo $value['status'];?> </td>
                                                    <td>
                                                        <button id = "editLoc<?php echo $key; ?>" class="item" data-toggle="tooltip" data-placement="top"
                                                            title="Edit" onclick="updateLocationDetail(<?php echo $value['location_id'];?>,
                                                            '<?php echo $value['name'];?>',
                                                            '<?php echo $value['status'];?>',<?php echo $key;?>,<?php echo sizeof($locationData); ?>)">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button></td>
                                                </tr>
                                                <?php
                                            }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  END TOP CAMPAIGN-->
                            </div>

                            <!-- CARD-->
                            <div class="col-lg-5">
                                <div class="card">
                                    <div class="card-header modal-header">
                                        <h3 class="title-1" id="addUpdateLoc"> Add new location </h3>                                    
                                    </div>
                                    <div class="card-body">                                            
                                        <div class="form-group m-t-10">
                                            <input type="hidden" id="edLocId">
                                            <input type="hidden" id="selectedRowId" value="na">
                                            <input type="hidden" id="totalRowsId">
                                            <input type="text" id="edlocName" placeholder="City name" class="form-control">
                                        </div>
                                        <div class="form-group m-b-10">
                                            <select name="Status" id="edselectStatus" class="form-control">
                                                <!-- <option selected disabled> Select status </option> -->
                                                <option value="0" selected>Active</option>
                                                <option value="1">Inactive</option>
                                            </select>
                                        </div>
                                        <button id="btnSave" type="button" class="btn btn-save" onclick="updateDBWithNewLoc()">
                                            <i class="fa fa-save"></i>&nbsp; Save
                                        </button>
                                        <button id="btnReset" type="button" class="btn btn-danger btn-reset m-l-20" onclick="modalclear()">
                                            <i class="fa fa-eraser"></i>&nbsp; Reset
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- END CARD-->
                        </div>
                    </div>
                </div>
            </div>
            <!--  END MAIN CONTENT--> 

            <div id="snackbar"></div>

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