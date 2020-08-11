/* 
    # PROCESS HAPPENING ON JavaScript
    - PROCESS HAPPENING ON CONTROLLER
    + PROCESS HAPENNING ON MODEL
    PHP array = array (["position"]=>["value"])
*/ 

/*  GET ALL VEHICLES DETAILS ON DATABASE

    # When this script is loaded, set a GET request to controllers/account.php:
        - If GET request receive on controllers/account.php, run getVehicleDetails() method from models/vehicle_details.php
            + Query to SELECT all vehicle details on DB and store on $response
            + Return array(["success"] => TRUE, ["data"] => $result) in case of success or array(["success"] => FALSE) if not
        - If position success = TRUE on  $response send it back to JavaScript 
        - If position success = FALSE on $response, overwrite response with array(["success"] => FALSE) and send back to JavaScript
    # If position success on data is TRUE, store data["data"] (all vehicle details).       
*/

$(document).ready(() => {

    var vehicles;
    var bookings;
    var DisabledDates = [];

    $.ajax({
        url: "../controllers/account.php",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data["success"]) {
                vehicles = data['data'];
            } else {
                if (data["exists"]) {
                    alert("Email already used");
                } else {
                    alert("An error occurred");
                }
            }
        }
    })

/*  DYNAMICALLY FILL MANUFACTURER DROPDOWN LIST

    # When "Type" input (id = selectType) change
    # Select the manufaturer input (id = selectManufacturer) but disconsider first option (label)
    # For loop on all the vehicles variable
    # First checks if the type of vehicles[i] is the same as elected option on Type input
    # If type is the same, checks if the the manufacurer of vehicles[i] was not already added to added array
        # If not added append the value of manufacurer of vehicles[i] on dropdownlist
    # Add manufacrurer of vehicles[i] on added array (so it doesn't show repeated manufacturers
*/

    $('#selectType').on('change', (e) => {
        let select = $('#selectManufacturer');
        let added = [];
        select.children('option:not(:first)').remove();

        for (let i = 0; i < vehicles.length; i++) {
            if (vehicles[i]['type'] === $('#selectType option:selected').val()) {
                if (!added.includes(vehicles[i]['manufacturer'])) {
                    select.append($('<option>', {
                        value: vehicles[i]['manufacturer'],
                        text: vehicles[i]['manufacturer']
                    }));

                    added.push(vehicles[i]['manufacturer']);
                }
            }
        }
    });
    
