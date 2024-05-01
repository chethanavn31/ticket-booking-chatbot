<?php
  require('dbFunctions.php');
  $mID = $_POST['mid'];
  $thID = $_POST['thid'];
  $locID = $_POST['locid'];
  $shID = $_POST['shid'];
  $userID = $_POST['userId'];
  $bookdate = $_POST['date'];

  $bookingData = getBookingData($mID,$thID,$shID,$locID,$userID,$bookdate);   
  if($bookingData != "N" || $bookingData != "E")  {        
    $seatArr = [];
    $seats = "";
    foreach ($bookingData as $key => $value) {
        $seats = $seats.$value['seatNumber'].',';
    }
    $seats = rtrim($seats, ", ");
    $seatArr  = (explode(",",$seats));
  }
  $thSeatDetails = getSeatDetails($thID);
  $typeArrayMain=[];
  
?>

<html>

<head>
  <link rel="stylesheet" href="design.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
  <script>
    $(document).ready(function () {
      let markup = '';
      let c = 65;
      for (var i = 0; i < 13; i++, c++) {
        if (i == 6 || i == 11) {
          markup = markup + '<tr> <td></td></tr>  <tr><td></td></tr>';
        }
        markup = markup + '<tr>';
        for (var j = 0; j <= 10; j++) {
          if (j == 0) {
            markup = markup + '<td><label style="font-size: 16px;border: none;" class="rowSeat">' 
            + String.fromCharCode(c) + '</label></td>';
          }
          else {
            markup = markup + '<td><button id="' + String.fromCharCode(c) + j + '" class="seatEmpty" onclick="btnClick(getElementById(id))">' +
              j + '</button></td>';
          }

          if (j == 2 || j == 8) {
            markup = markup + '<td></td> <td></td> <td></td>';
          }
        }
        markup = markup + '</tr>';

      }
      tableBody = $("#seatTable tbody");
      tableBody.append(markup);

      // console.log($('#txtseat').val());
      var seatArr = JSON.parse($('#txtseat').val());
      if(seatArr != "") {
        for(var i=0; i<seatArr.length; i++) {
          console.log("----",seatArr[i]);
          $("#"+seatArr[i]).attr('disabled','disabled');
          $("#"+seatArr[i]).removeClass("seatEmpty")
          $("#"+seatArr[i]).addClass("seatBooked");

        }
      }
    });

    function btnClick(e){
      classAddRemove(e);
      var eid = e.id.toString();
      var classname = $("#"+e.id).attr('class');
      // console.log('ssssssss',id,);
      var name = $("#seatName").text().toString();
      var count = $("#seatCount").text().toString();
      // console.log('aaaaaaaaaaaaaaa',count,Number(count));
       if (classname == "seatSelected") {
        if (!name.trim()) {
          $("#seatName").text(eid);
          $("#seatCount").text("1");
          // console.log('000',$("#seatCount").text().toString());
        }
        else {
          $("#seatName").text(name + ',' + eid);
          var cnt = Number(count);
          cnt = cnt + 1;
          $("#seatCount").text(cnt);
          // console.log('0+1',$("#seatCount").text().toString());
        }
        calculateAmount(e.id,'A');
      }
      else {
        // console.log('aad', eid, name, name.indexOf(eid));
        if (name.indexOf(eid) == 0) {
          var str = $("#seatName").text().replace(eid, '');
          if (name.indexOf(',') == 2 || name.indexOf(',') == 3) {
            var str = $("#seatName").text().replace(eid + ',', '');
          }
        }
        else {
          var str = $("#seatName").text().replace(',' + eid, '');
        }
        var cnt = Number(count);
        cnt = cnt - 1;
        $("#seatCount").text(cnt);
        $("#seatName").text(str);
        calculateAmount(e.id,'S');
      }     
}
function classAddRemove(e) {
  var classname = $("#"+e.id).attr('class');
  if (classname == "seatEmpty") {
        // console.log('eeeee',classname);
        $("#"+e.id).removeClass("seatEmpty");
        $("#"+e.id).addClass("seatSelected");
       
  }
  else {
    // console.log('ssssssss',classname);
        $("#"+e.id).removeClass("seatSelected");
        $("#"+e.id).addClass("seatEmpty");
  }
}

function calculateAmount(btnID,cal) {
  var rSeat = btnID.split("");
  var amount = parseInt($('#totalAmount').html());
  if(cal == 'A') {
    amount = amount + parseInt($('#'+rSeat[0]).val());
    console.log('AAA',amount);
  }
  else if (cal == 'S') {
    amount = amount - parseInt($('#'+rSeat[0]).val());
    console.log('SSSS',amount);
  }
  $('#totalAmount').html(amount.toString());
}

