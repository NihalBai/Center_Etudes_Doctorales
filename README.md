# Thesis Defense Management System

A web application that digitizes and automates the thesis defense management process for doctoral studies centers.

## What it does

This system replaces manual paper-based processes for managing PhD thesis defenses. It handles the complete workflow from defense creation to diploma generation, including jury management, scheduling, result processing, and document archiving.

### Core Functionality

**Defense Management**
- Create and schedule thesis defenses
- Manage jury member selection and availability
- Track defense progress and status
- Handle venue and time allocation

**Document Generation**
- Generate invitation letters automatically
- Create official defense minutes
- Produce diplomas and certificates
- Support multi-language documents (French/Arabic)

**User Management**
- Role-based access control (Administrator, Service Employee, Director, Rapporteur)
- Secure authentication system
- Member profile management
- Academic record tracking

**Administrative Tools**
- Template management for all documents
- Reference data management (universities, faculties, grades)
- User account administration
- System configuration

## Technology Stack

**Backend**
- Laravel 11 (PHP Framework)
- PHP 8
- MySQL Database

**Frontend**
- HTML/CSS/JavaScript
- Bootstrap (Responsive Design)

**Development Environment**
- XAMPP (Local Development Server)
- Visual Studio Code
- PhpMyAdmin (Database Management)
- StarUML (System Design)

## System Architecture

Built using Laravel's MVC (Model-View-Controller) architecture:
- **Models**: Handle data logic and database interactions
- **Views**: Manage user interface and presentation
- **Controllers**: Process user input and coordinate between models and views

## User Roles

**Administrator**
- Complete system access
- User and template management
- Reference data administration

**Service Employee**
- Defense creation and management
- Result processing
- Document generation

**Director**
- Defense oversight
- Academic supervision

**Rapporteur**
- Thesis evaluation
- Defense participation

## Key Features

- Secure authentication with password recovery
- Real-time availability checking for venues and jury members
- Automated document generation with customizable templates
- Complete defense lifecycle tracking
- Data archiving and search functionality
- Responsive web interface accessible on all devices

## Installation

```bash
# Clone repository
git clone [repository-url]

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start server
php artisan serve
```

## License

Educational and institutional use.
