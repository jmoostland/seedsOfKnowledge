<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//////////////////////user
class user {

    public $id = null;
    public $name;
    public $email;
    public $password;
    public $role;

    public function __construct($name, $email, $password, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getemail() {
        return $this->email;
    }

    public function setemail($email) {
        $this->email = $email;
    }

    public function getpassword() {
        return $this->password;
    }

    public function setpassword($password) {
        $this->password = $password;
    }

    public function getrole() {
        return $this->role;
    }

    public function setrole($role) {
        $this->role = $role;
    }

    public function checkaccountinput() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["name"])) {

                $this->setname(test_input($_POST["name"]));

                if ($this->name == "") {
                    return "name can not be empty <br>";
                }
            }


            if (isset($_POST["email"])) {
                $this->setemail(test_input($_POST["email"]));
                if ($this->email == "") {
                    return "email can not be empty";
                }
            }

            if (isset($_POST["pwd"])) {
                $this->setpassword(test_input($_POST["pwd"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }
            if (isset($_POST["cpwd"])) {
                $this->setpassword(test_input($_POST["cpwd"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }

            if ($_POST['pwd'] != $_POST['cpwd']) {
                return "passwords do not match";
            }
            if (isset($_POST["role"])) {
                $this->setrole(test_input($_POST["role"]));
            }


            if ($this->checkifaccountexist() == false) {
                return $this->addaccount();
            } else {
                return "the username or the email that you have enterd is al ready exist! Please choose another one";
            }
        }
    }

    public function checkifaccountexist() {
        $sql = "SELECT * FROM `user` WHERE `email`='.$this->email.'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addaccount() {

        $sql = "INSERT INTO `user`(`name`, `email`, `password`, `role`) VALUES";
        $sql .= " ('$this->name','$this->email','$this->password','$this->role')";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        $connection->conn->close();
        header("location: login.php");
    }

    public function signin() {

        $sql = "SELECT * FROM `user` WHERE `email`= BINARY '$this->email' AND `password`= BINARY '$this->password'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if (isset($result)) {
            if ($result->num_rows <= 0) {
                return "email or password does not match";
            } else {
                $row = $result->fetch_assoc();
                $_SESSION["name"] = $row['name'];
                $_SESSION["userID"] = $row['id'];
                $connection->conn->close();
                header("location: index.php");
            }
        }
    }

    public function checksignin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["email"])) {
                $this->setemail(test_input($_POST["email"]));
                if ($this->email == "") {
                    return "email can not be empty <br>";
                }
            }
            if (isset($_POST["psw"])) {
                $this->setpassword(test_input($_POST["psw"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }
            if ($this->email != "" && $this->password != "") {
                return $this->signin();
            }
        }
    }

}

//////////////////////course
class course {

    public $id;
    public $name;
    public $description;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

    public function checkcourse() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "Course name can not be empty <br>";
                }
            }
            if (isset($_POST["description"])) {
                $this->setdescription(test_input($_POST["description"]));
                if ($this->description == "") {
                    return "description can not be empty <br>";
                }
            }
        }

        if ($this->checkifcourseexist() == false) {
            $this->addcourse();
        } else {
            return "course is al ready exist! Please choose another name";
        }
    }

    public function checkifcourseexist() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT course.name, course.description, course.id";
        $sql .= " FROM `course`,`courseuser`";
        $sql .= " WHERE course.name='$this->name' AND courseuser.userID=$userID AND course.id=courseuser.courseID";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addcourse() {
        $sql = "INSERT INTO `course`(`name`, `description`) VALUES";
        $sql .= " ('$this->name','$this->description')";
        $connection = new Database();
        $connection->conn->query($sql);
        $_SESSION['courseID'] = mysqli_insert_id($connection->conn);
        $this->addcourseuser();
        $connection->conn->close();
    }

    public function addcourseuser() {
        $courseID = $_SESSION['courseID'];
        $userID = $_SESSION['userID'];
        $sql = "INSERT INTO `courseuser`(`courseID`, `userID`) VALUES";
        $sql .= " ('$courseID','$userID')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function showcourse() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT course.name, course.description, course.id ";
        $sql .= " FROM `course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID";
        $sql .= " ORDER BY course.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=course-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Course Name</th>";
        echo "<th>Course Description</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button onclick=delcourse(" . $row['id'] . ")  class='glyphicon glyphicon-trash' id=cousredelete title=Delete></button></td>";
            echo "</tr>";
        }
        echo "</table>";
        $connection->conn->close();
    }

    static public function showcourselist() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT course.name, course.description, course.id ";
        $sql .= " FROM `course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID";
        $sql .= " ORDER BY course.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            $courseID = $row['id'];
            echo "<a id=course$courseID onclick=subcoursesToggle($courseID)>" . $row['name'] . "</a>";
            echo "<div class=course-list  >";
            subcourse::showsubcourselist($courseID);
            echo '</div>';
        }
        $connection->conn->close();
    }

}

