$(document).ready(function () {

    $("#bookingForm").submit(function (e) {
        e.preventDefault();

        // Clear previous messages
        $("#message").html("").removeClass("error success");

        // Get form values
        let name = $("#customer_name").val().trim();
        let phone = $("#phone").val().trim();
        let email = $("#email").val().trim();
        let service = $("#service").val();
        let time = $("#time_slot").val();
        let date = $("#appointment_date").val();

        // Basic validation
        if (!name || !phone || !email || !service || !time || !date) {
            $("#message")
                .html("Please fill in all fields")
                .addClass("error");
            return;
        }

        // Email format check
        let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/;
        if (!email.match(emailPattern)) {
            $("#message")
                .html("Invalid email format")
                .addClass("error");
            return;
        }

        // Show spinner
        $("#spinner").show();

        // AJAX request
        $.ajax({
            url: "../backend/submit_booking.php", // adjust if needed
            method: "POST",
            data: {
                customer_name: name,
                phone: phone,
                email: email,
                service: service,
                time_slot: time,
                appointment_date: date
            },
            dataType: "json",

            success: function (response) {
                $("#spinner").hide();

                if (response.status === "success") {

                    $("#message")
                        .html("Booking successful!")
                        .removeClass("error")
                        .addClass("success");

                    // Reset form
                    $("#bookingForm")[0].reset();

                    // Regenerate JSON file
                    $.ajax({
                        url: "../backend/export_appointment.php",
                        method: "GET"
                    });

                } else {
                    $("#message")
                        .html("Booking unsuccessful!" + response.message)
                        .removeClass("success")
                        .addClass("error");
                }
            },

            error: function () {
                $("#spinner").hide();

                $("#message")
                    .html("Server error. Please try again.")
                    .removeClass("success")
                    .addClass("error");
            }
        });
    });

});
