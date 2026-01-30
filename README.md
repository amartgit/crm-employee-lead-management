
# CRM Employee & Lead Management System

A **Role-Based CRM Employee & Lead Management System** developed using **Laravel (PHP), MySQL, Bootstrap 4, JavaScript**, and **Pusher** for real-time functionality.

This project is designed to handle **employee management, attendance tracking, and sales lead management** with strict role-based access control, similar to real-world HR & CRM systems used in companies.

---

## 🚀 Project Highlights

* Real-world **Employee ID–based authentication**
* Role-based dashboards (SuperAdmin, Admin, Employee)
* Attendance system with login tracking
* Sales lead management with assignment & priority
* Clean Laravel MVC structure
* Production-ready GitHub setup

---

## 🔐 Authentication & Security

* Login using **Employee ID & Password**
* Forgot Password using **Employee ID & Registered Mobile Number**
* Secure Logout
* Auto Logout on inactivity
* Role-based route protection
* Environment-based configuration using `.env`

---

## 👥 Employee Roles & Departments

| Employee ID | Role       | Department                        |
| ----------- | ---------- | --------------------------------- |
| VI001       | SuperAdmin | Manager                           |
| VI002       | Admin      | HR                                |
| VI003       | Employee   | Sales                             |
| VI005       | Employee   | Not Assigned (IT / User / Others) |

---

## 🧑‍💼 Role-Based Functionalities

### 🔑 SuperAdmin (VI001) – Manager

* Register employees with **auto-generated Employee IDs**
* Assign roles and departments
* View employee attendance & daily login reports
* Manage own profile

---

### 🛠️ Admin (VI002) – HR

* Employee registration & profile management
* Attendance monitoring
* Complete **Lead Management (CRUD)**
* Assign leads to sales employees
* Mark important (priority) leads
* Import leads from Excel / CSV sheets
* Manage own profile

---

### 📞 Employee (VI003) – Sales

* View & update personal profile
* Attendance **Check-in / Check-out**
* View assigned leads
* Update lead follow-ups and status

---

### 👤 Employee (VI005) – No Department Assigned

* View & update personal profile
* Attendance **Check-in / Check-out**
* Limited access based on role permissions

---

## 🧩 Technologies Used

* **Backend:** Laravel (PHP – MVC Architecture)
* **Frontend:** Blade Templates, Bootstrap 4, JavaScript
* **Database:** MySQL
* **Real-time Updates:** Pusher
* **Authentication:** Customized Laravel Auth
* **Version Control:** Git & GitHub

---

## 🗄️ Database Setup

The database schema is provided **for reference and local testing**.

📁 **Database File**

```
/database/crm_employee_lead_management.sql
```

### 📌 Notes

* Contains **table structure + sample data only**
* No real credentials or sensitive data included
* Update database credentials in `.env`

---

## ⚙️ Installation & Setup

```bash
git clone https://github.com/amartgit/crm-employee-lead-management.git
cd your-repo-name
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## 🛠️ PHP Configuration Requirement

Ensure **GD Extension** is enabled in `php.ini` (required for image handling).

```ini
;extension=gd   ❌ disabled
extension=gd    ✅ enabled
```

🔁 Restart Apache / XAMPP after enabling.

---

## 📸 Screenshots

(Add screenshots of Dashboard, Attendance, Leads, Employee Management here)

---

## 📄 License

This project is open-source and available **for learning, demonstration, and portfolio use**.

---

## 👨‍💻 Developed By

**Amar Tarmale**
🌐 Web Developer | 🚀 Tech Enthusiast
📍 India

🔗 LinkedIn:
[https://www.linkedin.com/in/amar-tarmale-atdev](https://www.linkedin.com/in/amar-tarmale-atdev)

📸 Instagram:
[https://www.instagram.com/amart.atdev](https://www.instagram.com/amart.atdev)

---

<p align="center">
<a href="https://github.com/laravel/framework/actions">
<img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
</a>
<a href="https://packagist.org/packages/laravel/framework">
<img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
</a>
<a href="https://packagist.org/packages/laravel/framework">
<img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/laravel/framework">
<img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
</a>
</p>

---


