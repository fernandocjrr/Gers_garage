$(document).ready(() => {

    var vehicles;
    var bookings;
    var DisabledDates = [];

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


    $("#addvehicle-form").on("submit", (event) => {                             //if event submit from signup-form
        event.preventDefault();

        var serializedData = $("#addvehicle-form").serializeArray();

        $.ajax({
            url: "../controllers/account.php",
            type: "POST",
            dataType: "json",
            data: serializedData,
            success: function (data) {

                if (data["success"]) {                      //if success in data array = true
                    $('#addvehicle-modal').modal('hide');       //close modal
                    alert("Vehicle added");                //send alert user signed up
                } else {                                //if success = false and exists = true
                    alert("An error occurred");         //alert unknow error
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