<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');

$cadet_syllabus = $_SESSION['syllabus'];
$cadet_name = $_SESSION['name'];

$query = "SELECT COUNT(sortie_code) AS cadet_TotalSorties FROM tbl_sorties JOIN tbl_syllabus ON sortie_syllabus=syllabus_code WHERE syllabus_code='$cadet_syllabus'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)){
  $db_TotalSorties = $row['cadet_TotalSorties'];
}

$query = "SELECT COUNT(flightlist_sortie) AS cadet_CompletedSorties FROM tbl_flightlist WHERE flightlist_status = 'PostFlight' AND (flightlist_pilot1 = '$cadet_name' OR flightlist_pilot2 = '$cadet_name') ORDER BY flightlist_id";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)){
  $db_CompletedSorties = $row['cadet_CompletedSorties'];
}
$db_IncompleteSorties = $db_TotalSorties - $db_CompletedSorties;
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">

        <div class="row">
          <div class="col-md-3">
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">Progress</div>
                <!-- Pie Chart -->
                <div id="Progress_Chart"></div>

              </div>
          </div>
          <div class="col-md-4">
            <div class="panel panel-primary">
              <!-- Default panel contents -->
              <div class="panel-heading">Instructor</div>
              <div class="panel-body text-left">
                <p>Instructor notes:</p>
                <ul>
                  <li>Flies 2 students per day.</li>
                </ul>
              </div>
                <!-- List group -->
              <ul class="list-group">
                <li class="list-group-item">Cadet 1 <span class="pull-right">5</span><br /><small>195</small></li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
            <div class="panel panel-primary">
              <!-- Default panel contents -->
              <div class="panel-heading">Instructor</div>
              <div class="panel-body text-left">
                <p>Instructor notes:</p>
                <ul>
                  <li>Flies 2 students per day.</li>
                </ul>
              </div>
                <!-- List group -->
              <ul class="list-group">
                <li class="list-group-item">Cadet 1 <span class="pull-right">5</span><br /><small>195</small></li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-3">

            <div class="panel panel-danger">
              <!-- Default panel contents -->
              <div class="panel-heading">Repeated Sorties:</div>
              <!-- List group -->
              <ul class="list-group">
                <li class="list-group-item">Cadet 1 <br/ ><span class="text-muted"><small>B135</small></span></li>
                <li class="list-group-item">Cadet 2 <br/ ><span class="text-muted"><small>M114</small></span></li>
              </ul>
            </div>

            <div class="panel panel-info">
              <!-- Default panel contents -->
              <div class="panel-heading">No-Plan List:</div>
              <!-- List group -->
              <ul class="list-group">
                <li class="list-group-item">Cadet 1 <br/ ><span class="text-muted"><small>15 Oct to 16 Oct</small></span></li>
                <li class="list-group-item">Cadet 2 <br/ ><span class="text-muted"><small>17 Oct to 20 Oct</small></span></li>
                <li class="list-group-item">Cadet 3 <br/ ><span class="text-muted"><small>19 Oct to 30 Oct</small></span></li>
              </ul>
            </div>

          </div>
          <div class="col-md-9">
            <div class="col-md-4">
              <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">Instructor</div>
                <div class="panel-body text-left">
                  <p>Instructor notes:</p>
                  <ul>
                    <li>Flies 2 students per day.</li>
                  </ul>
                </div>

                <!-- List group -->
                <ul class="list-group">
                  <li class="list-group-item">Cadet 1 <span class="pull-right">5</span><br /><small>195</small></li>
                  <li class="list-group-item">Dapibus ac facilisis in</li>
                  <li class="list-group-item">Morbi leo risus</li>
                  <li class="list-group-item">Porta ac consectetur ac</li>
                  <li class="list-group-item">Vestibulum at eros</li>
                </ul>
              </div>
            </div>
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


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>

    $(document).ready( function () {

        $('#Progress_Chart').height('282');

        myDonut = Morris.Donut({
          element: 'Progress_Chart',
          resize: true,
          data: [
            {label: "Incomplete Sorties", value: <?php echo $db_IncompleteSorties; ?>},
            {label: "Complete Sorties", value: <?php echo $db_CompletedSorties; ?>},
          ],
          colors: [
            '#FE4D4D', '#05E177'
          ]
        }).select(1);

    } );
    </script>
  </body>
</html>
