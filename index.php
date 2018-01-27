<?php
include 'inti.php';
include $tem . 'header.php';
?>
<div class="webcontainer">
    <?php
    if (isset($_SESSION["name"]) && $_SESSION["name"] != "") {

        include $tem . 'loginnav.php';
    } else {
        include $tem . 'nav.php';
    }
    ?>

    <div class="row">
        <div class="column">
            <h3>Learning Path</h3>
            <p>Learning path gives you a full idea about what you have to learn.</p> 
            <p>Learning path allows you to build knowledge progressively.</p>
            <p>Learning path is the big picture so you don't get lost.</p>

            <p><img id="lpimg" src="<?php echo $img; ?>lp.png" alt="Learning Path Image"></p>
        </div>
        <div class="column">
            <h3>Structured</h3>
            <p>Moving from one topic to another keep you in your path.</p>
            <p>Learn step by step in perfect order.</p>
            <p>Keep yourself organised and learn what is necessary.</p>
            <p><img id="siimg" src="<?php echo $img; ?>si.png" alt="Structured Information Image"></p>
        </div>
        <div class="column">
            <h3>Simple and Easy</h3>        
            <p>Easy and simple to follow and to find any time.</p>
            <p>Make sure you have enough in each lesson no more no less.</p>
            <p>Simple lesson with a specific points in each lesson.</p>
            <p><img id="saeimg" src="<?php echo $img; ?>sae.png" alt="Simple and Easy Image"></p>
        </div>
    </div>
</div>

<?php include $tem . 'footer.php'; ?> 
