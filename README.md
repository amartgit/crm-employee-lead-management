# CRM Employee & Lead Management System

A **Role-Based CRM Employee & Lead Management System** developed using **Laravel (PHP), MySQL, Bootstrap 4, JavaScript**, and **Pusher** for real-time updates.

This system manages **employees, attendance tracking, and sales leads**, with **strict role-based access**, similar to real-world HR & CRM platforms.

---

## 🚀 Project Highlights

* Real-world **Employee ID–based authentication**
* Role-based dashboards (SuperAdmin, Admin, Employee)
* Attendance system with login tracking
* Sales lead management with assignment & priority
* Clean Laravel **MVC structure**
* GitHub-ready for version control and collaboration
* Easy local setup using `.env` and SQL import

---

## 🔐 Authentication & Security

* Login using **Employee ID & Password**
* Forgot Password via **Employee ID & Registered Mobile Number**
* Secure logout & auto-logout after inactivity
* Role-based route protection
* Environment-based configuration via `.env`

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

* Register new employees with **auto-generated Employee IDs**
* Assign roles & departments
* View employee attendance & daily login reports
* Manage own profile

---

### 🛠️ Admin (VI002) – HR

* Employee registration & profile management
* Attendance monitoring
* **Lead Management (CRUD)**
* Assign leads to sales employees
* Mark important (priority) leads
* Import leads from Excel / CSV sheets
* Manage own profile

---

### 📞 Employee (VI003) – Sales

* View & update profile
* Attendance **Check-in / Check-out**
* View assigned leads
* Update lead follow-ups & status

---

### 👤 Employee (VI005) – No Department Assigned

* View & update profile
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

📁 **Database File**: `/database/....sql`

### 📌 Notes

* Contains **table structure + sample data only**
* No sensitive data included
* Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_employee_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## ⚙️ Installation & Setup

1. Clone the repository:

```bash
git clone https://github.com/amartgit/crm-employee-lead-management.git
cd crm-employee-lead-management
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Setup environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Import database:

* Open phpMyAdmin / MySQL Workbench
* Create database: `crm_employee_db`
* Import SQL: `/database/databasedemo.sql`

5. Storage & permissions:

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache  # Linux/Mac
```

6. Enable **GD extension** in `php.ini`:

```ini
;extension=gd   ❌ disabled
extension=gd    ✅ enabled
```

Restart Apache/XAMPP after enabling.

7. Clear caches:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

8. Run the project:

```bash
php artisan serve
```

Open browser: `http://127.0.0.1:8000`

---

## 🔹 Sample Login Credentials

| Employee ID | Role       | Password   |
| ----------- | ---------- | ---------- |
| VI001       | SuperAdmin | (from SQL) |
| VI002       | Admin      | (from SQL) |
| VI003       | Employee   | (from SQL) |

---

## 📸 Screenshots

(Add dashboard, employee management, attendance, and leads screenshots here)

---

## 📄 License

This project is **open-source**, intended for **learning, demonstration, and portfolio purposes**.

---

## 👨‍💻 Developed By

**Amar Tarmale**
🌐 Web Developer | 🚀 Tech Enthusiast
📍 India

LinkedIn: [https://www.linkedin.com/in/amar-tarmale-atdev](https://www.linkedin.com/in/amar-tarmale-atdev)
Instagram: [https://www.instagram.com/amart.atdev](https://www.instagram.com/amart.atdev)

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