//////////////////////subcourse
class subcourse {

    public $id;
    public $name;
    public $description;
    public $courseID;

    public function __construct($name, $description, $courseID) {
        $this->name = $name;
        $this->description = $description;
        $this->courseID = $courseID;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

    public function getcourseID() {
        return $this->courseID;
    }

    public function setcourseID($courseID) {
        $this->courseID = $courseID;
    }

    public function checksubcourse() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "Subcourse name can not be empty <br>";
                }
            }
            if (isset($_POST["description"])) {
                $this->setdescription(test_input($_POST["description"]));
                if ($this->description == "") {
                    return "Description can not be empty <br>";
                }
            }
            if (isset($_POST["courseID"])) {
                $this->setcourseID(test_input($_POST["courseID"]));

                if ($this->courseID == "") {
                    return "Course ID can not be empty <br>";
                }
            }
        }

        if ($this->checkifsubcourseexist() == false) {
            $this->addsubcourse();
        } else {
            return "Subcourse already exists! Please choose another name";
        }
    }

    public function checkifsubcourseexist() {
        $sql = "SELECT * FROM `subcourse` WHERE `name`='$this->name'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addsubcourse() {
        $sql = "INSERT INTO `subcourse`(`name`, `description`,`courseID`) VALUES";
        $sql .= " ('$this->name','$this->description','$this->courseID')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function showsubcourses() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT course.name AS course, subcourse.name, subcourse.description, subcourse.id";
        $sql .= " FROM `courseuser`,`user`,`subcourse`, `course`";
        $sql .= " WHERE user.id=$userID AND subcourse.courseID=course.id AND courseuser.userID=$userID AND course.id=courseuser.courseID ";
        $sql .= " ORDER BY course.name ASC, subcourse.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=subcourse-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col id='thirdcol'>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Course Name</th>";
        echo "<th>Subcourse Name</th>";
        echo "<th>Subcourse Description</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['course'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button onclick=deletesubcourse(" . $row['id'] . ") class='glyphicon glyphicon-trash' id=cousredelete title=Delete></button></td>";
            echo "</tr>";
        }
        echo "</table>";
        $connection->conn->close();
    }

    static public function optioncourse() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT course.name, course.description, course.id ";
        $sql .= " FROM `course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID";
        $sql .= " ORDER BY course.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<select name='courseID'>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
        }
        echo "</select>";
        $connection->conn->close();
    }

    static public function showsubcourselist($courseID) {
        $userID = $_SESSION['userID'];
        $sql = "SELECT subcourse.name, subcourse.id ";
        $sql .= " FROM `subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID AND courseuser.courseID=$courseID";
        $sql .= " AND subcourse.courseID=$courseID";
        $sql .= " ORDER BY subcourse.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            $subcourseID = $row['id'];
            echo "<a id=subcourse$subcourseID onclick=lessonToggle($subcourseID)>" . $row['name'] . "</a>";
            echo "<div class=subcourse-list>";
            lesson::showlessonlist($subcourseID, $courseID);
            echo "</div>";
        }
        $connection->conn->close();
    }

}

//////////////////////lesson
class lesson {

    public $id;
    public $name;
    public $description;
    public $subcourseID;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

    public function getsubcourseID() {
        return $this->subcourseID;
    }

    public function setsubcourseID($subcourseID) {
        $this->subcourseID = $subcourseID;
    }

    public function checklesson() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "lesson name can not be empty <br>";
                }
            }
            if (isset($_POST["description"])) {
                $this->setdescription(test_input($_POST["description"]));
                if ($this->description == "") {
                    return "Description can not be empty <br>";
                }
            }
            if (isset($_POST["subcourseID"])) {
                $this->setsubcourseID(test_input($_POST["subcourseID"]));

                if ($this->subcourseID == "") {
                    return "Subcourse ID can not be empty <br>";
                }
            }
        }
        $this->addlesson();
    }

    public function addlesson() {
        $sql = "INSERT INTO `lesson`(`name`, `description`,`subcourseID`) VALUES";
        $sql .= " ('$this->name','$this->description','$this->subcourseID')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function showlessons() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT subcourse.name as subcourse, lesson.name, lesson.description, lesson.id ";
        $sql .= " FROM `lesson`,`subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID AND subcourse.courseID=course.id AND lesson.subcourseID=subcourse.id";
        $sql .= " ORDER BY subcourse.name ASC, lesson.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=lesson-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col id='thirdcol'>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Subcourse Name</th>";
        echo "<th>Lesson Name</th>";
        echo "<th>Lesson Description</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['subcourse'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button onclick=deletelesson(" . $row['id'] . ") class='glyphicon glyphicon-trash' id=cousredelete title=Delete></button></td>";
            echo "</tr>";
        }
        echo "</table>";
        $connection->conn->close();
    }

    static public function optionsubcourse() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT subcourse.name, subcourse.description, subcourse.id ";
        $sql .= " FROM `subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID AND subcourse.courseID=course.id";
        $sql .= " ORDER BY subcourse.name ASC";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<select name='subcourseID'>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
        }
        echo "</select>";
        $connection->conn->close();
    }

    static public function showlessonlist($subcourseID, $courseID) {
        $userID = $_SESSION['userID'];
        $sql = "SELECT lesson.name, lesson.id ";
        $sql .= " FROM `lesson`,`subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID AND courseuser.courseID=$courseID";
        $sql .= " AND subcourse.courseID=$courseID AND subcourse.id=$subcourseID AND lesson.subcourseID=$subcourseID";
        $sql .= " ORDER BY lesson.name ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);

        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            $lessonID = $row['id'];
            echo "<div class=lesson-list id=lesson" . $lessonID . " onclick=getsections($lessonID)>" . $row['name'];
            echo "</div>";
        }
        $connection->conn->close();
    }

}

