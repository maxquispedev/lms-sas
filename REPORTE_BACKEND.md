# Reporte Completo del Backend - Sistema LMS

## 📋 Información General

**Proyecto:** Sistema de Gestión de Aprendizaje (LMS)  
**Framework:** Laravel 12  
**Panel Administrativo:** FilamentPHP v4  
**Frontend Interactivo:** Livewire 3  
**Estilos:** TailwindCSS 4  
**PHP:** 8.2+  
**Base de Datos:** MySQL/PostgreSQL (configurable)

---

## 🛠️ Stack Tecnológico

### Dependencias Principales
- **Laravel Framework:** ^12.0
- **Filament:** ^4.4 (Panel administrativo)
- **Filament Shield:** ^4.0 (Gestión de roles y permisos)
- **Spatie Permission:** Integrado (Sistema de permisos)
- **Livewire:** 3.x (Componentes interactivos)
- **TailwindCSS:** ^4.0.0
- **Vite:** ^7.0.7 (Build tool)
- **AlpineJS:** ^3.15.3

### Herramientas de Desarrollo
- **Laravel Pail:** ^1.2.2 (Log viewer)
- **Laravel Pint:** ^1.24 (Code formatter)
- **PHPUnit:** ^11.5.3 (Testing)
- **Laravel Sail:** ^1.41 (Docker environment)

---

## 📁 Estructura del Proyecto

```
backend/
├── app/
│   ├── Enums/              # Enumeraciones del sistema
│   ├── Filament/           # Recursos del panel administrativo
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/        # Controladores de API REST
│   │   └── Resources/
│   │       └── Api/        # Transformadores de API
│   ├── Livewire/           # Componentes Livewire
│   ├── Models/             # Modelos Eloquent
│   ├── Policies/           # Políticas de autorización
│   ├── Providers/          # Service Providers
│   └── Services/           # Servicios de negocio
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   └── seeders/            # Seeders de datos
├── routes/
│   ├── api.php             # Rutas de API
│   └── web.php             # Rutas web
└── config/                 # Archivos de configuración
```

---

## 🗄️ Modelos y Relaciones

### 1. **User** (Usuario)
**Ubicación:** `app/Models/User.php`

**Atributos:**
- `id`, `name`, `email`, `password`, `avatar_url`
- `email_verified_at`, `remember_token`
- Timestamps automáticos

**Relaciones:**
- `orders()` - HasMany → Ordenes del usuario
- `courses()` - BelongsToMany → Cursos en los que está inscrito
- `lessons()` - BelongsToMany → Lecciones con estado de completado
- `lessons_completed()` - BelongsToMany → Lecciones completadas (filtrado)

**Características:**
- Usa el trait `HasRoles` de Spatie Permission
- Autenticación estándar de Laravel
- Soft deletes no implementado

---

### 2. **Course** (Curso)
**Ubicación:** `app/Models/Course.php`

**Atributos:**
- `id`, `teacher_id`, `title`, `slug`, `description`
- `price` (decimal:2), `image_url`, `status` (enum)
- Timestamps y soft deletes

**Relaciones:**
- `teacher()` - BelongsTo → Usuario instructor
- `modules()` - HasMany → Módulos del curso
- `students()` - BelongsToMany → Estudiantes inscritos (con pivot: status, enrolled_at, expires_at)
- `reviews()` - MorphMany → Reseñas polimórficas

**Características:**
- Slug único para URLs amigables
- Estado del curso (Draft, Published, Archived)
- Imagen de portada con URL pública y fallback
- Soft deletes habilitado

---

### 3. **Module** (Módulo)
**Ubicación:** `app/Models/Module.php`

**Atributos:**
- `id`, `course_id`, `title`, `sort_order`
- Timestamps y soft deletes

**Relaciones:**
- `course()` - BelongsTo → Curso padre
- `lessons()` - HasMany → Lecciones del módulo

**Características:**
- Ordenamiento mediante `sort_order`
- Soft deletes habilitado

---

