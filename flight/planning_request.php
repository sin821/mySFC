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
        <h2>Submit a Flight Planning Request</h2>

        <div class="row">
          <div class="col-lg-offset-2 col-lg-8 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Request No-Plan Dates</div>

                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <form class="form-horizontal" method="POST" action='../php/sendNoPlanRequest.php'>
                        <div class="form-group">
                          <label for="startDate" class="col-sm-5 control-label">Start of No-Plan (inclusive):</label>
                          <div class="col-sm-7">
                            <input name='startDate' type="date" class="form-control" min="<?php echo date('Y-m-d', strtotime('today')); ?>" value="<?php echo date('Y-m-d', strtotime('today')); ?>" required />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="endDate" class="col-sm-5 control-label">End of No-Plan (inclusive):</label>
                          <div class="col-sm-7">
                            <input name='endDate' type="date" class="form-control" min="<?php echo date('Y-m-d', strtotime('today')); ?>" value="<?php echo date('Y-m-d', strtotime('tomorrow')); ?>" required />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="reason" class="col-sm-5 control-label">Reason / Additional Remarks:</label>
                          <div class="col-sm-7">
                            <textarea name='reason' class="form-control" required></textarea>
                          </div>
                        </div>
                        <p class="text-red"><small><b>Planners cannot guarantee the approval of your requests but they will try as much as they can to grant them where the requests made are fair and reasonable.</b></small></p>
                        <div class="text-right">
                          <button type="submit" class="btn btn-success">Request No-Plan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-lg-offset-2 col-lg-8 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Submit Other Request</div>

                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <form method="POST" action="../php/sendPlannerRequest.php">

                          <div class="form-group">
                            <textarea class="form-control" id="commentBox" name="message" placeholder="Write your request here!" required></textarea>
                          </div>
                          <p class="text-red"><small><b>Planners cannot guarantee the approval of your requests but they will try as much as they can to grant them where the requests made are fair and reasonable.</b></small></p>
                          <p class="text-muted"><small>Note: requests sent using this form are not anonymous. Your name and course will be sent to the planners along with your request. If you would like to make an anonymous comment, please use the <a href="/others/contact.php">contact form</a>.</small></p>

                        <hr />

                        <button type="submit" class="btn btn-success btn-block">Submit Request</button>
                      </form>
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