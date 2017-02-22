<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Add New Instructor</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="addForm" method="POST" action="../php/createInstructorDetails.php">
        		<div class="form-group">
        			<label for="name" >Name: </label>
        			<input class="form-control" name="instName" id="instName" type="text" value="" required />
        		</div>
        		<div class="form-group">
                    <label for="opsname" >Initials: </label>
                    <input class="form-control" name="instOpsname" id="instOpsname" type="text" value="" required />
                </div>
                <div class="form-group">
                    <label for="course" >Weights: </label>
                    <input class="form-control" name="instWeight" id="instWeight" type="number" value="79" required />
                </div>
        	</form>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" onclick="submitForm()">Save</button>
    </div>
</div>

<script>
function submitForm() {
    document.getElementById('addForm').submit();
}
</script>