# Inventory Management System for the Garment Industry

## 1. Project Overview

Raw material inventory management is an important part of garment manufacturing operations, as it helps ensure material availability and supports production activities. This project focuses on developing a web-based inventory management system for CV Akusara, a garment manufacturing company, as part of an internship program. The system was developed using PHP, CodeIgniter 4, and MySQL to support structured inventory recording, stock monitoring, and transaction management. It integrates various inventory operations into a single platform with a user-friendly interface and role-based access control.

## 2. Business Problem

The company's previous inventory system primarily supported basic data recording, document printing, and data exporting. However, it had limitations in providing comprehensive inventory monitoring and managing detailed material information. This project addresses several challenges:

- Limited visibility into raw material stock availability.
- Difficulty monitoring low-stock materials and purchase order statuses.
- Inefficient tracking of material transactions and inventory details.
- Limited support for tracking materials based on lot and roll numbers.
- Complex user interfaces that affected usability.

## 3. Project Objectives

- Develop an integrated web-based raw material inventory management system.
- Improve the organization and accessibility of inventory data.
- Support material purchasing, receiving, returning, and usage transactions.
- Provide inventory monitoring through an informative dashboard.
- Enable detailed inventory tracking based on lot and roll numbers.
- Implement role-based access control for different system users.
- Design a user-friendly interface to support daily operations.

## 4. System Scope

The system focuses on internal raw material inventory management within the garment manufacturing environment. The main operational areas include:

- Raw material stock management.
- Supplier and material master data management.
- Purchase order management.
- Material receiving and purchase return transactions.
- Material usage recording.
- Lot and roll tracking.
- Inventory reporting and document generation.
- User account management.

The system does not cover production planning, financial management, or product distribution.

## 5. Tools & Technologies

- **PHP:** Backend application development.
- **CodeIgniter 4:** PHP framework for web application development.
- **MySQL:** Relational database management.
- **HTML, CSS, and JavaScript:** Web interface development.
- **Model-View-Controller (MVC):** Application architecture.
- **Unified Modeling Language (UML):** System analysis and design.
- **Black-Box Testing:** Functional system testing.

## 6. Development Workflow

The project followed an incremental software development approach, allowing the system to be developed and tested in stages.

**Requirement Analysis → System Design → Development → Testing → Implementation & Integration**

The development process included identifying user requirements, designing system workflows and database structures, implementing individual modules, and testing system functionality.

## 7. Key System Features

### 7.1 Dashboard & Inventory Monitoring

- Displays inventory and transaction summaries.
- Provides information about materials approaching minimum stock levels.
- Monitors purchase order statuses.
- Supports easier access to inventory-related information.

### 7.2 Master Data Management

- Manages supplier information.
- Maintains material categories, materials, colors, and units.
- Supports adding, updating, and deleting master data.
- Organizes inventory data within a relational database.

### 7.3 Inventory & Transaction Management

- **Purchase Orders:** Records and manages raw material purchase orders.
- **Material Receiving:** Tracks incoming materials from suppliers.
- **Purchase Returns:** Records materials returned to suppliers.
- **Material Usage:** Records materials used in production.
- **Stock Management:** Maintains raw material inventory information.

### 7.4 Lot & Roll Tracking

- Records detailed inventory information based on lot and roll numbers.
- Supports tracking materials across receiving, return, and usage transactions.
- Provides detailed stock information for inventory monitoring.

### 7.5 Reporting & Data Export

- Exports inventory and transaction records to Excel.
- Generates printable transaction documents.
- Provides document previews before printing.

### 7.6 User Management & Access Control

The system implements two user roles:

- **Admin:** Manages master data, inventory transactions, and user accounts.
- **User:** Views dashboards, inventory records, transaction details, and manages personal profile information.

## 8. System Architecture & Database Design

The application uses the **Model-View-Controller (MVC)** architecture provided by CodeIgniter 4.

- **Model:** Handles database operations and data management.
- **View:** Presents information through the user interface.
- **Controller:** Processes user requests and coordinates interactions between models and views.

The database was designed using MySQL with relational structures to manage materials, suppliers, inventory records, transactions, and user information. System design documentation includes:

- Use Case Diagrams
- Class Diagram
- Activity Diagrams
- Sequence Diagrams
- Component Diagram

## 9. System Testing

Functional testing was conducted using the **black-box testing** method to evaluate whether the implemented features worked according to system requirements. Testing covered major functionalities, including:

- User authentication and access control.
- Dashboard and inventory information display.
- Master data management.
- Purchase order and inventory transactions.
- Lot and roll data management.
- Data export and document printing.
- User account management.

The testing results documented in the internship report indicate that the implemented system functionalities operated according to the specified requirements.

## 10. System Screenshots

The following section presents the main interfaces of the developed web application.

<img width="512" height="243" alt="image" src="https://github.com/user-attachments/assets/b842713b-f57d-4762-988d-33ae6546101d" />
<img width="512" height="243" alt="image" src="https://github.com/user-attachments/assets/97ee14e8-33a0-4dd1-8b11-b55e1f7849c1" />

## 11. Project Limitations

- The application focuses on internal raw material inventory management and does not include production, financial, or distribution modules.
- System development and testing were conducted within the scope of the internship project.
- Further evaluation may be required to assess long-term performance under larger operational workloads.
- Additional improvements can be considered based on future operational requirements.

## 12. Project Outcomes

The project resulted in the development of a functional web-based inventory management system designed to support garment manufacturing operations. The implemented application provides:

- Centralized raw material inventory records.
- More structured inventory and transaction management.
- Improved visibility into stock availability and purchase order statuses.
- Detailed material tracking through lot and roll information.
- Data export and transaction document generation.
- Role-based access to system functionalities.

## 13. Conclusion

This project demonstrates the development of a web-based raw material inventory management system using PHP, CodeIgniter 4, and MySQL. By integrating inventory records, transaction management, stock monitoring, and reporting features, the system provides a structured approach to supporting daily inventory operations in the garment industry. The project also demonstrates practical experience in system analysis, database design, backend development, user interface implementation, and functional testing.
