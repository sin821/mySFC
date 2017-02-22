<?php
include('../php/db_conn.php');
if(isset($_GET['id'])) {
    $instructor_id = $_GET['id'];
    $query = "SELECT * FROM tbl_instructors WHERE instructor_id='$instructor_id'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
        $instructor_name = $row['instructor_name'];
        $instructor_initials = $row['instructor_initials'];
        $instructor_weight = $row['instructor_weight'];
    }
}
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Edit Instructor Details for <?php echo $instructor_name; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="editForm" method="POST" action="../php/editInstructorDetails.php">
                <input type="hidden" name="instId" value="<?php echo $instructor_id; ?>" />
        		<div class="form-group">
        			<label for="name" >Name: </label>
        			<input class="form-control" name="instName" id="instName" type="text" value="<?php echo $instructor_name; ?>" required />
        		</div>
        		<div class="form-group">
                    <label for="opsname" >Initials: </label>
                    <input class="form-control" name="instOpsname" id="instOpsname" type="text" value="<?php echo $instructor_initials; ?>" required />
                </div>
                <div class="form-group">
                    <label for="course" >Weight: </label>
                    <input class="form-control" name="instWeight" id="instWeight" type="number" value="<?php echo $instructor_weight; ?>" required />
                </div>
        	</form>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onclick="deleteInstructor('<?php echo $instructor_id; ?>')">Delete</button>
        <button type="submit" class="btn btn-success" onclick="submitForm()">Save</button>
    </div>
</div>

<script>
function submitForm() {
    document.getElementById('editForm').submit();
}
</script>