# Centro Médico — API & Panel Administrativo

Sistema de gestión de centro médico construido con **Laravel 12**, **Filament 3.3**, **Sanctum** y **Spatie Permissions**.

## Requisitos

- PHP 8.3+
- Composer
- MySQL / SQLite
- Node.js & npm

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve
```

Panel: `http://localhost:8000/admin`

**Admin:** `admin@medico.com` / `password`

---

## API Endpoints

Todos los endpoints (excepto login) requieren `Authorization: Bearer {token}`.

### Autenticación

| Método | URL           | Descripción          |
|--------|---------------|----------------------|
| POST   | `/api/login`  | Obtener token de API |

**Body:**
```json
{ "email": "admin@medico.com", "password": "password" }
```

---

### Usuarios (`/api/users`)

| Método | URL               | Descripción           | Rol requerido |
|--------|-------------------|-----------------------|---------------|
| GET    | `/api/users`      | Listar todos          | admin         |
| POST   | `/api/users`      | Crear usuario         | admin         |
| PUT    | `/api/users/{id}` | Actualizar usuario    | admin         |
| DELETE | `/api/users/{id}` | Desactivar usuario    | admin         |

**POST Body:**
```json
{
  "name": "Dr. García",
  "email": "garcia@medico.com",
  "password": "12345678",
  "role": "doctor",
  "is_active": true
}
```

---

### Pacientes (`/api/patients`)

| Método | URL                  | Descripción          | Rol requerido       |
|--------|----------------------|----------------------|---------------------|
| GET    | `/api/patients`      | Listar con expediente| admin, asistente    |
| POST   | `/api/patients`      | Crear paciente       | admin, asistente    |
| GET    | `/api/patients/{id}` | Ver detalle          | admin, asistente    |
| PUT    | `/api/patients/{id}` | Actualizar           | admin, asistente    |
| DELETE | `/api/patients/{id}` | Eliminar             | admin, asistente    |

**POST Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@ejemplo.com",
  "phone": "7777-8888",
  "dui": "12345678-9",
  "birth_date": "1990-05-15",
  "address": "San Salvador"
}
```

---

### Citas (`/api/appointments`)

| Método | URL                       | Descripción       | Rol requerido              |
|--------|---------------------------|--------------------|----------------------------|
| GET    | `/api/appointments`       | Listar todas       | admin, doctor, asistente   |
| POST   | `/api/appointments`       | Crear cita         | admin, doctor, asistente   |
| GET    | `/api/appointments/{id}`  | Ver detalle        | admin, doctor, asistente   |
| PUT    | `/api/appointments/{id}`  | Actualizar         | admin, doctor, asistente   |
| DELETE | `/api/appointments/{id}`  | Eliminar           | admin, doctor, asistente   |

**POST Body:**
```json
{
  "patient_id": 1,
  "user_id": 2,
  "appointment_date": "2026-03-25",
  "appointment_time": "09:00",
  "status": "pending",
  "notes": "Primera consulta"
}
```

---

### Expedientes Médicos (`/api/medical-records`)

| Método | URL                          | Descripción       | Rol requerido    |
|--------|------------------------------|--------------------|------------------|
| GET    | `/api/medical-records`       | Listar todos       | admin, doctor    |
| POST   | `/api/medical-records`       | Crear expediente   | admin, doctor    |
| GET    | `/api/medical-records/{id}`  | Ver detalle        | admin, doctor    |
| PUT    | `/api/medical-records/{id}`  | Actualizar         | admin, doctor    |
| DELETE | `/api/medical-records/{id}`  | Eliminar           | admin, doctor    |

**POST Body:**
```json
{
  "patient_id": 1,
  "blood_type": "O+",
  "allergies": "Penicilina",
  "medical_history": "Sin antecedentes relevantes",
  "current_medications": "Ninguno",
  "notes": "Paciente sano"
}
```

---

## Dashboard Filament

- **Admin**: Ve estadísticas globales (total pacientes, citas de hoy, citas del mes, médicos activos) + gráfico de citas diarias
- **Doctor**: Ve solo sus propias citas de hoy y su gráfico personal

## Calendario

Vista de calendario mensual con disponibilidad de doctores (verde) y citas existentes (coloreadas por estado).

## Seeds

El seeder genera:
- 1 admin + 5 doctores + 3 asistentes
- 25 pacientes con expediente médico
- 20+ horarios de doctores
- 50 citas (pasadas y futuras)
