/* 
    # PROCESS HAPPENING ON JavaScript
    - PROCESS HAPPENING ON CONTROLLER
    + PROCESS HAPENNING ON MODEL
    PHP array = array (["position"]=>["value"])
*/ 


/* SIGN UP FUNCTION
    
    # When submit from modal form (id = signup-form) get data and stores on serializedData
    # POST serializedData to controllers/index.php:
        - Check if it is login, if not, filter all data and stores each on a variable
        - Use email variable as parameter to model method checkUser() on models/user.php:
                + Prepare "SELECT user_id FROM user WHERE email = ? LIMIT 1" (limit one because if one is found, means there is email already registered)
                + If query prepare, bind email with $email and execute query
                + Store user_id found (or not) on $result
                + Return an array (["success"]=>[TRUE],["data"]=>[$result])
                + If not prepared, return array (["success"]=>[FALSE])
        - Store answer from model on $response
        - If the position success on $response = TRUE, then checks if position ["data"] is empty, if empty (means email not found on DB)
            - Overwrite $response with model method createUser(), using all data that came from JavaScript as parameters (models/user.php:)
                    + Prepare query INSERT INTO user (first_name, surname, phone, address, email, password
                    + If prepared, bind parameters and execute query
                    + return (["success"]=>[TRUE])
                    + If not prepared, return array (["success"]=>[FALSE])
            - Send response from model to JavaScript    
        - If position ["data"] is not empty (means email found on DB, dont allow use)
            - Overwrite $response with array(["success"] => [FALSE], ["exists"] => TRUE) and send back to JavaScript    
    # If position success on data is TRUE, close modal and alert user "user signed up"  
    # If position success on data is FALSE and position exists is TRUE, alert user "Email already used"
    
*/


$(document).ready(() => {
    $("#signup-form").on("submit", (event) => {
        event.preventDefault();

        var serializedData = $("#signup-form").serializeArray();

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
    
    
    /* LOGIN FUNCTION
    
    # When submit from form (id = login) get data and stores on serializedData
    # POST serializedData to controllers/index.php:
        - Check if it is login, if so, filter email and password and stores on $email and $password
        - Use $email and $password as parameters to model method login() on models/user.php:
                + Prepare "SELECT user_id FROM user WHERE email = ? AND password = ?"
                + If prepare,bind email with $email and password with $password sent
                + Store user_id on $result
                + Return an array (["success"]=>[TRUE],["data"]=>[$result])
                + If dont prepare return array (["success"]=>[FALSE])
        - Store answer from model on $response
        - If the position success on $response = TRUE, then checks if position ["data"] is empty, if empty
            - Overwrite $response with an array (["success"]=>[FALSE],["wrong"]=>[TRUE]) and sends back to JavaScript
            - If not empty send found user_id as parameter on model method setUserSessionAndCookie() on models/user.php:
                    + Checks if there is a sessions already started and non active, if yes run session_start
                    + Set session and cookie, related to user_id
            - Overwrite $response with an array (["success"]=>[TRUE]) and sends back to JavaScript
        -  If the position success on $response = FALSE overwrite $response with an array (["success"]=>[FALSE]) and send back to JavaScript
    # If position success on data is TRUE, send user to view/home.php    
    # If position success on data is FALSE and position wrong is TRUE, alert user "Email or password wrong"
    
*/

    $("#login").on("submit", (event) => {
        event.preventDefault();

        var serializedData = $("#login").serializeArray();

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
