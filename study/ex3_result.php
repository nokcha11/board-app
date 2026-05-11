<?php
$school = $_GET['school'];
$grade = $_GET['grade'];

echo "<h3>if ~ else if 결과</h3>";

if ($school == "초등학교") {
    echo $school . " " . $grade . "학년입니다.";

} else if ($school == "중학교") {
    if ($grade >= 4) {
        echo "중학교는 1~3학년만 존재합니다.";
    } else {
        echo $school . " " . $grade . "학년입니다.";
    }

} else if ($school == "고등학교") {
    if ($grade >= 4) {
        echo "고등학교는 1~3학년만 존재합니다.";
    } else {
        echo $school . " " . $grade . "학년입니다.";
    }
}

echo "<hr>";

echo "<h3>switch 결과</h3>";

switch ($school) {
    case "초등학교":
        echo $school . " " . $grade . "학년입니다.";
        break;

    case "중학교":
        if ($grade >= 4) {
            echo "중학교는 1~3학년만 존재합니다.";
        } else {
            echo $school . " " . $grade . "학년입니다.";
        }
        break;

    case "고등학교":
        if ($grade >= 4) {
            echo "고등학교는 1~3학년만 존재합니다.";
        } else {
            echo $school . " " . $grade . "학년입니다.";
        }
        break;
}
?>