### 4. **Lesson** (Lección)
**Ubicación:** `app/Models/Lesson.php`

**Atributos:**
- `id`, `module_id`, `title`, `slug`, `iframe_code`
- `content` (longText), `is_free` (boolean), `sort_order`
- Timestamps y soft deletes

**Relaciones:**
- `module()` - BelongsTo → Módulo padre

**Características:**
- Slug único para URLs amigables
- Contenido enriquecido (RichEditor)
- Soporte para videos embebidos (iframe_code)
- Lecciones gratuitas de vista previa
- Ordenamiento mediante `sort_order`
- Soft deletes habilitado

---

### 5. **Order** (Orden)
**Ubicación:** `app/Models/Order.php`

**Atributos:**
- `id`, `user_id`, `course_id`, `total_amount` (decimal:2)
- `status` (enum), `payment_gateway` (enum), `transaction_id`
- Timestamps (sin soft deletes)

**Relaciones:**
- `user()` - BelongsTo → Usuario que realiza la compra
- `course()` - BelongsTo → Curso comprado

**Características:**
- Estados: Pending, Paid, Failed
- Pasarelas de pago: Manual, Culqi
- Referencia de transacción opcional

---

### 6. **Review** (Reseña)
**Ubicación:** `app/Models/Review.php`

**Atributos:**
- `id`, `user_id`, `reviewable_id`, `reviewable_type`
- `rating` (integer), `comment` (text nullable)
- Timestamps y soft deletes

**Relaciones:**
- `user()` - BelongsTo → Usuario que escribe la reseña
- `reviewable()` - MorphTo → Modelo reseñable (polimórfico)

**Características:**
- Sistema polimórfico (puede reseñar cursos u otros modelos)
- Soft deletes habilitado

---

## 🔗 Tablas Pivote

### **course_user** (Inscripciones)
- `user_id`, `course_id`
- `status` (default: 'active')
- `enrolled_at`, `expires_at` (nullable)
- Timestamps

**Propósito:** Gestiona las inscripciones de usuarios a cursos con fechas de expiración.

---

### **lesson_user** (Progreso de Lecciones)
- `user_id`, `lesson_id` (clave primaria compuesta)
- `completed` (boolean, default: false)
- `completed_at` (timestamp nullable)
- Timestamps

**Propósito:** Rastrea el progreso y completado de lecciones por usuario.

---

## 📊 Enumeraciones (Enums)

### 1. **CourseStatus**
**Ubicación:** `app/Enums/CourseStatus.php`

**Valores:**
- `Draft` - Borrador (color: gray)
- `Published` - Publicado (color: success)
- `Archived` - Archivado (color: danger)

**Métodos:**
- `getColor(): string` - Retorna el color para badges en Filament

---

### 2. **OrderStatus**
**Ubicación:** `app/Enums/OrderStatus.php`

**Valores:**
- `Pending` - Pendiente (color: warning)
- `Paid` - Pagado (color: success)
- `Failed` - Fallido (color: danger)

**Métodos:**
- `getColor(): string` - Retorna el color para badges

---

### 3. **PaymentGateway**
**Ubicación:** `app/Enums/PaymentGateway.php`

**Valores:**
- `Manual` - Manual (color: gray)
- `Culqi` - Culqi (color: primary)

**Métodos:**
- `getColor(): string` - Retorna el color para badges

---

## 🎯 Servicios de Negocio

### 1. **EnrollmentService**
**Ubicación:** `app/Services/EnrollmentService.php`

**Métodos:**

#### `enrollUser(User $user, Course $course): void`
Inscribe un usuario en un curso.
- Crea registro en tabla pivot `course_user`
- Establece status como 'active'
- Registra fecha de inscripción

#### `checkAccess(User $user, Course $course): bool`
Verifica si un usuario tiene acceso activo a un curso.
- Verifica existencia de inscripción activa
- Valida fecha de expiración (si existe)
- Retorna `true` si tiene acceso válido

---

### 2. **PaymentService**
**Ubicación:** `app/Services/PaymentService.php`

