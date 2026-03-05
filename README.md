# Group 3: Warehouse Logistics Management System (Backend API)

## Project Overview
A Vanilla PHP REST API for managing warehouses, shipments, and delivery tracking, featuring mandatory AES-256-GCM encryption for sensitive data.

## 🛠 Setup Instructions
1. **Database:** Import the SQL schema provided into MySQL/MariaDB. Name the database `warehouse_db`.
2. **Configuration:** Ensure `config.php` has the correct database credentials and a 32-byte HEX `ENCRYPTION_KEY`.
3. **Server:** Host the folder in XAMPP `htdocs`. 
4. **Base URL:** `http://localhost/warehouse_api/api/`

## 🔐 Security Implementation (Mandatory Requirements)
This project strictly follows the AES-256-GCM encryption standards using `openssl_encrypt()` and `openssl_decrypt()`.

### 1. Which fields are encrypted?
To satisfy the requirement of encrypting at least TWO types of sensitive data:
*   **User Personal Data:** The `email` field in the `users` table.
*   **Logistics Location Data:** The `destination` address field in the `shipments` table.

### 2. Encryption Process
Encryption occurs **before** data is stored in the database at these endpoints:
*   `POST /api/auth/register.php` (Encrypts User Email)
*   `PUT /api/user/profile.php` (Encrypts Updated Email)
*   `POST /api/shipment/index.php` (Encrypts Shipment Destination)

For every encryption, a unique **12-byte random IV** is generated. The resulting **Encrypted Data**, **IV**, and **Authentication Tag** are stored in separate columns in the database.

### 3. Decryption Process
Decryption occurs **after** retrieving data but **before** returning the JSON response at these endpoints:
*   `GET /api/user/profile.php` (Decrypts Email for user view)
*   `GET /api/shipment/index.php?id={id}` (Decrypts Destination for tracking)

### 4. Key Management
*   The system uses a **single secret encryption key** (256-bit).
*   The key is stored securely in `config.php` and is never hardcoded within the controllers or the `EncryptionHelper` utility class.

### 5. Password Security
*   As per instructions, passwords are **NOT** encrypted. 
*   Passwords are secured using `password_hash()` with the `BCRYPT` algorithm and verified via `password_verify()`.

## 🚀 API Endpoints

### Auth
- `POST /api/auth/register.php` - Register new user/admin
- `POST /api/auth/login.php` - Authenticate and start session

### User Profile
- `GET /api/user/profile.php` - View profile (demonstrates decryption)
- `PUT /api/user/profile.php` - Update profile

### Warehouses
- `POST /api/warehouse/index.php` - Create warehouse (Admin only)
- `GET /api/warehouse/index.php` - List all warehouses

### Shipments
- `POST /api/shipment/index.php` - Create shipment (Encrypts destination)
- `GET /api/shipment/index.php` - List user shipments

### Reports (Admin Only)
- `GET /api/reports/index.php?type=status` - Delivery status counts
- `GET /api/reports/index.php?type=performance` - Success rate metrics