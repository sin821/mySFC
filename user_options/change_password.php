<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
$cadet_id = $_SESSION['cadet'];
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">
        <h2>Change Password</h2>

        <div class="row">

          <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8 col-xs-12">
              <div class="panel panel-default">
                  <div class="panel-heading">Change Password</div>

                  <div class="panel-body">
                    <div class="row">
                      <div class="col-xs-12">
                        <form class="form-horizontal" method="POST" action='../php/updatePassword.php'>
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-5 control-label">Old Password:</label>
                              <div class="col-sm-7">
                                <input name='oldPassword' type="password" class="form-control" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-5 control-label">New Password:</label>
                              <div class="col-sm-7">
                                <input name='newPassword' type="password" class="form-control" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-5 control-label">Confirm Password:</label>
                              <div class="col-sm-7">
                                <input name='confirmPassword' type="password" class="form-control" />
                              </div>
                            </div>

                          <div class="text-right">
                            <button type="submit" class="btn btn-success">Submit</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
              </div>
          </div>

        </div>

        </div>
      </div>

    </div><!-- /.container -->


    <?php include('../modules/scripts.php'); ?>

    <!-- Page-dependent plugin scripts -->

    <!-- Page-dependent custom scripts -->

  </body>
</html>
