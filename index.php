<?php

//index.php

include('admin/srms.php');

$object = new srms();

$object->query = "
SELECT * FROM exam_srms 
WHERE exam_result_published = 'Yes' 
AND exam_status = 'Enable'
";

$result = $object->get_result();

include('header.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>online system</title>
</head>
<body>

	<?php 
		      			date_default_timezone_set('Africa/nairobi');
                           $date = date('Y-m-d H:m:s');?>
		      			<font color="blue"><?php include('clock.php'); ?></font><i> <font color="red"> 
		      			</i></font>

		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header"><h3><b>Search Result</b></h3></div>
			      		<div class="card-body">
		      			
		      				<div class="row form-group">
		      					<label class="col col-md-4 text-right"><b>Select Exam</b></label>
		      					<div class="col col-md-8">
			      					<select name="exam_name" class="form-control" required>
			      						<option value="">Select Exam</option >
			      						<?php
			      						foreach($result as $row)
			      						{
			      							echo '<option value="'.$row["exam_id"].'">'.$row["exam_name"].'</option>';
			      						}
			      						?>
			      					</select>
			      				</div>
		      				</div>
		      				<div class="row form-group">
		      					<label class="col col-md-4 text-right"><b>Enter Adm No.</b></label>
		      					<div class="col col-md-8">
			      					<input type="text" name="student_roll_no" class="form-control"  required />
			      				</div>
		      				</div>
		      			</div>
		      			<div class="card-footer text-center">
		      				<input type="submit" name="submit" class="btn btn-primary" value="Search" />
		      			</div>
		      		</form>
		      	</div>
		    </body>
		    </html>

<?php

include('footer.php');

?>