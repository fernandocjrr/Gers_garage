$(document).ready(() => {                                                    //if page ready
    
        $.ajax({
            url: "../controllers/account.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data["success"]) {                      //if success in data array = true
                    console.log (data);
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