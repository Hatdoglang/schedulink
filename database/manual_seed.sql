-- Manual Database Seeding Script
-- Run this in your database to create test data for role-based redirects

-- 1. Insert Roles
INSERT INTO roles (name, created_at, updated_at) VALUES
('Admin', NOW(), NOW()),
('Manager', NOW(), NOW()),
('User', NOW(), NOW()),
('Driver', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 2. Insert Business Units
INSERT INTO business_units (name, created_at, updated_at) VALUES
('Information Technology', NOW(), NOW()),
('Human Resources', NOW(), NOW()),
('Finance & Accounting', NOW(), NOW()),
('Operations', NOW(), NOW()),
('Marketing & Sales', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 3. Insert Company Codes
INSERT INTO company_codes (name, created_at, updated_at) VALUES
('GMALL-HQ', NOW(), NOW()),
('GMALL-NORTH', NOW(), NOW()),
('GMALL-SOUTH', NOW(), NOW()),
('GMALL-CENTRAL', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 4. Insert Branches
INSERT INTO branches (name, created_at, updated_at) VALUES
('Head Office', NOW(), NOW()),
('Manila Branch', NOW(), NOW()),
('Quezon City Branch', NOW(), NOW()),
('Makati Branch', NOW(), NOW()),
('Cebu Branch', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 5. Insert Departments
INSERT INTO departments (name, created_at, updated_at) VALUES
('Executive Management', NOW(), NOW()),
('IT Development', NOW(), NOW()),
('IT Support', NOW(), NOW()),
('HR Management', NOW(), NOW()),
('Finance', NOW(), NOW()),
('Operations Management', NOW(), NOW()),
('Marketing', NOW(), NOW()),
('Customer Service', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 6. Insert Test Users
-- Get IDs for foreign keys (you may need to adjust these based on your actual IDs)
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'Admin' LIMIT 1);
SET @manager_role_id = (SELECT id FROM roles WHERE name = 'Manager' LIMIT 1);
SET @user_role_id = (SELECT id FROM roles WHERE name = 'User' LIMIT 1);
SET @driver_role_id = (SELECT id FROM roles WHERE name = 'Driver' LIMIT 1);

SET @business_unit_id = (SELECT id FROM business_units LIMIT 1);
SET @company_code_id = (SELECT id FROM company_codes LIMIT 1);
SET @branch_id = (SELECT id FROM branches LIMIT 1);
SET @department_id = (SELECT id FROM departments LIMIT 1);

-- Insert Admin User
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('John', 'Admin', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', @business_unit_id, @company_code_id, @branch_id, @department_id, @admin_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Insert Manager User
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('Jane', 'Manager', 'manager@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567891', @business_unit_id, @company_code_id, @branch_id, @department_id, @manager_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Insert Regular User
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('Bob', 'Requester', 'user@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567892', @business_unit_id, @company_code_id, @branch_id, @department_id, @user_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Insert Driver User
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('Mike', 'Driver', 'driver@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567893', @business_unit_id, @company_code_id, @branch_id, @department_id, @driver_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Insert additional test users
INSERT INTO users (first_name, last_name, email, password, mobile_number, business_unit_id, company_code_id, branch_id, department_id, role_id, is_active, created_at, updated_at) VALUES
('User', '1', 'user1@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567801', @business_unit_id, @company_code_id, @branch_id, @department_id, @user_role_id, 1, NOW(), NOW()),
('User', '2', 'user2@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567802', @business_unit_id, @company_code_id, @branch_id, @department_id, @user_role_id, 1, NOW(), NOW()),
('Manager', '1', 'manager1@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567803', @business_unit_id, @company_code_id, @branch_id, @department_id, @manager_role_id, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Show created data
SELECT 'ROLES CREATED:' as info;
SELECT * FROM roles;

SELECT 'USERS CREATED:' as info;
SELECT u.first_name, u.last_name, u.email, r.name as role 
FROM users u 
JOIN roles r ON u.role_id = r.id 
WHERE u.email LIKE '%@example.com';

SELECT '✅ DATABASE SEEDED SUCCESSFULLY!' as status;
SELECT '📧 LOGIN CREDENTIALS:' as info;
SELECT 'Admin: admin@example.com / password123' as credential
UNION ALL
SELECT 'Manager: manager@example.com / password123'
UNION ALL  
SELECT 'User: user@example.com / password123'
UNION ALL
SELECT 'Driver: driver@example.com / password123';