# OBU Student Registration System - Setup Guide

This guide will help you set up and run the Student Registration System locally.

## Prerequisites
1.  **XAMPP** or **WAMP** installed on your computer.
2.  A web browser (Chrome, Edge, Firefox, etc.).

## Step 1: Database Setup
1.  Open your browser and go to `http://localhost/phpmyadmin`.
2.  Create a new database named `obu_registration`.
3.  Click on the `obu_registration` database in the left sidebar.
4.  Click on the **Import** tab.
5.  Click **Choose File** and select the `database.sql` file from this project folder.
6.  Click **Go** (usually at the bottom) to import the tables.

## Step 2: Configure Project
1.  Copy this entire project folder into your server's web root:
    - For XAMPP: `C:\xampp\htdocs\student-registration-system`
    - For WAMP: `C:\wamp64\www\student-registration-system`
2.  Open `db.php` and verify the database credentials:
    - **Host**: `localhost`
    - **Username**: `root`
    - **Password**: (usually empty `''` for XAMPP)
    - **Database**: `obu_registration`

## Step 3: Run the System
1.  Ensure Apache and MySQL modules are **Running** in your XAMPP/WAMP Control Panel.
2.  Open your browser and navigate to:
    `http://localhost/student-registration-system/`

---

## How to Access the Admin Page
The Admin page allows you to view and manage all registered students.

1.  Go to the **Login** page (`login.php`).
2.  Enter the default Admin credentials:
    - **Username**: `admin`
    - **Password**: `admin123`
3.  Click **Login Now**.
4.  Once logged in, you will be on the Admin Dashboard.
5.  Click **"View Registration Database"** or **"Students"** in the navigation menu to see student details.

---

## Default User Roles
- **Admin**: `admin` / `admin123` (Full control, manage registrars, view reports)
- **Registrar**: Created by Admin (Can approve/reject students)
- **Student**: Created via Registration (Application pending until Registrar approval)
