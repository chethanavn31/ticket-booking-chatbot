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
    <title>Login</title>

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

    <!-- Main CSS-->
    <link href="css/theme.css?v=1" rel="stylesheet" media="all">
    <!-- <link href="" rel="stylesheet" media="all"> -->

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

    function checkLogin() {        
        var usrname = $('#txtname').val();
        var pwd = $('#txtpwd').val();
        if(usrname==null || usrname=="" || usrname==undefined) {
            toast('Username cannot be null','C');
        }
        else if(pwd==null || pwd=="" || pwd==undefined) {
            toast('Password cannot be null','C');
        }
        else {         
            $.ajax({
                url : 'checkAdminSignin.php',
                type : 'POST',        
                data: { username: usrname, password:pwd },
                success : function(data) {  
                        console.log('......',data);
                        if (data == 'S') {
                            console.log('Success');
                            toast('Valid Admin','S');
                            setTimeout(function(){ window.location.href = 'vdashboard.php'; }, 2000);   
                        }   
                        else {
                            console.log('ERROR');
                            toast('Username or Password incorrect !','E');
                        }                              
                    },
                    error : function(request,error){
                        console.log(error);
                        
                        toast('ERROR !!','E');

                    }
                });
        }            
    }                
    </script>
    
</head>

<body class="animsition">
    <div class="page-wrapper-login" style="background-image:url('./images/login-bg2.jpg'); background-repeat: repeat;" >
        <!-- <div class="page-content--bge5">  -->
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">                    
                            <div class="form-group">
                                <input style="line-height:40px;font-size:24px" class="au-input au-input--full m-b-20" type="text" name="username" placeholder="Username" id="txtname">
                            </div>
                            <div class="form-group">
                                <input style="line-height:40px;font-size:24px" class="au-input au-input--full m-b-30" type="password" name="password" placeholder="Password" id="txtpwd">
                            </div>
                            <!-- <div class="login-checkbox">
                                <label>
                                    <input type="checkbox" name="remember">Remember Me
                                </label>
                            </div> -->
                            <!-- <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Sign in</button> -->
                            <button style="font-size:24px" class="au-btn au-btn--block au-btn--green m-b-20" type="button" onclick="checkLogin()">Sign in</button>
                        </div>
                    </div>
                </div>

                

            </div>
            <div id="snackbar"></div>
        <!-- </div> -->
    </div>

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

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
