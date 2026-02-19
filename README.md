# Loan System API

Simple Loan Management System built with Laravel & Sanctum.

## 🚀 Features

- User Register & Login
- Role-based Access (Borrower & Admin)
- Apply Loan
- Approve Loan (Admin Only)
- Loan History
- Authentication using Laravel Sanctum

---

## ⚙️ Tech Stack

- Laravel
- Sanctum
- MySQL
- REST API

---

## 🔑 API Endpoints

### Auth
POST /api/register  
POST /api/login  
POST /api/logout  

### User
GET /api/user  

### Loan
POST /api/loan/apply  
GET /api/loan/history  
POST /api/loan/{id}/approve  

---

## 📦 Installation

```bash
git clone https://github.com/masbin5/loan-system.git
cd loan-system
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
