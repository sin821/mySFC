<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
$cadet_id = $_SESSION['cadet'];
$query = "SELECT cadet_crosswind, cadet_rwy0624, cadet_rwy12, cadet_rwy30, cadet_signedCCT, cadet_signedGH, cadet_signedNav, cadet_weight, cadet_startOfCourse FROM tbl_cadets WHERE cadet_id = '$cadet_id'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)){
$db_crosswind = $row['cadet_crosswind'];
$db_rwy0624 = $row['cadet_rwy0624'];
$db_rwy12 = $row['cadet_rwy12'];
$db_rwy30 = $row['cadet_rwy30'];
$db_signedCCT = $row['cadet_signedCCT'];
$db_signedGH = $row['cadet_signedGH'];
$db_signedNav = $row['cadet_signedNav'];
$db_weight = $row['cadet_weight'];
$db_startOfCourse = date('Y-m-d', strtotime($row['cadet_startOfCourse']));
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">
        <h2>Update Details</h2>

        <div class="row">
          <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Planning Information</div>

                <div class="panel-body">
                    <div class="row">
                      <div class="col-xs-12">
                        <form class="form-horizontal" method="POST" action='../php/updatePlanningDetails.php'>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-5 control-label">Crosswind:</label>
                            <div class="col-sm-7">
                              <select name='crosswind' class="form-control">
                                <option value='0' <?php if($db_crosswind==0) echo 'selected'; ?>>Nil</option>
                                <option value='5' <?php if($db_crosswind==5) echo 'selected'; ?>>5 kts</option>
                                <option value='8' <?php if($db_crosswind==8) echo 'selected'; ?>>8 kts</option>
                                <option value='10' <?php if($db_crosswind==10) echo 'selected'; ?>>10 kts</option>
                                <option value='12' <?php if($db_crosswind==12) echo 'selected'; ?>>12 kts</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-5 control-label">Runway:</label>
                            <div class="col-sm-7 text-left">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name='rwy0624' <?php if($db_rwy0624==1) echo 'checked'; ?>/> Rwy 06/24
                                </label>
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name='rwy12' <?php if($db_rwy12==1) echo 'checked'; ?>/> Rwy 12
                                </label>
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name='rwy30' <?php if($db_rwy30==1) echo 'checked'; ?>/> Rwy 30
                                </label>
                              </div>
                            </div>
                          </div>
                          <?php
                          $query = "SELECT syllabus_code FROM tbl_cadets JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE cadet_id = '$cadet_id'";
                          $result = mysqli_query($link, $query);
                          while($row = mysqli_fetch_array($result)){
                            $syllabus_code = $row['syllabus_code'];
                          }
                          ?>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-5 control-label">Signed Circuits:</label>
                            <div class="col-sm-7">
                              <select name='signedCCT' class="form-control">
                                <option>Nil</option>
                                <?php
                                $query = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus = '$syllabus_code' AND sortie_area = 'CCT' AND sortie_nature = 'FLT' AND sortie_type = 'SOLO'";
                                $result = mysqli_query($link, $query);
                                while($row = mysqli_fetch_array($result)){
                                  ?>
                                  <option <?php if($db_signedCCT==$row['sortie_code']) echo 'selected'; ?>><?php echo $row['sortie_code']; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-5 control-label">Signed GH:</label>
                            <div class="col-sm-7">
                              <select name='signedGH' class="form-control">
                                <option>Nil</option>
                                <?php
                                $query = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus = '$syllabus_code' AND sortie_area = 'GH' AND sortie_nature = 'FLT' AND sortie_type = 'SOLO'";
                                $result = mysqli_query($link, $query);
                                while($row = mysqli_fetch_array($result)){
                                  ?>
                                  <option <?php if($db_signedGH==$row['sortie_code']) echo 'selected'; ?>><?php echo $row['sortie_code']; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-5 control-label">Signed Nav:</label>
                            <div class="col-sm-7">
                              <select name='signedNav' class="form-control">
                                <option>Nil</option>
                                <?php
                                $query = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus = '$syllabus_code' AND sortie_area = 'Nav' AND sortie_nature = 'FLT' AND sortie_type = 'SOLO'";
                                $result = mysqli_query($link, $query);
                                while($row = mysqli_fetch_array($result)){
                                  ?>
                                  <option <?php if($db_signedNav==$row['sortie_code']) echo 'selected'; ?>><?php echo $row['sortie_code']; ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                          <p class="text-muted"><small>Note: Signed sorties refer to the furthest signed sorties on your solo card. E.g. if your instructor has signed you off for 115-122 circuit solos, then the furthest signed circuit solo is 122. Select 122 from the dropdown list above.</small></p>
                          <div class="text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">Account Information</div>

                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <form class="form-horizontal" method="POST" action='../php/updateAccountDetails.php'>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-5 control-label">Cadet Weight:</label>
                          <div class="col-sm-7">
                            <input name='weight' type="number" class="form-control" value="<?php echo $db_weight; ?>"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-5 control-label">Start of Course:</label>
                          <div class="col-sm-7">
                            <input name='date' type="date" class="form-control" value="<?php echo $db_startOfCourse; ?>"/>
                          </div>
                        </div>

                        <div class="text-right">
                          <button type="submit" class="btn btn-success">Update</button>
                        </div>
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
