# leavesysdbN  
**Cyber-tech Solution's First Project**  

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About leavesysdbN  
leavesysdbN is a **leave request management system** built using Laravel. It provides a structured workflow for handling employee leave requests efficiently while integrating **role-based access control** and **secure authentication**.  

### **Key Features**  
- 🔹 **User-friendly leave request interface**  
- 🔹 **Role-based access control for admins & supervisors**  
- 🔹 **Automated leave status tracking**  
- 🔹 **Secure authentication with Laravel’s built-in security features**  
- 🔹 **Data validation & structured database integration**  

## **Built With**  
- 🌐 Laravel  
- 🛠️ MySQL / PostgreSQL  
- 🎨 Tailwind CSS (for UI design)  
- 🔄 GitHub (for version control)  

## **Installation & Setup**  

Follow these steps to set up the project locally:

### **1. Prerequisites**
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or PostgreSQL

### **2. Clone the Repository**  
```bash
git clone https://github.com/Deya200/leavesysdbN.git
cd leavesysdbN
```

### **3. Install Dependencies**
```bash
composer install
npm install
```

### **4. Environment Configuration**
Copy the example environment file and configure your database:
```bash
cp .env.example .env
```
Edit `.env` and set your database credentials:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leavesysdb
DB_USERNAME=root
DB_PASSWORD=
```

### **5. Generate App Key**
```bash
php artisan key:generate
```

### **6. Run Migrations & Seeders**
This will create the database tables and populate them with default test data (Roles, Leave Types, default Admins).
```bash
php artisan migrate --seed
```

### **7. Frontend Build**
```bash
npm run build
```

### **8. Serve the Application**
```bash
php artisan serve
```
Visit http://127.0.0.1:8000 in your browser.

---

## **Default Login Credentials**

Use these accounts to test different roles:

**Administrator**
- **Email**: `lumalizani@gmail.com`
- **Password**: `Airtel@2063`

**Test Admin**
- **Email**: `test.admin@example.com`
- **Password**: `password123`

**Test Supervisor**
- **Email**: `test.supervisor@example.com`
- **Password**: `password123`

**Test Employee**
- **Email**: `test.employee@example.com`
- **Password**: `password123`

