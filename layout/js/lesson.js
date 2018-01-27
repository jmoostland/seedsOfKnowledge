function deletelesson(id) {
    window.location.href = "deletelesson.php?id=" + id;
}

function getsections(lessonID) {
    $(document).ready(function () {
        $("#data-container").load("getsections.php?lesID=" + lessonID, function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
    });

    $("#lesson-title").text($("#lesson" + lessonID).text());
    $("#lesson-title").css("background-color","lightgray");







}
