-- Create the database
CREATE DATABASE car_rentals;

-- Use the database
USE car_rentals;

-- Create a table for user registration (Customers and Car Rental Agencies)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('customer', 'agency') NOT NULL
);

-- Create a table for car details
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_model VARCHAR(255) NOT NULL,
    vehicle_number VARCHAR(20) NOT NULL,
    seating_capacity INT NOT NULL,
    rent_per_day DECIMAL(10, 2) NOT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1, -- 1 for available, 0 for not available
    agency_id INT, 
    image_data VARCHAR(250) DEFAULT NULL
);

-- Create a table for bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL, -- Foreign key to link bookings to customers
    car_id INT NOT NULL, -- Foreign key to link bookings to cars
    booking_date DATE NOT NULL,
    return_date DATE NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
);
