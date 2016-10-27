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
                <div class="panel-heading">Planner Instructions:</div>
                <!-- List group -->
                <ul class="list-group">
                  <li class="list-group-item">Instruction 1</li>
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
        //initialise datatable
        $('#table').DataTable({
            "paging": false,
            "responsive": true
        });
    } );
    </script>
  </body>
</html>