/*  DYNAMICALLY FILL MODEL DROPDOWN LIST

    EXACT SAME LOGIC AS METHOD ABOVE
  
*/

    $('#selectManufacturer').on('change', (e) => {
        let select = $('#selectModel');
        let added = [];
        select.children('option:not(:first)').remove();

        for (let i = 0; i < vehicles.length; i++) {
            if (vehicles[i]['manufacturer'] === $('#selectManufacturer option:selected').val()) {
                if (!added.includes(vehicles[i]['model'])) {
                    select.append($('<option>', {
                        value: vehicles[i]['model'],
                        text: vehicles[i]['model']
                    }));

                    added.push(vehicles[i]['model']);
                }
            }
        }
    });
    
    /*  DYNAMICALLY FILL YEAR DROPDOWN LIST

    EXACT SAME LOGIC AS METHOD ABOVE
  
*/

    $('#selectModel').on('change', (e) => {
        let select = $('#selectYear');
        let added = [];
        select.children('option:not(:first)').remove();

        for (let i = 0; i < vehicles.length; i++) {
            if (vehicles[i]['model'] === $('#selectModel option:selected').val()) {
                if (!added.includes(vehicles[i]['year'])) {
                    select.append($('<option>', {
                        value: vehicles[i]['vehicle_details_id'],
                        text: vehicles[i]['year']
                    }));

                    added.push(vehicles[i]['year']);
                }
            }
        }
    });

    
    /*  ADD VEHICLE TO COSTUMER'S ACCOUNT
    
    # Receive data from form id=ddvehicle-form, and add on serializedData variable
    # POST data to controllers/account.php
        - If controller get POST request, store all variables, received on request, in different variables
        - Use variables as input for addVehicle() method from models/vehicle.php and stores what return on $response
            + Insert new vehicle with query "INSERT INTO vehicle (licence_details, engine, vehicle_details_id)"
            + Return the id of the just inserted vehicle
        - Checks if $response stores the ID, if yes, use this id and the $userID (got on cookie checking) on addOwnership() method of models/own.php
                + Uses $userID and vehicle id on query "INSERT INTO own (user_id,vehicle_id)"
            - Return anwser from model to JavaScript
        - If $response doen't store if return array (["success"]=>FALSE)
    # If success returned from controller, show "Vehicle added" to user
    # If sucess = FALSE returned, show "An error occurred"
    */

    $("#addvehicle-form").on("submit", (event) => {
        event.preventDefault();

        var serializedData = $("#addvehicle-form").serializeArray();

        $.ajax({
            url: "../controllers/account.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {

                if (data["success"]) {
                    $('#addvehicle-modal').modal('hide');
                    alert("Vehicle added");
                } else {
                    alert("An error occurred");
                }
            }

        })
    });
    
    

    $("#booking-form").on("submit", (event) => {
        event.preventDefault();

        var serializedData = $("#booking-form").serializeArray();

        $.ajax({
            url: "../controllers/booking.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {
                if (data["success"]) {
                    $('#booking-modal').modal('hide');
                    alert("Service Booked");
                    location.reload();
                } else {
                    alert("An error occurred");
                }
            }
        })
    });

    createBooking = function (vehicle_id) {

        $("#vehID").val(parseInt(vehicle_id));
    }

    viewHistory = function (vehicle_id) {
        
        $.ajax({
            url: "../controllers/booking.php",
            type: "POST",
            dataType: "json",
            data: {vehicle_id:vehicle_id, history: true},
            success: function (data) {
                if (data["success"]) {
                    $('#history').empty();
                    table = `<table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col"> Booking ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Fix Type</th>
                            <th scope="col">Details</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        for (i = 0; i < data['data'].length; i++) {
            date = data['data'][i]['date'].split("-");
                        table += `<tr>
                        <td class="text-capitalize">`+ data['data'][i]['booking_id'] + `</td>
                          <td> `+ date[2] + "/" + date[1] + "/" + date[0] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['fix_type'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['details'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['cost'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['status'] + `</td>
                        </tr>`
        }

        table += `</tbody>
                    </table>`
                    $('#history').append(table);
                    $('#history-modal').modal('show');
                } else {
                    alert("This vehicle have not been booked");
                }
            }
        })
        
    }


    $('#selectBookingType').on('change', (e) => {
        bookingType = $('#selectBookingType option:selected').val();
        if (bookingType != "Chose a type") {
            $('#dateInput').prop('disabled', false);
            loadCalendar();
        } else {
            $('#dateInput').prop('disabled', true);
        }
    });


    function loadCalendar() {
        DisabledDates = [];
        $.ajax({
            url: "../controllers/booking.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data["success"]) {
                    bookings = data['data'];

                    for (let i = 0; i < bookings.length; i++) {
                        let weight;
                        switch ($('#selectBookingType option:selected').val()) {
                            case "anual service":
                                weight = 1;
                                break;

                            case "major service":
                                weight = 2;
                                break;

                            case "major repair":
                                weight = 2;
                                break;

                            case "other service":
                                weight = 1;
                                break;
                        }

                        if (parseInt(bookings[i]['total']) + weight > 4) {
                            DisabledDates.push(bookings[i]['date']);
                        }
                    }

                    $('#datetimepicker3').datepicker("destroy");

                    $('#datetimepicker3').datepicker({
                        startDate: '+1d',
                        daysOfWeekDisabled: [0],
                        beforeShowDay: function (date) 
                        {
                            day = date.getDate();
                            daystr = day.toString();
                            month = date.getMonth() + 1;
                            monthstr = month.toString();

                            while (daystr.length < 2) {
                                daystr = "0" + daystr;
                            }
                            while (monthstr.length < 2) {
                                monthstr = "0" + monthstr;
                            }

                            dmy = date.getFullYear() + "-" + monthstr + "-" + daystr;
                            if (DisabledDates.indexOf(dmy) != -1) {
                                return false;
                            }
                            else {
                                return true;
                            }
                        }
                    });
                } else {
                    alert("An error occurred");
                }
            }
        })
    }

});