function confirmBooking() {
  console.log('confirm Booking');
  // {$bdate: , $bcount:, $bseat:, $bamount:, }

  var bDate = $('#txtdate').val();
  var bCount = parseInt($("#seatCount").text());
  var bSeat = $("#seatName").text();
  var bAmount = $('#totalAmount').html();
  var bMovie = $('#txtmid').val();
  var bTheatre = $('#txtthid').val();
  var bShow = $('#txtshid').val();
  var bLoc = $('#txtlocid').val();
  var bUser = $('#txtuserid').val();
            
  if((bDate==null || bDate=="" || bDate==undefined) || (bCount==null || bCount=="" || bCount==undefined) ||
  (bSeat==null || bSeat=="" || bSeat==undefined) || (bAmount==null || bAmount=="" || bAmount==undefined) || 
  (bMovie==null || bMovie=="" || bMovie==undefined) || (bTheatre==null || bTheatre=="" || bTheatre==undefined) ||
  (bShow==null || bShow=="" || bShow==undefined) || (bLoc==null || bLoc=="" || bLoc==undefined) || 
  (bUser==null || bUser=="" || bUser==undefined)){
      console.log('Missing parameters');
  }
  else {
    $.ajax({
      url : 'insertBooking.php',
      type : 'POST', 
      data: {bdate:bDate, bcount:bCount, bseat:bSeat, bamount:bAmount,
             bmov:bMovie, bth:bTheatre, bsh:bShow, bloc:bLoc, buser:bUser },
      success : function(data) {              
          console.log('Success');
          //var selRowindex = $('#selectedRowId').val(); 
          if (data == 'S') {
            toast('Booked','S');                              
            setTimeout(function(){ window.location.href = 'ticketConfirmation.php'; }, 2000);  
            successBot(bUser);  
          }
          else {

          }

          $('#txtdate').val('');
          $("#seatCount").text('');
          $("#seatName").text('');
          $('#totalAmount').html('0');
          $('#txtmid').val('');
          $('#txtthid').val('');
          $('#txtshid').val('');
          $('#txtlocid').val('');
          $('#txtuserid').val('');                                            
      },
      error : function(request,error){
          console.log('Error '+JSON.stringify(request))
          //$('#dbResponse').html('Error');
          toast('ERROR !!','E');

      }
      });      
  }  
  console.log(bDate, bCount, bSeat, bAmount, bMovie, bTheatre, bShow, bLoc, bUser );
}

function successBot(uID) {
    $.ajax({
      url : 'http://localhost:5700/ack',
      type : 'POST', 
      data: {senderID : uID },
      success : function(data) {              
          console.log('Success');
                                                                   
      },
      error : function(request,error){
          console.log('Error '+JSON.stringify(request))
        
      }
      });       
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

</script>
</head>

<body background="bg.jpg" >
  <div class="row">
    <div class="column" style="background-color: darkgray;">
      <table>
        <tr style="border : 2px solid;">
          <th class="shadow"> SCREEN </th>
        </tr>
      </table>

      <div style="margin-top: 10px;">
        <table id="seatTable" >
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
    
    <?php
          // $bookingData = getBookingData($mID,$thID,$shID,$locID,$userID);
          $date = new DateTime($bookdate);
          $bkDt = $date;
          $formattedDate = $bkDt->format('M d, Y');
          $bkDt =$date;
          $day = $bkDt->format('l');

          $ticketData = getTicketDetails($mID,$thID,$shID,$locID); 
      ?> 

    <div class="column">
      <input type="hidden" id="txtthid" value="<?php echo $thID; ?>">
      <input type="hidden" id="txtdate" value="<?php echo $bookdate; ?>">
      <input type="hidden" id="txtshid" value="<?php echo $shID; ?>">
      <input type="hidden" id="txtmid" value="<?php echo $mID; ?>">
      <input type="hidden" id="txtlocid" value="<?php echo $locID; ?>">
      <input type="hidden" id="txtuserid" value="<?php echo $userID; ?>">
      <input type="hidden" id="txtseat" value='<?php echo json_encode($seatArr); ?>'>


      <?php
          foreach ($thSeatDetails as $key => $value) {
            $sfrom = $value['fromSeat'];
            $sto = $value['toSeat'];
            $fAlpha  = str_split($sfrom);
            $tAlpha  = str_split($sto);

            $fromAsci = ord($fAlpha[0]);
            $toAsci = ord($tAlpha[0]);

            // echo $fromAsci,"    ",$toAsci,"  ";

            for ($i=$fromAsci;$i<=$toAsci;$i++){
              ?>
                <input type="hidden" id="<?php echo chr($i); ?>" value="<?php echo $value['amount'];?>" >
              <?php
            }
          }
      ?>

      <table class="tablecard ticket" style="table-layout: fixed;">
      <tr>        
          <th colspan="2px"> <?php echo $ticketData[0]['theatre']; ?>, <?php echo $ticketData[0]['location']; ?> </th>
        </tr>
        <tr>
          
          <th colspan="2px"> 
            <img class="imgMov" src="<?php echo $ticketData[0]['poster']; ?>" > </th>
        </tr>

        <tr>
          <td> <?php echo $ticketData[0]['showtype']; ?> </td>
          <td> <?php echo $ticketData[0]['timeFrom']; ?> </td>
        </tr>
        
        <tr>
          <td> <?php echo $formattedDate; ?> </td>
          <td> <?php echo $day; ?> </td>
        </tr>
        
        <tr> 
          <td>Tickets :</td>
          <td id="seatCount">  </td>
        </tr>

        <tr> 
          <td>Seats :</td>
          <td id="seatName" style="word-break: break-all;"> </td>
        </tr>

        <tr>
          <!-- <th colspan="2px"> ----------------------------- </th> -->
          <td> ----------------------------------  </td>
          <td> ----------------------------------  </td>
        </tr>

        <tr>
          <td> Total (Rs.) : </td>
          <td id="totalAmount"> 0 </td>
        </tr>

        <tr>
          <!-- <th colspan="2px"> ----------------------------- </th> -->
          <td> ----------------------------------  </td>
          <td> ----------------------------------  </td>
        </tr>

        <tr>
          <!-- <td> <button class="btn"> CANCEL </button> </td> -->
          <th colspan="2px">
             <button type="button" class="btn" onclick="confirmBooking()"> CONFIRM </button> </th>
        </tr>
      </table>
    </div>
    <div id="snackbar"></div>
  </div>

</body>

</html>