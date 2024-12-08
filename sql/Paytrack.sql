-- Create Database
CREATE DATABASE PayTrack;
USE PayTrack;

-- Employee Table
CREATE TABLE Employee (
    EmployeeID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    EmployeeType VARCHAR(10),  
    Department VARCHAR(50),
    Position VARCHAR(50),
    HireDate DATE,
    Salary DECIMAL(10, 2),     
    HourlyRate DECIMAL(10, 2), 
    TaxRate DECIMAL(5, 2)      
);

-- Attendance Table
CREATE TABLE Attendance (
    AttendanceID INT PRIMARY KEY AUTO_INCREMENT,
    EmployeeID INT,
    Date DATE,
    HoursWorked DECIMAL(5, 2),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

-- Leave Table
CREATE TABLE EmployeeLeave (
    LeaveID INT PRIMARY KEY AUTO_INCREMENT,
    EmployeeID INT,
    LeaveDate DATE,
    LeaveType VARCHAR(50),     
    Duration DECIMAL(5, 2),   
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

-- Payroll Table
CREATE TABLE Payroll (
    PayrollID INT PRIMARY KEY AUTO_INCREMENT,
    EmployeeID INT,
    PayPeriodStart DATE,
    PayPeriodEnd DATE,
    BaseSalary DECIMAL(10, 2), 
    HoursWorked DECIMAL(5, 2), 
    OvertimePay DECIMAL(10, 2),
    Bonus DECIMAL(10, 2),
    Deductions DECIMAL(10, 2),
    TaxRate DECIMAL(5, 2),    
    PensionContribution DECIMAL(10, 2),
    BenefitsContribution DECIMAL(10, 2),
    NetPay DECIMAL(10, 2),     
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

-- Payroll Audit Table
CREATE TABLE PayrollAudit (
    AuditID INT PRIMARY KEY AUTO_INCREMENT,
    PayrollID INT,
    ActionTaken VARCHAR(50),   -- 'Created', 'Updated', 'Deleted'
    ActionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PayrollID) REFERENCES Payroll(PayrollID)
);

-- Add a column for termination reason (optional, but recommended)
ALTER TABLE Employee
ADD COLUMN TerminationReason VARCHAR(255) DEFAULT NULL;

-- Add a column for employee status (active or inactive)
ALTER TABLE Employee
ADD COLUMN Status ENUM('Active', 'Inactive') DEFAULT 'Active';

-- Update the Employee table to include salary type (fixed or hourly)
ALTER TABLE Employee
ADD COLUMN salary_type VARCHAR(50) NOT NULL DEFAULT 'fixed',
ADD COLUMN hourly_rate DECIMAL(10, 2) DEFAULT NULL;

-- Create Payroll table to store payroll calculations for each employee
CREATE TABLE Payroll (
    payroll_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    month VARCHAR(20) NOT NULL,
    year INT NOT NULL,
    gross_salary DECIMAL(10, 2) NOT NULL,
    bonus DECIMAL(10, 2) DEFAULT 0.00,
    deductions DECIMAL(10, 2) DEFAULT 0.00,
    net_pay DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id)
);