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
                    for(i=0 ; i<bookings.length;i++){
                        EnabledDates.push(bookings[i]['date']);
                    }                

                    $('#datetimepicker3').datepicker("destroy");

                    $('#datetimepicker3').datepicker({
                        
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
                        daysOfWeekDisabled: [0,2,3,4,5,6],
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
                data: {date: today, bookingByDay: true},
                success: function (data) {
                    if (data["success"]) {
                        console.log(data);
                    } else {
                        alert("An error occurred");
                    }
                }
            });

        });

        $('#weekInput').on('change', (e) => {
            startDate = $('#weekInput').val();
            startDate = new Date(startDate);
            endDate = new Date (startDate);
            endDate = endDate.setDate(endDate.getDate()+(6-endDate.getDay()));
            endDate = new Date(endDate)

            $.ajax({
                url: "../controllers/booking.php",
                type: "POST",
                dataType: "json",
                data: {startDate: startDate, endDate: endDate, bookingByWeek: true},
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