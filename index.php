<?php
session_name('myApp');
session_start([
        'cookie_lifetime' => 300,
    ]);
 require_once "inc/function.php";

 $info = "";
 $task = $_GET['task'] ?? 'report';
 $error = $_GET['error'] ?? '0';
 
 if ('delete' == $task) {
    if(!isAdmin()){
        header('location: /crud/index.php?task=report');
    }
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        if ($id>0) {
            deleteStudent($id);
            header('location: /crud/index.php?task=report');
        }
    }
 if('seed' == $task){
    if(!isAdmin()){
        header('location: /crud/index.php?task=report');
    }
    //seed();
    $info = "<script> alert('Hi! Already You have seeded :)')</script>";
}

$fname = '';
$lname = '';
$roll = '';
if(isset($_POST['submit'])){
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);


    if ($id) {
        if($fname != '' && $lname != '' && $roll != ''){
            $result = updateStudent($id, $fname, $lname, $roll);
            if($result){
              header('location: /crud/index.php?task=report');
            }else{
                 $error = 1;
            }   
        }
    } else {
         if($fname != '' && $lname != '' && $roll != ''){
         $result = addStudentData($fname, $lname, $roll);
            if($result){
              header('location: /crud/index.php?task=report');
            }else{
                 $error = 1;
            }    
        }
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <p class="float-right">Now Time: <?php timeForDhaka(); ?></p>
            <?php if($_SESSION['role']): ?>
            <h3>Hi! <strong><?php echo strtoupper($_SESSION['role']); ?></strong></h2>
            <?php else: ?>
                <h2>CRUD</h2>
            <?php endif; ?>
            <p>A sample project to perform CRUD operations using plain files and PHP</p>
            <hr/>
            <?php include_once "inc/template/nav.php"; ?>
            <hr/>
            <p>
            <?php
                if($info != ''){
                    echo $info;
                }
            ?>
            </p>
        </div>
    </div>
      <?php if ( '1' == $error ): ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Duplicate Roll Number
                </blockquote>
            </div>
        </div>
    <?php endif; ?>
    <?php if('report' == $task): ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <?php generateReport(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if('add' == $task): ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <form action="/crud/index.php?task=add" method="POST">
                <label for="fnamefield">First Name</label>
                <input type="text" id="fnamefield" name="fname" value="<?php echo $fname; ?>">
                <label for="lnamefield">Last Name</label>
                <input type="text" id="lnamefield" name="lname" value="<?php echo $lname; ?>">
                <label for="rollfield">Roll</label>
                <input type="number" id="rollfield" name="roll" value="<?php echo $roll; ?>">
                <button type="submit" class="button-primary" name="submit">Save</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php 
        if('edit' == $task):
        if (!hasPrivilege()) {
            header('location: /crud/index.php?task=report');
         } 
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            $student = getStudent($id);
            if ($student):
    ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <form method="POST">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <label for="fnamefield">First Name</label>
                <input type="text" id="fnamefield" name="fname" value="<?php echo $student['fname']; ?>">
                <label for="lnamefield">Last Name</label>
                <input type="text" id="lnamefield" name="lname" value="<?php echo $student['lname']; ?>">
                <label for="rollfield">Roll</label>
                <input type="number" id="rollfield" name="roll" value="<?php echo $student['roll']; ?>">
                <button type="submit" class='button-primary' name="submit">Update</button>
            </form>
        </div>
    </div>
    <?php endif;
        endif;
    ?>

</div>
<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>