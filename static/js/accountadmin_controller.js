  
/* 
    # PROCESS HAPPENING ON JavaScript
    - PROCESS HAPPENING ON CONTROLLER
    + PROCESS HAPENNING ON MODEL
*/ 


$(document).ready(() => {

    var bookings;
    var EnabledDates = [];
    var parts;
    var staff;
    var part_n = 1;
    
    /* ENABLE DATES ON DATEPICKER DATE WITH BOOKING
    
        This method load two different datapickers (3 and 4) 3 will only enable the dates with bookings (view by day tab) and 
        4 will enable all mondays (view by week tab) 
        
        DATEPICKER 3
    
        # Send a GET request to controller/booking.php
            + Call cheeckBookingWeight() method from models/booking.php
                - This method is explaned on last function of  account_controler.js, in this case the weight is not relevant
                - This will return an array with the dates that have bookings (and weight but not relevant here)
            + Send data from model to JavaScrpit
        # Store data from controller on bookings variable and iterate on every item of it
            # Gets every date on bookings variable and add to EnablesDates array
        # Again before date will run function for every date
            # First take month and day of calendar and parse to string, then adds 0 on the left if month or/and day have only one digit (MySQL format)
            # Formats on MuSQL (YYYY-MM-DD) and checks if date of datepicker is found on EnabledDates array, if yes enable the date on the calendar, if not found disable
            
            
            DATEPICKER 4 (view by week)
            
            Using daysOfWeekDisabled, disable every day of the week but not mondays
            
    */

    $.ajax({
        url: "../controllers/booking.php",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data["success"]) {
                bookings = data['data'];
                for (i = 0; i < bookings.length; i++) {
                    EnabledDates.push(bookings[i]['date']);
                }

                $('#datetimepicker3').datepicker("destroy");
                
                $('#datetimepicker3').datepicker({

                    beforeShowDay: function (date) {
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
                        if (EnabledDates.indexOf(dmy) != -1) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                });

                $('#datetimepicker4').datepicker("destroy");

                $('#datetimepicker4').datepicker({
                    daysOfWeekDisabled: [0, 2, 3, 4, 5, 6],
                });
            } else {
                alert("An error occurred");
            }
        }
    })

    /* TABLE OF BOOKINGS BY DAY
      
       This JS appends the table with the booking history of the chosen date in the modal on accountAdmin.php view
       
       # Gets the date from user input ans store in today
       # Change date format
       # POST date and bookingByDay TRUE (to differenciate between POST requests) to controllers/booking.php
          + If bookingBuDay TRUE, change date format to MySQL format and use it on getBookingDay() method from model booking.php
              - The model will run a query that will join 7 tables in order to get all the information needed to be displayed on the table
              - If success TRUE, return success TRUE and all the data on a array, if success FALSE, return only success FALSE
          + If success TRUE and data not empty (means booking was found)
              + Iterate with for loop on every booking 
                  + Using the booking if of the index (["data"][$j]["booking_id"]) as input on getCosts() from models/cost.php
                    - Query SELECT * FROM cost   LEFT JOIN parts ON cost.part_id = parts.part_id WHERE cost.booking_id = ?  o get all the costs
                    - If success, return success TRUE and data found, if not return success FALSE
                  + Store the cost on $costs_info
                  + If success from cost query is TRUE, iterate every cost with for loop
                    + This loop will multiply every part cost by the quantity and sum them with any assigned cost by adm
                    + It will return to the JavaScript success TRUE and the total cost value in an array
       # If data returned with success TRUE, append the table with all the data that came from the query on the div id = bookingByDay
    */
    

    $('#dateInput').on('change', (e) => {
        today = $('#dateInput').val();
        today = new Date(today);

        $.ajax({
            url: "../controllers/booking.php",
            type: "POST",
            dataType: "json",
            data: { date: today, bookingByDay: true },
            success: function (data) {
                if (data["success"]) {
                    $('#bookingByDay').empty();

                    table = `<table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Fix Type</th>
                                        <th scope="col" colspan="2">Details</th>
                                        <th scope="col">Cost</th>
                                        <th scope="col">Staff</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    for (i = 0; i < data['data'].length; i++) {
                        date = data['data'][i]['date'].split("-");
                        table += `<tr>
                          <td> `+ date[2] + "/" + date[1] + "/" + date[0] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['first_name'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['model'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['fix_type'] + `</td>
                          <td class="text-capitalize" colspan="2">`+ data['data'][i]['details'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['cost'] + `</td>
                          <td class="text-capitalize">`+ (data['data'][i]['staff_fname'] ? data['data'][i]['staff_fname'] : "Unassigned") + `</td>                          
                          <td class="text-capitalize">`+ data['data'][i]['status'] + `</td>
                          <td><button onClick="editBooking(`+ data['data'][i]['booking_id'] + `)" class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#editbooking-modal">EDIT</button></td>
                        </tr>`
                    }

                    table += `</tbody>
                    </table>`
                    $('#bookingByDay').append(table);
                } else {
                    alert("An error occurred");
                }
            }
        });

    });
  
  /*   TABLE OF BOOKING BY INTERVAL (ONE WEEK)
    
    # Gets the date selected by the user on the calendar, stores in a variable startDate and change format
    # Adds 6 days on the start date and store it com endDate and change the format
    # POST the date interval and booking bytweek (diferentiate POST) to controllers/booking.php
      + Use startDate and EndDate as inputs on method getBookingByInterval() from models/booking.php
        - query will find all booking in the date interva, joining 7 tables to get all information needed to be displayed on the table 
        - If success TRUE return success TRUE and data found in a array, if not return FALSE
      + Return data found on db to JS
    # Adds all the information found an a table and append it on div id = bookingByWeek in modal (views/accountAdmin.php)
    
    
    Prepares the Generate button to create a new blank page with the date interval, this page show the roster
    Please check views/roster.php
    
  */

    $('#weekInput').on('change', (e) => {
        startDate = $('#weekInput').val();
        startDate = new Date(startDate);
        endDate = new Date(startDate);
        endDate = endDate.setDate(endDate.getDate() + (6 - endDate.getDay()));
        endDate = new Date(endDate)

        $.ajax({
            url: "../controllers/booking.php",
            type: "POST",
            dataType: "json",
            data: { startDate: startDate, endDate: endDate, bookingByWeek: true },
            success: function (data) {
                if (data["success"]) {
                    $('#bookingByWeek').empty();
                    table = `<table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-center">Name</th>
                                        <th scope="col" class="text-center">Model</th>
                                        <th scope="col" class="text-center">Fix Type</th>
                                        <th scope="col" class="text-center" colspan="3">Details</th>
                                        <th scope="col" class="text-center">Staff</th>
                                    </tr>   
                                </thead>
                                <tbody>`;
                    for (i = 0; i < data['data'].length; i++) {
                        date = data['data'][i]['date'].split("-");
                        table += `<tr>
                                      <td> `+ date[2] + "/" + date[1] + "/" + date[0] + `</td>
                                      <td class="text-capitalize text-center">`+ data['data'][i]['first_name'] + `</td>
                                      <td class="text-capitalize text-center">`+ data['data'][i]['model'] + `</td>
                                      <td class="text-capitalize text-center">`+ data['data'][i]['fix_type'] + `</td>
                                      <td class="text-capitalize text-center" colspan="3">`+ data['data'][i]['details'] + `</td>
                                      <td class="text-capitalize text-center">`+ (data['data'][i]['staff_fname'] ? data['data'][i]['staff_fname'] : "Unassigned") + `</td>
                                      </tr>`
                    }
                    
                    table += `<tr>
                                <td colspan="7"></td>
                                <td class="text-center "><a class="btn btn-outline-info" type="button" href="roster.php?startDate=`+startDate.toISOString().slice(0,10)+`&endDate=`+endDate.toISOString().slice(0,10)+`" target="_blank">Generate Roster</a></td>
                                
                                </tr>
                                </tbody>
                    </table>`
                    $('#bookingByWeek').append(table);
                    
                } else {
                    alert("An error occurred");
                }
            }
        });
    });

  /* Methods call to use info on Edit booking modal
    Explanation bellow
  */
    getParts();
    loadStaffs();
  
  /* BOOKING EDITOR MODAL FUNCTION
    
    # Get data on from id = editbooking-form and stores on serializedData variable
    # On this data array add editBooking TRUE to diferentiate POST request
    # POST variable to controllers/booking.php
      + With form data first run method addCost() from models/cost (only with cost added not parts)
        - Add info inserted by adm on the db
      + With a for loop iterate as many times as parts were added (not quantity, but number of inputs, this number is stored on a hidden input "qnt" in the view )
      + Now for every item, added it one by one on the db (this way we have different lines, more organized and they will be summed when needed)
      + Now the assigned staff will be deleted (in case adm wants to change previouslly assigned staff) from the database
      + With the staff id and booking id, call assignStaff() method from models/assign.php
      + If staff registered successfully, with booking id and status select by user, run editStatus() method from models/booking.php
        - Update the status on dd, if success return success TRUE, if not, success FALSE
      + Send success TRUE or FALSe to JS
    # If success returned from controller, hide edut modal, and show prevuious modal (viewbookings)
    # Show success alert to user and clean all modal from inputs (date) and the div with table
  
  */
  
    $("#editbooking-form").on("submit", (event) => {
        event.preventDefault();

        var serializedData = $("#editbooking-form").serializeArray();
        serializedData.push({ name: 'editBooking', value: true });

        $.ajax({
            url: "../controllers/booking.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {
                if (data["success"]) {
                    $('#editbooking-modal').modal('hide');
                    $('#viewbookings-modal').modal('show');
                    alert("Booking Edited");
                    $('#dateInput').val("");
                    $('#bookingByDay').empty();
                } else {
                    alert("An error occurred");
                }
            }
        })
    });
  
    /*
      Method simply clean div and date input in case user closed modal.
    */
 

    $("#viewbooking").on("click", (event) => {

        $('#dateInput').val("");
        $('#bookingByDay').empty();

    });

  /* METHOD APPEND DROPDOWN LISTS AND TEXT INPUT WHEN CLICK ON BUTTOM
    
    When more parts button is pressed on edit booking model it will append a new dropdown list and text input (for quantity) on div id = partInput
    The new added inputs will be dinamically named and give the same as ID
    Affter append a add 1 to part_n in case user press more parts again they will have different names and ID
    Also sends the number of part_n(number of inputs) to hidden input qnt that will be used later when add parts to database
  */

    $("#moreParts").on("click", (event) => {
        event.preventDefault();

        $("#partInput").append(`<div class="col-md-6 mt-1"><select class="form-control" name="part_` + part_n.toString() + `" value="" id="selectPart_` + part_n.toString() + `" required>
        <option value="">Add Part</option>
      </select>
    </div>
    <div class="col-md-6 mt-1">
      <input type='number' class="form-control" placeholder="Quantity" name="quantity_`+ part_n.toString() + `" id="partQuantity_` + part_n.toString() + `" required/>
    </div>`)

        part_n++;

        $('#qnt').val(part_n);
        loadParts();


    });
  
  /* POPULATE DROPDOWN LIST WITH STAFF FROM DATABASE
  
    # Select dropdown input id = selectStaff and ignore first item (label)
    # POST to controller that will run query on model that will return all the staffs on de database
    # With a for loop it will append options (staffs) on the dropdown menu
  */

    function loadStaffs() {
        let select = $('#selectStaff');
        select.children('option:not(:first)').remove();

        $.ajax({
            url: "../controllers/staff.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data["success"]) {
                    staff = data['data'];

                    for (let i = 0; i < staff.length; i++) {
                        select.append($('<option>', {
                            value: staff[i]['staff_id'],
                            text: staff[i]['staff_fname']
                        }));
                    }
                } else {
                    alert("An error occurred");
                }
            }

        });
    }
  
  /* SIMPLY GETS THE PARTS ON THE DATABASE
  
    With post to controller and then calling model method with query
    and call the function that will append the items on the dropdown list on all the input fields
  */


    function getParts() {
        $.ajax({
            url: "../controllers/parts.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data["success"]) {
                    parts = data['data'];
                    loadParts();
                } else {
                    alert("An error occurred");
                }
            }

        });
    }

    /* POPULATE DROPDOWN LIST WITH PARTS FROM DATABASE
    
    # With a for loop it will checks how many "more Items" (buttons pressed) there are on the modal
      # Select dropdown input id = selectPart and ignore first item (label)
      # For every input field it will append the parts in its input dropdown
    # 
  */
  
    function loadParts() {
        for (let n = 0; n < part_n; n++) {
            let select = $('#selectPart_' + n.toString());
            select.children('option:not(:first)').remove();
            for (let i = 0; i < parts.length; i++) {
                select.append($('<option>', {
                    value: parts[i]['part_id'],
                    text: parts[i]['part']
                }));
            }
        }
    }
  
 /*
 This function will run then adm click on edit buttom
This methos receive the booking id and send it to a hidden input on the model, so we have this information to register all edit data on this booking ID
and prepares the invoice buuton to create a blank page with the boooking id to generate a invoice page
(check views/invoice.php)

 
 */


    editBooking = function (booking_id) {
        $("#bookID").val(parseInt(booking_id));
        $('#viewbookings-modal').modal('hide');
        $("#invoice").attr("href", "invoice.php?bookingId=" + booking_id);
    }




});
