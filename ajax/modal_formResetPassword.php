<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Reset your password</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="registrationForm" method="POST" action="../php/resetPassword.php">
        		<div class="form-group">
        			<label>E-mail: </label>
        			<input class="form-control" name="email" id="email" type="email" placeholder="Email" value="<?php if(isset($_GET['email'])) echo $_GET['email']; ?>" required/>
        		</div>
                <p><small>Please answer the following questions according to what you selected when you created your account.</small></p>
        		<div class="form-group">
        			<label>Course: </label>
        			<input class="form-control" name="course" type="text" placeholder="Course" required/>
        		</div>
        		<div class="form-group">
        			<label>Weight: </label>
        			<input class="form-control" name="weight" type="number" placeholder="Weight" step="1" required/>
        		</div>
        		<div>
        			<span id="errorSpan"></span>
        		</div>
        	</form>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick="submitRequest()">Reset</button>
    </div>
</div>

<script>
function submitRequest() {
	var error = 0;
	var email = document.getElementById('email').value;
  	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	if(!re.test(email)){
    	document.getElementById('errorSpan').innerHTML = "Not a valid email address.";
    	error = 1;
  	}
  	if(error==0) {
  		document.getElementById('registrationForm').submit();
  	}
}
</script>