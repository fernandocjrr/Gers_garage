$(document).ready(() => {                                                    //if page ready
    $("#signup-form").on("submit", (event) => {                             //if event submit
        event.preventDefault();

        var serializedData = $("#signup-form").serializeArray();             //serialize form info into array

        $.ajax({
            url: "controllers/index.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {
                if (data["success"]) {
                    $('#signup-modal').modal('hide');
                    alert("User signed up");
                } else {
                    if (data["exists"]) {
                        alert("Email already used");
                    } else {
                        alert("An error occurred");
                    }
                }
            }
        })
    });
});