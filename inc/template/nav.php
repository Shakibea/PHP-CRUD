<?php session_name('myApp');
session_start([
        'cookie_lifetime' => 300,
    ]); ?>
<div style="border-bottom: 1px solid; border-color:#eee; padding-bottom: 30px; margin-bottom:30px;">
	<div class="float-left">
	<p>
		<a href="/crud/index.php?task=report">Show All Student</a> 
		<?php if(hasPrivilege()): ?>
		|
		<a href="/crud/index.php?task=add">Add new Student</a>
		<?php endif; ?>
		<?php if(isAdmin()): ?>
		|
		<a href="/crud/index.php?task=seed">Seed</a>
	<?php endif; ?>
	</p>
	</div>
	<div>
		<div class="float-right">
			<?php if(!$_SESSION['loggedin']){ ?>
			<a href="/crud/auth.php">Log in</a>
			<?php }else{ ?>
			<a href="/crud/auth.php?logout=true"><?php echo $_SESSION['role']; ?> | Log out(<?php echo strtoupper($_SESSION['user']); ?>)</a>
		<?php } ?>
		</div>
	</div>
</div>