**Dependencias:**
- `EnrollmentService` (inyectado)

**Métodos:**

#### `createManualOrder(User $user, Course $course, ?string $transactionRef = null): Order`
Crea una orden manual y automáticamente inscribe al usuario.
- Crea orden con status `Paid`
- Gateway `Manual`
- Inscribe automáticamente al usuario en el curso
- Transacción atómica (DB::transaction)
- Retorna la orden creada

**Flujo:**
1. Crea la orden en estado "Pagado"
2. Llama a `EnrollmentService::enrollUser()` para inscribir al usuario

---

## 🌐 API REST

### Endpoints Disponibles

**Base URL:** `/api`

#### 1. **GET /api/courses**
Lista todos los cursos publicados.

**Controlador:** `App\Http\Controllers\Api\CourseApiController@index`

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Título del Curso",
      "slug": "titulo-del-curso",
      "price": 99.99,
      "image_url": "http://...",
      "description": "...",
      "teacher": {
        "name": "Nombre Instructor",
        "avatar_url": "http://..."
      },
      "modules": [
        {
          "id": 1,
          "title": "Módulo 1",
          "sort_order": 1,
          "lessons": [...]
        }
      ]
    }
  ]
}
```

**Características:**
- Solo muestra cursos con status `Published`
- Incluye relaciones: teacher, modules, lessons
- Usa `CourseApiResource` para transformación

---

#### 2. **GET /api/courses/{course:slug}**
Obtiene un curso específico por slug.

**Controlador:** `App\Http\Controllers\Api\CourseApiController@show`

**Respuesta:**
```json
{
  "data": {
    "id": 1,
    "title": "...",
    // ... mismo formato que el listado
  }
}
```

**Características:**
- Solo muestra cursos publicados (404 si no está publicado)
- Incluye todas las relaciones
- Usa `CourseApiResource` para transformación

---

### Recurso de API: CourseApiResource
**Ubicación:** `app/Http/Resources/Api/CourseApiResource.php`

**Transformaciones:**
- Convierte `price` a float
- Genera URLs absolutas para imágenes
- Incluye información del instructor condicionalmente
- Ordena módulos y lecciones por `sort_order`
- Incluye metadatos de lecciones (slug, is_free, sort_order)

---

## 🎨 Panel Administrativo (Filament)

### Configuración
**Ubicación:** `app/Providers/Filament/AdminPanelProvider.php`

**Características:**
- Panel ID: `admin`
- Ruta: `/admin`
- Color primario: Amber
- Plugin: Filament Shield (gestión de roles)
- Autenticación requerida

---

### Recursos Administrativos

#### 1. **CourseResource**
**Ubicación:** `app/Filament/Resources/CourseResource.php`

**Funcionalidades:**
- **Listado:**
  - Imagen circular del curso
  - Título, instructor, estado, precio
  - Filtros por estado
  - Ordenamiento por fecha de creación

- **Formulario de Creación/Edición:**
  - Selección de instructor (búsqueda)
  - Título con generación automática de slug
  - Editor rico para descripción
  - Subida de imagen (con editor)
  - Precio con prefijo de moneda
  - Toggle buttons para estado

- **Relación:**
  - `ModulesRelationManager` - Gestión de módulos y lecciones

**Páginas:**
- Listado: `ListCourses`
- Crear: `CreateCourse`
- Editar: `EditCourse`

---

#### 2. **OrderResource**
**Ubicación:** `app/Filament/Resources/OrderResource.php`

**Funcionalidades:**
- **Listado:**
  - ID, usuario, total, estado, pasarela
  - Badges de colores según estado/pasarela
  - Ordenamiento por fecha de creación

- **Formulario:**
  - Selección de usuario (búsqueda)
  - Selección de curso (solo publicados)
  - ID de referencia de transacción (opcional)

**Páginas:**
- Listado: `ListOrders`
- Crear: `CreateOrder`
- Editar: `EditOrder`

---

#### 3. **ModulesRelationManager**
**Ubicación:** `app/Filament/Resources/CourseResource/RelationManagers/ModulesRelationManager.php`

**Funcionalidades:**
- Gestión de módulos dentro de un curso
- **Formulario de Módulo:**
  - Título del módulo
  - Orden de visualización
  - **Repeater de Lecciones:**
    - Título con generación automática de slug
    - Código iframe para videos
    - Editor rico para contenido
    - Toggle para lección gratuita
    - Orden de visualización
    - Reordenamiento con botones

- **Tabla:**
  - Título del módulo
  - Contador de lecciones (badge)
  - Ordenamiento por `sort_order`

---

## ⚡ Componentes Livewire

### 1. **StudentDashboard**
**Ubicación:** `app/Livewire/StudentDashboard.php`  
**Ruta:** `/my-courses` (requiere autenticación)

**Funcionalidades:**
- Muestra cursos inscritos del usuario autenticado
- Calcula progreso por curso:
  - Total de lecciones
  - Lecciones completadas
  - Porcentaje de progreso
- Ordena por fecha de inscripción (más recientes primero)
- Solo muestra cursos con status 'active' en el pivot

**Datos Computados:**
- `courses()` - Collection de cursos con progreso calculado

---

### 2. **WatchLesson**
**Ubicación:** `app/Livewire/WatchLesson.php`  
**Ruta:** `/learn/{course:slug}/{lesson:slug?}` (requiere autenticación)

**Funcionalidades:**
- Visualización de lecciones del curso
- Verificación de acceso mediante `EnrollmentService`
- Navegación entre lecciones (anterior/siguiente)
- Marcar lección como completada
- Autoplay (preferencia del usuario)
- Carga automática de la primera lección si no se especifica

**Métodos:**
- `toggleComplete()` - Alterna estado de completado
- `toggleAutoplay()` - Alterna preferencia de autoplay
- `isLessonCompleted()` - Verifica si está completada
- `getNextLesson()` - Obtiene siguiente lección
- `getPreviousLesson()` - Obtiene lección anterior

**Datos Pasados a la Vista:**
- Módulos ordenados con lecciones
- IDs de lecciones completadas
- Lección siguiente y anterior

---

### 3. **CourseCheckout**
**Ubicación:** `app/Livewire/CourseCheckout.php`  
**Ruta:** `/checkout/{course:slug}` (requiere autenticación)

**Funcionalidades:**
- Página de checkout para comprar un curso
- Verifica si el usuario ya tiene acceso
- Redirige al dashboard si ya está inscrito

---

### 4. **StudentProfile**
**Ubicación:** `app/Livewire/StudentProfile.php`  
**Ruta:** `/my-profile` (requiere autenticación)

**Funcionalidades:**
- Edición de perfil (nombre)
- Cambio de contraseña:
  - Validación de contraseña actual
  - Validación de nueva contraseña (mínimo 8 caracteres)
  - Confirmación de contraseña

**Métodos:**
- `updateProfile()` - Actualiza nombre
- `updatePassword()` - Cambia contraseña

---

### 5. **Login**
**Ubicación:** `app/Livewire/Auth/Login.php`  
**Ruta:** `/login` (solo para invitados)

**Funcionalidades:**
- Formulario de inicio de sesión
- Validación de credenciales
- Regeneración de sesión
- Redirección al dashboard después del login

---

## 🔒 Políticas de Autorización

### Sistema de Permisos
El proyecto usa **Filament Shield** con **Spatie Permission** para gestión de roles y permisos.

### Políticas Implementadas

#### 1. **CoursePolicy**
**Ubicación:** `app/Policies/CoursePolicy.php`

**Métodos:**
- `viewAny`, `view`, `create`, `update`, `delete`
- `restore`, `forceDelete`, `forceDeleteAny`, `restoreAny`
- `replicate`, `reorder`

Todos los métodos verifican permisos con formato: `{Action}:Course`

---

#### 2. **OrderPolicy**
**Ubicación:** `app/Policies/OrderPolicy.php`

**Métodos:**
- Mismos métodos que `CoursePolicy`
- Verifica permisos con formato: `{Action}:Order`

---

#### 3. **UserPolicy** y **RolePolicy**
**Ubicación:** `app/Policies/`

Políticas para gestión de usuarios y roles (implementadas por Filament Shield).

---

## 🗺️ Rutas

### Rutas Web (`routes/web.php`)

#### Públicas:
- `GET /` - Página de bienvenida

#### Solo Invitados:
- `GET /login` - Formulario de login (componente Livewire)

#### Autenticadas:
- `POST /logout` - Cerrar sesión
- `GET /my-courses` - Dashboard del estudiante
- `GET /my-profile` - Perfil del estudiante
- `GET /learn/{course:slug}/{lesson:slug?}` - Ver lección
- `GET /checkout/{course:slug}` - Checkout de curso

---

### Rutas API (`routes/api.php`)

- `GET /api/courses` - Listar cursos publicados
- `GET /api/courses/{course:slug}` - Obtener curso por slug

---

## 🗄️ Base de Datos

### Migraciones Principales

#### 1. **create_courses_table**
- Tabla principal de cursos
- Foreign key a `users` (teacher_id)
- Slug único
- Soft deletes

#### 2. **create_modules_table**
- Módulos de cursos
- Foreign key a `courses`
- Campo `sort_order` para ordenamiento
- Soft deletes

#### 3. **create_lessons_table**
- Lecciones de módulos
- Foreign key a `modules`
- Slug único
- Campos: `iframe_code`, `content`, `is_free`, `sort_order`
- Soft deletes

#### 4. **create_orders_table**
- Órdenes de compra
- Foreign key a `users` y `courses`
- Campos de pago: `status`, `payment_gateway`, `transaction_id`
- Sin soft deletes

#### 5. **create_course_user_table**
- Tabla pivot de inscripciones
- Foreign keys a `users` y `courses`
- Campos: `status`, `enrolled_at`, `expires_at`

#### 6. **create_lesson_user_table**
- Tabla pivot de progreso
- Clave primaria compuesta (`user_id`, `lesson_id`)
- Campos: `completed`, `completed_at`

#### 7. **create_reviews_table**
- Reseñas polimórficas
- Foreign key a `users`
- Campos polimórficos: `reviewable_id`, `reviewable_type`
- Soft deletes

#### 8. **create_permission_tables**
- Tablas de Spatie Permission (roles, permisos, etc.)

---

## 🌱 Seeders

### 1. **DatabaseSeeder**
**Ubicación:** `database/seeders/DatabaseSeeder.php`

Llama a:
- `UserFlowSeeder`

---

### 2. **UserFlowSeeder**
**Ubicación:** `database/seeders/UserFlowSeeder.php`

**Datos Creados:**
- **Usuario de prueba:**
  - Email: `user@test.com`
  - Password: `password`
  - Nombre: "Test User"

- **Instructor:**
  - Email: `teacher@test.com`
  - Password: `password`
  - Nombre: "Test Teacher"

- **Curso:**
  - Slug: `test-course`
  - Título: "Test Course"
  - Precio: 99.99
  - Status: Published
  - Instructor: Test Teacher

- **Módulo:**
  - Título: "Test Module"
  - Sort order: 1

- **Lecciones:**
  - 3 lecciones (Lesson 1, 2, 3)
  - Todas no gratuitas
  - Sort order: 1, 2, 3

- **Orden:**
  - Crea orden manual para el usuario de prueba
  - Inscribe automáticamente al usuario en el curso

**Uso:**
```bash
php artisan db:seed
```

---

## 🔧 Configuración y Características Técnicas

### Autenticación
- Sistema estándar de Laravel
- Middleware `auth` para rutas protegidas
- Middleware `guest` para rutas de login

### Almacenamiento
- Disco `public` para imágenes de cursos
- Directorio: `storage/app/public/courses`
- URLs públicas generadas automáticamente

### Soft Deletes
Implementado en:
- `Course`
- `Module`
- `Lesson`
- `Review`

### Ordenamiento
- Módulos y lecciones usan `sort_order` para ordenamiento
- Ordenamiento por defecto: ascendente

### URLs Amigables
- Cursos: `/learn/{course:slug}`
- Lecciones: `/learn/{course:slug}/{lesson:slug}`
- Checkout: `/checkout/{course:slug}`

### Validaciones
- Formularios Livewire con validación en tiempo real
- Validación de acceso antes de mostrar contenido
- Validación de permisos en políticas

---

## 📝 Notas de Implementación

### Características Destacadas

1. **Sistema de Progreso:**
   - Rastreo de lecciones completadas por usuario
   - Cálculo de progreso porcentual por curso
   - Visualización en dashboard del estudiante

2. **Gestión de Acceso:**
   - Verificación de inscripción activa
   - Soporte para fechas de expiración
   - Control de acceso a lecciones

3. **Panel Administrativo Completo:**
   - CRUD completo de cursos
   - Gestión anidada de módulos y lecciones
   - Gestión de órdenes
   - Sistema de roles y permisos

4. **API REST:**
   - Endpoints para listado y detalle de cursos
   - Transformación de datos con Resources
   - Solo cursos publicados visibles

5. **Experiencia de Usuario:**
   - Navegación entre lecciones
   - Marcar lecciones como completadas
   - Autoplay configurable
   - Dashboard con progreso visual

---

## 🚀 Comandos Útiles

### Desarrollo
```bash
# Instalar dependencias
composer install
pnpm install

