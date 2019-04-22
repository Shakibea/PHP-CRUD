<?php

define('DB_NAME', '/opt/lampp/htdocs/crud/data/db.txt');

/* function seed(){
	$data = array(
		array(
			'id' => 1,
			'fname' => 'Shazid',
			'lname' => 'Rafiul Karim',
			'roll' => 25
		),
		array(
			'id' => 2,
			'fname' => 'Shakib',
			'lname' => 'Ebna Atiq',
			'roll' => 21
		),
		array(
			'id' => 3,
			'fname' => 'Sakin',
			'lname' => 'Hasan',
			'roll' => 18
		),
		array(
			'id' => 4,
			'fname' => 'Sadip',
			'lname' => 'Bin Reza',
			'roll' => 15
		),
		array(
			'id' => 5,
			'fname' => 'Zarif',
			'lname' => 'Bin Reza',
			'roll' => 10
		)
	);

	$serializedData = serialize($data);
	file_put_contents(DB_NAME, $serializedData, LOCK_EX);
} */

function generateReport(){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);

		?>
		<table>
			<tr>
				<th>Name</th>
				<th>Roll</th>
				<?php if(hasPrivilege()): ?>
				<th width="25%">Action</th>
				<?php endif; ?>
			</tr>
		<?php foreach ($students as $student) { ?>
			<tr>
			<td><?php printf('%s %s',$student['fname'], $student['lname']); ?></td>
			<td><?php printf('%s',$student['roll']); ?></td>
			<?php if(isAdmin()): ?>
			<td><?php printf('<a class="editData" href="/crud/index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="/crud/index.php?task=delete&id=%s">Delete</a>',$student['id'], $student['id']); ?></td>
			<?php elseif(isEditor()): ?>
			<td><?php printf('<a class="editData" href="/crud/index.php?task=edit&id=%s">Edit</a>', $student['id']); ?></td>
			<?php endif; ?>
			</tr>
		 <?php } ?>
		</table>
	<?php
	}


function addStudentData($fname, $lname, $roll){
	$found = false;
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	foreach ($students as $_student) {
		if ($_student['roll'] == $roll) {
			$found = true;
			break;
		}
	}

	if(!$found){
	$studentID = getStudentId($students);
	$student = array(
		'id' => $studentID,
		'fname' => $fname,
		'lname' => $lname,
		'roll' => $roll
	);

	array_push($students, $student);

	$serializedData = serialize($students);
	file_put_contents(DB_NAME, $serializedData, LOCK_EX);

		return true;
	}

	return false;
}

function getStudent($id){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);

	foreach ($students as $student) {
		if ($student['id'] == $id) {
			return $student;
		}
	}
	return false;
}

function updateStudent($id, $fname, $lname, $roll){

	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	   foreach ( $students as $_student ) {
        if ( $_student['roll'] == $roll && $_student['id'] != $id ) {
            $found = true;
            break;
        }
    }
    	if ( ! $found ) {
	    	$students[$id-1]['fname'] = $fname;
			$students[$id-1]['lname'] = $lname;
			$students[$id-1]['roll'] = $roll;

		$serializedData = serialize($students);
		file_put_contents(DB_NAME, $serializedData, LOCK_EX);

			return true;
		}
		return false;
}

function deleteStudent($id){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);

	foreach ($students as $key => $value) {
		if ($value['id'] == $id) {

			unset($students[$key]);
		}
	}
	
	$serializedData = serialize($students);
	file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}


function printRaw(){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);

	print_r($students);
}

function getStudentId($students){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);

		$maxID = max(array_column($students, 'id'));

		return $maxID + 1;
}


function isAdmin(){
	return('admin' == $_SESSION['role']);
}

function isEditor(){
	return('editor' == $_SESSION['role']);
}

function hasPrivilege(){
	return(isAdmin() || isEditor());
}

function timeForDhaka(){
    date_default_timezone_set('Asia/Dhaka');
    echo date('d M, Y || h:i:s a');
}
