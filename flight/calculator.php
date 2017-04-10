<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">
        <h2>Mass, Balance & Performance Calculator</h2>
        <?php
        //get METAR
        $metar_xml = simplexml_load_file('https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=3&mostRecent=true&stationString=YPJT');
        $metar = $metar_xml->data->METAR->raw_text;

        if($metar==null) {
          $metar = "No METAR available.";
        }
        else {
          $temp = $metar_xml->data->METAR->temp_c;
          $alt_hg = $metar_xml->data->METAR->altim_in_hg;
          $qnh = round(floatval($alt_hg) * 33.8639);
          $temp = round($temp);
        }
        ?>
        <div class="row">
          <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">Loadsheet for Cessna</div>

                <div class="panel-body" id="cessnaLoadsheet">
                    <div class="row">
                      <div class="col-xs-6 load-form-group">
                        <label>Select Aircraft:</label>
                        <select id="aircraftInput" class="form-control input-md text-center" style="text-align-last:center;" onchange="calculateBalance()">
                        <?php
                        //get aircraft information
                        $query = "SELECT * FROM tbl_aircrafts WHERE aircraft_type='cessna'";
                        $result = mysqli_query($link, $query);
                        while($row = mysqli_fetch_array($result)) {
                          $reg = $row['aircraft_reg'];
                          $aircraft[$reg]['weight'] = $row['aircraft_weight'];
                          $aircraft[$reg]['moment'] = $row['aircraft_moment'];
                          ?>
                            <option value="<?php echo $reg; ?>"><?php echo $reg; ?></option>
                          <?php
                        }
                        ?>
                        </select>
                        <small class="text-muted">Looking for YH aircraft? <a href="calculator_leased.php">Click Here.</a></small>
                      </div>
                      <div class="col-xs-6 load-form-group">
                        <label>Fuel:</label>
                        <input id="fuelInput" type="number" class="form-control input-md text-center" value="185" min="0" max="220" onkeyup="calculateBalance()" onchange="calculateBalance()" />
                      </div>
                    </div>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                          <tr>
                            <th class="col-xs-6 text-center">Item</th>
                            <th class="col-xs-3 text-center">Weight</th>
                            <th class="col-xs-3 text-center">Moment</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic Weight<br /><small>(incl. unusable fuel and full oil)</small></td>
                                <td><span id="basicWeight"></span></td>
                                <td><span id="basicMoment"></span></td>
                            </tr>
                            <tr>
                              <?php
                              $cadet = $_SESSION['cadet'];
                              $query = "SELECT cadet_weight, instructor_weight FROM tbl_cadets JOIN tbl_instructors ON cadet_instructor=instructor_id WHERE cadet_id='$cadet'";
                              $result = mysqli_query($link, $query);
                              while($row = mysqli_fetch_array($result)) {
                                $cadet_weight = $row['cadet_weight'];
                                $instructor_weight = $row['instructor_weight'];
                                $total_weight = $cadet_weight + $instructor_weight;
                              }
                              ?>
                                <td>Pilot & Front Passenger<br /><small><a onclick="viewInstructorWeights()">Instructor Weights</a></small></td>
                                <td>
                                  <input id="frontPaxInput" type="number" class="form-control input-md text-center" value="<?php echo $total_weight; ?>" min="0" max="400" onkeyup="calculateBalance()" onchange="calculateBalance()" />
                                </td>
                                <td><span id="frontPaxMoment"></span></td>
                            </tr>
                            <tr>
                                <td>Rear Passenger</td>
                                <td>
                                  <input id="rearPaxInput" type="number" class="form-control input-md text-center" value="0" min="0" max="200" onkeyup="calculateBalance()" onchange="calculateBalance()" />
                                </td>
                                <td><span id="rearPaxMoment"></span></td>
                            </tr>
                            <tr>
                                <td>Baggage Area 1<br /><small>(max 54.4kg)</small></td>
                                <td>
                                  <input id="bag1Input" type="number" class="form-control input-md text-center" value="5" min="0" max="54.4" onkeyup="calculateBalance()" onchange="calculateBalance()" />
                                </td>
                                <td><span id="bag1Moment"></span></td>
                            </tr>
                            <tr>
                                <td>Baggage Area 2<br /><small>(max 22.7kg)</small></td>
                                <td>
                                  <input id="bag2Input" type="number" class="form-control input-md text-center" value="0" min="0" max="22.7" onkeyup="calculateBalance()" onchange="calculateBalance()" />
                                </td>
                                <td><span id="bag2Moment"></span></td>
                            </tr>
                            <tr class="active">
                                <td class="text-center"><b>Zero Fuel Weight</b></td>
                                <td><span id="zeroFuelWeight"></span></td>
                                <td><span id="zeroFuelMoment"></span></td>
                            </tr>
                            <tr>
                                <td>Usable Fuel</td>
                                <td><span id="fuelWeight"></span></td>
                                <td><span id="fuelMoment"></span></td>
                            </tr>
                            <tr class="success">
                                <td class="text-center text-uppercase"><b>Total</b></td>
                                <td><span id="takeOffWeight"></span></td>
                                <td><span id="takeOffMoment"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <span id="massError" class="text-red"></span>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Weather</div>

                <div class="panel-body">
                <p>METAR: </p>
                <code id="metar"><?php echo $metar; ?></code>
                <hr />
                <p>TAF:</p>
                <code id="taf"><i class="fa fa-spinner fa-spin"></i> Searching for TAF...</code>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">Performance Calculations</div>

                <div class="panel-body" id="cessnaPerformance">
                    <div class="row">
                        <div class="col-xs-4 load-form-group">
                            <label class="form-label">Temp: </label><input id="tempInput" class="form-control input-md text-center" type=number value="<?php echo $temp; ?>" onkeyup="calculatePerformance()" onchange="calculatePerformance()" />
                        </div>
                        <div class="col-xs-4 load-form-group">
                            <label class="form-label">QNH: </label><input id="qnhInput" class="form-control input-md text-center" type="number" value="<?php echo $qnh; ?>" onkeyup="calculatePerformance()" onchange="calculatePerformance()" />
                        </div>
                        <div class="col-xs-4 load-form-group">
                            <label class="form-label">T-Wind: </label><input id="tWind" class="form-control input-md text-center" type="number" value="0" onkeyup="calculatePerformance()" onchange="calculatePerformance()" />
                        </div>
                        <span id="perfError" class="text-red"></span>
                        <p><small>Pressure Altitude: <u><span id="pressureAlt"></span>ft</u></small></p>
                    </div>

                    <div class="well well-sm col-xs-12">
                        <div class="row col-xs-12">
                            <h5><b>Takeoff Ground Roll Required:</b></h5>
                            <div class="col-xs-4"><p class="text-center"><u><span id="takeoff0"></span></u> (ft)</p></div>
                            <div class="col-xs-4"><p><u><span id="takeoff0m"></span></u> (m)</p></div>
                        </div>

                        <div class="row col-xs-12">
                            <h5><b>Takeoff Distance to 50ft:</b></h5>
                            <div class="col-xs-4"><p class="text-center"><u><span id="takeoff50"></span></u> (ft)</p></div>
                            <div class="col-xs-4"><p><u><span id="takeoff50m"></span></u> (m)</p></div>
                        </div>
                    </div>

                    <div class="well well-sm col-xs-12">
                        <div class="row col-xs-12">
                            <h5><b>Landing Ground Roll Required:</b></h5>
                            <div class="col-xs-4"><p class="text-center"><u><span id="landing0"></span></u> (ft)</p></div>
                            <div class="col-xs-4"><p><u><span id="landing0m"></span></u> (m)</p></div>
                        </div>

                        <div class="row col-xs-12">
                            <h5><b>Landing Distance Required from 50ft:</b></h5>
                            <div class="col-xs-4"><p class="text-center"><u><span id="landing50"></span></u> (ft)</p></div>
                            <div class="col-xs-4"><p><u><span id="landing50m"></span></u> (m)</p></div>
                        </div>
                    </div>
                    <p><small class="text-muted">Will interpolate for temperature and pressure altitude (assumes sea level for PA less than 0 feet).</small></p>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Other Information</div>

                <div class="panel-body" id="cessnaOthers">
                    <table class="table table-bordered table-hover table-condensed">
                        <tbody>
                            <tr>
                                <td><b>Fuel:</b><span class="pull-right"><u><span id="fuelAmount"></span></u> (litres)</span></td>
                                <td><b>Endurance:</b><span class="pull-right"><u><span id="endurance"></span></u> (minutes)</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <p><small class="text-muted">Subtracts 6 litres for taxi fuel as per SFC SOP.</small></p>
                </div>
            </div>

            <p><small class="text-muted">Disclaimer: This flight planning tool is only meant to assist in expediting calculations when rushing for time. You are expected to know and understand how to calculate this manually. In using this tool you agree that it is ultimately your responsilibity to ensure the data within is correct to ensure a safe flight.</small></p>

        </div>

        </div>
      </div>

    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="ajax"></div>
      </div>
    </div>


    <?php include('../modules/scripts.php'); ?>

    <!-- Page-dependent plugin scripts -->

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        calculateBalance();
        calculatePerformance();
        getTAF();
    } );

    var aircraft = <?php echo json_encode($aircraft); ?>;

    function getTAF() {
      $('#taf').load( encodeURI("../ajax/getTaf.php") ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }

    function calculateBalance() {
      var error = "";
      var errorSpan = document.getElementById('massError');

      var userAircraft = document.getElementById('aircraftInput').value;
      var fuel = document.getElementById('fuelInput').value;
      var frontPax = document.getElementById('frontPaxInput').value;
      var rearPax = document.getElementById('rearPaxInput').value;
      var bag1 = document.getElementById('bag1Input').value;
      var bag2 = document.getElementById('bag2Input').value;
      document.getElementById('fuelAmount').innerHTML = fuel;

      //update aircraft basic mass and moment
      document.getElementById('basicWeight').innerHTML = aircraft[userAircraft]['weight'];
      document.getElementById('basicMoment').innerHTML = aircraft[userAircraft]['moment'];

      //update zero fuel weight
      var zeroFuelWeight = +aircraft[userAircraft]['weight'] + +frontPax + +rearPax + +bag1 + +bag2;
      document.getElementById('zeroFuelWeight').innerHTML = zeroFuelWeight;

      //update fuel weight
      var fuelWeight = Math.round(fuel * 0.72);
      document.getElementById('fuelWeight').innerHTML = fuelWeight;

      //update take off weight
      var takeOffWeight = +zeroFuelWeight + +fuelWeight;
      document.getElementById('takeOffWeight').innerHTML = takeOffWeight;

      //calculate and update moments
      var frontPaxMoment = Math.round(frontPax * 0.95428571);
      document.getElementById('frontPaxMoment').innerHTML = frontPaxMoment;

      var rearPaxMoment = Math.round(rearPax / 0.53880597);
      document.getElementById('rearPaxMoment').innerHTML = rearPaxMoment;

      var bag1Moment = Math.round(bag1 * 2);
      document.getElementById('bag1Moment').innerHTML = bag1Moment;

      var bag2Moment = Math.round(bag2 / 0.36666667);
      document.getElementById('bag2Moment').innerHTML = bag2Moment;

      var zeroFuelMoment = +aircraft[userAircraft]['moment'] + +frontPaxMoment + +rearPaxMoment + +bag1Moment + +bag2Moment;
      var fuelMoment = Math.round(fuelWeight * 1.22881356);
      document.getElementById('fuelMoment').innerHTML = fuelMoment;
      var takeOffMoment = +zeroFuelMoment + +fuelMoment;

      //update moments
      document.getElementById('zeroFuelMoment').innerHTML = zeroFuelMoment;
      document.getElementById('takeOffMoment').innerHTML = takeOffMoment;

      //calculate fuel endurance, 5 litres deducted for taxi
      var endurance = Math.round(((fuel - 6)/35)*60);
      document.getElementById('endurance').innerHTML = endurance;

      //push alerts to user if out of range
      if(takeOffWeight>1114) {
        error += "Takeoff Weight exceeds 1114kg!<br />";
      }
      if(fuel>190) {
        error += "Are you sure your fuel is more than 190 litres?<br />";
      }
      if(bag1>54.4) {
        error += "Baggage compartment 1 is too heavy, take something out.<br />";
      }
      if(bag2>22.7) {
        error += "Baggage compartment 2 is too heavy, take something out.<br />";
      }

      errorSpan.innerHTML = error;
    }

    function calculatePerformance() {
      var error = "";
      var errorSpan = document.getElementById('perfError');

      var qnh = document.getElementById('qnhInput').value;
      var temp = document.getElementById('tempInput').value;
      var tailwind = document.getElementById('tWind').value;
      if(temp>=0&&temp<10) {
       tempCategory = 1;
      }
      else if(temp>=10&&temp<20) {
       tempCategory = 2;
      }
      else if(temp>=20&&temp<30) {
       tempCategory = 3;
      }
      else if(temp>=30&&temp<=40) {
       tempCategory = 4;
      }
      else {
       tempCategory = 0;
      }

      //calculate pressure altitude
      var pressureAlt = 100 + ((1013 - +qnh)*30);
      document.getElementById('pressureAlt').innerHTML = pressureAlt;

      if(pressureAlt<=0) {
        var pressureAltCorrectionFactor = 0;
      }
      else {
        var pressureAltCorrectionFactor = pressureAlt/100;
      }

      //calculate tailwind margin
      var tailWindMargin = 1 + Math.ceil(tailwind/2) * 0.1;

      //calculate take off performance
      switch (tempCategory) {
          case 1: takeoff0 = ((6.5 * temp) + 845 + (pressureAltCorrectionFactor * 8)) * 1.15 * tailWindMargin;
              break;
          case 2: takeoff0 = ((7 * (temp-10)) + 910 + (pressureAltCorrectionFactor * 9)) * 1.15 * tailWindMargin;
              break;
          case 3: takeoff0 = ((7.5 * (temp-20)) + 980 + (pressureAltCorrectionFactor * 9.5)) * 1.15 * tailWindMargin;
              break;
          case 4: takeoff0 = ((8 * (temp-30)) + 1055 + (pressureAltCorrectionFactor * 10.5)) * 1.15 * tailWindMargin;
              break;
          default: takeoff0 = ((0 * (temp-100)) + 0) * 1.15 * tailWindMargin;
              break;
      }

      switch (tempCategory) {
          case 1: takeoff50  = ((11.5 * temp) + 1510 + (pressureAltCorrectionFactor * 15)) * 1.15 * tailWindMargin;
              break;
          case 2: takeoff50  = ((12 * (temp-10)) + 1625 + (pressureAltCorrectionFactor * 16.5)) * 1.15 * tailWindMargin;
              break;
          case 3: takeoff50  = ((12.5 * (temp-20)) + 1745 + (pressureAltCorrectionFactor * 18)) * 1.15 * tailWindMargin;
              break;
          case 4: takeoff50  = ((13 * (temp-30)) + 1875 + (pressureAltCorrectionFactor * 19.5)) * 1.15 * tailWindMargin;
              break;
          default: takeoff50  = ((0 * (temp-100)) + 0) * 1.15 * tailWindMargin;
              break;
      }

        //calculate landing performance
        switch (tempCategory) {
          case 1: landing0 = ((1.5 * temp) + 525 + (pressureAltCorrectionFactor * 2)) * 1.15 * tailWindMargin;
              break;
          case 2: landing0 = ((2 * (temp-10)) + 540 + (pressureAltCorrectionFactor * 2)) * 1.15 * tailWindMargin;
              break;
          case 3: landing0 = ((2.5 * (temp-20)) + 560 + (pressureAltCorrectionFactor * 2)) * 1.15 * tailWindMargin;
              break;
          case 4: landing0 = ((3 * (temp-30)) + 580 + (pressureAltCorrectionFactor * 2)) * 1.15 * tailWindMargin;
              break;
          default: landing0 = ((0 * (temp-100)) + 0) * 1.15 * tailWindMargin;
              break;
      }

      switch (tempCategory) {
          case 1: landing50  = ((3 * temp) + 1250 + (pressureAltCorrectionFactor * 3)) * 1.15 * tailWindMargin;
              break;
          case 2: landing50  = ((3 * (temp-10)) + 1280 + (pressureAltCorrectionFactor * 3)) * 1.15 * tailWindMargin;
              break;
          case 3: landing50  = ((3 * (temp-20)) + 1310 + (pressureAltCorrectionFactor * 3.5)) * 1.15 * tailWindMargin;
              break;
          case 4: landing50  = ((3 * (temp-30)) + 1340 + (pressureAltCorrectionFactor * 3.5)) * 1.15 * tailWindMargin;
              break;
          default: landing50  = ((0 * (temp-100)) + 0) * 1.15 * tailWindMargin;
              break;
      }

      document.getElementById('takeoff0').innerHTML = Math.round(takeoff0);
      document.getElementById('takeoff50').innerHTML = Math.round(takeoff50);
      document.getElementById('landing0').innerHTML = Math.round(landing0);
      document.getElementById('landing50').innerHTML = Math.round(landing50);

      document.getElementById('takeoff0m').innerHTML = Math.round(takeoff0 * 0.3048);
      document.getElementById('takeoff50m').innerHTML = Math.round(takeoff50 * 0.3048);
      document.getElementById('landing0m').innerHTML = Math.round(landing0 * 0.3048);
      document.getElementById('landing50m').innerHTML = Math.round(landing50 * 0.3048);

      if(temp>=40) {
        error += "It is too hot, maybe you should not fly.<br />";
      }
      if(temp<0) {
        error += "Oops, this calculator does not go below 0 degrees celcius.<br />";
      }
      if(qnh<900) {
        error += "The pressure seems too low! Did your ears pop?<br />";
      }
      if(qnh>1100) {
        error += "The pressure seems too high! Did the atmosphere composition change or something?<br />";
      }

      errorSpan.innerHTML = error;

    }

    function viewInstructorWeights() {
      $('#ajax').load( encodeURI("../ajax/modal_instructorWeights.php") ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    </script>

  </body>
</html>
