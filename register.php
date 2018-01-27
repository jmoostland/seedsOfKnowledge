<?php
include 'inti.php';
$user = new user("", "", "", "", ""); //nieuwe parameters
$msg = $user->checkaccountinput();
include $tem . 'header.php';
include $tem . 'nav.php';
?>

<div class="formdiv">
    
    <form class="registerform" action="register.php" method="POST">
        <div class="container">
            <legend>New User</legend>
            <input type="text"  id="name" placeholder="Name" name="name" required>
            <input type="email"  id="email" placeholder="Email" name="email" required>
            <input type="password"  id="pwd" placeholder="Password" name="pwd" required>
            <input type="password" id="cpwd" placeholder="Verify password" name="cpwd" required>
            <label><input type="radio" name="role" value="student" required>&nbsp;Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label><input type="radio" name="role" value="teacher" required>&nbsp;Teacher</label>
            <button type="submit" >Submit</button>
            <div class=error> <?php echo $msg; ?> </div>
        </div>
    </form>
</div>
<?php include $tem . 'footer.php'; ?> 