//////////////////////section
class section {

    public $id;
    public $name;
    public $content;
    public $letter;
    public $lessonID;

    public function __construct($name, $content, $letter) {
        $this->name = $name;
        $this->content = $content;
        $this->letter = $letter;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getcontent() {
        return $this->content;
    }

    public function setcontent($content) {
        $this->content = $content;
    }

    public function getletter() {
        return $this->letter;
    }

    public function setletter($letter) {
        $this->letter = $letter;
    }

    public function getlessonID() {
        return $this->lessonID;
    }

    public function setlessonID($lessonID) {
        $this->lessonID = $lessonID;
    }

    public function checksection() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "section name can not be empty <br>";
                }
            }
            if (isset($_POST["content"])) {
                $this->setcontent(test_input($_POST["content"]));
                if ($this->content == "") {
                    return "Content can not be empty <br>";
                }
            }

            if (isset($_POST["letter"])) {
                $this->setletter(test_input($_POST["letter"]));

                if ($this->letter == "") {
                    return "Letter can not be empty <br>";
                }
            }
            if (isset($_POST["lessonID"])) {
                $this->setlessonID(test_input($_POST["lessonID"]));

                if ($this->lessonID == "") {
                    return "Lesson ID can not be empty <br>";
                }
            }

            $this->addsection();
        }
    }

    public function addsection() {
        $sql = "INSERT INTO `section`(`name`, `content`,`letter`,`lessonID`) VALUES";
        $sql .= " ('$this->name','$this->content','$this->letter','$this->lessonID')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function optionlesson() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT lesson.name, lesson.id ";
        $sql .= " FROM `lesson`,`subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID";
        $sql .= " AND subcourse.courseID=course.id AND lesson.subcourseID=subcourse.id";
        $sql .= " ORDER BY lesson.name ASC";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<select name='lessonID'>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
        }
        echo "</select>";
        $connection->conn->close();
    }

    static public function showsections() {
        $userID = $_SESSION['userID'];
        $sql = "SELECT subcourse.name AS subcourse, lesson.name AS lesson, section.name AS section,section.letter AS letter, section.id ";
        $sql .= " FROM `section`,`lesson`,`subcourse`,`course`,`courseuser`";
        $sql .= " WHERE courseuser.userID=$userID AND course.id=courseuser.courseID AND subcourse.courseID=course.id";
        $sql .= " AND lesson.subcourseID=subcourse.id AND section.lessonID=lesson.id";
        $sql .= " ORDER BY subcourse.name ASC, lesson.name ASC, section.letter ASC ";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=section-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col>";
        echo "<col id='thirdcol'>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Subcourse Name</th>";
        echo "<th>Lesson Name</th>";
        echo "<th>Section Letter</th>";
        echo "<th>Section Title</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['subcourse'] . "</td>";
            echo "<td>" . $row['lesson'] . "</td>";
            echo "<td>" . $row['letter'] . "</td>";
            echo "<td>" . $row['section'] . "</td>";
            echo "<td><button onclick=deletesection(" . $row['id'] . ") class='glyphicon glyphicon-trash' id=cousredelete title=Delete></button></td>";
            echo "</tr>";
        }
        echo "</table>";
        $connection->conn->close();
    }

    static public function getsections($lessonID) {
        $sectionArray=array();
        $sql = "SELECT * FROM `section` WHERE section.lessonID='$lessonID'ORDER BY section.letter";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            for ($x = 0; $x < $result->num_rows; $x++) {
                $row = $result->fetch_assoc();
                $section =new section("","","");
                $section->setid($row['id']);
                $section->setname($row['name']);
                $section->setcontent($row['content']);
                $section->setletter($row['letter']);
                $section->setlessonID($lessonID);
                $sectionArray[$x]=$section;
            }
            $connection->conn->close();
            return $sectionArray;
        }
    }

}

?>