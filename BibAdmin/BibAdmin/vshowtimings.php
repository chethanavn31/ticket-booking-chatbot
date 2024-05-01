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
    <title>Showtimings</title>

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

       
    function updateShowDetail(shID,shType,shFrom,shTo,thName, shStatus) {
        // clear();
        console.log('=============');
        console.log(shID,shType,shFrom,shTo,thName, shStatus);
        console.log('=============');
        var selRowindex = $('#selectedRowId').val('update');
        
        $('#showId').val(shID);
        $('#shTypeUpd').html(shType);
        $('#selFromUpd').val(shFrom.split(' ')[1]);
        var ftime = shFrom.split(' ')[0];
        $('#fromHUpd').val(ftime.split('.')[0]);
        $('#fromMUpd').val(ftime.split('.')[1]);

        $('#selToUpd').val(shTo.split(' ')[1]);
        var ttime = shTo.split(' ')[0];
        $('#toHUpd').val(ttime.split('.')[0]);
        $('#toMUpd').val(ttime.split('.')[1]);
        $('#thName').html(thName);
        // $('#selStatusUpd').val(shStatus);

        console.log('<<<<<<<<<<<<<<<<<<<<<<<<<<'+shStatus);
        
        if(shStatus.toLowerCase()=='active'){
            $("#selStatusUpd").val('0'); 
        }
        else if(shStatus.toLowerCase()=='inactive'){
            $("#selStatusUpd").val('1'); 
        }           
    }

    function addShow() {
        console.log('addTheatre()');
        var selRowindex = $('#selectedRowId').val('add');

        // $('#mediumModalLabel').html('Add new showtiming');
        // clear();
    }

    function updateDBWithShowDetails() {
        var selRowindex = $('#selectedRowId').val();
        console.log(selRowindex);
        if(selRowindex=='add') {
            var thID = $('#selTheatre').val();
            var shTypeSize = $('#showTimesSize').val();
            console.log('AAAAAAAAAAAAAAAAAAAAAAA');
            console.log(shTypeSize);
            
            var showAndTimings = [];
            var shType='AA';
            for (var i=0;i<shTypeSize;i++){
                var showStatus='';
                console.log('------------',$('#showType' + i), $('#showType' + i).is(":checked"))
                if($('#showType' + i).is(":checked")){
                    showStatus='Active';
                    var showTimeF = $('#fromH'+i).val()+'.'+$('#fromM'+i).val()+' '+$('#selFrom'+i).val();
                    var showTimeT = $('#toH'+i).val()+'.'+$('#toM'+i).val()+' '+$('#selTo'+i).val();
                    console.log('#showType'+i);
                    console.log('=====================================');
                    console.log(showTimeF, showTimeT,showStatus);
                    console.log('=====================================');
                    var showAndTimingsObj = {
                        showType:$('#showType'+i).val(),showTimeF:showTimeF,showTimeT:showTimeT,showStatus:showStatus
                    }

                    showAndTimings.push(showAndTimingsObj);
                }                   
            }

            console.log(thID);
            console.log(JSON.stringify(showAndTimings));

            console.log('Add showtiming details');
            
            if(thID==null || thID == "" || thID==undefined){
                console.log('thID cannot be null');
                toast('theatre empty','C');
            }
            else if(shType==null || shType=="" || shType==undefined){
                console.log('shType cannot be null');
                toast('show type empty','C');
            }
            else if(showTimeF==null || showTimeF=="" || showTimeF==undefined){
                console.log('showTimeF cannot be null');
                toast('show from empty','C');
            }
            else if(showTimeT==null || showTimeT=="" || showTimeT==undefined){
                console.log('showTimeT cannot be null');
                toast('show to empty','C');
            }
            else{
                console.log('success call insert');
                $.ajax({

                    url : 'addShowtiming.php',
                    type : 'POST',
                    data: { thId:thID, showTimeData:JSON.stringify(showAndTimings)},
                    success : function(data) { 
                        console.log(JSON.stringify(data));             
                        console.log('Success');
                        var selRowindex = $('#selectedRowId').val();
                        toast(' add success','S');                              
                        setTimeout(function(){ window.location.href = 'vshowtimings.php'; }, 2000);                                     
                    },
                    error : function(request,error){
                        console.log('Error '+JSON.stringify(request))
                        //$('#dbResponse').html('Error');
                        toast('ERROR !!','E');

                    }
                });
            }                
        }
        else if(selRowindex=='update'){
            // var thID = $('#selTheatreUpd').val();
            var shID = $('#showId').val();
            var fromTime = $('#fromHUpd').val() +'.'+$('#fromMUpd').val()+' '+$('#selFromUpd').val();
            console.log('ff',fromTime);
            var toTime = $('#toHUpd').val() +'.'+$('#toMUpd').val()+' '+$('#selToUpd').val();
            var shStatus = $('#selStatusUpd option:selected').text();
            console.log('tt================',shStatus);


            console.log('update showtiming details');
            
            // if(thID==null || thID == "" || thID==undefined){
            //     console.log('thID cannot be null');
            // }
            // else
            if(shID==null || shID=="" || shID==undefined){
                console.log('shType cannot be null');
                toast('show type empty','C');
            }
            else if(fromTime==null || fromTime=="" || fromTime==undefined){
                console.log('fromTime cannot be null');
                toast('show from empty','C');
            }
            else if(toTime==null || toTime=="" || toTime==undefined){
                console.log('toTime cannot be null');
                toast('show to empty','C');
            }
            else if(shStatus==null || shStatus=="" || shStatus==undefined){
                console.log('selstText cannot be null');
            }
            else{
                console.log('success call update');
                console.log(JSON.stringify({shID:shID, shFrom:fromTime, shTo:toTime, shStatus:shStatus }));
                $.ajax({
                    url : 'updateShowtiming.php',
                    type : 'POST', 
                    data: {shID:shID, shFrom:fromTime, shTo:toTime, shStatus:shStatus },
                    success : function(data) {              
                        console.log('Success');
                        toast(' update success','S');                              
                        setTimeout(function(){ window.location.href = 'vshowtimings.php'; }, 2000);                                     
                    },
                    error : function(request,error){
                        console.log('Error '+JSON.stringify(request))
                        //$('#dbResponse').html('Error');
                        toast('ERROR !!','E');

                    }
                });
            }

            console.log(shID,fromTime,toTime,shStatus);
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
                        <a class="aSelected" href="vshowtimings.php">
                            <i class="zmdi zmdi-timer"></i>Showtimings</a>
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
                                    <i class="zmdi zmdi-timer m-r-10"></i> Showtimings</h2>
                            </div>
                            <div class="header-button2">
                                <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#mediumModal" onclick="addShow()">
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
                        <div class="top-campaign">                        
                            <div class="table-responsive">
                                <input hidden id="selectedRowId" value="na">
                                <table class="table table-top-campaign">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th hidden> shID </th>                                                    
                                            <th> Theatre </th>
                                            <th> Show type </th>
                                            <th> Show from </th>
                                            <th> Show to </th>
                                            <th> Status </th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="showtimingBody">
                                        <?php
                                            include 'dbFunctions.php';
                                            $showData = getAllShowtimings();                                                
                                    
                                            foreach ($showData as $key => $value) {

                                                $thNameReplaced = str_replace ("'","\'",$value['name']);
                                        ?>
                                        <tr>
                                            <td id="ID<?php echo $key;?>"> <?php echo ($key+1);?> </td>
                                            <td hidden id="thID<?php echo $key; ?>"> <?php echo $value['theatre_id_sh'];?> </td>
                                            <td id="thName<?php echo $key;?>"> <?php echo $value['name'];?> </td>
                                            <td hidden id="shID<?php echo $key; ?>"> <?php echo $value['show_id'];?> </td>
                                            <td id="shType<?php echo $key;?>"> <?php echo $value['type'];?> </td>
                                            <td id="shFrom<?php echo $key;?>"> <?php echo $value['fromTime'];?> </td>
                                            <td id="shTo<?php echo $key;?>"> <?php echo $value['toTime'];?> </td>
                                            <td id="shStatus<?php echo $key;?>"> <?php echo $value['status'];?> </td>
                                            <td>
                                                <button id = "editShow<?php echo $key; ?>" class="item" data-toggle="modal" data-placement="top" data-target="#mediumModalUpdate"
                                                    title="Edit" onclick="updateShowDetail(<?php echo $value['show_id'];?>,
                                                    '<?php echo $value['type'];?>',
                                                    '<?php echo $value['fromTime'];?>',
                                                    '<?php echo $value['toTime'];?>',
                                                    '<?php echo $thNameReplaced;?>',
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

             <!-- MODAL ADD -->
			<div class="modal fade" data-backdrop="static" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
					<div class="modal-content">
						<div class="modal-header">
                            <?php                                
                                $theatreData = getActiveTheatres();
                                // $showtypeData = getShowtype();
                                $showtypeData = ["Morning show","Afternoon show",
                                                "Evening show", "Night show"];
                            ?>
							<h3 class="modal-title title-1" id="mediumModalLabel"> Add new showtiming </h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">                
                            <div class="form-group m-t-10">                                                
                                <select name="Theatre" id="selTheatre" class="form-control">
                                <option selected disabled> Select theatre </option>
                                    <?php
                                    foreach ($theatreData as $key => $value) {
                                    ?>
                                        <option value=<?php echo $value['theatre_id']; ?>><?php echo $value['name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>                                           
                            
                            <div class="form-group">
                                <!-- <label class=" form-control-label m-l-10">Select shows :</label> -->
                                <div class="row m-l-10">
                                    <input type="hidden" id="showTimesSize" value="4">
                                    <?php
                                    foreach ($showtypeData as $key => $value) {
                                    ?> 
                                    <div class="col-lg-6 m-t-10">
                                        <input type="checkbox" id="showType<?php echo $key; ?>" name="checkbox<?php echo $key; ?>" value="<?php echo $value; ?>" 
                                        class="form-check-input"> <?php echo $value; ?> 
                                        <div class="row m-t-5" >
                                            <input type="text" id="fromH<?php echo $key; ?>" placeholder="HH" class="form-control col-sm-3 ">
                                            <input type="text" id="fromM<?php echo $key; ?>" placeholder="MM" class="form-control col-sm-3 ">
                                            <select name="" id="selFrom<?php echo $key; ?>" class="form-control col-sm-4" >
                                                <option class="selected" value="am">am</option>
                                                <option value="pm">pm</option>
                                            </select>
                                        </div> 
                                        <div class="row m-t-5 m-b-5">
                                            <input type="text" id="toH<?php echo $key; ?>" placeholder="HH" class="form-control col-sm-3 ">
                                            <input type="text" id="toM<?php echo $key; ?>" placeholder="MM" class="form-control col-sm-3 ">
                                            <select name="" id="selTo<?php echo $key; ?>" class="form-control col-sm-4 " >
                                                <option class="selected" value="am">am</option>
                                                <option value="pm">pm</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <?php
                                    }
                                    ?>  
                                </div>
                            </div>
                            <div class="form-group m-b-10">                                                
                                <select name="Status" id="selStatusUpd" class="form-control">
                                    <!-- <option selected disabled> Select status </option> -->
                                    <option value="0" selected>Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
						<div class="modal-footer">
							<button id="btnCancel" type="button" class="btn btn-danger btn-reset" data-dismiss="modal">Cancel</button>
							<button id="btnConfirm" type="button" class="btn btn-save " onclick="updateDBWithShowDetails()">Confirm</button>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL ADD-->

             <!-- MODAL UPDATE -->
			<div class="modal fade" data-backdrop="static" id="mediumModalUpdate" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
					<div class="modal-content">
						<div class="modal-header">                
							<h3 class="modal-title title-1" id="mediumModalLabel"> Update showtiming </h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">        
                            <div class="form-group m-t-10 row">
                                <input type="hidden" id="showId">
                                <label class=" form-control-label col-sm-5 m-l-10"> Selected theatre : </label>
                                <label class=" form-control-label col-sm-6" id="thName"> <?php echo $value['name']; ?> </label> 
                            </div>                            
                            <div class="form-group m-t-10 row">
                                <label class=" form-control-label col-sm-5 m-l-10"> Selected show : </label>
                                <label class=" form-control-label col-sm-6" id="shTypeUpd">
                                <?php echo $value['type']; ?> </label> 
                            </div>                               
                            <div class="form-group m-t-10 m-l-10 row">
                                <label for="text" class="form-control-labels m-r-10 m-t-5">Show From</label>
                                <input type="text" id="fromHUpd" placeholder="HH" class="form-control col-sm-2 m-r-10">
                                <input type="text" id="fromMUpd" placeholder="MM" class="form-control col-sm-2 m-r-10">
                                <select name="" id="selFromUpd" class="form-control col-sm-2 m-r-10" >
                                    <option class="selected" value="am">am</option>
                                    <option value="pm">pm</option>
                                </select>
                            </div>
                            <div class="form-group m-t-10 m-l-10 row">
                                <label for="text" class="form-control-labels m-r-10 m-t-5">Show To</label>
                                <input type="text" id="toHUpd" placeholder="HH" class="form-control col-sm-2 m-r-10">
                                <input type="text" id="toMUpd" placeholder="MM" class="form-control col-sm-2 m-r-10">
                                <select name="" id="selToUpd" class="form-control col-sm-2 m-r-10" >
                                    <option class="selected" value="am">am</option>
                                    <option value="pm">pm</option>
                                </select>
                            </div>
                            <div class="form-group m-b-10">                                                
                                <select name="Status" id="selStatusUpd" class="form-control">
                                    <!-- <option selected disabled> Select status </option> -->
                                    <option value="0" selected>Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
						<div class="modal-footer">
							<button id="btnCancel" type="button" class="btn btn-danger btn-reset" data-dismiss="modal">Cancel</button>
							<button id="btnConfirm" type="button" class="btn btn-save " onclick="updateDBWithShowDetails()">Confirm</button>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL UPDATE -->
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