<div align="center">

# 🌿 FLOWZONE

**Plataforma de turismo para Ortega, Tolima**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql&logoColor=white)](https://postgresql.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev)

</div>

---

## 📖 Descripción

**FLOWZONE** es una plataforma web de turismo orientada al municipio de Ortega, Tolima. Permite a visitantes explorar hoteles, lugares turísticos, eventos, gastronomía y planes turísticos; hacer reservas en línea con pago integrado mediante **Wompi**; y a las empresas turísticas registradas gestionar su propio catálogo de servicios. Un panel de administración centralizado supervisa y controla toda la plataforma.

---

## ✨ Funcionalidades principales

### 🌐 Zona pública
- Exploración de hoteles, lugares, eventos y gastronomía con detalle completo
- Mapa interactivo de puntos de interés turístico
- Blog de contenido turístico con slugs SEO-friendly
- Directorio de empresas turísticas registradas
- Planes turísticos publicados por empresas

### 👤 Panel de usuario
- Registro e inicio de sesión con recuperación de contraseña por correo
- Reservas de hoteles con selección de fechas y número de personas
- **Pago en línea vía Wompi** (tarjeta, Nequi, PSE) con confirmación por webhook
- Historial de reservas con estados en tiempo real
- Lista de favoritos (hoteles, lugares, eventos, gastronomía)
- Sistema de calificaciones y reseñas

### 🏢 Panel de empresa
- Dashboard con métricas propias de la empresa
- Gestión de menú de gastronomía con control de stock diario y disponibilidad
- Gestión de habitaciones y hoteles propios
- Creación y publicación de paquetes turísticos con cupo
- Generación de planes turísticos personalizados
- Blog corporativo de la empresa
- Visualización y gestión de reservas recibidas
- Respuesta a reseñas de clientes
- Exportación de datos a Excel y PDF

### 🛡️ Panel de administrador
- Dashboard con KPIs, gráficas de barras, dona y serie temporal
- Gestión completa (CRUD) de: hoteles, lugares, eventos, gastronomía, blog, reservas, empresas y usuarios
- Aprobación / rechazo de solicitudes de registro de empresas con notificaciones internas
- Importación y exportación masiva en `.xlsx` y `.pdf` para todos los módulos
- Gestión de imágenes hero del sitio con orden arrastrable y activación por toggle

---

## 🏗️ Arquitectura y tecnologías

| Capa | Tecnología |
|---|---|
| Framework backend | Laravel 12 (PHP 8.2+) |
| Base de datos | PostgreSQL |
| ORM | Eloquent (16 modelos, 33 migraciones) |
| Frontend | Blade + Tailwind CSS v4 + Vite 5 |
| Pagos | Wompi (checkout widget + webhook + firma SHA-256) |
| Excel | Maatwebsite Excel 3.1 |
| PDF | barryvdh/laravel-dompdf 3.1 |
| Autenticación | Laravel Auth nativo con roles (`admin`, `empresa`, `usuario`) |

### Roles del sistema

```
admin
  └── Accede a /admin/*  (middleware: es_admin)
empresa
  └── Accede a /empresa/* (middleware: es_empresa)
usuario
  └── Accede a /mi-cuenta, /reservar, /favoritos, etc.
```

---

## 📋 Requisitos previos

- PHP >= 8.2 con extensiones: `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`
- Composer >= 2.x
- Node.js >= 18.x y npm >= 9.x
- PostgreSQL >= 14

---

## 🚀 Instalación y configuración

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/flowzone.git
cd flowzone
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con tus credenciales:

```env
# Base de datos
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=flowzone
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña

# Wompi (pagos)
WOMPI_PUBLIC_KEY=pub_test_XXXXXXXXXXXXXXXX
WOMPI_PRIVATE_KEY=prv_test_XXXXXXXXXXXXXXXX
WOMPI_INTEGRITY_KEY=test_integrity_XXXXXXXX
WOMPI_EVENTS_KEY=test_events_XXXXXXXXXXXXXXX

# Correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.tuproveedor.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@dominio.com
MAIL_PASSWORD=tu_contraseña
MAIL_FROM_ADDRESS=noreply@flowzone.co
MAIL_FROM_NAME="FLOWZONE"
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

### 5. Compilar assets y levantar el servidor

**Modo desarrollo (todos los servicios en paralelo):**

```bash
composer run dev
```

Este comando levanta simultáneamente:
- `php artisan serve` — servidor Laravel
- `npm run dev` — compilación Vite en modo watch
- `php artisan queue:listen` — procesamiento de cola (emails, notificaciones)
- `php artisan pail` — visor de logs en tiempo real

**Modo producción:**

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve
```

### 6. (Opcional) Crear el usuario administrador

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name'     => 'Administrador',
    'email'    => 'admin@flowzone.co',
    'password' => bcrypt('contraseña_segura'),
    'rol'      => 'admin',
    'estado'   => 'activo',
]);
```

---

## 💳 Integración con Wompi

FLOWZONE utiliza el **checkout widget de Wompi** para procesar pagos colombianos (Nequi, PSE, tarjeta de crédito/débito).

### Flujo de pago

```
Usuario ──► Formulario reserva
              │
              ▼
        Crear reserva (estado: pendiente)
              │
              ▼
        Redirigir a checkout.wompi.co
              │
              ├──► Pago aprobado ──► Webhook ──► Reserva confirmada
              ├──► Pago rechazado ──► Webhook ──► Reserva pendiente
              └──► Retorno manual ──► GET /wompi/retorno
