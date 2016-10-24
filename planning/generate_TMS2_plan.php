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
        <h2>Input Today's TMS2 Plan</h2>
        
        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <form id="latency-form" class="form-horizontal" method="POST" action="../php/generatePlanTMS2.php">

              <hr />

              <div class="form-group">
                <textarea id="input-tms" name="flightList" class="form-control" placeholder="Copy and paste today's flight list from TMS2 here..."></textarea>
              </div>

              <hr />

              <div class="form-group">
                <button id="submit-btn" type="submit" class="btn btn-lg btn-block btn-success">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div><!-- /.container -->


    <?php include('../modules/scripts.php'); ?>

    <!-- Page-dependent plugin scripts -->

    <!-- Page-dependent custom scripts -->

  </body>
</html>