# Configurar proyecto
php artisan key:generate
php artisan migrate

# Iniciar servidor de desarrollo
php artisan serve
pnpm run dev

# Ejecutar seeders
php artisan db:seed
```

### Producción
```bash
# Compilar assets
pnpm run build

# Optimizar Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Filament
```bash
# Crear usuario administrador
php artisan make:filament-user

# Generar permisos de Shield
php artisan shield:generate
```

---

## 📌 Funcionalidades Pendientes o Mejorables

### Posibles Mejoras Futuras:
1. **Pagos:**
   - Integración completa con Culqi
   - Webhooks de pago
   - Reembolsos

2. **Reseñas:**
   - Interfaz para crear reseñas
   - Visualización de reseñas en cursos
   - Sistema de calificaciones

3. **Notificaciones:**
   - Notificaciones de nuevos cursos
   - Recordatorios de lecciones pendientes
   - Notificaciones de progreso

4. **Certificados:**
   - Generación de certificados al completar cursos
   - Descarga de certificados

5. **Analíticas:**
   - Dashboard de estadísticas
   - Reportes de ventas
   - Métricas de progreso de estudiantes

6. **Contenido:**
   - Soporte para diferentes tipos de contenido
   - Descargas de recursos
   - Foros de discusión

---

## 🔍 Archivos Clave para Entender el Proyecto

1. **Modelos:** `app/Models/` - Estructura de datos
2. **Servicios:** `app/Services/` - Lógica de negocio
3. **Controladores API:** `app/Http/Controllers/Api/` - Endpoints REST
4. **Recursos Filament:** `app/Filament/Resources/` - Panel administrativo
5. **Componentes Livewire:** `app/Livewire/` - Interfaz interactiva
6. **Rutas:** `routes/` - Definición de endpoints
7. **Migraciones:** `database/migrations/` - Esquema de base de datos
8. **Seeders:** `database/seeders/` - Datos de prueba

---

## 📚 Convenciones de Código

- **TypeScript/Type Hints:** Uso estricto de tipos (declare(strict_types=1))
- **PSR-4:** Autoloading estándar
- **SOLID:** Principios aplicados en servicios
- **Documentación:** Funciones importantes documentadas
- **Naming:** Convenciones de Laravel (snake_case para DB, camelCase para código)

---

**Última actualización:** Enero 2026  
**Versión del Framework:** Laravel 12  
**Estado del Proyecto:** En desarrollo activo
