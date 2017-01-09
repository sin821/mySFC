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
            <p class="text-muted"><small>This player is optimised by TMS3 to be viewed on a desktop computer. Can't view the player in its full glory? Try opening it in a standalone tab by clicking <a href="https://tms3.sfcpl.com/eBriefingSFC/SFCPL/main.html">here</a>.</small></p>
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="https://tms3.sfcpl.com/eBriefingSFC/SFCPL/main.html"></iframe>
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
