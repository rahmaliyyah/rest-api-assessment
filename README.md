# REST API Assessment - PT Linkdataku MAXY Academy

## Overview

This project is a **RESTful API Wallet System** developed as part of a PT Linkdataku MAXY ACADEMY Internship assessment. The system supports user registration, authentication using JWT, balance top-up, payment, transfer between users, transaction reporting, profile updates, and a **monitoring dashboard** for internal observation.

---

## Tech Stack

* **Backend Framework**: Laravel 12
* **Language**: PHP 8.2
* **Database**: MySQL
* **Authentication**: JWT (JSON Web Token)
* **API Testing**: Postman
* **Dashboard**: Laravel Blade (Web Dashboard)

---

## API Endpoints

### 1️. Register

**POST** `/api/register`

### 2️. Login

**POST** `/api/login`

---

### 3️. Top Up Balance

**POST** `/api/topup`

**Headers**

```
Authorization: Bearer {jwt_token}
```

**Body**

```json
{
  "amount": 500000
}
```

---

### 4️. Payment

**POST** `/api/pay`

**Body**

```json
{
  "amount": 100000,
  "remarks": "Pulsa Telkomsel 100k"
}
```

---

### 5️. Transfer

**POST** `/api/transfer`

**Body**

```json
{
  "target_user": "{target_user_uuid}",
  "amount": 30000,
  "remarks": "Hadiah Ultah"
}
```

---

### 6️. Transaction Report

**GET** `/api/transactions`

Returns all transaction history including:

* TOP UP (CREDIT)
* PAYMENT (DEBIT)
* TRANSFER (DEBIT)

---

### 7️. Update Profile

**PUT** `/api/profile`

**Body**

```json
{
  "first_name": "Tom",
  "last_name": "Araya",
  "address": "Jl. Diponegoro No. 215"
}
```

---

## Dashboard Monitoring

The project also includes a **web-based dashboard** for internal monitoring.

### Access Dashboard

Open your browser and visit:

```
http://127.0.0.1:8000/dashboard
```

> Note: The dashboard uses Laravel session-based authentication. Make sure the `sessions` table exists in the database.

### Session Table Setup (If Needed)

If the session table does not exist, generate it using:

```
php artisan session:table
php artisan migrate
```
---


##  How to Run Project

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

GitHub Repository:
[https://github.com/username/rest-api-assessment](https://github.com/rahmaliyyah/rest-api-assessment/)
