-- EMS Sample Data (Fake Data for Demo)
-- ----------------------------------

-- Employees
INSERT INTO employees (employee_id, fname, lname, phone_number, department, gender, mailid, created_at) VALUES
('EMP001', 'John', 'Doe', '9999990001', 'IT', 'Male', 'john.doe@example.com', NOW()),
('EMP002', 'Jane', 'Smith', '9999990002', 'Sales', 'Female', 'jane.smith@example.com', NOW()),
('EMP003', 'Amit', 'Patil', '9999990003', 'HR', 'Male', 'amit.patil@example.com', NOW());

-- Users
INSERT INTO users (employee_id, name, password, role, created_at) VALUES
('EMP001', 'John Doe', '$2y$10$demopasswordhash', 'Admin', NOW()),
('EMP002', 'Jane Smith', '$2y$10$demopasswordhash', 'Employee', NOW()),
('EMP003', 'Amit Patil', '$2y$10$demopasswordhash', 'Employee', NOW());

-- Attendance
INSERT INTO attendances (employee_id, date, check_in, check_out, work_type, status, total_working_time) VALUES
('EMP001', CURDATE(), '09:30:00', '18:30:00', 'Office', 'Present', 540),
('EMP002', CURDATE(), '10:00:00', '19:00:00', 'WFH', 'Present', 540);

-- Breaks
INSERT INTO breakemps (attendance_id, type, start_time, end_time) VALUES
(1, 'Lunch', '13:30:00', '14:00:00');

-- Leads
INSERT INTO leads (name, company, contact_info, city, upload_date, status, priority, created_at) VALUES
('Demo Client', 'ABC Technologies', '9999998888', 'Mumbai', CURDATE(), 'Interested', 'High', NOW()),
('Test Lead', 'XYZ Solutions', '9999997777', 'Pune', CURDATE(), 'Not Answered', 'Medium', NOW());

-- Leave Requests
INSERT INTO leave_requests (employee_id, start_date, end_date, leave_type, reason, status, created_at) VALUES
(1, '2025-06-01', '2025-06-01', 'Casual', 'Personal work', 'Pending', NOW());
