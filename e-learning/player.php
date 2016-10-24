<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
$brief = $_GET['brief'];
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
          <div class="col-lg-offset-1 col-lg-10 col-sm-12">
            <div class="text-left">
              <a href="contents.php"><i class="fa fa-chevron-left"></i> Back</a>
              <span class="pull-right">Next Video <i class="fa fa-chevron-right"></i></span>
            </div>
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="https://s3-ap-southeast-2.amazonaws.com/elasticbeanstalk-ap-southeast-2-398500234633/e-learning/<?php echo $brief; ?>/player.html"></iframe>
            </div>
            <div class="text-left">
              <a href="contents.php"><i class="fa fa-chevron-left"></i> Back</a>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container -->


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        //initialise datatable
        $('#table').DataTable();
    } );
    </script>
  </body>
</html>
