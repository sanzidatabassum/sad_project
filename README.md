# Pharmacy Management System

Simple web-based pharmacy management system built with PHP. Provides CRUD for medicines, customers, suppliers, sales, prescriptions and user management.

## Prerequisites
- XAMPP (Apache + MySQL) or equivalent PHP + MySQL stack
- PHP 7.4+ (compatible)

## Installation
1. Copy project to your web root (e.g. C:\xampp\htdocs\pharmacy or /Applications/XAMPP/xamppfiles/htdocs/pharmacy).
2. Start Apache and MySQL.
3. Create a database (e.g. `pharmacy_db`) and import any provided SQL dump (if available).
4. Update DB settings in `config/db.php`.
5. Open the app in your browser: http://localhost/pharmacy/

## Default Login
- Email: admin@pharmacy.com  
- Password: password

## Project Structure (top-level)
- index.php, dashboard.php, logout.php  
- config/db.php  
- medicines/, customers/, suppliers/, sales/, prescriptions/, reports/, users/

## Demo
Place a demo image at `assets/demo.png` (or `images/demo.png`) and include it in this README:
![Project Demo](assets/demo.png)

## Notes
- Update credentials and DB configuration before production use.
- This repository is intended for local/demo use.