<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bus details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/seat-list-style.css">
    <link rel="stylesheet" href="../css/header-design.css">
    <link rel="stylesheet" href="../css/footer-design.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script>

        var total_seat = <?php echo json_encode($total); ?>;
        var tripID = <?php echo json_encode($tripID); ?>;

        var seat_arr=<?php echo $seat_info; ?>;
        var username=<?php echo json_encode(Session::get('agent-username')); ?>

        var userID=<?php echo json_encode(Session::get('agentID')); ?>;
        var i=0;
        var j=0;

        var total_selected=0;
        var charge=0;
        var unit_charge=0;
        var total_price=0;


        var details=document.getElementById("seat-info");
        var sc=document.getElementById("sc");
        var total=document.getElementById("total");

        var seat_status='';

        var inter_val=setInterval(getStatus,10000);

        function getStatus() {
            jQuery.ajax({
                type:'GET',
                url:'../get-status/'+tripID,
                data:'',
                async:false,
                success:function(data) {
                    //jQuery("#pp").html("<p>"+data.seat_status+"</p>");
                    $("#pp").text(seat_arr[0].status);
                    for(i=0;i<total_seat;i++){
                        var idx=seat_arr[i].id;

                        if(seat_arr[i].status.localeCompare('blocked')){ // not blocked
                            if(data[idx].localeCompare('available')==0){ // some how available
                                seat_arr[i].status='available';
                                if (seat_arr[i].category.localeCompare('Business')==0) {
                                    document.getElementById(i).style.color = '#4b88a6';
                                }
                                else{
                                    document.getElementById(i).style.color = '#CCCCCB';}
                            }
                            else if(data[idx].localeCompare('selected')==0) { // some how selected by him or another
                                document.getElementById(i).style.color = 'forestgreen';
                                if(seat_arr[i].status.localeCompare('available')==0){
                                    seat_arr[i].status='booked';
                                }
                            }
                            else if(data[idx].localeCompare('booked')==0) { // some how selected by him or another
                                document.getElementById(i).style.color = '#78341a';
                                seat_arr[i].status='booked';
                            }
                        }
                    }
                },
                error:function() {
                    $("#pp").text("error");
                }
            });
        }

        function select_seat(id) { //auto refresh

            if(seat_arr[id].status.localeCompare('selected')==0){
                seat_arr[id].status='available';
                jQuery.ajax({
                    type:'GET',
                    url:'../update-status/'+seat_arr[id].id+"/"+'available/'+userID,
                    data:'',
                    success:function(data) {
                        //jQuery("#pp").html("<p>"+data.seat_status+"</p>");
                        //seat_status=data.seat_status;
                    },
                    error:function() {
                        $("#pp").text("error-1");
                    }
                });
                if (seat_arr[id].category.localeCompare('Business')==0) {
                    document.getElementById(id).style.color = '#4b88a6';
                }
                else
                    document.getElementById(id).style.color = '#CCCCCB';

                var elm=document.getElementById('details-'+id);
                elm.parentNode.removeChild(elm);

                charge = charge-unit_charge;
                total_price = total_price - unit_charge - parseInt(seat_arr[id].fare);

                document.getElementById("total").innerHTML = total_price+" Tk";
                document.getElementById("sc").innerHTML = charge + " Tk";

                total_selected--;
                jQuery("#alert-2").hide();
            }
            else if(total_selected>5){
                jQuery("#alert-2").show();
                //document.getElementById("alert-2").show;
            }else {
                //document.getElementById("alert-2").hidden;
                if(seat_arr[id].status.localeCompare('available')==0){
                    seat_arr[id].status='selected';

                    jQuery.ajax({
                        type:'GET',
                        url:'../update-status/'+seat_arr[id].id+"/"+'selected/'+userID,
                        data:'',
                        success:function(data) {
                            //jQuery("#pp").html("<p>"+data.seat_status+"</p>");
                            //seat_status=data.seat_status;
                        },
                        error:function() {
                            $("#pp").text("error-2");
                        }
                    });
                    document.getElementById(id).style.color = 'forestgreen';

                    var param=document.createElement("p");
                    var node=document.createTextNode(seat_arr[id].category+" class : Seat "+seat_arr[id].seatNo+" : "+seat_arr[id].fare+" Tk ");
                    param.appendChild(node);
                    param.setAttribute('id','details-'+id);
                    document.getElementById("seat-info").appendChild(param);

                    var canparam=document.createElement("span");
                    var cannode=document.createTextNode("Cancel");
                    canparam.setAttribute("id","cancel-"+id);
                    canparam.style.color="red";
                    canparam.appendChild(cannode);
                    canparam.setAttribute("onclick","cancel("+id+")");
                    canparam.style.cursor="pointer";
                    param.appendChild(canparam);
                    //document.getElementById("seat-info").innerHTML='checked';

                    charge = charge+unit_charge;
                    total_price = total_price + unit_charge + parseInt(seat_arr[id].fare);

                    document.getElementById("total").innerHTML = total_price+" Tk";
                    document.getElementById("sc").innerHTML = charge + " Tk";

                    total_selected++;
                }
            }

        }

        function cancel(id) {
            //document.getElementById("cancel-"+id).style.color="black";
            seat_arr[id].status='available';
            jQuery.ajax({
                type:'GET',
                url:'../update-status/'+seat_arr[id].id+"/"+'available/'+userID,
                data:'',
                success:function(data) {
                    //jQuery("#pp").html("<p>"+data.seat_status+"</p>");
                    //seat_status=data.seat_status;
                },
                error:function() {
                    $("#pp").text("error-1");
                }
            });
            if (seat_arr[id].category.localeCompare('Business')==0) {
                document.getElementById(id).style.color = '#4b88a6';
            }
            else
                document.getElementById(id).style.color = '#CCCCCB';

            var elm=document.getElementById('details-'+id);
            elm.parentNode.removeChild(elm);

            charge = charge-unit_charge;
            total_price = total_price - unit_charge - parseInt(seat_arr[id].fare);

            document.getElementById("total").innerHTML = total_price+" Tk";
            document.getElementById("sc").innerHTML = charge + " Tk";

            total_selected--;
            jQuery("#alert-2").hide();
        }

        function login_alert() {
            alert("Sign in First");
        }

        function initialization() {

            //document.getElementById("pp").innerHTML=username+tripID;

            for(i=0;i<total_seat;i++){

                if(seat_arr[i].status.localeCompare('available')==0){
                    if (seat_arr[i].category.localeCompare('Business')==0) {
                        document.getElementById(i).style.color = '#4b88a6';
                    }
                    else{
                        document.getElementById(i).style.color = '#CCCCCB';}
                }
                else if(seat_arr[i].status.localeCompare('booked')==0){
                    document.getElementById(i).style.color = '#78341a';
                }
                else if(seat_arr[i].status.localeCompare('blocked')==0){
                    document.getElementById(i).style.color = '#3c3c3c';
                }
                else if(seat_arr[i].status.localeCompare('selected')==0){
                    document.getElementById(i).style.color = 'forestgreen';
                    //document.getElementById(i).innerHTML='hello';

                    if(userID != seat_arr[i].userID) {
                        seat_arr[i].status = 'booked';
                    }
                    else{

                        var param=document.createElement("p");
                        var node=document.createTextNode(seat_arr[i].category+" class : Seat "+seat_arr[i].seatNo+" : "+seat_arr[i].fare+" Tk ");
                        param.appendChild(node);
                        param.setAttribute('id','details-'+i);

                        var canparam=document.createElement("span");
                        var cannode=document.createTextNode("Cancel");
                        canparam.setAttribute("id","cancel-"+i);
                        canparam.style.color="red";
                        canparam.appendChild(cannode);
                        canparam.setAttribute("onclick","cancel("+i+")");
                        canparam.style.cursor="pointer";
                        param.appendChild(canparam);

                        document.getElementById("seat-info").appendChild(param);
                        //document.getElementById("seat-info").innerHTML='checked';

                        charge = charge+unit_charge;
                        total_price = total_price + unit_charge + parseInt(seat_arr[i].fare);

                        document.getElementById("total").innerHTML = total_price+" Tk";
                        document.getElementById("sc").innerHTML = charge + " Tk";

                        total_selected++;
                    }
                }
            }
            if(total_selected<6) {
                jQuery("#alert-2").hide();
            }
        }

    </script>

