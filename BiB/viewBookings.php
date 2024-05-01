<?php
    $userID = $_POST['userId'];
?>

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
    <!-- <link href="vendor/vector-map/jqvmap.min.css" rel="stylesheet" media="all"> -->

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"> 
    </script>

    <script type="text/javascript">
        // $( document ).ready(function() {
        //     var cnt = $('#bookingCount').val();
        //     for(var i; i<cnt;i++) {
        //         if($('#cardStatus'+i).val() == 'O') {
        //             $('#maincard').css('opacity', '0.4');
        //         }
        //     }
           
        // });

        function setModaltxt(bookingId) {
                $('#txtDisplay').html('Are you sure to cancel this booking ?');
                $('#bkId').val(bookingId);
                $('#bkStatus').val('Cancelled');
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
            }
            else {
                $.ajax({
                    url : 'updateBooking.php',
                    type : 'POST',
                    data: { bkID:bkID, bkStatus:bkStatus },
                    success : function(data) {              
                        console.log('Success');
                        toast(' update success','S');
                        setTimeout(function(){ window.location.href = 'viewBookings.php'; }, 2000);  
                                                                                    
                    },
                    error : function(request,error){
                        console.log('Error '+JSON.stringify(request));
                        toast('ERROR !!','E');
                    }
                });     
            }             
        }    
    </script>    
</head>

<body class="animsition">
    <div class="page-wrapper">       
        <!-- PAGE CONTAINER-->
        <!-- <div class="page-container2"> -->
           
            <!-- CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <?php
                                include 'dbFunctions.php';
                                $bookingData = getUserBookings($userID);
                                foreach ($bookingData as $key => $value) {
                                    $d = $value['date'];
                                    $dt = new DateTime($d);                
                                    $bookDate = $dt->format('d/m/y');                                    

                                  $ticketData = getTicketDetails($value['movie_id_b'],$value['theatre_id_b'],$value['show_id'],$value['location_id_b']); 
                            ?>                      
                            <!-- BOOKING CARD-->
                            <!--  // , , , , , 
                                // booking_id, date, no_of_seat, seatNumber, amount, , , , , user_id, status -->

                        <div class="col-md-3">                                 
                            <?php
                                if ($value['status'] == 'Confirmed' || $value['status'] == 'Cancelled') {
                            ?>
                            <div class="card" style="opacity:0.5;"> 
                            <?php
                                }
                                else {
                            ?>
                                <div class="card" >
                            <?php
                                }                                                
                            ?>                                                                
                                <div class="card-body cb-bg">
                                    <img class="card-img-top" src="<?php echo $ticketData[0]['poster'];?>" alt="Card image cap" style="height: 150px; width:fit-content;">
                                    <div class="cb-content">
                                        <input type="hidden" id="bookingId<?php echo $key;?>" value="<?php echo $value['booking_id'];?>"> 
                                        <label> BOOKED ON : <?php echo $bookDate;?> </label> </br>
                                        <label> <?php echo $ticketData[0]['theatre'];?> , <?php echo $ticketData[0]['location'];?></label> </br>
                                        <label> <?php echo $ticketData[0]['showtype'];?> @ <?php echo $ticketData[0]['timeFrom'];?></label> </br>
                                        
                                        <label>  <?php echo $value['seatNumber'];?> </label> </br>
                                        <label> AMOUNT :  ₹ <?php echo $value['amount'];?> </label> </br>   
                                    </div>
                                    <?php
                                        if ($value['status'] == 'Booked') {
                                    ?>                                              
                                        <div class="cb-footer">
                                            <button id="btnConfirm" type="button" class=" btn btn-confirm" data-toggle="modal" data-target="#staticModal" onclick="setModaltxt(<?php echo $value['booking_id'];?>)"> Cancel Booking </button>
                                        </div> 
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
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
                        <div class="modal-body" style="color:black; padding-left:30px;">
                            <p id="txtDisplay"> </p>
                            <input type="hidden" id="bkStatus"> </br>
                            <input type="hidden" id="bkId"> 
                            
                        </div>
                        <div class="modal-footer" style="padding:0px 80px;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button id="modalYes" type="button" class="btn btn-primary" onclick=updateDBWithBookingStatus()>Yes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL  -->            
        <!-- </div> -->
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