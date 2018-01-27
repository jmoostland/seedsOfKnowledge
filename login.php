<?php
include 'inti.php';
include $tem . 'header.php';

if (isset($_SESSION["name"]) && $_SESSION["name"] != "") {
    include $tem . 'loginnav.php';
    header("location: makecourse.php");
} else {
    include $tem . 'nav.php';
}
?>

<div class="formdiv">
    <form action="login.php" method="POST">
        <div class="imgcontainer">
            <img src="<?php echo $img; ?>img_avatar2.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <label><b>Email</b></label>
            <input type="email" placeholder="Enter Email" name="email" required>

            <label><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <button type="submit">Login</button>
            <?php $user = new user("", "", "", "", "");
            $msg = $user->checksignin();
            ?>
            <span class=error>
                <?php
                if (isset($msg)) {
                    echo $msg;
                }
                ?>
            </span>
        </div>


    </form>
</div>
<?php include $tem . 'footer.php'; ?> 