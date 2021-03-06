<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])||($_SESSION['role']==0)||($_SESSION['role']==2)) header('location: /index.php?status=failed&msg=You need to log in.');
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
        <h2>Update TMS2 Flightlist</h2>
        
        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <?php
            $query = "SELECT MAX(tms2flightlist_date) AS lastUpdatedDate FROM tbl_tms2flightlist LIMIT 1";
            $result = mysqli_query($link, $query);
            while($row = mysqli_fetch_array($result)) {
              $lastUpdatedDate = $row['lastUpdatedDate'];
              $yesterday = date('Y-m-d', strtotime('now +6 hours -1 day'));
              $today = date('Y-m-d', strtotime('now +6 hours'));
              if($lastUpdatedDate==$yesterday||$lastUpdatedDate==$today) {
                ?>
                <p><b class="bg-success"><i class="glyphicon glyphicon-ok"></i> LATENCY IS UP TO DATE</b></p>
                <?php
              }
              else {
                ?>
                <p><b class="bg-danger"><i class="glyphicon glyphicon-exclamation-sign"></i> LATENCY IS NOT UP TO DATE</b></p>
                <?php
              }
            }
            ?>
            <p>Last Updated: <b><?php echo $lastUpdatedDate; ?></b></p>
            <form id="latency-form" class="form-horizontal" method="POST" action="../php/updateLatencyTMS2.php">

              <hr />


              <div class="col-md-2">
                <div class="panel panel-info">
                  <!-- Default panel contents -->
                  <div class="panel-heading">Update Log</div>
                  <!-- List group -->
                  <ul class="list-group">

                  <?php
                  $query = "SELECT DISTINCT(flightlist_date) AS flightlist_date FROM tbl_flightlist ORDER BY flightlist_date DESC LIMIT 5";
                  $result = mysqli_query($link, $query);
                  while($row = mysqli_fetch_array($result)) {
                    ?>
                    <li class="list-group-item"><?php echo date('j M',strtotime($row['flightlist_date'])); ?></li>
                    <?php
                  }
                  ?>

                  </ul>
                </div>
              </div>

              <div class="col-md-10 form-group">
                <textarea id="input-tms" name="flightList" class="form-control" placeholder="Copy and paste daily flight list from TMS2 here..."></textarea>
              </div>

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
