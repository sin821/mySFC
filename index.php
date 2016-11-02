<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => true,
]);
if(isset($_SESSION['cadet'])) header('location: flight/calculator.php');
include('php/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
  <?php include('modules/head.php'); ?>

  <body>

    <?php include('modules/navbar.php'); ?>

    <div class="container">

    <?php include('modules/alerts.php'); ?>

      <div class="starter-template">
        <div class="row">
          <div class="col-lg-offset-4 col-lg-4 col-md-offset-2 col-md-8 col-xs-12 text-left">
            <div class="panel panel-default">
              <div class="panel-heading">Login</div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 form-group">
                    <form method="POST" action="php/loginValidation.php">
                      <div class="form-group" id="loginForm">
                        <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email" />
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password" />
                      </div>
                      <div class="form-group text-right">
                        <button class="btn btn-success" type="submit">Login</button>
                      </div>
                      <div class="form-group">
                        <p><a onclick="passwordReset()" href="#">Forgot your password?</a></p>
                        <p><a onclick="loadRegistrationModal()" href="#">Need a new account?</a></p>
                        <p class="text-muted text-center"><small>This site is best used with Google Chrome.</small></p>
                        <span id="errorSpan" class="text-red"></span>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center text-muted">
              <p>
                <small>This app was made possible and free to use thanks to donations from the following people:</small>
                <ul class="text-left text-small">
                <?php
                $query = "SELECT * FROM tbl_doners WHERE 1 ORDER BY doner_amount DESC";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <li><small><?php echo $row['doner_name']; ?> (<?php echo $row['doner_course']; ?>)</small></li>
                  <?php
                }
                ?>
                </ul>
              </p>
            </div>

          </div>
        </div>
      	

      </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="ajax"></div>
      </div>
    </div>

    <?php include('modules/scripts.php'); ?>
    <script>
    function passwordReset() {
      var email = document.getElementById('loginEmail').value;
      $('#ajax').load( encodeURI("../ajax/modal_formResetPassword.php?email="+email) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }

    function loadRegistrationModal() {
      var email = document.getElementById('loginEmail').value;
      $('#ajax').load( encodeURI("../ajax/modal_formRegistration.php?email="+email) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    </script>
  </body>
</html>