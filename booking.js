document.addEventListener("DOMContentLoaded", function () {

    fetch("export_appointment.php")
        .then(response => response.json())
        .then(data => {

            const tbody = document.querySelector("#appointmentsTable tbody");
            tbody.innerHTML = "";

            data.forEach(app => {

                const row = `
                    <tr>
                        <td>${app.appointment_id}</td>
                        <td>${app.customer_first} ${app.customer_last}</td>
                        <td>${app.therapist_first} ${app.therapist_last}</td>
                        <td>${app.service_name}</td>
                        <td>${app.start_datetime}</td>
                        <td>${app.end_datetime}</td>
                        <td>${app.status}</td>
                        <td>${app.price}</td>
                    </tr>
                `;

                tbody.innerHTML += row;
            });

        })
        .catch(error => {
            console.error("Error loading appointments:", error);
        });

});