</head>
<body onload="initialization();">
<div class="section">

    <div id="header">
        <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #120A2A; color: red;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../home" style="color: white;"><span>
                        <i class="glyphicon glyphicon-home"></i></span>Online ticket booking</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="../home">Home</a></li>
                    <li><a href="#footer">Contact</a></li>
                    <li><a href="#footer">About</a></li>
                    <li><a href="#operator-container">Operators</a></li>
                    <li><a href="#operator-container">Routes</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(\Illuminate\Support\Facades\Session::has('agent-username'))
                        @php $username=Session::get('agent-username');@endphp
                        <li><a href="../representative/{{\Illuminate\Support\Facades\Session::get('rep-username')}}">
                            <span style="margin-right: 8px;"><i class="fas fa-user-tie"></i>
                                    {{\Illuminate\Support\Facades\Session::get('agent-username')}}</span></a> </li>
                        <li><a href="../agent-logout"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
                    @else
                        <li><a href="../agent/create"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                        <li><a href="../agent-sign-in"><span class="glyphicon glyphicon-log-in"></span> Sign in</a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>

    <div class="container" style="min-height: 500px;">
        @if(\Illuminate\Support\Facades\Session::has('agent-username'))

        <div id="main-container">
            <div id="couch-text">
                <div class="row">
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: #78341a;"></i></span> Booked(M)
                        </p></div>
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: #DB7484;"></i></span> Booked(F)
                        </p></div>
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: #3c3c3c;"></i></span> Blocked
                        </p></div>
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: #CCCCCB;"></i></span> Economy(A)
                        </p></div>
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: forestgreen;"></i></span> Selected
                        </p></div>
                    <div class="col-sm-2"><p>
                            <span><i class="fas fa-couch  fa-3x" style="color: #4b88a6;"></i></span> Business(A)
                        </p></div>
                </div>
            </div>

            <!--details-->
            <div id="details-container">
                <div class="row">
                    <!--left side -->
                    <div class="col-sm-4">
                        @if($total>30)
                            <div id="details-seat-view">
                                <div id="front-side"><div class="row">
                                        <div class="col-sm-4"><strong>Gate</strong></div>
                                        <div class="col-sm-6 col-sm-offset-2"><strong>Driver</strong></div> </div> </div>
                                @php $idx=0; @endphp
                                @for($i=0;$i<10;$i++)
                                    <div id="seat-view-group">
                                        <div class="row">
                                            @for($j=0;$j<5;$j++)
                                                @if(\Illuminate\Support\Facades\Session::get('agent-username')==null)
                                                    @if($j==0)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==1)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==2)
                                                        <div class="col-sm-2"><span></span></div>
                                                    @elseif($j==3)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==4)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @endif
                                                @else
                                                    @if($j==0)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==1)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==2)
                                                        <div class="col-sm-2"><span></span></div>
                                                    @elseif($j==3)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==4)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @endif
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endfor

                            </div>
                        @else
                            <div id="details-seat-view" style="margin-left: 10%;width: 80%;">
                                <div id="front-side"><div class="row">
                                        <div class="col-sm-4"><strong>Gate</strong></div>
                                        <div class="col-sm-6 col-sm-offset-1"><strong>Driver</strong></div> </div> </div>
                                @php $idx=0; @endphp
                                @for($i=0;$i<10;$i++)
                                    <div id="seat-view-group">
                                        <div class="row">
                                            @for($j=0;$j<5;$j++)
                                                @if(\Illuminate\Support\Facades\Session::get('agent-username')==null)
                                                    @if($j==0)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==1)
                                                        <div class="col-sm-2"><span></span></div>
                                                    @elseif($j==3)
                                                        <div class="col-sm-2 col-sm-offset-1"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==4)
                                                        <div class="col-sm-2"><span onclick="login_alert()"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @endif
                                                @else
                                                    @if($j==0)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==1)
                                                        <div class="col-sm-2"><span></span></div>
                                                    @elseif($j==3)
                                                        <div class="col-sm-2 col-sm-offset-1"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @elseif($j==4)
                                                        <div class="col-sm-2"><span onclick="select_seat({{$idx}})"><i class="fas fa-couch fa-2x" id="{{$idx}}" style="color: #CCCCCB;"></i></span></div>
                                                        @php $idx = $idx+1; @endphp
                                                    @endif
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endfor

                            </div>
                        @endif
                    </div>
                    <!--right side -->
                    <div class="col-sm-8">
                        @php $usern=Session::get('agent-username');      @endphp
                        <form method="post" action="{{url('/agent-booking-details',['id'=>$usern,'tripID'=>$tripID])}}">
                            {{csrf_field()}}
                            <div id="booking-details">
                                <div id="booking-details-top"><h2>Booking information</h2>
                                    <h4>
                                        @if(isset($bdtf))
                                            {{$bdtf->get('busname')}} - {{$bdtf->get('bustype')}}
                                        @endif
                                    </h4>
                                </div>

                                <div id="booking-details-bottom">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h3>Selected seats : </h3>
                                            <div id="seat-info">  </div>
                                            <div id="service-total-info">
                                                <div class="row"><p style="float: left;"><strong>Service charge : </strong></p>
                                                    <p id="sc" style="padding-left: 50px;">0 Tk</p></div>
                                                <div class="row" ><p style="float: left;"><strong>Total : </strong></p>
                                                    <p id="total" style="padding-left: 50px;">0 Tk</p></div>
                                            </div>
                                            <div id="buy-button">
                                                @php $usern=Session::get('agent-username');      @endphp
                                                @if($usern==null)
                                                    <button class="btn btn-success" onclick="login_alert()">Buy now</button>
                                                @else
                                                        <button type="submit" class="btn btn-success">Buy now</button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="form-group">
                                                        <span class="form-label">Mobile No</span>
                                                        <input class="form-control" name="phone_no" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="form-group">
                                                        <span class="form-label">Name</span>
                                                        <input class="form-control" name="fullname" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="form-group">
                                                        <span class="form-label">Gender</span>
                                                        <select class="form-control" name="gender">
                                                            <option>male</option>
                                                            <option>female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="form-group">
                                                        <span class="form-label">Boarding Point</span>
                                                        <select class="form-control" name="boarding">
                                                            <option>Required</option>
                                                            @if(isset($bdtf))
                                                                @foreach($bdtf->get('boarding') as $bd)
                                                                    @foreach($bd as $dt)
                                                                        <option>{{$dt}}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="form-group">
                                                        <span class="form-label">Dropping Point</span>
                                                        <select class="form-control" name="dropping">
                                                            <option>Optional</option>
                                                            @if(isset($bdtf))
                                                                @foreach($bdtf->get('dropping') as $bd)
                                                                    @foreach($bd as $dt)
                                                                        <option>{{$dt}}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        @else
            <h3 style="text-align: center;margin-top: 100px;">Pleage log in first</h3>

        @endif

    </div>

    <div id="footer">
        <div class="container">
            <div class="row-space">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Online Tickets</h2>
                        <span>onlinetickets.com is a premium online booking portal which allows you to purchase tickets for various bus services, launch services, movies and events across the country.</span>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <h2>Company Info</h2>
                        <a href="term-conditions"><li>Terms and Conditins</li></a>
                        <a href="faq"><li>FAQ</li></a>
                        <a href="privacy-policy"><li>Privacy Policy</li></a>
                    </div>
                    <div class="col-sm-3">
                        <h2>About Online Tickets</h2>
                        <a href="about-us"><li>About Us</li></a>
                        <a href="contact-info"><li>Contact Us</li></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




</body>
</html>


