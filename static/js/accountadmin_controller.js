$(document).ready(() => {

    var bookings;
    var EnabledDates = [];
    var parts;
    var staff;
    var part_n = 1;

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
                          <td class="text-capitalize">`+ data['data'][i]['staff'] + `</td>                          
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
                                      <td class="text-capitalize text-center">`+ data['data'][i]['staff'] + `</td>
                                      </tr>`
                    }
                    table += `<tr>
                                <td colspan="7"></td>
                                <td class="text-center "><button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#roster-modal">Generate Roster</button></td>
                                
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

    getParts();
    loadStaffs();

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
                    $('#tbediooking-form').trigger('reset');
                    $('#editbooking-modal').modal('hide');
                    $('#viewbookings-modal').modal('show');
                    alert("Booking Edited");
                } else {
                    alert("An error occurred");
                }
            }
        })
    });


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


    editBooking = function (booking_id) {
        $("#bookID").val(parseInt(booking_id));
        $('#viewbookings-modal').modal('hide');
        $("#invoice").attr("href","invoice.php?bookingId="+booking_id);
    }


});