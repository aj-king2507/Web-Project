#  OpalGlow – Database Implementation (Member 1 – Database Architect)
# Overview
This part of the project was completed by **Member 1 – Database Architect**, responsible for designing and implementing the **MySQL database** for the OpalGlow salon booking system.

The database includes six main entities:
- **Admin**
- **Customer**
- **Therapist**
- **Service**
- **Therapist_Availability**
- **Appointment**
- **Payment**

All relationships were defined using **primary keys** and **foreign keys**, and normalization was applied up to **3NF** to ensure data integrity.

---

# How to Import the Database
1. Open **phpMyAdmin** from XAMPP Control Panel.
2. Create a new database named `opalglow`.
3. Click the **Import** tab.
4. Choose the file `opalglow.sql` from this repository.
5. Click **Go** — the tables will be created automatically.

---

# How to Test the Database
After importing:
1. Go to the **SQL tab** in phpMyAdmin.
2. Run these sample queries to verify data and relationships:

   ```sql
   -- View all appointments with customer, therapist, and service
   SELECT a.appointment_id, c.first_name AS customer, t.first_name AS therapist,
          s.name AS service, a.start_datetime, a.end_datetime, a.status
   FROM Appointment a
   JOIN Customer c ON a.customer_id = c.customer_id
   JOIN Therapist t ON a.therapist_id = t.therapist_id
   JOIN Service s ON a.service_id = s.service_id;

   -- View all payments with customer and service details
   SELECT p.payment_id, c.first_name AS customer, s.name AS service,
          p.amount, p.method, p.status
   FROM Payment p
   JOIN Appointment a ON p.appointment_id = a.appointment_id
   JOIN Customer c ON a.customer_id = c.customer_id
   JOIN Service s ON a.service_id = s.service_id;

   -- Count total appointments per therapist
   SELECT t.first_name AS therapist, COUNT(a.appointment_id) AS total_appointments
   FROM Therapist t
   LEFT JOIN Appointment a ON t.therapist_id = a.therapist_id
   GROUP BY t.first_name;
