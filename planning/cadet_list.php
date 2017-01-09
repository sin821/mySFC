<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])||($_SESSION['role']==0)||($_SESSION['role']==2)) header('location: /index.php?status=failed&msg=You need to log in.');
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
        <h1>Cadet List</h1>

        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <!--<div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-block btn-success" style="margin-bottom: 50px;"><i class="fa fa-plus"></i> Add Cadet</button>
            </div>-->
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Ops Name</th>
                  <th>Course</th>
                  <th>Instructor</th>
                  <th>Syllabus</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT cadet_id, cadet_name, cadet_opsname, cadet_course, cadet_instructor, instructor_initials, cadet_syllabus, syllabus_code FROM tbl_cadets JOIN tbl_instructors ON cadet_instructor=instructor_id JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE 1";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td><a onclick="loadEditForm('<?php echo $row['cadet_id']; ?>')"><?php echo $row['cadet_name']; ?></a></td>
                    <td><?php echo $row['cadet_opsname']; ?></td>
                    <td><?php echo $row['cadet_course']; ?></td>
                    <td><?php echo $row['instructor_initials']; ?></td>
                    <td><?php echo $row['syllabus_code']; ?></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="ajax"></div>
      </div>
    </div>


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        //initialise datatable
        $('#table').DataTable({
            "paging": false,
            "responsive": true
        });
    } );

    function loadEditForm(cadet) {
      $('#ajax').load( encodeURI("../ajax/modal_editCadet.php?cadet="+cadet) ,function(responseTxt,statusTxt,xhr){
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
