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
        <h2>Make a Donation</h2>
        
        <div class="row">
          <div class="col-lg-offset-1 col-lg-7 col-md-offset-1 col-md-8 text-left">
            <p><b>Hi!</b> This application started out as a pet project to improve the flight planning efficiency of cadets in Jandakot. Due to positive feedback, I have kept it running to benefit all future cadets. I hope the performance calculator and planning assistant has helped you in some way. It costs around $50 SGD per month to run this application and database using Amazon Web Services. Some of you have mentioned that, as it has saved you much precious time, you are keen to contribute to the operating costs of the app. If you want to contribute to the continuous operation and maintenance of the app, you can donate through an internet bank transfer. I do not make a profit from the donations and you are free to donate any amount you so choose.
            <p>
            <ul>
              <li>An average donation of <b>$1 per cadet per month ($12 per year)</b> will help to offset the cost of the application.</li>
              <li>An average donation of <b>$2 per cadet per month ($24 per year)</b> will help to offset the cost of the application and cover the cost for 1 other cadet.</li>
            </ul>
            </p>
            <p>
              <?php
                $query = "SELECT ROUND(SUM(doner_amount),2) AS total_collected FROM tbl_doners WHERE doner_approved='1'";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  $total_collected = $row['total_collected'];
                }
                $days_funded = ceil($total_collected / 1.68);
                $date_started = '2016-10-1';
                $date_end = date('j F Y', strtotime($date_started.' + '.$days_funded.' days'));
                ?>
                Currently collected donations will help fund the app until: <b><?php echo $date_end; ?></b>
            </p>
            <br />
            <p><b>3 Steps To Donate:</b></p>
            <p>
              <ol>
                <li>To donate, you may transfer your donation to <b>UOB Savings 366-383-127-3</b>. In the transfer remarks/description, please <b>indicate your name</b>.</li>
                <li>After performing the transaction, please <b>use the form below</b> to indicate your name, course, donation amount, and transaction reference.</li>
                <li>Give the bank <b>3 working days</b> to receive your donation, <b>Check the list of doners</b> for your name!</li>
              </ol>
            </p>

            <br />

            <form class="form-horizontal" method="POST" action="../php/indicateDonation.php">

                <div class="form-group">
                  <label for="cadetName" class="col-sm-2 control-label">Name: </label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cadetCourse" class="col-sm-2 control-label">Course: </label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="course" name="course" placeholder="Course" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cadetDonation" class="col-sm-2 control-label">Donation: </label>
                  <div class="col-sm-6">
                    <input type="number" step="any" class="form-control" id="donation" name="donation" placeholder="Donation" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cadetDonation" class="col-sm-2 control-label">Transaction Reference: </label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="transaction" name="transaction" placeholder="Transaction ID" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-8 text-right">
                    <button type="submit" class="btn btn-success">Submit</button>
                  </div>
                </div>

            </form>
          </div>
          <div class="col-lg-3 col-md-3 text-left text-muted">
            <p>
                <small>List of Doners:</small>
                <ul class="text-left text-small">
                <?php
                $query = "SELECT * FROM tbl_doners WHERE doner_approved='1' ORDER BY doner_amount DESC";
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

    </div><!-- /.container -->


    <?php include('../modules/scripts.php'); ?>

    <!-- Page-dependent plugin scripts -->

    <!-- Page-dependent custom scripts -->

  </body>
</html>
