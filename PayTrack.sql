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
    ActionTaken VARCHAR(50),  
    ActionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PayrollID) REFERENCES Payroll(PayrollID)
);

-- Users Table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,      
    Username VARCHAR(255) NOT NULL UNIQUE,          
    Password VARCHAR(255) NOT NULL,                 
    Role ENUM('Admin', 'Employee') NOT NULL,      
    EmployeeID INT,                               
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE SET NULL 
    
);
