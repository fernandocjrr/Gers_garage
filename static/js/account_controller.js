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
    
    /* BOOKING FUNCTION
       
       # Get data from form id = booking-form when submit event, store data on serializedData and post to controllers/booking.php
            + Stores data from JavaScript on variables and change format of $date to MySQL format
            + Uses variables as inputs on createBooking() method from models/booking.php
                - Query "INSERT INTO booking (fix_type, details, date)", and return success TRUE or FALSE and the booking id of the added row
            + If sucess = TRUE and booking id is not empty uses booking id and vehicle id as inputs on addHaeBooking() method on models/have.php
                - Query ""INSERT INTO have (vehicle_id, booking_id)" and returns success TRUE or FALSE
            + Returns success TRUE to JavaScript (if any success FALSE or no booking id, return success FALSE)
       # If returned data from controllers is contains success = TRUE show alert to user "service Booked"
       
    */

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

    /*  Get vehicle ID
        # Gets the vehicle id of the card when button is clicked on view account.php
        # Send this vehicle id to a hidden input on booking modal (so it have the vehicle id to use on query on have model
    */
    
    
    createBooking = function (vehicle_id) {

        $("#vehID").val(parseInt(vehicle_id));
    }
    
    /*  VIEW HISTORY FUNCTION
        
        When "view history" button is clicked on view account.php, it used the vehicle id as input to the function below
        Differently from other modals, this is rendered by JavaScript not by view page (get data first and then oprn modal to user)
        
        # POST vehicle id and history = TRUE (history = TRUe to differenciate from other POST requestes) to controller/booking.php
            + Uses vehicle id on getHistory() method from models/booking.php
                - Query "SELECT * FROM vehicle   INNER JOIN have ON vehicle.vehicle_id = have.vehicle_id
                                                 INNER JOIN booking ON booking.booking_id = have.booking_id
                                                 WHERE vehicle.vehicle_id = ?"
                - If success return "success" true and all data found, if no  success return FALSE               
            + If success = TRUE and data not empty (means if found bookings), creates a for loop for every item found
                + Call getCosts([i]["bookingid"]) method from controller invoice.php (return the costs by booking id not summed) and store answer on $costs_info
                + If any costs were fround for this booking ([i]["bookingid"]) it will iterate o all found rows and multiply the cost for quantity and sum all the results storing in $total
                + Set value of [$j]["cost"] (first loop) with $total
            + Returns $response with cost calculated on ["data"][i]["cost"]
        # If returned data contains success = TRUE it will first empty the div (id = history) and then append a table with the history data on the same div
        # If returned data contains success = FALSE means no booking found so no history
    
    */

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

    /* ENABLE DATE
        
       To force user to first select the booking type first, before chosing the date
       This is important to let the system calculate the dates that should be disabled accourding to the chosen type
       Because different types of bookings have different weights
       # First get selected type when input changes
       # Check if its not the label, if not enable date input and load calendar (method below)
       # While type is Chose a type, calendar wont load
    */

    $('#selectBookingType').on('change', (e) => {
        bookingType = $('#selectBookingType option:selected').val();
        if (bookingType != "Chose a type") {
            $('#dateInput').prop('disabled', false);
            loadCalendar();
        } else {
            $('#dateInput').prop('disabled', true);
        }
    });
    
    /* LOAD CALENDAR ON BOOKING MODAL
    
        This function check the dates booked and give weight values to it, it 4 or more it put on diassabled dates array, it also add the value of the selected
        booking type to the calc, it avoids booking of weight 2 on day that already have 3 of weight, resulting on 5 weight for that date.
    
       # First cleans it will send a GET request to controllers/booking.php
            + Call cheeckBookingWeight() method from models/booking.php
                   - Using alias to "create" a new table and case to give weight to the different type of bookings, the query should return
                   grouped by date, the sum of weight of each date found with booking. Now we have weight calculated from booking created.
                   - If success return TRUE and the "new" table of weights of not return FALSE
            + Send data from query to JavaScript
       # If data from controller contains success TRUE, than get the data and put on bookings variable
        # Iterate on every item of bookings with a for loop (each item is a date and the sum of the weights)
            # Get the selected type on the dropdown input and atribute a weight value to it
            # Checks if the value of total + weight given to selected type is bigger than 4
            # If its bigger add the date on the array DisableDates
            
            Continues below....
    */


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
                    
                    /* LOAD DATEPICKER CALENDAR
                        # Start date +1d so customer cannot book for the same day
                        # daysOfWeekDisabled = 0 so sundays are not enabled for bookings
                        # beforeShowDay will run this function for every date on the calendar
                            # First it gets the selected date and parse to string, than the same with the month but adds on because it considers january = 0
                            # Checks if day and month are 2 digits, if not, add 0 on the left (so it matches mysql format)
                            # Concatenate year month and day on mysql format YYYY-MM-DD
                            # Compares the date is on DisableDates, if indexOF() return different than -1 means date found return false that desable the date
                            # If date not found returns true enabling the date
                    */
                

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
