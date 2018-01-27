<?php

include 'inti.php';
$sectionArray = array();
$lessonID = $_REQUEST["lesID"];
$sectionArray = section::getsections($lessonID);
if (!empty($sectionArray)) {
    for ($x = 0; $x < count($sectionArray); $x++) {
        echo "<div class=section> ";
        echo "<table>";
        echo "<tr>";
        echo "<td rowspan=2>" . $sectionArray[$x]->letter . "</td>";
        echo "<td>" . $sectionArray[$x]->name . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>" . $sectionArray[$x]->content . "</td>";
        echo "</tr>";
        echo "</table>";
        echo '</div>';
    }
}


