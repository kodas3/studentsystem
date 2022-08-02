<?php

//result.php

include('admin/srms.php');

$object = new srms();

include('header.php');

?>
	
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col col-sm-6"><h6>Digital performance Report</h></div>
				<div class="col col-sm-6 text-right">
					<a href="<?php echo $object->base_url; ?>" class="btn btn-warning btn-sm">Back</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?php
			$download_button = '';
			if(isset($_POST["submit"]))
			{
				$data = array(
					':student_roll_no'		=>	trim($_POST["student_roll_no"])
				);
				$object->query = "
				SELECT * FROM student_srms 
				WHERE student_roll_no = :student_roll_no 
				AND student_status = 'Enable' 
				";

				$class_id = '';
				$student_id = '';
				$result_id = '';

				$object->execute($data);

				if($object->row_count() > 0)
				{
					foreach($object->statement_result() as $student_data)
					{
						echo '
						<p><b>Adm No: </b>'.trim($_POST["student_roll_no"]).'</p>
						<p><b>Student Name: </b>'.html_entity_decode($student_data["student_name"]).'</p>
						<p><b>Email: </b>'.$student_data["student_email_id"].'</p>
						<div class="row">
							<div class="col-md-6">
								<p><b>Date of Birth:  </b>'.$student_data["student_dob"].'</p>
							</div>
							<div class="col-md-6">
								<p><b>Gender: </b>'.$student_data["student_gender"].'</p>
							</div>
						</div>						
						<p><b>Class Name: </b>'.$object->Get_class_name($student_data["class_id"]).'</p>';

						$class_id = $student_data["class_id"];
						$student_id = $student_data["student_id"];
					}

					$object->query = "
					SELECT * FROM exam_srms 
					WHERE exam_id = '".$_POST["exam_name"]."'
					";
					$exam_result = $object->get_result();

					foreach($exam_result as $exam_data)
					{
						echo '
						<div class="row">
							<div class="col-md-6">
								<p><b>Exam:</b>'.$exam_data["exam_name"].'</p>
							</div>
							<div class="col-md-6">
								<p><b>Date & Time: </b>'.date("Y-m-d H:i:s").'</p>
							</div>
						</div>
						';
					}

					$object->query = "
					SELECT * FROM result_srms 
					WHERE class_id = '$class_id' 
					AND student_id = '$student_id' 
					AND exam_id = '".$_POST['exam_name']."'
					";

					$result_data = $object->get_result();
					foreach($result_data as $result)
					{
						if($result["result_status"] == "Enable")
						{
							$result_id = $result["result_id"];
							echo '<tr>
										<td colspan="2" align="right"><b>served by</b></td>
										<td>'.$result["result_added_by"].'</td>
									</tr>';
							echo '

							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<th>#</th>
										<th>Subject</th>
										<th>My Score</th>
									</tr>
							';
							$object->query = "
							SELECT subject_srms.subject_name, marks_srms.marks 
							FROM marks_srms 
							INNER JOIN subject_srms 
							ON subject_srms.subject_id = marks_srms.subject_id 
							WHERE marks_srms.result_id = '".$result["result_id"]."'
							";
							$marks_data = $object->get_result();
							$count = 0;
							$total = 0;
							foreach($marks_data as $marks)
							{
								$count++;
								$ma = $marks['marks'];
								if($ma<=24){
									$g ='E';
									$p ='1';
								}
								elseif ($ma<=29) {
									$g ='D-';
									$p ='2';
									# code...
								}
								elseif($ma<=34){
									$g= 'D';
									$p ='3';

								}
								elseif($ma<=39){
									$g = 'D+';
									$p ='4';

								}
								elseif ($ma<=44) {
									$g = 'C-';
									$p ='5';
									# code...
								}
								elseif($ma<=49){
									$g ='C';
									$p ='6';

								}
								elseif($ma<=54){
									$g='C+';
									$p ='7';
								}
								elseif ($ma<=59) {
									$g ='B-';
									# code...
									$p ='8';
								}
								elseif($ma<=64){
									$g ='B';
									$p ='9';

								}
								elseif($ma<=69){
									$g ='B+';
									$p ='10';

								}
								elseif($ma<=74){
									$g ='A-';
									$p ='11';

								}
							else{
								$g='A';
								$p ='12';
							}

								echo '
									<tr>
										<td>'.$count.'</td>
										<td>'.$marks["subject_name"].'</td>
										<td>'.$marks["marks"].'</td>
										<td>'.$g.'</td>
									</tr>
								';
								$total += $marks["marks"];
							}
							echo '
									<tr>
										<td colspan="2" align="right"><b>Total</b></td>
										<td>'.$total.'</td>
									</tr>
									<tr>
										<td colspan="2" align="right"><b>Percentage</b></td>
										<td>'.$result["result_percentage"].'%</td>
									</tr>
									<tr>
								</table>
								<center><i><b>you were served by
										'.$result["result_added_by"].'</center></i></b>
							</div>
							';
							$download_button = '<a href="download.php?exam_id='.$_POST['exam_name'].'&student_roll_no='.$_POST["student_roll_no"].'" class="btn btn-danger"><i class="fas fa-file-pdf-o" aria-hidden="true"></i> Download</a>';
						}
						else
						{
							echo '<h4 align="center">requested results have been held due to some reasons. Kindly contact the concerned personnel</h4>';
						}
					}
				}
				else
				{
					?>
					<h6 align="center"><font color="red">requested results could not be fount</h6></font>
					<?php 
				}
			}
			else
			{
				echo '<h4 align="center">sorry.The requested results could not be fount</h4>';
			}

			?>
			
		</div>
		<div class="card-footer text-center">
			<?php echo $download_button; ?>
		</div>
	</div>
	<br />
			<br />
			<br />	
<?php

include('footer.php');

?>















































































































