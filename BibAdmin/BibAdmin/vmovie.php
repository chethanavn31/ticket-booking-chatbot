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
    <title>Movie</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="all">

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
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
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
    function readURL(input) {
        if (input.files && input.files[0]) { 
            var mvURL  = 'http://localhost/BIB/BibAdmin/movieURL/'+input.files[0].name;
            $('#imgPoster').attr('src', mvURL);
               
            console.log('-----------',input.files[0].name, mvURL);
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

    function updateMovieDetail(mID, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mDate, mTime, mFrom, mTo, status, mtidx )
    {
        console.log('=============');
        console.log(mID, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mDate, mTime, mFrom, mTo, status, mtidx );
        console.log('=============');
        var movThData = JSON.parse($('#mtid'+mtidx).val());
        console.log(movThData);
        // mId txtName txtDir txtGenre txtLang txtCert txtDescp txtCast posterImg txtFrom txtTo selStatus thForMvArr

        var selRowindex = $('#selectedRowId').val('update');
       
        $('#mediumModalLabel').html('Update movie');

        $('#mId').val(mID);
        $('#txtName').val(mName);
        $('#txtDir').val(mDir);
        $('#txtGenre').val(mGenre);
        // $('#txtLang').val(mLang);
        var langArr = mLang.split(" ");
        // console.log(langArr);
        for(var j=0; j<langArr.length;j++)
        { 
            for(var i=0;i<6;i++)
            {
                if(langArr[j] == $('#txtLang'+i).val()) {
                    $('#txtLang'+i).prop('checked', true);
                }
            }         
         }
         for(var i=0;i<3;i++){
            if($('#txtCert'+i).val() == mCert){
                $('#txtCert'+i).prop('checked',true);
            }
        }
                 
        $('#txtDescp').val(mDescrp);
        $('#txtCast').val(mCast);
        $('#imgPoster').prop('src',mPoster);
        $('#txtDate').val(mDate);
        var timeArr = mTime.split(" ");
        $('#txtHr').val(timeArr[0]);
        $('#txtMin').val(timeArr[1]);
        $('#txtFrom').val(mFrom);
        $('#txtTo').val(mTo);
        $('#selStatus').val(status);
        var thCount = $('#thCount').val();
        console.log(thCount + '****************');
       for (var i=0;i<movThData.length;i++) {
            for (var j=0;j<thCount;j++) {
                if ($('#thName'+j).val() == movThData[i]['theatre_id_m']) {
                    $('#thName'+j).prop('checked', true);
                    console.log('thid  ' +  $('#thName'+j).val() +'  mtid' + movThData[i]['theatre_id_m']);
                }
            }            
        }

        if(status.toLowerCase()=='active'){
            $("#selStatus").val('0'); 
        }
        else if(status.toLowerCase()=='inactive'){
            $("#selStatus").val('1'); 
        }            
    }

    function modalclear() {
        $('#mId').val('');
        $('#txtName').val('');
        $('#txtDir').val('');
        $('#txtGenre').val('');
        // $('#txtLang').val('');
        // $('#txtCert').val(''); 
            for(var i=0;i<6;i++)
            {
                $('#txtLang'+i).prop('checked', false);
            }
            for(var i=0;i<3;i++){
                $('#txtCert'+i).prop('checked',false);
            }

        $('#txtDescp').val('');
        $('#txtCast').val('');
        $('#imgPoster').attr('src','');
        $('#txtDate').val('');
        $('#txtHr').val('');
        $('#txtMin').val('');
        $('#txtFrom').val('');
        $('#txtTo').val('');
        $('#selStatus').val('');
        $('#imgPostUpd').val('');
        var thCount = $('#thCount').val();
        for (var j=0;j<thCount;j++) {
            $('#thName'+j).prop('checked', false);
        }
    }

    // function addMovie() {
    //     console.log('ADD()');
    //     var selRowindex = $('#selectedRowId').val('add');
    //     $('#mediumModalLabel').html('Add new movie');
    // }

    $( document ).ready(function() {
        $( "#addMovieFunc" ).click(function() {
            console.log('==================================');
            console.log('ADD()');
            var selRowindex = $('#selectedRowId').val('add');
            $('#mediumModalLabel').html('Add new movie');
        });


        $( "#btnConfirm" ).click(function() {
            console.log('<<<<<<<<<<<<');
            // <!-- mId txtName txtDir txtGenre txtLang txtCert txtDescp txtCast imgPoster imgPostUpd txtDate txtHr txtMin selStatus-->

            var selRowindex = $('#selectedRowId').val();
                console.log(selRowindex);
                var mId = $('#mId').val();
                var mName = $('#txtName').val();
                var mDir = $('#txtDir').val();
                var mGenre = $('#txtGenre option:selected').text();
                var lang='';
                for (var i=0;i<6;i++){
                    if($('#txtLang' + i).is(":checked"))
                    {
                        lang = lang + $('#txtLang' + i).val()+' ';
                    }
                }        
                var mLang = lang.trim();
            
                for (var i=0;i<3;i++){
                    if($('#txtCert' + i).is(":checked"))
                    {
                        var mCert = $('#txtCert'+i).val();
                    }            
                }
                console.log(mCert);
                var mDescrp = $('#txtDescp').val();
                var mCast = $('#txtCast').val();
                var mPoster = $('#imgPoster').prop('src');
                // var mFrom = $('#txtFrom').val();
                var mDate = $('#txtDate').val();
                // var mTo = $('#txtTo').val();
                var mTime = $('#txtHr').val() + ' ' + $('#txtMin').val();
                var mFrom = $('#txtFrom').val();
                var mTo = $('#txtTo').val();
                var status = $('#selStatus option:selected').text();
                var thCount = $('#thCount').val();            

                console.log("DATATATTTAATA" , mId, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mDate, mTime, status);

                if(mName==null || mName=="" || mName==undefined){
                    console.log('name cannot be null');
                    // toast('Name cannot be null','C');
                }
                else if(mDir==null || mDir=="" || mDir==undefined){
                    console.log('directr cannot be null');
                    // toast('Director cannot be null','C');
                }
                else if(mGenre==null || mGenre=="" || mGenre==undefined){
                    console.log('genre cannot be null');
                    // toast('Genre cannot be null','C');
                }
                else if(mLang==null || mLang=="" || mLang==undefined){
                    console.log('lang cannot be null');
                    // toast('Language cannot be null','C');
                }
                else if(mCert==null || mCert=="" || mCert==undefined){
                    console.log('certfcte cannot be null');
                    // toast('Certificate cannot be null','C');
                }
                else if(mDescrp==null || mDescrp=="" || mDescrp==undefined){
                    console.log('descrptn cannot be null');
                    // toast('Description cannot be null','C');
                }
                else if(mCast==null || mCast=="" || mCast==undefined){
                    console.log('cast cannot be null');
                    // toast('Cast cannot be null','C');
                }
                else if(mPoster==null || mPoster=="" || mPoster==undefined){
                    console.log('poster cannot be null');
                    // toast('Poster cannot be null','C');
                }
                else if(mDate==null || mDate=="" || mDate==undefined){
                    console.log('date cannot be null');
                    // toast(' Date From cannot be null','C');
                }
                else if(mTime==null || mTime=="" || mTime==undefined){
                    console.log('time cannot be null');
                    // toast('Date To  cannot be null','C');
                }
                else if(mFrom==null || mFrom=="" || mFrom==undefined){
                    console.log('from cannot be null');
                    // toast(' Date From cannot be null','C');
                }
                else if(mTo==null || mTo=="" || mTo==undefined){
                    console.log('to cannot be null');
                    // toast('Date To  cannot be null','C');
                }
                else if(status==null || status=="" || status==undefined){
                    console.log('status cannot be null');
                    // toast('Status cannot be null','C');
                }
                else{
                    if(selRowindex=='add') {
                        console.log('ADDDDD-----------');
                        
                        console.log('success call ADDDD');
                        var thMvIDs = [];
                        for (var i=0;i<thCount;i++){
                            // console.log('------------',$('#thName' + i), $('#thName' + i).is(":checked"))
                            if($('#thName' + i).is(":checked")) {
                                var thMvIDsObj = {
                                    mthId:$('#thName'+i).val()
                                }
                                thMvIDs.push(thMvIDsObj);
                            }                  
                        }
                        console.log(JSON.stringify(
                            {mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp, mCast:mCast,
                            mPoster:mPoster, mDate:mDate, mTime:mTime, mFrom:mFrom, mTo:mTo, status:status}));
                        $.ajax({
                            url : 'addMovie.php',
                            type : 'POST', 
                            data: {mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp,
                                mCast:mCast, mPoster:mPoster, mDate:mDate, mTime:mTime, mFrom:mFrom, mTo:mTo, status:status, thMvIdData:JSON.stringify(thMvIDs) },
                            success : function(data) {  
                                console.log(data);            
                                console.log('Success');
                                toast('add success','S');
                                setTimeout(function(){ window.location.href = 'vmovie.php'; }, 2000);                                     
                            },
                            error : function(request,error){
                                console.log('Error '+JSON.stringify(request))
                                //$('#dbResponse').html('Error');
                                toast('ERROR !!','E');

                            }
                        });            
                        console.log(thMvIDs);
                        console.log(mId, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mDate, mTime, mFrom, mTo, status);
                                    
                    }
                
                else if(selRowindex=='update') {
                        if(mId==null || mId=="" || mId==undefined){
                            console.log('mid cannot be null');
                        }
                        else {
                            console.log('UPDATEE-----------');
                            var thMvIDs = [];
                            for (var i=0;i<thCount;i++){
                                // console.log('------------',$('#thName' + i), $('#thName' + i).is(":checked"))
                                if($('#thName' + i).is(":checked")) {
                                    var thMvIDsObj = {
                                        mthId:$('#thName'+i).val(), movID:mId
                                    }
                                    thMvIDs.push(thMvIDsObj);
                                }                  
                            }
                        
                            console.log('success call update');
                            console.log(JSON.stringify(
                                {mID:mId, mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp, mCast:mCast,
                                mPoster:mPoster, mDate:mDate, mTime:mTime, mFrom:mFrom, mTo:mTo, status:status}));
                            $.ajax({
                                url : 'updateMovie.php',
                                type : 'POST', 
                                data: {mID:mId, mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp,
                                    mCast:mCast, mPoster:mPoster, mDate:mDate, mTime:mTime,  mFrom:mFrom, mTo:mTo, status:status, thMvIdData:JSON.stringify(thMvIDs) },
                                success : function(data) {  
                                    console.log(data);            
                                    console.log('Success');
                                    toast('Update Success','S');

                                    setTimeout(function(){ window.location.href = 'vmovie.php'; }, 2000);                                     
                                },
                                error : function(request,error){
                                    console.log('Error '+JSON.stringify(request))
                                    //$('#dbResponse').html('Error');
                                    toast('ERROR !!','E');

                                }
                            });            
                            console.log(thMvIDs);
                            console.log(mId, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mDate, mTime, mFrom, mTo, status);
                        }
                }
            }
        });
    });


    function updateDBWithMovieDetails() {
        var selRowindex = $('#selectedRowId').val();
        console.log(selRowindex);
        // var mId = $('#mId').val();
        // var mName = $('#txtName').val();
        // var mDir = $('#txtDir').val();
        // var mGenre = $('#txtGenre').val();
        // var mLang = $('#txtLang').val();
        // var mCert = $('#txtCert').val();
        // var mDescrp = $('#txtDescp').val();
        // var mCast = $('#txtCast').val();
        // var mPoster = $('#imgPoster').prop('src');
        // var mFrom = $('#txtFrom').val();
        // var mTo = $('#txtTo').val();
        // var status = $('#selStatus option:selected').text();
        // var thCount = $('#thCount').val();            

        // if(mName==null || mName=="" || mName==undefined){
        //     console.log('name cannot be null');
        // }
        // else if(mDir==null || mDir=="" || mDir==undefined){
        //     console.log('directr cannot be null');
        // }
        // else if(mGenre==null || mGenre=="" || mGenre==undefined){
        //     console.log('genre cannot be null');
        // }
        // else if(mLang==null || mLang=="" || mLang==undefined){
        //     console.log('lang cannot be null');
        // }
        // else if(mCert==null || mCert=="" || mCert==undefined){
        //     console.log('certfcte cannot be null');
        // }
        // else if(mDescrp==null || mDescrp=="" || mDescrp==undefined){
        //     console.log('descrptn cannot be null');
        // }
        // else if(mCast==null || mCast=="" || mCast==undefined){
        //     console.log('cast cannot be null');
        // }
        // else if(mPoster==null || mPoster=="" || mPoster==undefined){
        //     console.log('poster cannot be null');
        // }
        // else if(mFrom==null || mFrom=="" || mFrom==undefined){
        //     console.log('from cannot be null');
        // }
        // else if(mTo==null || mTo=="" || mTo==undefined){
        //     console.log('to cannot be null');
        // }
        // else if(status==null || status=="" || status==undefined){
        //     console.log('status cannot be null');
        // }
        // else{
        //     if(selRowindex=='add') {
        //         console.log('ADDDDD-----------');
                
        //         console.log('success call ADDDD');
        //         var thMvIDs = [];
        //         for (var i=0;i<thCount;i++){
        //             // console.log('------------',$('#thName' + i), $('#thName' + i).is(":checked"))
        //             if($('#thName' + i).is(":checked")) {
        //                 var thMvIDsObj = {
        //                     mthId:$('#thName'+i).val()
        //                 }
        //                 thMvIDs.push(thMvIDsObj);
        //             }                  
        //         }
        //         console.log(JSON.stringify(
        //             {mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp, mCast:mCast,
        //             mPoster:mPoster, mFrom:mFrom, mTo:mTo, status:status}));
        //         $.ajax({
        //             url : 'addMovie.php',
        //             type : 'POST', 
        //             data: {mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp,
        //                 mCast:mCast, mPoster:mPoster, mFrom:mFrom, mTo:mTo, status:status, thMvIdData:JSON.stringify(thMvIDs) },
        //             success : function(data) {  
        //                 console.log(data);            
        //                 console.log('Success');

        //                 setTimeout(function(){ window.location.href = 'vmovie.php'; }, 2000);                                     
        //             },
        //             error : function(request,error){
        //                 console.log('Error '+JSON.stringify(request))
        //                 //$('#dbResponse').html('Error');
        //             }
        //         });            
        //         console.log(thMvIDs);
        //         console.log(mId, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mFrom, mTo, status);
                            
        //     }
        //     else if(selRowindex=='update') {
        //         if(mId==null || mId=="" || mId==undefined){
        //             console.log('mid cannot be null');
        //         }
        //         else {
        //             console.log('UPDATEE-----------');
        //             var thMvIDs = [];
        //             for (var i=0;i<thCount;i++){
        //                 // console.log('------------',$('#thName' + i), $('#thName' + i).is(":checked"))
        //                 if($('#thName' + i).is(":checked")) {
        //                     var thMvIDsObj = {
        //                         mthId:$('#thName'+i).val(), movID:mId
        //                     }
        //                     thMvIDs.push(thMvIDsObj);
        //                 }                  
        //             }
                
        //             console.log('success call update');
        //             console.log(JSON.stringify(
        //                 {mID:mId, mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp, mCast:mCast,
        //                 mPoster:mPoster, mFrom:mFrom, mTo:mTo, status:status}));
        //             $.ajax({
        //                 url : 'updateMovie.php',
        //                 type : 'POST', 
        //                 data: {mID:mId, mName:mName, mDir:mDir, mGenre:mGenre, mLang:mLang, mCert:mCert, mDescrp:mDescrp,
        //                     mCast:mCast, mPoster:mPoster, mFrom:mFrom, mTo:mTo, status:status, thMvIdData:JSON.stringify(thMvIDs) },
        //                 success : function(data) {  
        //                     console.log(data);            
        //                     console.log('Success');

        //                     setTimeout(function(){ window.location.href = 'vmovie.php'; }, 2000);                                     
        //                 },
        //                 error : function(request,error){
        //                     console.log('Error '+JSON.stringify(request))
        //                     //$('#dbResponse').html('Error');
        //                 }
        //             });            
        //             console.log(thMvIDs);
        //             console.log(mId, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mFrom, mTo, status);
        //         }
        //   }            
        }


    </script>
</head>
<?php
//$parameters = filter_input(INPUT_POST, 'parameters');
?>
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
                        <a class="aSelected" href="vmovie.php">
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
                                    <i class="zmdi zmdi-movie m-r-10"></i> Movie
                                </h2>
                            </div>
                            <div class="header-button2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button" id="addMovieFunc" style="float:right;" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#mediumModal">
                                                <i class="zmdi zmdi-plus m-r-10"></i>ADD
                                        </button> 
                                    </div>
                                </div>                                  
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- CONTENT-->
            <div class="main-content">            
                <div class="section__content section__content--p30">               
                    <div class="container-fluid m-l-5"> 
                        <input hidden id="selectedRowId" value="na">
                                              
                        <!-- FLIP CARD  -->
                        <div class="row m-l-5 ">                           
                            <?php
                                require('dbFunctions.php');
                                $movieData = getAllMovies();
                                // echo json_encode($movieData);
                                foreach ($movieData as $key => $value) {
                                    $descrpReplaced = str_replace ("'","\'",$value['description']);
                                    $theatreForMovie = getTheatresForMovie($value['movie_id']);
                                    $langReplaced = str_replace (" ",", ",$value['language']);  
                                    $castReplaced = preg_replace('~[\r\n]+~', ", ", $value['cast']);
                                    // $certReplaced = str_replace ("","\'",$value['certificate']);
                                    // $imgReplaced = str_replace ("","\'",$value['posterURL']);
                                    // $castReplaced = str_replace (",","\n",$value['cast']);

                            ?>
                             <div class="flip-card column">
                                <div class="flip-card-inner">
                                    <div class="flip-card-front">
                                        <img src="<?php echo $value['posterURL']; ?>" alt="Avatar" id="posterImg<?php echo $key; ?>" style="width:300px;height:280px;">
                                    </div>
                                    <div class="flip-card-back">
                                        <input type="hidden" id="mtid<?php echo $key; ?>" value="<?php echo htmlspecialchars(json_encode($theatreForMovie), ENT_QUOTES, 'UTF-8'); ?>" >
                                        <div class="heading" id="mName<?php echo $key; ?>"> <?php echo $value['name']; ?> </div>
                                        <div style="border-bottom: 2px solid white;" id="mDirector<?php echo $key; ?>"> <?php echo $value['director']; ?> </div>
                                        <div style="font-weight: 600;" class="p-t-5" id="mGenre<?php echo $key; ?>"> <?php echo $value['genre']; ?> </div>
                                        <div class="row"> 
                                            <div class="col-8" id="mLang<?php echo $key; ?>"> <?php echo $langReplaced; ?> </div>
                                            <div class="cert col-2" id="mCert<?php echo $key; ?>"> <?php echo $value['certificate']; ?> </div>                                        
                                        </div>
                                        <div class="cast" id="mCast<?php echo $key; ?>">Cast : <?php echo $castReplaced; ?> </div>
                                        <div class="row"> 
                                            <div class="col-8" id="mStatus<?php echo $key; ?>"> <?php echo $value['status']; ?> </div>
                                            <button class="item col-2" id="editMovie<?php echo $key; ?>" data-toggle="modal" data-placement="top" data-target="#mediumModal"
                                                title="Edit" onclick="updateMovieDetail(<?php echo $value['movie_id'];?>,
                                                '<?php echo $value['name'];?>',
                                                '<?php echo $value['director'];?>',
                                                '<?php echo $value['genre'];?>',
                                                '<?php echo $value['language'];?>',
                                                '<?php echo $value['certificate'];?>',
                                                '<?php echo $descrpReplaced;?>',
                                                '<?php echo $castReplaced;?>',
                                                '<?php echo $value['posterURL'];?>',
                                                '<?php echo $value['releaseDate'];?>',
                                                '<?php echo $value['duration'];?>',
                                                '<?php echo $value['fromDate'];?>',
                                                '<?php echo $value['toDate'];?>',
                                                '<?php echo $value['status'];?>',
                                                <?php echo $key;?>)">                                                
                                                <i class="fa fa-edit" style="color:white;"></i>                                            
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <!--  END FLIP CARD-->
                    </div> 
                </div>
            </div>
            <!-- END MAIN CONTENT-->

            <div id="snackbar"></div>

            <!-- MODAL LARGE -->
			<div class="modal fade" data-backdrop="static" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document" style="max-width:800px;">
					<div class="modal-content">
						<div class="modal-header">
                            <?php                                
                                $theatreData = getActiveTheatres();                                                               
                            ?>
							<h3 class="modal-title title-1" id="mediumModalLabel"> Add new movie </h3>
							<button type="button" class="close" data-dismiss="modal" onclick="modalclear()" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
                        <!-- mId txtName txtDir txtGenre txtLang txtCert txtDescp txtCast imgPoster imgPostUpd txtDate txtHr txtMin selStatus-->
						<div class="modal-body">                            
                            <div class="form-group">
                                <input type="hidden" id="mId">
                                <input type="text" id="txtName" placeholder="Name of movie" class="form-control m-t-10">                        
                            </div>
                            <div class="form-group row">
                                <div class="col-6 m-t-20">
                                    <img src="" alt="Poster" id="imgPoster" style="margin-left:20px;width:300px;height:350px;">                                
                                    <input class="m-t-10 m-l-20" type="file" id="imgPostUpd" name="img" accept="image/*" onchange="readURL(this);" >                                       
                                </div>
                                <div class="col-6">                                    
                                    <input type="text" id="txtDir" placeholder="Director" class="form-control m-t-10">
                                    <textarea name="textarea-input" id="txtCast" rows="4" placeholder="Cast" class="form-control m-t-10"></textarea> 
                                    
                                    <div class="m-t-10">
                                        <label class="form-control-label"> CBFC : &nbsp;&nbsp;</label>

                                        <input type="radio" id="txtCert0" name="txtCert" value="U">
                                            <label for="txtCert" class="m-r-10">U</label>&nbsp;&nbsp;
                                        <input type="radio" id="txtCert1" name="txtCert" value="U/A">
                                            <label for="txtCert" class="m-r-10">U/A</label>&nbsp;&nbsp;
                                        <input type="radio" id="txtCert2" name="txtCert" value="A">
                                        <label for="txtCert" class="m-r-10">A</label>
                                    </div> 
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="date" id="txtDate" name="releasedate" placeholder="dd/mm/yyyy" class="form-control m-t-10"/>
                                        </div>
                                        <div class="col-6">
                                            <select name="Genre" id="txtGenre" class="form-control m-t-10">
                                                <option selected disabled> Select Genre </option>
                                                <option value="Action">Action</option>
                                                <option value="Adventure">Adventure</option>
                                                <option value="Drama">Drama</option>
                                                <option value="Horror">Horror</option>
                                                <option value="Comedy">Comedy</option>
                                                <option value="Thriller">Thriller</option>
                                                <option value="Romance">Romance</option>
                                                <option value="Science Fiction">Science Fiction</option>
                                                <option value="Fantasy">Fantasy</option>
                                                <option value="Animation">Animation</option>
                                                <option value="Romantic Comedy">Romantic Comedy</option>
                                                <option value="Action Thriller">Action Thriller</option>
                                                <option value="Political">Political</option>
                                                <option value="Family Thriller">Family Thriller</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="row ">
                                        <div class="col-4"> 
                                            <input type='text' id='txtHr' placeholder="Hours" class="form-control m-t-10" />
                                        </div>
                                        <div class="col-4"> 
                                            <input type='text' id='txtMin' placeholder="Mins" class="form-control m-t-10" />
                                        </div>
                                    </div> 
                                    <div class="m-t-20 m-l-20"> 
                                        <input type="checkbox" id ="txtLang0" name="mal" value="Malayalam" >
                                            <label for="mal" class="m-r-10 m-l-5"> Malayalam </label>
                                        <input type="checkbox" id ="txtLang1" name="hin" value="Hindi" >
                                            <label for="hin" class="m-r-10 m-l-5"> Hindi </label>
                                        <input type="checkbox" id ="txtLang2" name="tam" value="Tamil" >
                                            <label for="tam" class="m-r-10 m-l-5"> Tamil </label> <br>
                                        <input type="checkbox" id ="txtLang3" name="kan" value="Kannada" >
                                            <label for="kan" class="m-r-10 m-l-5"> Kannada </label>
                                        <input type="checkbox" id ="txtLang4" name="tel" value="Telugu" >
                                            <label for="tel" class="m-r-10 m-l-5"> Telugu </label>
                                        <input type="checkbox" id ="txtLang5" name="eng" value="English" >
                                            <label for="eng" class="m-r-10 m-l-5"> English </label>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- <input type="text" id="txtGenre" placeholder="Genre" class="form-control"> -->
                            <div class="form-group row">
                                <div class="col-6">                            
                                <input type="date" id="txtFrom" name="fromdate" placeholder="dd/mm/yyyy" min="<?php echo date("Y-m-d") ?>" class="form-control"/>
                                </div>
                                <div class="col-6">
                                    <input type="date" id="txtTo" name="todate" placeholder="dd/mm/yyyy" min="<?php echo date("Y-m-d") ?>" class="form-control"/>
                                </div>
                            </div>
                            <textarea name="textarea-input" id="txtDescp" rows="2" placeholder="Description" class="form-control"></textarea>
                                    <!-- <input type="text" id="txtLang" placeholder="Language" class="form-control m-t-10">                                 -->
                                    <!-- <div class="form-group row m-t-10">
                                        <div class="col-3"> 
                                            <label class="form-control-label m-t-5"> From </label>
                                        </div>                                        
                                        <div class="col-8">
                                            <input type="text" id="txtFrom" placeholder="YYYY-MM-DD" class="form-control">
                                        </div>
                                    </div> 
                                    <div class="form-group row m-t-10">
                                        <div class="col-3"> 
                                            <label class="form-control-label m-t-5"> To </label>
                                        </div>                                        
                                        <div class="col-8">
                                            <input type="text" id="txtTo" placeholder="YYYY-MM-DD" class="form-control">
                                        </div>
                                    </div>     -->                            
                            
                            <label class="form-control-label"> Playing in theatres : </label>
                            <div class="multiselect m-b-5">  
                                <input type="hidden" id="thCount" value="<?php echo sizeof($theatreData) ?>">  
                                <?php
                                    foreach ($theatreData as $key => $value) {
                                ?>
                                    <label>
                                        <input type="checkbox" id="thName<?php echo $key; ?>" name="checkbox<?php echo $key; ?>" value="<?php echo $value['theatre_id']; ?>" />
                                            <?php echo $value['name']; ?>
                                    </label>
                                <?php
                                    }
                                ?>                               
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
							<button id="btnConfirm" type="button" class="btn btn-save" >Confirm</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

    <!-- <script>
        var location;
        location = ["Thrissur", "Kochi", "Kannur", "Kottayam","Palakkad","Kollam","Kozhikode"];
        
        // text = "<tr>";
        location.forEach(myFunction);
        // text += "</tr>";
        document.getElementById("locname").innerHTML = text;
        
        function myFunction(value) {
          text += "<tr> <td>" + value + "</td> </tr>";
        } 
        </script> -->

</body>

</html>
<!-- end document-->
