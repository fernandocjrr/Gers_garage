$(document).ready(() => {                                                    //if page ready
    $("#signup-form").on("submit", (event) => {                             //if event submit from signup-form
        event.preventDefault();

        var serializedData = $("#signup-form").serializeArray();             //serialize form info into array

        $.ajax({
            url: "controllers/index.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {
                if (data["success"]) {                      //if success in data array = true
                    $('#signup-modal').modal('hide');       //close modal
                    alert("User signed up");                //send alert user signed up
                } else {                                    //if success in data array = false
                    if (data["exists"]) {                   //if exists in data array = true
                        alert("Email already used");        //alert that email already in use
                    } else {                                //if success = false and exists = true
                        alert("An error occurred");         //alert unknow error
                    }
                }
            }
        })
    });
    

    $("#login").on("submit", (event) => {                             //if event submit
        event.preventDefault();

        var serializedData = $("#login").serializeArray();             //serialize form info into array

        $.ajax({
            url: "controllers/index.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {
                if (data["success"]) {
                    window.location.href = "views/home.php";
                } else {
                    if (data["wrong"]) {
                        alert("Email or password wrong")
                    } else {
                        alert("An error occurred");
                    }

                }
            }
        })
    });
});