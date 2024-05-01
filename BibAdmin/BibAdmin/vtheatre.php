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
    <title>Theatre</title>

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

    function modalcancel() {
        $('#thId').val('');
        $('#lId').val('');
        $('#ltId').val('');
        $('#theatreName').val('');
        $('#theatreSeats').val('');
        $('#selLoc').val('');
        $('#selLayoutfile').val('');
        $('#selLayoutname').val('');
        $('#selStatus').val('');
        $('#selectedRowId').val('');
    }

    function setFilename()
    {
        $('#selLayoutname').val($('#selLayoutfile').val().replace('C:\\fakepath\\', ''));
        // console.log($('#selLayoutname').val());
    }

    function updateTheatreDetail(thID,thName,locID,locName,ltID,seat,layout,status) {
        console.log('=============');
        console.log(thID,thName,locID,ltID,seat,layout,status);
         console.log(locName);
         console.log('=============');
         var selRowindex = $('#selectedRowId').val('update');
    
        $('#thId').val(thID);
        $('#lId').val(locID);
        $('#ltId').val(ltID);

        $('#mediumModalLabel').html('Update theatre');
        $('#theatreName').val(thName);
        $('#theatreSeats').val(seat);
        $('#selLoc').val(locID);
        $('#selLayoutname').val(layout);
        $('#selStatus').val(status);

        if(status.toLowerCase()=='active'){
            $("#selStatus").val('0'); 
        }
        else if(status.toLowerCase()=='inactive'){
            $("#selStatus").val('1'); 
        }
    }

    function addTheatre() {
        console.log('addTheatre()');
        var selRowindex = $('#selectedRowId').val('add');

        $('#mediumModalLabel').html('Add new theatre');
        // modalcancel();
    }

    function updateDBWithTheatreDetails(){
        var selRowindex = $('#selectedRowId').val();
        console.log(selRowindex);
        if(selRowindex=='add'){
            console.log('Add theatre details');
            var locId = $('#selLoc').val();   
            var thName = $('#theatreName').val();
            var thSeats = $('#theatreSeats').val();
            var selLocText = $('#selLoc option:selected').text();
            var layOut = $('#selLayoutname').val();
            var selstText = $('#selStatus option:selected').text();

            console.log(locId,thName,thSeats,selLocText,layOut,selstText);

            if(locId==null || locId == "" || locId==undefined){
                console.log('Location cannot be null');
                toast('empty location ','C');
            }
            else if(thName==null || thName=="" || thName==undefined){
                console.log('thname cannot be null');
                toast('empty theatre ','C');
            }
            else if(thSeats==null || thSeats=="" || thSeats==undefined){
                console.log('thSeats cannot be null');
                toast('empty Seats','C');
            }
            else if(layOut==null || layOut=="" || layOut==undefined){
                console.log('layOut cannot be null');
                toast('empty layout','C');
            }
            else if(selstText==null || selstText=="" || selstText==undefined){
                console.log('selstText cannot be null');                
            }
            else{
                console.log('success call insert');
                $.ajax({

                        url : 'addTheatre.php',
                        type : 'POST',
                        data: { locId: locId,thStatus:selstText,thName:thName,thSeats:thSeats,thLayout:layOut },
                        success : function(data) {              
                            console.log('Success');
                            //var selRowindex = $('#selectedRowId').val(); 
                            toast(' add success','S');                              
                              
                            setTimeout(function(){ window.location.href = 'vtheatre.php';  }, 2000); 
                            modalcancel();
                                                                 
                        },
                        error : function(request,error){
                            console.log('Error '+JSON.stringify(request))
                            //$('#dbResponse').html('Error');
                            toast('ERROR !!','E');

                        }
                        });
            }

            console.log(locId,thName,thSeats,selLocText,layOut,selstText);
        }
        else if(selRowindex=='update'){
            // thId,thName,locId,locThId,thSeats,thLayout,thStatus
            console.log('Update theatre details');
            var locId = $('#selLoc').val(); 
            var thId = $('#thId').val(); 
            var ltId = $('#ltId').val(); 

            var thName = $('#theatreName').val();
            var thSeats = $('#theatreSeats').val();
            var selLocText = $('#selLoc option:selected').text();
            var layOut = $('#selLayoutname').val();
            var selstText = $('#selStatus option:selected').text();

            if(locId==null || locId == "" || locId==undefined){
                console.log('Location id cannot be null');
            }
            else if(thId==null || thId == "" || thId==undefined){
                console.log('theatre id cannot be null');
            }
            else if(ltId==null || ltId == "" || ltId==undefined){
                console.log('loc_th id cannot be null');
            }
            else if(thName==null || thName=="" || thName==undefined){
                console.log('thname cannot be null');
                toast('empty theatre ','C');
            }
            else if(selLocText==null || selLocText == "" || selLocText==undefined){
                console.log('loc name cannot be null');
                toast('empty location','C');
            }
            else if(thSeats==null || thSeats=="" || thSeats==undefined){
                console.log('thSeats cannot be null');
                toast('empty seats','C');
            }
            else if(layOut==null || layOut=="" || layOut==undefined){
                console.log('layOut cannot be null');
                toast('empty layout','C');
            }
            else if(selstText==null || selstText=="" || selstText==undefined){
                console.log('selstText cannot be null');
            }
            else{
                console.log('success call update');
                $.ajax({
                    url : 'updateTheatre.php',
                    type : 'POST', 
                    data: { locId: locId,thStatus:selstText,thName:thName,thSeats:thSeats,thLayout:layOut,thId:thId ,locThId:ltId },
                    success : function(data) {              
                        console.log('Success');
                        //var selRowindex = $('#selectedRowId').val();  
                        toast(' update success','S');                              
                            
                        setTimeout(function(){ window.location.href = 'vtheatre.php'; }, 2000);  
                        modalcancel();                                                             
                    },
                    error : function(request,error){
                        console.log('Error '+JSON.stringify(request))
                        //$('#dbResponse').html('Error');
                        toast('ERROR !!','E');
                    }
                    });
            }
            console.log(locId,thName,thSeats,selstText,layOut,thId,ltId);
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
                            <button class="au-btn-upd" type="submit">Update</button>
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
                        <a href="vlocation.php">
                            <i class="zmdi zmdi-city-alt"></i>Location</a>
                    </li>
                    <li>
                        <a class="aSelected" href="vtheatre.php">
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
                                    <i class="zmdi zmdi-city m-r-10"></i> Theatre</h2>
                            </div>
                            <div class="header-button2">
                                <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#mediumModal" onclick="addTheatre()">
                                    <i class="zmdi zmdi-plus m-r-10"></i>ADD
                                </button>
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
                        <!-- TOP CAMPAIGN-->                        
                        <div class="top-campaign ">                            
                            <div class="table-responsive">
                                <input hidden id="selectedRowId" value="na">
                                <table class="table table-top-campaign">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th hidden> thID </th>
                                            <th> Name </th>
                                            <th hidden> lID </th>
                                            <th> Location </th>
                                            <th hidden> ltID </th>
                                            <th> Seats </th>
                                            <th> Layout </th>
                                            <th> Status </th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="theatreBody">
                                    <?php
                                        include 'dbFunctions.php';
                                        $theatreData = getAllTheatres();
                                        
                                        // echo sizeof($theatreData);// $movieData);

                                        foreach ($theatreData as $key => $value) {
                                    ?>
                                        <tr>                                                   
                                            <td id="ID<?php echo $key;?>"> <?php echo ($key+1);?> </td>
                                            <td hidden id="thID<?php echo $key; ?>"> <?php echo $value['theatre_id'];?> </td>
                                            <td id="thName<?php echo $key;?>"> <?php echo $value['name'];?> </td>
                                            <td hidden id="lID<?php echo $key; ?>"> <?php echo $value['location_id'];?> </td>
                                            <td id="thLoc<?php echo $key;?>"> <?php echo $value['locname'];?> </td>
                                            <td hidden id="ltID<?php echo $key; ?>"> <?php echo $value['loc_th_Id'];?> </td>
                                            <td id="thSeats<?php echo $key;?>"> <?php echo $value['seat'];?> </td>
                                            <td id="thLayout<?php echo $key;?>"> <?php echo $value['layout'];?> </td>
                                            <td id="thStatus<?php echo $key;?>"> <?php echo $value['status'];?> </td>
                                            <td>
                                                <button id = "editTh<?php echo $key; ?>" class="item" data-toggle="modal" data-placement="top" data-target="#mediumModal"
                                                    title="Edit" onclick="updateTheatreDetail(<?php echo $value['theatre_id'];?>,
                                                    '<?php echo $value['name'];?>',
                                                    <?php echo $value['location_id'];?>,
                                                    '<?php echo $value['locname'];?>',
                                                    <?php echo $value['loc_th_Id'];?>,
                                                    <?php echo $value['seat'];?>,
                                                    '<?php echo $value['layout'];?>',
                                                    '<?php echo $value['status'];?>')">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                            </td>
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
                </div>
            </div>
            <!--  END MAIN CONTENT--> 

            <div id="snackbar"></div>

            <!-- MODAL LARGE -->
			<div class="modal fade" data-backdrop="static" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
					<div class="modal-content">
						<div class="modal-header">
                            <?php
                                
                                $locData = getActiveLocations();
                                //echo json_encode($locData);
                                $layoutData = getAllLayoutsData();
                            ?>
							<h3 class="modal-title title-1" id="mediumModalLabel"> Add new theatre </h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modalcancel()">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">                
                            <div class="form-group m-t-10">
                                <input type="hidden" id="thId">
                                <input type="hidden" id="lId">
                                <input type="hidden" id="ltId">
                                <input type="text" id="theatreName" placeholder="Theatre name" class="form-control">
                            </div>
                            <div class="form-group m-t-10">
                                <input type="text" id="theatreSeats" placeholder="Total seats" class="form-control">
                            </div>

                            <div class="form-group row m-t-10">
                                <!-- <input type="text" id="selLayout" placeholder="Layout filename" class="form-control"> -->
                                <label for="selLayout" class="col-2 m-t-10 m-l-20">Layout</label> 
                                <input type="file" id="selLayoutfile" name="filename" class="form-control col-3 m-l-10" onChange="setFilename()">
                                <input type="text" id="selLayoutname" class="form-control col-4">
                            </div>
                            
                            <div class="form-group m-t-10">                                                
                                <select name="Location" id="selLoc" class="form-control">
                                <option selected disabled value="0"> Select Location </option>
                                    <?php
                                    foreach ($locData as $key => $value)
                                    {
                                    ?>
                                    <option value=<?php echo $value['location_id']; ?>><?php echo $value['name']; ?></option>

                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group m-b-10">                                                
                                <select name="Status" id="selStatus" class="form-control">
                                    <!-- <option selected disabled> Select status </option> -->
                                    <option value="0" selected>Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>

						<div class="modal-footer">
                            <button id="btnCancel" type="button" class="btn btn-danger btn-reset" data-dismiss="modal" onclick="modalcancel()">Cancel</button>
							<button id="btnConfirm" type="button" class="btn btn-save "  onclick="updateDBWithTheatreDetails()">Confirm</button>
                        </div>
				    </div>
				</div>
			</div>
			<!-- END MODAL LARGE -->
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