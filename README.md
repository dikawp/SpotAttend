# SpotAttend

SpotAttend is an internal Human Resource Information System (HRIS) designed to streamline employee management, attendance tracking, and leave requests. 

This application uses Laravel 11, Tailwind CSS, and Alpine.js, providing a fast, responsive, and modern user experience.

---

## 🚀 Features

*   **Role-Based Access Control:**
    *   **Superadmin (Role 2):** Can manage HR accounts (Create, Edit, Delete). Has an overview of the entire system.
    *   **HR Admin (Role 1):** Full access to manage employees, departments, office locations, holidays, and approve/reject leave requests. Can also monitor company-wide attendance.
    *   **Employee (Role 0):** Can check in and out for attendance, view their own attendance history, submit leave requests, and see upcoming company holidays.
*   **Attendance Management:** Clock in / clock out with lateness and overtime calculation based on employee schedules.
*   **Leave Management:** Submit leave requests with reason and dates. HR can approve or reject these requests.
*   **Office Locations & Departments:** Flexible management of company structure.
*   **Modern UI:** A clean, responsive, and intuitive interface with Dark Mode support.

---

## 🛠️ Prerequisites

Before you begin, ensure you have met the following requirements:
*   **PHP:** 8.2 or higher
*   **Composer:** Installed and accessible globally
*   **Node.js & NPM:** Installed (for compiling frontend assets)
*   **Database:** MySQL or PostgreSQL (MySQL recommended)

---

## ⚙️ Installation & Setup Guide

Follow these steps to get your development environment running:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/dikawp/SpotAttend.git
   cd SpotAttend
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   ```
   Open `.env` and set your database connection details (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations and Seeders:**
   This step will create all necessary database tables and populate the database with default accounts and sample data.
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Link Storage (For Profile Photos):**
   ```bash
   php artisan storage:link
   ```

8. **Build Frontend Assets:**
   ```bash
   npm run build
   # Or for development with hot-reloading: npm run dev
   ```

9. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://localhost:8000`.

---

## 🔐 Default Accounts

If you ran the database seeder (`php artisan db:seed`), the following default accounts are available for testing:

**Superadmin:**
*   **Email:** `superadmin@spotattend.com`
*   **Password:** `password`

**HR Admin:**
*   **Email:** `admin@spotattend.com`
*   **Password:** `password`

**Employee (Sample):**
*   **Email:** `karyawan@spotattend.com`
*   **Password:** `password`

> **Note:** The seeder may generate additional random employee accounts using Faker. You can find their credentials in your local database.

---

## 🧑‍💻 Usage Flow

1. **Initial Setup:** Log in as **Superadmin** if you need to create additional HR Admin accounts.
2. **HR Operations:** Log in as **HR Admin** (`admin@spotattend.com`) to set up Office Locations, Departments, and register new Employees. *Note: Employees cannot register themselves.*
3. **Employee Daily Use:** Employees log in using the credentials provided by HR to mark their daily attendance (Check-in/Check-out) and request leaves.

