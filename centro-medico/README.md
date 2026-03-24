# README.md for Centro Medico Project

# Centro Medico

Centro Medico is a medical appointment management system built with Laravel. This project provides a backend API and an admin panel for managing patients, medical records, and user roles.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## Installation

To set up the project locally, follow these steps:

1. **Install dependencies**
   ```
   composer install
   ```

2. **Copy the environment configuration**
   ```
   cp .env.example .env
   ```

3. **Generate the application key**
   ```
   php artisan key:generate
   ```

4. **Create the SQLite database**
   Navigate to the `database/` directory and create a file named:
   ```
   database.sqlite
   ```

5. **Run migrations and seed the database**
   ```
   php artisan migrate --seed
   ```

6. **Create a user for Filament**
   Open Tinker:
   ```
   php artisan tinker
   ```
   Then run the following commands:
   ```php
   $user = App\Models\User::where('email', 'admin@medico.com')->first();
   $user->password = bcrypt('password');
   $user->save();
   exit;
   ```

7. **Access the admin panel**
   Open your browser and navigate to:
   ```
   http://centro-medico.test/admin
   ```
   Use the following credentials:
   - **Email:** admin@medico.com
   - **Password:** password

## Usage

This project includes a Filament admin panel for managing patients and their medical records. The roles defined in the system are:
- Admin
- Doctor
- Assistant

Each role has specific permissions to access and manage resources.

## API Endpoints

The following API endpoints are available for managing patients and medical records:

- **GET /api/patients** - List all patients (paginated)
- **POST /api/patients** - Create a new patient (validates unique DUI)
- **GET /api/patients/{id}** - View details of a specific patient
- **PUT /api/patients/{id}** - Update a specific patient
- **DELETE /api/patients/{id}** - Delete a specific patient (admin only)
- **GET /api/patients/{id}/records** - Get medical record of a patient
- **POST /api/patients/{id}/records** - Create or update a patient's medical record

All routes are protected with `auth:sanctum` middleware.

## Contributing

Contributions are welcome! Please follow the GitFlow workflow for managing features and fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.