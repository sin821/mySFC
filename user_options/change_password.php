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
                        <form class="form-horizontal" id='changePassForm' method="POST" action='../php/updatePassword.php'>
                            <div class="form-group">
                              <label for="oldPass" class="col-sm-5 control-label">Old Password:</label>
                              <div class="col-sm-7">
                                <input id='oldPass' name='oldPassword' type="password" class="form-control" minlength="2" required />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="newPass" class="col-sm-5 control-label">New Password:</label>
                              <div class="col-sm-7">
                                <input id='newPass' name='newPassword' type="password" class="form-control" required/>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="newPassConfirmed" class="col-sm-5 control-label">Confirm Password:</label>
                              <div class="col-sm-7">
                                <input id='newPassConfirmed' name='confirmPassword' type="password" class="form-control" required/>
                              </div>
                            </div>
                            <div class="text-red">
                              <span id="errorSpan"></span>
                            </div>

                          <div class="text-right">
                            <button type="button" class="btn btn-success" onclick="submitForm()">Submit</button>
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

    <script>
    function submitForm() {
      var error = 0;
      var message = '';
      var oldPass = document.getElementById('oldPass').value;
      var newPass = document.getElementById('newPass').value;
      var newPassConfirmed = document.getElementById('newPassConfirmed').value;
        if(oldPass=='' || newPass=='' || newPassConfirmed=='') {
          message += "You have left a required field blank.<br />";
          error = 1;
        }
        if(newPass!=newPassConfirmed){
          message += "Your new passwords in both fields do not match.<br />";
          error = 1;
        }
        document.getElementById('errorSpan').innerHTML = message;
        if(error==0) {
          document.getElementById('changePassForm').submit();
        }
    }
    </script>

  </body>
</html>
