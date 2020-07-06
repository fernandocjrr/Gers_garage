$(document).ready(() => {

    var vehicles;

    $.ajax({
        url: "../controllers/account.php",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data["success"]) {                      //if success in data array = true
                vehicles = data['data'];
            } else {                                    //if success in data array = false
                if (data["exists"]) {                   //if exists in data array = true
                    alert("Email already used");        //alert that email already in use
                } else {                                //if success = false and exists = true
                    alert("An error occurred");         //alert unknow error
                }
            }
        }
    })

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

        var serializedData = $("#addvehicle-form").serializeArray();             //serialize form info into array

        console.log(serializedData);

        $.ajax({
            url: "../controllers/account.php",
            type: "POST",
            dataType: "json", 
            data: serializedData,
            success: function (data) {

                if (data["success"]) {                      //if success in data array = true
                    $('#addvehicle-modal').modal('hide');       //close modal
                    alert("Vehicle added");                //send alert user signed up
                }  else {                                //if success = false and exists = true
                        alert("An error occurred");         //alert unknow error
                    }
                }
            
        })
    });

});