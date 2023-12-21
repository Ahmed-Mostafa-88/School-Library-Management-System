# Library Management System

## Introduction

This project is a robust Library Management System designed to efficiently manage library activities for educational institutions. The system was developed using PHPMyAdmin and Sublime Text 3.

## Features

- **Book Catalog and Search:** Enables users to search for available books using book ID, name, or publisher.
- **User Account Management:** Allows students and teachers to create and manage their accounts.
- **Role-based Dashboard:** Separate dashboards for students, teachers, and an admin dashboard for managing members and books.
- **Publication Approval:** Teachers can publish their books in the library, pending admin approval.
- **Efficient Access:** Designed for faster book searches and ease of use.

## System Requirements

### Functional Requirements

- **User Login:** Users must log in with valid credentials (user ID and password). The system determines user roles (teacher, student, admin).
- **Register New User:** Teachers and students can register new users by providing necessary information for verification.
- **Publish New Book:** Teachers can add new books to the system for approval by providing book details.
- **Book Search:** Allows searching for books using various criteria.
- **Manage Members and Books:** Admin has the authority to manage users and books, including blocking/unblocking.

### Non-Functional Requirements

- **Efficiency Requirement:** Fast and efficient book searches for easy access.
- **Reliability Requirement:** Accurate member registration, validation, and book search functionality.
- **Usability Requirement:** User-friendly environment for easy navigation and task execution.

## Diagrams

- **Use Case Diagram**
- **Activity Diagram**
- **Sequence Diagram**
- **Entity-Relationship Diagram (ERD)**
- **Login Flow Diagram**
- **Registration Flow Diagram**
- **Student Dashboard Flow Diagram**
- **Teacher Dashboard Flow Diagram**
- **Admin Dashboard Flow Diagram**

## Test Cases

| Test Case | Pre-condition | Test Data | Steps | Expected Output | Actual Output |
| --------- | ------------- | --------- | ----- | --------------- | ------------- |
| 1. Validate error message for an unregistered user | User on login page | Unregistered user ID: 12222, Password: test00 | Enter invalid user ID and password, click sign-in | Display error message, deny login | Error message displayed, login denied |
| 2. New user cannot log in until approved by admin | User on login page | New user ID: 888, Password: test01 | Enter newly registered user ID and password, attempt login | Display error message, deny login | Error message displayed, login denied |
| 3. New book addition requires admin approval | User on teacher dashboard | Book details: ID: 99, Name: 99, URL: 99, Description: 99, Publisher: 99 | Add book to the system | Book does not appear until admin approval | Book not visible until admin approval |

## Installation and Setup

1. Clone the repository.
2. Set up PHPMyAdmin and create the necessary database tables as per the provided schema.
3. Configure the database connection in the PHP files.
4. Run the project on a web server.

## Technologies Used

- PHPMyAdmin
- Sublime Text 3


