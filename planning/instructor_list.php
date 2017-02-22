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
        <h1>Instructor List</h1>

        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-block btn-success" style="margin-bottom: 50px;" onclick="loadAddForm()"><i class="fa fa-plus"></i> Add Instructor</button>
            </div>
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Initials</th>
                  <th>Weight</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT instructor_id, instructor_name, instructor_initials, instructor_weight FROM tbl_instructors WHERE 1";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td><a onclick="loadEditForm('<?php echo $row['instructor_id']; ?>')"><?php echo $row['instructor_name']; ?></a></td>
                    <td><?php echo $row['instructor_initials']; ?></td>
                    <td><?php echo $row['instructor_weight']; ?></td>
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

    function loadEditForm(instructor) {
      $('#ajax').load( encodeURI("../ajax/modal_editInstructor.php?id="+instructor) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }

    function loadAddForm() {
      $('#ajax').load( encodeURI("../ajax/modal_addInstructor.php") ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }

    function deleteInstructor(instructor) {
      if(confirm('Are you sure you want to delete this instructor record? This cannot be undone.\nDo this only if the instructor has left SFC.')){
        $('#ajax').load( encodeURI("../php/deleteInstructorDetails.php?id="+instructor) ,function(responseTxt,statusTxt,xhr){
              if(statusTxt=="success") {
                $('#myModal').modal('toggle');
                window.location = 'instructor_list.php?status=success&msg=Successfully deleted instructor details.';
              }
              if(statusTxt=="error")
                console.log("Error: "+xhr.status+": "+xhr.statusText);
                window.location = 'instructor_list.php?status=failed&msg=Unable to delete instructor details. Contact administrator.';
            });
      }
    }
    </script>
  </body>
</html>
