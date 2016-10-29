<?php
session_start([
    'cookie_lifetime' => 86400,
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

        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">Planner Instructions: <span class="pull-right"><i class="fa fa-plus"></i></span></div>
                <!-- List group -->
                <ul class="list-group">
                <?php
                $query = "SELECT * FROM tbl_plannerinstructions JOIN tbl_cadets ON plannerinstruction_creator=cadet_id WHERE plannerinstruction_done='0'";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <li class="list-group-item"><?php echo $row['plannerinstruction_content']; ?> <span class="text-muted pull-right"><small><?php echo $row['cadet_opsname']; ?></small></span></li>
                  <?php
                }
                ?>
                </ul>
              </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-2">

            <div class="panel panel-danger">
              <!-- Default panel contents -->
              <div class="panel-heading">Repeated Sorties:</div>
              <!-- List group -->
              <ul class="list-group">
              <?php
              $query = "SELECT cadet_opsname, sortie_code FROM tbl_repeatedsorties JOIN tbl_cadets ON repeatedsortie_cadet=cadet_id JOIN tbl_sorties ON repeatedsortie_sortie=sortie_id WHERE repeatedsortie_done='0'";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                ?>
                <li class="list-group-item"><?php echo $row['cadet_opsname']; ?><br/ ><span class="text-muted"><small><?php echo $row['sortie_code']; ?></small></span></li>
                <?php
              }
              ?>
              </ul>
            </div>

            <div class="panel panel-info">
              <!-- Default panel contents -->
              <div class="panel-heading">No-Plan List:</div>
              <!-- List group -->
              <ul class="list-group">
              <?php
              $query = "SELECT cadet_opsname, request_noplanstart, request_noplanend, request_remarks FROM tbl_requests JOIN tbl_cadets ON request_cadet=cadet_id WHERE request_type='no-plan' AND (DATE(NOW()) BETWEEN (request_noplanstart-INTERVAL 1 DAY) AND request_noplanend) ORDER BY request_noplanstart DESC";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                ?>
                <li class="list-group-item"><?php echo $row['cadet_opsname']; ?><br/ ><small class="text-muted"><?php echo date('j M',strtotime($row['request_noplanstart'])); ?> - <?php echo date('j M',strtotime($row['request_noplanend'])); ?></small><br /><small><?php echo $row['request_remarks']; ?></small></li>
                <?php
              }
              ?>
              </ul>
            </div>

          </div>
          <div class="col-md-10">
          <?php
          $query = "SELECT instructor_initials FROM tbl_instructors";
          $result = mysqli_query($link, $query);
          while($row = mysqli_fetch_array($result)) {
            ?>
            <div class="col-md-4">
              <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><?php echo $row['instructor_initials']; ?></div>
                <div class="panel-body text-left">
                  <ul>
                    <li>Flies 2 students per day.</li>
                  </ul>
                </div>

                <!-- List group -->
                <ul class="list-group">
                <?php
                $instructor = $row['instructor_initials'];
                $query2 = "SELECT cadet_opsname, total_latency FROM vw_latency JOIN tbl_cadets ON dual_cadet=cadet_name WHERE instructor_initials='$instructor' ORDER BY total_latency DESC, vw_latency.cadet_course ASC";
                $result2 = mysqli_query($link, $query2);
                while($row2 = mysqli_fetch_array($result2)) {
                  if($row2['total_latency']>=5) {
                    $class = "list-group-item list-group-item-danger";
                  }
                  elseif($row2['total_latency']<5 && $row2['total_latency']>=3) {
                    $class = "list-group-item list-group-item-warning";
                  }
                  else {
                    $class = "list-group-item list-group-item-success";
                  }
                  ?>
                  <li class="<?php echo $class; ?>"><?php echo $row2['cadet_opsname']; ?><span class="pull-right"><?php echo $row2['total_latency']; ?></span></li></span></li>
                  <?php
                }
                ?>
                </ul>
              </div>
            </div>
            <?php
          }
          ?>
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
        //initialise datatable
        $('#table').DataTable({
            "paging": false,
            "responsive": true
        });
    } );
    </script>
  </body>
</html>
