<?php
include 'inti.php';
include $tem . 'header.php';
?>

<?php
if (isset($_SESSION["name"]) && $_SESSION["name"] != "") {

    include $tem . 'loginnav.php';
} else {
    header("location: login.php");
}
?>


<div id="makecourse-container">

    <div id="makecourse-course">

        <div id="makecourse-course-inputs">
            <form id="course" action="section.php" method="POST">
                <div class="course-inputs">
                    <br>
                    <?php section::optionlesson(); ?>
                    <input id="cousrename" type="text" name="name" placeholder="Section Title">
                    <input id="coursedesc" type="text" name="content" placeholder="Section Content">
                    <select name="letter">
                        <option name="A" value="A">A</option>
                        <option name="B" value="B">B</option>
                        <option name="C" value="C">C</option>
                        <option name="D" value="D">D</option>
                        <option name="E" value="E">E</option>
                    </select>
                    <input id="cousreoperatopn" type="hidden" name="operation" value="">
                </div>
                <div class="course-buttons">
                    <button type="reset"  class="glyphicon glyphicon-repeat" id="cousreclear" title="Clear"></button>
                    <?php
                    $msg = "";
                    $section = new section("", "","");
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $msg = "";
                        $msg = $section->checksection();
                        $_POST = array();
                    }
                    ?>
                    <button type="submit" id="cousreadd" class=" glyphicon glyphicon-plus" title="Add"></button>
                    <span class=error>
                        <?php
                        if (isset($msg)) {
                            echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;';
                            echo $msg;
                        }
                        ?></span> 
                </div>
            </form>
        </div>

        <div id="makecourse-course-table">

            <?php section::showsections(); ?>
        </div>
    </div>
</div>

<?php include $tem . 'footer.php'; ?> 