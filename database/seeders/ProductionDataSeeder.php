<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Supervisor'],
            ['id' => 3, 'name' => 'Employee'],
        ];
        DB::table('roles')->delete();
        DB::table('roles')->insert($roles);

        // 2. Grades
        $grades = [
            ['GradeID' => 1, 'GradeName' => 'Grade I', 'AnnualLeaveDays' => 30],
            ['GradeID' => 2, 'GradeName' => 'Grade J', 'AnnualLeaveDays' => 24],
            ['GradeID' => 3, 'GradeName' => 'Grade K', 'AnnualLeaveDays' => 24],
            ['GradeID' => 4, 'GradeName' => 'Grade H', 'AnnualLeaveDays' => 30],
            ['GradeID' => 5, 'GradeName' => 'Grade G', 'AnnualLeaveDays' => 30],
            ['GradeID' => 6, 'GradeName' => 'Grade N', 'AnnualLeaveDays' => 21],
            ['GradeID' => 7, 'GradeName' => 'Grade M', 'AnnualLeaveDays' => 24],
            ['GradeID' => 8, 'GradeName' => 'Grade L', 'AnnualLeaveDays' => 24],
            ['GradeID' => 9, 'GradeName' => 'Grade O', 'AnnualLeaveDays' => 21],
            ['GradeID' => 10, 'GradeName' => 'Grade P', 'AnnualLeaveDays' => 21],
            ['GradeID' => 11, 'GradeName' => 'Grade Q', 'AnnualLeaveDays' => 15],
            ['GradeID' => 12, 'GradeName' => 'Grade R', 'AnnualLeaveDays' => 15],
            ['GradeID' => 13, 'GradeName' => 'Grade T', 'AnnualLeaveDays' => 20],
        ];
        DB::table('grades')->delete();
        DB::table('grades')->insert($grades);

        // 3. Departments
        $departments = [
            ['DepartmentID' => 1, 'DepartmentName' => 'Administration'],
            ['DepartmentID' => 2, 'DepartmentName' => 'Customer Care'],
            ['DepartmentID' => 3, 'DepartmentName' => 'Clinical'],
            ['DepartmentID' => 4, 'DepartmentName' => 'Laboratory'],
            ['DepartmentID' => 5, 'DepartmentName' => 'Pharmacy'],
            ['DepartmentID' => 6, 'DepartmentName' => 'Radiology'],
            ['DepartmentID' => 7, 'DepartmentName' => 'Nursing'],
            ['DepartmentID' => 9, 'DepartmentName' => 'Admin and Finance'],
        ];
        DB::table('departments')->delete();
        foreach ($departments as $dept) {
            DB::table('departments')->insert($dept);
        }

        // 4. Leave Types
        $leaveTypes = [
            ['LeaveTypeID' => 1, 'LeaveTypeName' => 'Annual Leave', 'IsPaidLeave' => 1, 'GenderApplicable' => 'Both', 'MaxLeaveDays' => 18, 'MinServiceYears' => 0, 'DeductsFromAnnual' => 0],
            ['LeaveTypeID' => 2, 'LeaveTypeName' => 'Sick Leave', 'IsPaidLeave' => 1, 'GenderApplicable' => 'Both', 'MaxLeaveDays' => 60, 'MinServiceYears' => 1, 'DeductsFromAnnual' => 0],
            ['LeaveTypeID' => 3, 'LeaveTypeName' => 'Maternity Leave', 'IsPaidLeave' => 1, 'GenderApplicable' => 'Female', 'MaxLeaveDays' => 56, 'MinServiceYears' => 0, 'DeductsFromAnnual' => 0],
            ['LeaveTypeID' => 4, 'LeaveTypeName' => 'Paternity Leave', 'IsPaidLeave' => 1, 'GenderApplicable' => 'Male', 'MaxLeaveDays' => 14, 'MinServiceYears' => 1, 'DeductsFromAnnual' => 0],
        ];
        DB::table('leave_types')->delete();
        DB::table('leave_types')->insert($leaveTypes);

        // 5. Positions
        $positions = [
            ['PositionID' => 1, 'PositionName' => 'Hospital Administrative Director', 'GradeID' => 5, 'DepartmentID' => 1],
            ['PositionID' => 2, 'PositionName' => 'Human Resource Manager', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 3, 'PositionName' => 'Head of Accounts', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 4, 'PositionName' => 'Chaplain', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 5, 'PositionName' => 'Procurement Officer', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 6, 'PositionName' => 'Senior Administrative Assistant', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 7, 'PositionName' => 'Administrative Assistant', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 8, 'PositionName' => 'Executive Assistant to HD', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 9, 'PositionName' => 'Billing Coordiantor', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 10, 'PositionName' => 'Data Preparatiion Clerk', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 11, 'PositionName' => 'Head Chef', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 12, 'PositionName' => 'Chef', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 13, 'PositionName' => 'Driver', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 14, 'PositionName' => 'Senior Security Guard', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 15, 'PositionName' => 'Security Guard', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 16, 'PositionName' => 'Ground worker', 'GradeID' => 1, 'DepartmentID' => 1],
            ['PositionID' => 17, 'PositionName' => 'Head of Client Care', 'GradeID' => 1, 'DepartmentID' => 2],
            ['PositionID' => 18, 'PositionName' => 'MASM Billing-Assistant Coordinator', 'GradeID' => 1, 'DepartmentID' => 2],
            ['PositionID' => 19, 'PositionName' => 'Cashier/Front Desktop Personnel', 'GradeID' => 1, 'DepartmentID' => 2],
            ['PositionID' => 20, 'PositionName' => 'MASM Coding and Records Clerk', 'GradeID' => 1, 'DepartmentID' => 2],
            ['PositionID' => 21, 'PositionName' => 'Head of Clinical Services', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 22, 'PositionName' => 'Medical Officer', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 23, 'PositionName' => 'Dentist', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 24, 'PositionName' => 'Senior Ophthalmic Clincal Therapist', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 25, 'PositionName' => 'Senior Anaesthetic Clinical Officer', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 26, 'PositionName' => 'Clinic Orthopedic Technician', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 27, 'PositionName' => 'Optometry Technician', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 28, 'PositionName' => 'Account Assistant', 'GradeID' => 8, 'DepartmentID' => 1],
            ['PositionID' => 29, 'PositionName' => 'Clinical Officer', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 30, 'PositionName' => 'Senior Nursing Officer', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 31, 'PositionName' => 'Optometry Technician', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 32, 'PositionName' => 'Optical-Frame Technician', 'GradeID' => 1, 'DepartmentID' => 3],
            ['PositionID' => 34, 'PositionName' => 'Senior Lab Technician', 'GradeID' => 1, 'DepartmentID' => 4],
            ['PositionID' => 35, 'PositionName' => 'Lab Technician', 'GradeID' => 1, 'DepartmentID' => 4],
            ['PositionID' => 36, 'PositionName' => 'Laboratory Assistant', 'GradeID' => 1, 'DepartmentID' => 4],
            ['PositionID' => 37, 'PositionName' => 'Radiography Technologist', 'GradeID' => 1, 'DepartmentID' => 6],
            ['PositionID' => 38, 'PositionName' => 'Nursing Officer', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 39, 'PositionName' => 'Senior Nursing Sister', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 40, 'PositionName' => 'Registered Nurse Midwife', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 41, 'PositionName' => 'Nursing Midwife Technician', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 42, 'PositionName' => 'Orthopedic Assistant-POP', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 43, 'PositionName' => 'Hospital Attendant', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 44, 'PositionName' => 'Theatre and Packs Supervisor', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 45, 'PositionName' => 'Senior Hospital Attendant', 'GradeID' => 1, 'DepartmentID' => 7],
            ['PositionID' => 46, 'PositionName' => 'Pharmacy Assistant', 'GradeID' => 1, 'DepartmentID' => 5],
            ['PositionID' => 47, 'PositionName' => 'Chief Radiographer', 'GradeID' => 1, 'DepartmentID' => 6],
            ['PositionID' => 48, 'PositionName' => 'Nursing Superintendent', 'GradeID' => 9, 'DepartmentID' => 7],
            ['PositionID' => 49, 'PositionName' => 'Pharmacy Technician', 'GradeID' => 5, 'DepartmentID' => 5],
            ['PositionID' => 50, 'PositionName' => 'Accounts', 'GradeID' => 1, 'DepartmentID' => 9],
        ];
        DB::table('positions')->delete();
        DB::table('positions')->insert($positions);

        // 6. Employees
        $employeesData = [
            ['EMP-01ABC', 'Mwerapusa', 'Mawindo', 'Female', '1997-02-23', 1, 5, 1, 'EMP-03ABC', 3, 0],
            ['EMP-02ABC', 'Doreen', 'Banda', 'Female', '1967-02-22', 1, 4, 2, 'EMP-03ABC', 3, 0],
            ['EMP-03ABC', 'Dorothy', 'Msiska Nyasulu', 'Female', '1967-11-04', 1, 4, 3, 'EMP-03ABC', 3, 0],
            ['EMP-04ABC', 'Jonathan', 'Tembo', 'Male', '2000-02-08', 1, 1, 4, 'EMP-03ABC', 3, 0],
            ['EMP-05ABC', 'Ginger', 'Mtonga', 'Male', '1970-02-04', 1, 1, 5, 'EMP-03ABC', 3, 0],
            ['EMP-06ABC', 'Steve', 'Kumwenda', 'Male', '1990-02-04', 1, 1, 6, 'EMP-03ABC', 3, 0],
            ['EMP-07ABC', 'Lilly', 'Hara', 'Female', '1980-02-04', 1, 1, 7, 'EMP-03ABC', 3, 0],
            ['EMP-08ABC', 'Mercy', 'Mbundungu', 'Female', '1986-02-14', 1, 1, 8, 'EMP-03ABC', 3, 0],
            ['EMP-09ABC', 'Ulemu', 'Chibophe', 'Female', '1990-09-01', 1, 1, 9, 'EMP-03ABC', 3, 0],
            ['EMP-100ABC', 'Ellina', 'Chigomira', 'Female', '1999-03-11', 7, 9, 48, 'EMP-100ABC', 3, 0],
            ['EMP-101ABC', 'Christina', 'Magomba', 'Female', '1999-03-27', 7, 9, 45, 'EMP-100ABC', 3, 0],
            ['EMP-102ABC', 'Lucius', 'Msuli', 'Male', '1999-03-27', 7, 9, 45, 'EMP-100ABC', 3, 0],
            ['EMP-103ABC', 'Margaret', 'Chikuwo', 'Male', '1999-10-10', 7, 9, 45, 'EMP-100ABC', 3, 0],
            ['EMP-104ABC', 'Rose', 'Scotch', 'Female', '1999-10-01', 7, 9, 45, 'EMP-100ABC', 3, 0],
            ['EMP-105ABC', 'Agness', 'Posiyana', 'Female', '1999-10-20', 7, 10, 45, 'EMP-100ABC', 3, 0],
            ['EMP-106ABC', 'Aishling', 'Kachenga', 'Female', '1999-12-22', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-107ABC', 'Alepha', 'Makawa', 'Female', '1999-02-11', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-108ABC', 'Chimwemwe', 'Majawa', 'Female', '1999-09-29', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-109ABC', 'Daniel', 'Moris', 'Male', '1999-08-31', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-10ABC', 'Chrispin', 'Phiri', 'Male', '1980-10-06', 1, 8, 28, 'EMP-03ABC', 3, 0],
            ['EMP-110ABC', 'Esther', 'Banda Posiano', 'Male', '1999-08-30', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-111ABC', 'Esther', 'Chimoto', 'Male', '1999-02-24', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-112ABC', 'Evelyn', 'Alibaba', 'Female', '1999-10-18', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-113ABC', 'Fausta', 'Phiri', 'Female', '1999-06-06', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-114ABC', 'Khumbo', 'Nyasulu', 'Male', '1999-12-27', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-115ABC', 'Lissy', 'Allan', 'Female', '1999-08-02', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-116ABC', 'Loveness', 'Diness', 'Female', '1999-10-01', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-117ABC', 'Luwayo-Mwale', 'Alinafe', 'Female', '1999-10-01', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-118ABC', 'Maggie', 'Chimsinde', 'Female', '1999-10-22', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-119ABC', 'Maxon', 'Songa', 'Male', '1999-09-11', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-11ABC', 'Alick', 'Gristone', 'Male', '1999-08-15', 1, 7, 10, 'EMP-03ABC', 3, 0],
            ['EMP-120ABC', 'Mumderannji', 'Dzowa', 'Female', '1999-08-05', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-121ABC', 'Oliver', 'Chimtolo', 'Female', '1999-12-22', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-122ABC', 'Patricia', 'Nampinga', 'Female', '1999-04-02', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-123ABC', 'Patricia', 'Nyirenda', 'Female', '1999-11-04', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-124ABC', 'Tamara', 'Mapando', 'Female', '1999-04-27', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-125ABC', 'Zelesi', 'Saka', 'Female', '1999-02-05', 7, 10, 43, 'EMP-100ABC', 3, 0],
            ['EMP-126ABC', 'Masambiro', 'Mkandawire', 'Male', '1999-02-05', 5, 3, 46, 'EMP-126ABC', 3, 0],
            ['EMP-127ABC', 'Christopher', 'Zimba', 'Male', '1999-09-25', 5, 3, 49, 'EMP-126ABC', 3, 0],
            ['EMP-128ABC', 'Ian', 'Nyondo', 'Male', '1999-09-14', 5, 3, 49, 'EMP-126ABC', 3, 0],
            ['EMP-129ABC', 'Prince', 'Binton', 'Male', '1999-09-14', 5, 3, 49, 'EMP-126ABC', 3, 0],
            ['EMP-12ABC', 'Alick', 'Pachalo', 'Male', '1999-08-22', 1, 7, 10, 'EMP-03ABC', 3, 0],
            ['EMP-130ABC', 'Tiyamike', 'Kamkhwani', 'Female', '1999-02-13', 5, 3, 49, 'EMP-126ABC', 3, 0],
            ['EMP-131ABC', 'Precious', 'Magomero', 'Male', '1999-07-05', 5, 3, 46, 'EMP-126ABC', 3, 0],
            ['EMP-13ABC', 'Evance', "Mang'omba", 'Male', '1980-12-04', 1, 7, 10, 'EMP-03ABC', 3, 0],
            ['EMP-14ABC', 'Limbani', 'Katambo', 'Female', '1990-05-24', 1, 7, 10, 'EMP-03ABC', 3, 0],
            ['EMP-15ABC', 'Lawrence', 'Nankhulumwa', 'Male', '1990-09-05', 1, 6, 13, 'EMP-03ABC', 3, 0],
            ['EMP-16ABC', 'Severina', 'Kanyumbu', 'Female', '1980-03-03', 1, 7, 10, 'EMP-03ABC', 3, 0],
            ['EMP-17ABC', 'Samuel', 'Kazuma', 'Male', '1980-06-23', 1, 9, 11, 'EMP-03ABC', 3, 0],
            ['EMP-18ABC', 'Elvin', 'Phiri', 'Male', '1976-04-01', 1, 10, 12, 'EMP-03ABC', 3, 0],
            ['EMP-19ABC', 'Laiter', 'Mandimu', 'Male', '1970-03-03', 1, 6, 13, 'EMP-03ABC', 3, 0],
            ['EMP-20ABC', 'Richard', 'Mdzeka', 'Male', '1979-08-25', 1, 9, 14, 'EMP-03ABC', 3, 0],
            ['EMP-21ABC', 'Chikondi', 'Mwalejames', 'Female', '2000-12-19', 1, 10, 15, 'EMP-03ABC', 3, 0],
            ['EMP-22ABC', 'Hope', 'Mwale', 'Male', '1980-08-03', 1, 10, 15, 'EMP-03ABC', 3, 0],
            ['EMP-23ABC', 'Isaac', 'Banda', 'Male', '1999-03-23', 1, 10, 15, 'EMP-03ABC', 3, 0],
            ['EMP-24ABC', 'Milton', 'Chiona', 'Male', '1990-04-04', 1, 10, 15, 'EMP-03ABC', 3, 0],
            ['EMP-25ABC', 'Davies', 'Balakasi', 'Male', '1981-06-05', 1, 11, 16, 'EMP-03ABC', 3, 0],
            ['EMP-26ABC', 'Hardwell', 'Kaliveni', 'Male', '1990-07-14', 1, 12, 16, 'EMP-03ABC', 3, 0],
            ['EMP-27ABC', 'Kondwani', 'Kalako', 'Male', '1999-02-14', 1, 12, 16, 'EMP-03ABC', 3, 0],
            ['EMP-28ABC', 'Lorenti', 'Fanuwell', 'Male', '1990-02-02', 1, 12, 16, 'EMP-03ABC', 3, 0],
            ['EMP-29ABC', 'Peter', 'Mwale', 'Male', '1980-10-10', 1, 12, 16, 'EMP-03ABC', 3, 0],
            ['EMP-30ABC', 'Esther', 'Nkhoma', 'Female', '1970-07-18', 2, 1, 17, 'EMP-30ABC', 2, 30],
            ['EMP-31ABC', 'Alinafe', 'Mchenga', 'Female', '1998-10-04', 2, 1, 18, 'EMP-30ABC', 3, 30],
            ['EMP-32ABC', 'Alinafe', 'Mchenga', 'Female', '1998-10-04', 2, 1, 18, 'EMP-30ABC', 3, 30],
            ['EMP-33ABC', 'Esther', 'Bezalamu', 'Female', '1870-02-23', 2, 1, 19, 'EMP-30ABC', 3, 30],
            ['EMP-34ABC', 'Evelyn', 'Maluwa', 'Female', '1999-12-21', 2, 1, 19, 'EMP-30ABC', 3, 30],
            ['EMP-35ABC', 'Maggie', 'Ngwira', 'Female', '1980-03-04', 2, 1, 19, 'EMP-30ABC', 3, 30],
            ['EMP-36ABC', 'Sibongire', 'Chauluka', 'Female', '1976-11-02', 2, 1, 19, 'EMP-30ABC', 3, 30],
            ['EMP-37ABC', 'Aiiiida', 'Baluwa', 'Female', '1998-03-21', 2, 3, 19, 'EMP-30ABC', 2, 30],
            ['EMP-38ABC', 'Zonse', 'Nyemba', 'Female', '1998-12-19', 2, 1, 19, 'EMP-30ABC', 3, 30],
            ['EMP-39ABC', 'Cardi', 'Albert', 'Male', '1999-04-18', 2, 8, 20, 'EMP-30ABC', 3, 30],
            ['EMP-40ABC', 'Caroline', 'Kufeyani', 'Female', '1999-04-08', 2, 7, 19, 'EMP-30ABC', 3, 30],
            ['EMP-41ABC', 'Fanny', 'Kaiwalika', 'Female', '1999-02-17', 2, 7, 19, 'EMP-30ABC', 3, 30],
            ['EMP-42ABC', 'Lennie', 'Sumana', 'Male', '1990-04-23', 2, 7, 19, 'EMP-30ABC', 3, 30],
            ['EMP-43ABC', 'Precious', 'Malemia', 'Male', '1972-06-15', 3, 4, 21, 'EMP-43ABC', 2, 30],
            ['EMP-444ABC', 'Uchi', 'Kachale', 'Female', '2005-03-04', 1, 1, 4, null, 1, 30],
            ['EMP-44ABC', 'Innocent', 'Nkungula', 'Male', '1990-11-12', 3, 4, 22, 'EMP-43ABC', 3, 30],
            ['EMP-45ABC', 'Mercy', 'Nasoro', 'Female', '1978-09-18', 3, 4, 22, 'EMP-43ABC', 3, 30],
            ['EMP-46ABC', 'Daniel', 'Frank Chimutu', 'Male', '1969-08-04', 3, 4, 22, 'EMP-43ABC', 3, 30],
            ['EMP-47ABC', 'Francisco', 'Mlenga', 'Male', '1998-05-24', 3, 4, 22, 'EMP-43ABC', 3, 30],
            ['EMP-48ABC', 'Alfred', 'Kapyepye', 'Male', '1982-02-12', 3, 1, 23, 'EMP-43ABC', 3, 30],
            ['EMP-49ABC', 'Bettie', 'Salamu', 'Female', '1990-03-07', 3, 1, 25, 'EMP-43ABC', 3, 30],
            ['EMP-50ABC', 'Vinicent', 'Cheyo', 'Male', '1987-11-04', 3, 2, 26, 'EMP-43ABC', 3, 30],
            ['EMP-51ABC', 'Alice', 'Nyasulu', 'Female', '1997-09-25', 3, 2, 26, 'EMP-43ABC', 3, 30],
            ['EMP-52ABC', 'Angella', 'Mpakata', 'Female', '1976-05-16', 3, 3, 22, 'EMP-43ABC', 3, 30],
            ['EMP-53ABC', 'Danfoard', 'Matayo', 'Male', '1987-02-05', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-54ABC', 'Hendrix', 'Katsekera', 'Male', '1987-11-28', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-55ABC', 'Mclean', 'Kapenda', 'Male', '1997-06-15', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-56ABC', 'Patience', 'Dziyende', 'Male', '1997-04-14', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-57ABC', 'Tauze', 'Mtenga', 'Female', '1997-03-10', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-58ABC', 'Mathews', 'Chabwa', 'Male', '1978-10-02', 3, 3, 29, 'EMP-43ABC', 3, 30],
            ['EMP-59ABC', 'Chisomo', 'Mhango', 'Male', '1978-03-03', 3, 3, 27, 'EMP-43ABC', 3, 30],
            ['EMP-60ABC', 'Maxwell', 'Nkhoma', 'Male', '1978-03-03', 3, 3, 32, 'EMP-43ABC', 3, 30],
            ['EMP-61ABC', 'James', 'Chauluka', 'Male', '1978-03-03', 4, 1, 34, 'EMP-61ABC', 2, 30],
            ['EMP-62ABC', 'Mwayiwawo', 'Somanje', 'Female', '1978-03-03', 4, 2, 34, 'EMP-61ABC', 3, 30],
            ['EMP-63ABC', 'Bright', 'Kachiwala', 'Male', '1978-06-11', 4, 3, 35, 'EMP-61ABC', 3, 30],
            ['EMP-64ABC', 'Memory', 'Mkandawire', 'Female', '1978-06-11', 4, 3, 35, 'EMP-61ABC', 3, 30],
            ['EMP-65ABC', 'Hilda', 'Motokala', 'Female', '1978-05-30', 4, 8, 36, 'EMP-61ABC', 3, 30],
            ['EMP-66ABC', 'Jack', 'Chaluma', 'Male', '1978-02-03', 4, 8, 36, 'EMP-61ABC', 3, 30],
            ['EMP-67ABC', 'Trintas', 'Kaunda', 'Female', '1997-05-09', 4, 8, 36, 'EMP-61ABC', 3, 30],
            ['EMP-68ABC', 'Wickson', 'Magunda', 'Male', '1993-11-01', 6, 1, 37, 'EMP-68ABC', 2, 30],
            ['EMP-69ABC', 'Victoria', 'Kapenda', 'Female', '1993-09-26', 7, 1, 38, 'EMP-100ABC', 3, 30],
            ['EMP-70ABC', 'Rabecca', 'Bvumbwe', 'Female', '1993-04-11', 7, 1, 30, 'EMP-100ABC', 3, 30],
            ['EMP-71ABC', 'Evelyn', 'Chitsokwe Msonkho', 'Female', '1993-06-18', 7, 2, 40, 'EMP-100ABC', 3, 30],
            ['EMP-72ABC', 'Naseem', 'Gill', 'Female', '1993-09-16', 7, 2, 40, 'EMP-100ABC', 3, 30],
            ['EMP-73ABC', 'Alinafe', 'Phiri', 'Female', '1990-06-15', 7, 2, 40, 'EMP-100ABC', 3, 30],
            ['EMP-74ABC', 'Agathasssss', 'Chisale', 'Female', '2000-06-05', 4, 2, 35, null, 2, 30],
            ['EMP-75ABC', 'Aggie', 'Mboma', 'Female', '1999-03-07', 7, 3, 41, 'EMP-100ABC', 2, 30],
            ['EMP-76ABC', 'Alex', 'Libanga', 'Female', '1999-11-24', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-77ABC', 'Alinafe', 'Kefa', 'Female', '1999-12-28', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-78ABC', 'Berthia', 'Chapweteka', 'Female', '1999-12-28', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-79ABC', 'Carolie', 'Chipata', 'Female', '1999-12-28', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-80ABC', 'Chikondi', 'Banda', 'Female', '1999-12-28', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-81ABC', 'Christopher', 'Bicycle', 'Male', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-82ABC', 'Effie', 'Majawa', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-83ABC', 'Eliza', 'Suwedi', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-84ABC', 'Hawa', 'Deula Banda', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-85ABC', 'Jestina', 'Mghandila', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-86ABC', 'Jonathan', 'Brian Teleka', 'Male', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-87ABC', 'Levison', 'Mkandawire', 'Male', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-88ABC', 'Lucy', 'Gama', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-89ABC', 'Madress', 'Msowoya', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-90ABC', 'Mwai', 'Mundoli Kunyokwa', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-91ABC', 'Rosa', 'Msadala', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-92ABC', 'Sakina', 'Mdala', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-93ABC', 'Tiyanjane', 'Banda', 'Female', '1979-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-94ABC', 'Yollanda', 'Thinda Mtisunge', 'Female', '1980-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-95ABC', 'Judith', 'Chigalu', 'Female', '1980-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-96ABC', 'Rose', 'Majawa', 'Female', '1980-12-31', 7, 3, 41, 'EMP-100ABC', 3, 30],
            ['EMP-97ABC', 'Eunice', 'Majawa', 'Female', '1980-12-31', 7, 3, 42, 'EMP-100ABC', 3, 30],
            ['EMP-98ABC', 'Jessie', 'Malove', 'Female', '1980-12-31', 7, 7, 43, 'EMP-100ABC', 3, 30],
            ['EMP-99ABC', 'Ireen', 'Kagalu', 'Female', '1980-12-31', 7, 6, 44, 'EMP-100ABC', 3, 30],
        ];

        DB::table('employees')->delete();
        foreach ($employeesData as $data) {
            DB::table('employees')->insert([
                'EmployeeNumber' => $data[0],
                'FirstName' => $data[1],
                'LastName' => $data[2],
                'Gender' => $data[3],
                'DateOfBirth' => $data[4],
                'DepartmentID' => $data[5],
                'GradeID' => $data[6],
                'PositionID' => $data[7],
                'SupervisorID' => null, // Defer supervisor to avoid circular FK issues
                'role_id' => $data[9],
                'RemainingAnnualLeaveDays' => $data[10],
                'password' => Hash::make('Airtel@2063'),
                'email' => strtolower($data[1]) . '.' . strtolower($data[2]) . '.' . strtolower(str_replace('EMP-', '', $data[0])) . '@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 7. Update Employee Supervisors
        foreach ($employeesData as $data) {
            if ($data[8]) {
                DB::table('employees')->where('EmployeeNumber', $data[0])->update(['SupervisorID' => $data[8]]);
            }
        }

        // 8. Update Departments with Supervisors
        $supervisorMappings = [
            1 => 'EMP-01ABC',
            2 => 'EMP-30ABC',
            3 => 'EMP-43ABC',
            4 => 'EMP-61ABC',
            5 => 'EMP-126ABC',
            6 => 'EMP-68ABC',
            7 => 'EMP-100ABC',
            9 => 'EMP-03ABC',
        ];

        foreach ($supervisorMappings as $deptId => $empNum) {
            DB::table('departments')->where('DepartmentID', $deptId)->update(['SupervisorID' => $empNum]);
        }
    }
}