```

### Configuración del webhook

Registra la siguiente URL en tu panel de Wompi para recibir confirmaciones asíncronas (especialmente útil para PSE):

```
https://tu-dominio.com/wompi/webhook
```

> ⚠️ **Nota:** Para desarrollo local puedes usar [ngrok](https://ngrok.com) para exponer tu servidor y recibir webhooks.

---

## 📁 Estructura del proyecto

```
flowzone/
├── app/
│   ├── Exports/          # Exportaciones Excel (9 módulos)
│   ├── Imports/          # Importaciones Excel (9 módulos)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/    # Controllers del panel admin
│   │   │   ├── Empresa/  # Controllers del panel empresa
│   │   │   └── Auth/     # Autenticación y recuperación de contraseña
│   │   └── Middleware/   # EsAdmin, EsEmpresa
│   └── Models/           # 16 modelos Eloquent
├── database/
│   └── migrations/       # 33 migraciones
├── resources/
│   └── views/
│       ├── admin/        # Vistas del panel administrador
│       ├── empresa/      # Vistas del panel empresa
│       ├── auth/         # Login, registro, reset password
│       └── pages/        # Vistas públicas
├── routes/
│   └── web.php           # Todas las rutas organizadas por rol
└── config/
    └── wompi.php         # Configuración de la pasarela de pago
```

---

## 🗄️ Modelos principales

| Modelo | Descripción |
|---|---|
| `User` | Usuarios del sistema (roles: admin, empresa, usuario) |
| `Empresa` | Empresas turísticas registradas |
| `Hotel` | Hoteles disponibles para reserva |
| `Habitacion` | Habitaciones de un hotel |
| `Reserva` | Reservas de usuario con estado y pago |
| `ReservaHabitacion` | Reservas de habitaciones específicas |
| `PaqueteTuristico` | Paquetes creados por empresas |
| `PlanTuristico` | Planes turísticos generados |
| `Lugar` | Lugares de interés turístico |
| `Evento` | Eventos del municipio |
| `Gastronomia` | Platos y menú de empresas gastronómicas |
| `BlogPost` | Artículos del blog con slug |
| `Calificacion` | Reseñas y calificaciones por tipo |
| `Favorito` | Ítems favoritos del usuario por tipo |
| `HeroImage` | Imágenes del carrusel principal |
| `NotificacionAdmin` | Notificaciones internas del sistema |

---

## 🧪 Ejecutar pruebas

```bash
composer run test
```

---

## 👥 Equipo de desarrollo

Este proyecto fue desarrollado por estudiantes del programa de **Análisis y Desarrollo de Software (ADSO)** del SENA:

| Desarrollador/a | Rol |
|---|---|
| **Johan** | Desarrollo backend y arquitectura |
| **Stephanie** | Desarrollo frontend y UI/UX |
| **Sofía** | Módulos de empresa y panel admin |
| **Danna** | Integración de pagos y exportaciones |
| **Lina** | Autenticación, modelos y base de datos |

---

## 📄 Licencia

Este proyecto fue desarrollado con fines académicos como parte del **Proyecto Formativo ADSO — SENA**.

---

<div align="center">
  Hecho con ❤️ en Colombia 🇨🇴 · Ortega, Tolima
</div>