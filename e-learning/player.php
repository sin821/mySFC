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

        <div class="row">
          <div class="col-lg-12">
            <p class="text-muted"><small><span class="text-red">The e-learning is no longer available unless you login again to TMS3. I did not create TMS3, so there is nothing I can do about this. I am sorry. Only Jason Garcia can change this.</span><br />Use the login page by clicking <a href="https://tms3.sfcpl.com/UserLogin/Login" target="_blank">here</a>. Default username should be your surname.givennames e.g. Lim.AhBengLeroy</small></p>
           <!-- <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="https://tms3.sfcpl.com/eBriefingSFC/SFCPL/main.html"></iframe>
            </div> -->
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
