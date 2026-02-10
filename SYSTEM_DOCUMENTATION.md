# Leave Management System Documentation

## 🌟 Overview
The Leave Management System (leavesysdbN) is a robust Laravel-based application designed to streamline the process of applying for, approving, and managing employee leaves. It features a tiered approval workflow, automated balance tracking, and real-time notifications.

---

## 👥 User Roles & Permissions

### 1. Employee
*   **Apply for Leave**: Submit requests for various leave types (Annual, Sick, Maternity, etc.).
*   **Dashboard**: View remaining leave days and request status.
*   **Manage Active Leaves**: Appeal rejected requests, request extensions on active leaves, or cancel upcoming leaves.
*   **Profile**: View and manage personal profile details.

### 2. Supervisor
*   **First-Level Approval**: Review leave requests from direct reports.
*   **Notes**: Add approval notes or must-provide rejection reasons.
*   **Monitoring**: See a dashboard of pending requests in their department.

### 3. Administrator (HR)
*   **Final Verification**: Provide the final stamp of approval on requests already cleared by supervisors.
*   **System Configuration**: Manage Leave Types, Departments, Positions, and Grades.
*   **User Management**: Create and manage employee accounts and assign supervisors.
*   **Reporting**: Generate summary reports and export them as PDF.

---

## 🔄 The Leave Lifecycle

### 1. Application
An employee submits a leave request. The system automatically checks their remaining balance and prevents submission if they exceed their limit.

### 2. Supervisor Review
The supervisor receives a notification and sees the request on their dashboard. They can:
*   **Approve**: Request moves to "Pending Admin Verification".
*   **Reject**: Request ends here, and the employee is notified (Must provide a reason).

### 3. Admin Verification
The Administrator reviews requests that have supervisor approval. 
*   **Final Approval**: The leave balance is deducted, and the request is marked as "Approved".
*   **Final Rejection**: The request is marked as "Rejected by Admin".

---

## 🛠️ Key Features

### 📅 Automated Balance Tracking
When a leave is approved (final step), the system automatically deducts the `TotalDays` from the employee's `RemainingAnnualLeaveDays`.

### 🔔 Notification System
Users receive in-app notifications for:
*   New leave requests (for supervisors).
*   Final status updates (for employees).
*   Appeals and extension requests.

### ⚖️ Post-Approval Actions
*   **Appeals**: Employees can appeal a rejection within a set period.
*   **Extensions**: If an employee needs more time while on leave, they can request an extension.
*   **Cancellations**: Employees can request to cancel a leave before it ends.

### 📊 PDF Reporting
Administrators can generate a "Leave Summary Report" in PDF format, which highlights current pending and approved requests.

---

## � Request Statuses & Possible Responses

| Status | Meaning | Possible Next Actions |
| :--- | :--- | :--- |
| **Pending Supervisor Approval** | Request is submitted and waiting for the supervisor to review. | Approve (to Admin) or Reject |
| **Pending Admin Verification** | Supervisor approved; waiting for HR/Admin for final check. | Final Approve or Final Reject |
| **Approved** | Final approval granted. Balance is deducted. Leave is valid. | Extend or Cancel (Post-Approval) |
| **Rejected** | Supervisor denied the request. | Appeal (within deadline) |
| **Rejected by Admin** | Admin denied the request after supervisor approval. | Appeal (within deadline) |
| **Appealed** | Employee has submitted an appeal against a rejection. | Re-review by Supervisor/Admin |
| **Cancelled** | Employee requested cancellation and it was approved. | None (History only) |

---

## 💬 Visibility of Comments
Employees can **always see the reasons and notes** provided by their superiors:
*   **Rejection Reasons**: Mandatory for both Supervisors and Admins. Visible on the Employee Dashboard.
*   **Approval Notes**: Optional notes added during approval. Both Supervisor and Admin notes are visible once the action is taken.

---

## �💻 Technical Architecture

*   **Framework**: Laravel 12.x
*   **Database**: PostgreSQL / MySQL
*   **Frontend**: Blade Templates, Bootstrap 5, Vanilla CSS
*   **Key Modules**:
    *   `LeaveRequestController`: Handles the core leave logic.
    *   `AppServiceProvider`: Manages global data sharing (sidebar badges).
    *   `Notification`: Handles real-time alerts.

---

## 🚀 Setup & Installation
1.  **Clone**: `git clone [repository-url]`
2.  **Install**: `composer install` & `npm install`
3.  **Configure**: Copy `.env.example` to `.env` and set database credentials.
4.  **Migrate**: `php artisan migrate --seed`
5.  **Serve**: `php artisan serve`

---
*Created by Cyber-tech Solutions*
