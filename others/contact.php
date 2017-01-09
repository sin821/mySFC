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
        <h2>Request a Feature</h2>
        
        <div class="row">
          <div class="col-lg-offset-2 col-lg-8 col-md-12">
            <form method="POST" action="../php/sendComment.php">

              <hr />

                <div class="form-group">
                  <textarea class="form-control" id="commentBox" name="message" placeholder="Drop an anonymous suggestion here!" required></textarea>
                </div>

              <hr />

              <button type="submit" class="btn btn-success btn-block">Submit</button>
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
