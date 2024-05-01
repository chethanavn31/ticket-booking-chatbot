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
    <title>Seats</title>

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

    // thID thName sID sType sFrom sTo sCount sAmount

    function updateSeatDetail(thName, sID, sType, sFrom, sTo, sCount, sAmount, status){
        console.log('=============');
        console.log(thName, sID, sType, sFrom, sTo, sCount, sAmount, status);
        console.log('=============');
        var selRowindex = $('#selectedRowId').val('update');
        $("#thName").show();
        $('#selTheatre').hide();
        $('#thlbl').show();
        $('#mediumModalLabel').html('Update seat');

        $('#stId').val(sID);
        $('#stType').val(sType);
        $('#stFrom').val(sFrom);
        $('#stTo').val(sTo);
        $('#stCount').val(sCount);
        $('#stAmount').val(sAmount);
        $('#thName').html(thName);
        $('#selStatus').val(status);

        if(status.toLowerCase()=='active'){
            $("#selStatus").val('0'); 
        }
        else if(status.toLowerCase()=='inactive'){
            $("#selStatus").val('1'); 
        }            
    }

    function modalclear() {
        $('#thId').val('');
        $('#stId').val('');
        $('#stType').val('');
        $('#stFrom').val('');
        $('#stTo').val('');
        $('#stCount').val('');
        $('#stAmount').val('');
        $('#selStatus').val('')
        $('#selTheatre').val('');
        $('#selectedRowId').val('na');
    }

    function addSeat() {
        console.log('addseat()');
        var selRowindex = $('#selectedRowId').val('add');

        $('#mediumModalLabel').html('Add new Seat');
        $("#thName").hide();
        $('#selTheatre').show();
        $('#thlbl').hide();
    }

    function updateDBWithSeatDetails(){
        var selRowindex = $('#selectedRowId').val();
        console.log(selRowindex);
        if(selRowindex=='add'){
            console.log('Add theatre details');
            var stType = $('#stType option:selected').text(); // $('#stType').val();
            var stFrom = $('#stFrom').val();
            var stTo = $('#stTo').val();
            var stCount = $('#stCount').val();
            var stAmount = $('#stAmount').val();
            var selTheatre = $('#selTheatre option:selected').val();
            var selStatus = $('#selStatus option:selected').text();
            
            if(stType==null || stType == "" || stType==undefined){
                console.log('type cannot be null');
                toast('empty type','C');
            }
            else if(stFrom==null || stFrom == "" || stFrom==undefined){
                console.log('from id cannot be null');
                toast('seat from empty','C');
            }
            else if(stTo==null || stTo=="" || stTo==undefined){
                console.log('to cannot be null');
                toast('seat to empty ','C');
            }
            else if(stCount==null || stCount == "" || stCount==undefined){
                console.log('seatcount name cannot be null');
                toast('empty seat count','C');
            }
            else if(stAmount==null || stAmount=="" || stAmount==undefined){
                console.log('amount cannot be null');
                toast('empty amount','C');
            }            
            else if(selStatus==null || selStatus=="" || selStatus==undefined){
                console.log('status cannot be null');
            }
            else if(selTheatre==null || selTheatre=="" || selTheatre==undefined){
                console.log('thid cannot be null');
                toast('empty theatre','C');
            }
            else{
                console.log('success call insert');
                $.ajax({
                        url : 'addSeat.php',
                        type : 'POST',
                        data: { thID: selTheatre, seatType:stType, fromSeat:stFrom, toSeat:stTo, totalSeats:stCount ,amount:stAmount, status:selStatus },
                        success : function(data) {              
                            // var selRowindex = $('#selectedRowId').val('na');
                            console.log('Success','addddd');

                            toast(' add success','S');                               
                            setTimeout(function(){ window.location.href = 'vseats.php';  }, 2000);                                                              
                        },
                        error : function(request,error){
                            console.log('Error '+JSON.stringify(request))
                            //$('#dbResponse').html('Error');
                            toast('ERROR !!','E');

                        }
                    });
            }
            console.log(selTheatre, stType,stFrom,stTo,stCount,stAmount,selStatus);
        }
        else if(selRowindex=='update'){
    // thID thName sID sType sFrom sTo sCount sAmount
            console.log('Update theatre details');

            var stId = $('#stId').val();
            var stType = $('#stType option:selected').text();  //$('#stType').val();
            var stFrom = $('#stFrom').val();
            var stTo = $('#stTo').val();
            var stCount = $('#stCount').val();
            var stAmount = $('#stAmount').val();
            var selStatus = $('#selStatus option:selected').text();
            
            if(stId==null || stId == "" || stId==undefined){
                console.log('seat id cannot be null');
            }
            else if(stType==null || stType == "" || stType==undefined){
                console.log('type cannot be null');
                toast('empty seat type','C');
            }
            else if(stFrom==null || stFrom == "" || stFrom==undefined){
                console.log('from id cannot be null');
                toast('seat from empty','C');
            }
            else if(stTo==null || stTo=="" || stTo==undefined){
                console.log('to cannot be null');
                toast('seat to empty','C');
            }
            else if(stCount==null || stCount == "" || stCount==undefined){
                console.log('seatcount name cannot be null');
                toast('empty seat count','C');
            }
            else if(stAmount==null || stAmount=="" || stAmount==undefined){
                console.log('amount cannot be null');
                toast('empty amount','C');
            }            
            else if(selStatus==null || selStatus=="" || selStatus==undefined){
                console.log('status cannot be null');
            }
            else{
                // // seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status
                console.log('success call update');
                $.ajax({
                        url : 'updateSeat.php',
                        type : 'POST', 
                        data: { seatID:stId, seatType:stType, fromSeat:stFrom, toSeat:stTo, totalSeats:stCount ,amount:stAmount, status:selStatus },
                        success : function(data) {              
                            console.log('Success');
                            // var selRowindex = $('#selectedRowId').val('na'); 
                            // console.log('Success', selRowindex,'upddddd');

                            toast(' update success','S');                              
                            setTimeout(function(){ window.location.href = 'vseats.php'; }, 2000);  
                                                                                        
                        },
                        error : function(request,error){
                            console.log('Error '+JSON.stringify(request))
                            //$('#dbResponse').html('Error');
                            toast('ERROR !!','E');

                        }
                        });
            }
            console.log(stID,stType,stFrom,stTo,stCount,stAmount,selStatus);
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
                        <a href="vshowtimings.php">
                            <i class="zmdi zmdi-timer"></i>Showtimings</a>
                    </li>
                    <li>
                        <a class="aSelected" href="vseats.php">
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
                                    <i class="zmdi zmdi-tag m-r-10"></i> Seats</h2>
                            </div>
                            <div class="header-button2">
                                <button type="button" id="addbtn" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#mediumModal" onclick="addSeat()">
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
                                            <th hidden> thID </th> 
                                            <th> Theatre </th>       
                                            <th hidden> stID </th>                                                                                         
                                            <th> Type </th>
                                            <th> From </th>
                                            <th> To </th>
                                            <th> Seats </th>
                                            <th> Amount (₹) </th> 
                                            <th> Status </th>                                            
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="seatBody">
                                        <?php
                                            include 'dbFunctions.php';
                                            $seatData = getAllSeats();                                                
                                    
                                            foreach ($seatData as $key => $value) {

                                                $thNameReplaced = str_replace ("'","\'",$value['name']);
                                        ?>
                                        <tr>
                                            <td id="ID<?php echo $key;?>"> <?php echo ($key+1);?> </td>
                                            <td hidden id="thID<?php echo $key; ?>"> <?php echo $value['theatre_id_s'];?> </td>
                                            <td id="thName<?php echo $key;?>"> <?php echo $value['name'];?> </td>
                                            <td hidden id="sID<?php echo $key; ?>"> <?php echo $value['seat_id'];?> </td>
                                            <td id="sType<?php echo $key;?>"> <?php echo $value['seatType'];?> </td>
                                            <td id="sFrom<?php echo $key;?>"> <?php echo $value['fromSeat'];?> </td>
                                            <td id="sTo<?php echo $key;?>"> <?php echo $value['toSeat'];?> </td>
                                            <td id="sCount<?php echo $key;?>"> <?php echo $value['totalSeats'];?> </td>
                                            <td id="sAmount<?php echo $key;?>"> <?php echo $value['amount'];?> </td>
                                            <td id="status<?php echo $key;?>"> <?php echo $value['status'];?> </td>
                                            <td>
                                                <button class="item" id="editSeat<?php echo $key; ?>" data-toggle="modal" data-placement="top" data-target="#mediumModal"
                                                    title="Edit" onclick="updateSeatDetail('<?php echo $thNameReplaced;?>',
                                                    <?php echo $value['seat_id'];?>,
                                                    '<?php echo $value['seatType'];?>',
                                                    '<?php echo $value['fromSeat'];?>',
                                                    '<?php echo $value['toSeat'];?>',
                                                    <?php echo $value['totalSeats'];?>,
                                                    <?php echo $value['amount'];?>,
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
                                $theatreData = getActiveTheatres();
                            ?>
							<h3 class="modal-title title-1" id="mediumModalLabel"> Add new seats </h3>
							<button type="button" class="close" data-dismiss="modal" onclick="modalclear()" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">                            
                            <div class="form-group m-t-10">
                                <input type="hidden" id="thId">                
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
                                <label class="form-control-label col-sm-5" id="thlbl"> Selected theatre : </label>
                                <label class="form-control-label col-sm-6" id="thName"></label>
                            </div>
                            <div class="form-group m-t-10">  
                                 <input type="hidden" id="stId">                 
                                <!--<input type="text" id="stType" placeholder="Seat type" class="form-control">   -->  
                                 <select name="type" id="stType" class="form-control m-t-10">
                                    <option selected disabled> Select Type </option>
                                    <option value="Gold">Gold</option>
                                    <option value="Silver">Silver</option>
                                    <option value="Platinum">Platinum</option>                                    
                                </select>                                         
                            </div>
                            <div class="form-group row m-t-10 m-l-5">                                                
                                <input type="text" id="stFrom" placeholder="From" class="form-control col-lg-5 m-r-60">
                                <input type="text" id="stTo" placeholder="To" class="form-control col-lg-5">                                    
                            </div>
                            <div class="form-group m-t-10 m-l-10 row">
                                <input type="text" id="stCount" placeholder="Seat" class="form-control col-sm-2">
                                <label class="form-control-label m-t-10 m-r-10"> Seats</label>
                                <label class="form-control-label m-l-120 m-t-10 "> ₹ </label>                                    
                                <input type="text" id="stAmount" placeholder="Amount" class="form-control col-sm-3 m-r-20">  
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
                            <button id="btnCancel" type="button" class="btn btn-danger btn-reset" data-dismiss="modal" onclick="modalclear()">Cancel</button>
							<button id="btnConfirm" type="button" class="btn btn-save " onclick="updateDBWithSeatDetails()">Confirm</button>
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