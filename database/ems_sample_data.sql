-- EMS Sample Data (Fake Data for Demo)
-- ----------------------------------

-- Employees
INSERT INTO employees (employee_id, fname, lname, phone_number, department, gender, mailid, created_at) VALUES
('VI001', 'John', 'Doe', '9999990001', 'IT', 'Male', 'john.doe@example.com', NOW()),
('VI002', 'Jane', 'Smith', '9999990002', 'HR', 'Female', 'jane.smith@example.com', NOW()),
('VI003', 'Amit', 'Patil', '9999990003', 'Sales', 'Male', 'amit.patil@example.com', NOW());

-- Users
-- Use LAST_INSERT_ID or numeric employee id from employees
INSERT INTO users (employee_id, name, password, role, created_at) VALUES
((SELECT id FROM employees WHERE employee_id='VI001'), 'John Doe', '$2y$10$demopasswordhash', 'SuperAdmin', NOW()),
((SELECT id FROM employees WHERE employee_id='VI002'), 'Jane Smith', '$2y$10$demopasswordhash', 'Admin', NOW()),
((SELECT id FROM employees WHERE employee_id='VI003'), 'Amit Patil', '$2y$10$demopasswordhash', 'Employee', NOW());

-- Attendance
INSERT INTO attendances (employee_id, date, check_in, check_out, work_type, status, total_working_time) VALUES
((SELECT id FROM employees WHERE employee_id='VI001'), CURDATE(), '09:30:00', '18:30:00', 'Office', 'Present', 540),
((SELECT id FROM employees WHERE employee_id='VI002'), CURDATE(), '10:00:00', '19:00:00', 'WFH', 'Present', 540);

-- Breaks
-- Assuming attendance id 1 corresponds to John Doe's attendance (can also select dynamically)
INSERT INTO breakemps (attendance_id, type, start_time, end_time) VALUES
((SELECT id FROM attendances WHERE employee_id=(SELECT id FROM employees WHERE employee_id='VI001') ORDER BY date DESC LIMIT 1), 'Lunch', '13:30:00', '14:00:00');

-- Leads
INSERT INTO leads (name, company, contact_info, city, upload_date, status, priority, created_at) VALUES
('Demo Client', 'ABC Technologies', '9999998888', 'Mumbai', CURDATE(), 'Interested', 'High', NOW()),
('Test Lead', 'XYZ Solutions', '9999997777', 'Pune', CURDATE(), 'Not Answered', 'Medium', NOW());

-- Leave Requests
INSERT INTO leave_requests (employee_id, start_date, end_date, leave_type, reason, status, created_at) VALUES
((SELECT id FROM employees WHERE employee_id='VI001'), '2025-06-01', '2025-06-01', 'Casual', 'Personal work', 'Pending', NOW());
