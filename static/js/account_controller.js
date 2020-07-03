$(document).ready(() => {   
    
    var vehicles;
    
        $.ajax({
            url: "../controllers/account.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data["success"]) {                      //if success in data array = true
                    vehicles=data['data'];
                } else {                                    //if success in data array = false
                    if (data["exists"]) {                   //if exists in data array = true
                        alert("Email already used");        //alert that email already in use
                    } else {                                //if success = false and exists = true
                        alert("An error occurred");         //alert unknow error
                    }
                }
            }
        })

        $('#vehicleType').on('change', (e) => {
            let select = $('#selectManufacturer');
            let added = [];
        select.children('option:not(:first)').remove();
    
        for (let i = 0; i < vehicles.length; i++) {  
            if (vehicles[i]['type'] === $('#vehicleType option:selected').val()) {  
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
    

        
    });