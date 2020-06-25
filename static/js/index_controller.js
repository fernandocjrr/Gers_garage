$(document).ready(() =>{                                                    //if page ready
    $("#signup-form").on("submit", (event) => {                             //if event submit
        event.preventDefault();

        var serializeData = $("#signup-form").serializeArray();             //serialize form info into array
        var data = {};
        for (let i=0;i<serializeData.length;i++){                           //for loop take info from array serializeData and add on data
            data[serializeData[i]["name"]] = serializeData[i]["value"]
        }
        console.log(data)

        $.ajax({
            url: "controllers/index.php",
            type: "POST",
            dataType: "json",
            data: data,
            success: function(data){
                console.log(data)
            }
        })
    });
});