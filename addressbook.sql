-- INFX 472-001-202720 Full Stack Web Development
-- Group 3 - Samuel A., Avery S., Mason Z.
-- Project 4 - addressbook.sql

-- create the main database (A.S.)
CREATE DATABASE IF NOT EXISTS addressbook;
USE addressbook;

-- drop table if it already exists to ensure a clean setup during testing (A.S.)
DROP TABLE IF EXISTS contacts;

-- create the contacts table matching all minimum assignment specifications (A.S.)
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,             -- primary key, auto-incrementing identifier
    first_name VARCHAR(50) NOT NULL,               -- contact's first name (required)
    last_name VARCHAR(50) NOT NULL,                -- contact's last name (required)
    email VARCHAR(100) NOT NULL,                   -- contact's email address (required)
    phone VARCHAR(20) NOT NULL,                    -- contact's phone number (required)
    address1 VARCHAR(100),                         -- primary street address
    address2 VARCHAR(100),                         -- apartment, suite, unit, etc.
    city VARCHAR(50),                              -- city name
    state VARCHAR(2),                              -- two-letter U.S. state abbreviation
    zip_code VARCHAR(10),                          -- postal / ZIP code
    contact_group ENUM('Family', 'Friends', 'Business') NOT NULL, -- category drop-down restriction
    notes TEXT,                                    -- long-form text field for miscellaneous notes
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- automatic timestamp default
);

-- insert 3 required initial sample records for testing display and filtering (A.S.)
INSERT INTO contacts (first_name, last_name, email, phone, address1, address2, city, state, zip_code, contact_group, notes) VALUES
('Eleanor', 'Ford', 'eleanor.f@example.com', '337-555-0192', '104 Hilltop Rd', 'Apt 2B', 'Lafayette', 'LA', '70506', 'Family', 'Calls on Sundays.'),
('Julian', 'St. Claire', 'julian@stclaire.co', '337-555-8431', '400 Jefferson St', '', 'Lafayette', 'LA', '70501', 'Business', 'Design client for branding.'),
('Clara', 'Oswin', 'oswin.clara@gmail.com', '504-555-3822', '712 Canal St', 'Suite 400', 'New Orleans', 'LA', '70130', 'Friends', 'Met at the craft market.');
