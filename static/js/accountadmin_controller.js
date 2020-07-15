$(document).ready(() => {

    var bookings;
    var EnabledDates = [];

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
                                        <th scope="col">User's Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Manufacturer</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Fix Type</th>
                                        <th scope="col">Booking Details</th>
                                        <th scope="col">Cost</th>
                                        <th scope="col">Staff Assigned</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    for (i = 0; i < data['data'].length ; i++){
                        date = data['data'][i]['date'].split("-");
                            table += `<tr rowspan="2">
                          <td> `+ date[2] + "/" + date[1] + "/" + date[0] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['first_name'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['phone'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['manufacturer'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['model'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['fix_type'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['details'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['cost'] + `</td>
                          <td class="text-capitalize">`+ data['data'][i]['staff'] + `</td>                          
                          <td class="text-capitalize">`+ data['data'][i]['status'] + `</td>
                        </tr>
                        <tr>
                        <td class="border-top-0" colspan="2"><input type='text' class="form-control" placeholder="Add cost Value" id="costInput" /></td>
                        <td class="border-top-0" colspan="3"><div class="form-group">
                                            <select class="form-control" name="selectPart" id="selectPart">
                                                <option>Add Part</option>
                                                <option value="diesel">Diesel</option>
                                                <option value="petrol">Petrol</option>
                                                <option value="hybrid">Hybrid</option>
                                                <option value="electric">Electric</option>
                                            </select>
                                        </div>
                        </td>
                        <td class="border-top-0" colspan="2"><div class="form-group">
                        <select class="form-control" name="selectStatus" id="selectStatus">
                            <option>Status</option>
                            <option value="booked">Booked</option>
                            <option value="inService">In Service</option>
                            <option value="fixes">Fixed</option>
                            <option value="collected">Collected</option>
                            <option value="unrepairrable">Unrepairrable</option>
                        </select>
                    </div>
    </td>
                        <td class="border-top-0" colspan="3"><div class="form-group">
                                            <select class="form-control" name="selectStaff" id="selectStaff">
                                                <option>Asign Staff</option>
                                                <option value="diesel">Diesel</option>
                                                <option value="petrol">Petrol</option>
                                                <option value="hybrid">Hybrid</option>
                                                <option value="electric">Electric</option>
                                            </select>
                                        </div>
                        </td>
                        </tr>
                        <tr>
                        <td class="border-top-0" colspan="8"><textarea type="text" name="details" id="details" class="form-control" placeholder="Comments"></textarea></td>
                        <td class="border-top-0" colspan="1"><button type="submit" class="btn btn-success">Invoice</button></td>
                        <td class="border-top-0" colspan="1"><button type="submit" class="btn btn-success">Send</button></td>
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
                    console.log(data);
                } else {
                    alert("An error occurred");
                }
            }
        });
    });


});