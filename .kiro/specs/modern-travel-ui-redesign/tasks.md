# Plan de Implementación: modern-travel-ui-redesign

## Resumen

Rediseño visual completo de la plataforma FlowZone usando Laravel Blade + CSS puro.
El trabajo es exclusivamente de capa de presentación: CSS, layouts, partials y vistas.
Los modelos, controladores y rutas existentes no se modifican.

## Tareas

- [ ] 1. Reescribir `public/css/style.css` con el nuevo sistema de diseño completo
  - Reemplazar el archivo completo con las variables CSS (design tokens) definidas en el diseño
  - Incluir: `:root` con colores, tipografía, espaciado, sombras y transiciones
  - Incluir: reset/base, utilidades (`.container`, `.section`, `.glass`, `.animate-on-scroll`)
  - Incluir: componente `.navbar` con estados `transparent`, `.scrolled` y `body.no-hero`
  - Incluir: componente `.hero` con overlay, search bar glassmorphism, badges, float-cards y scroll indicator
  - Incluir: componente `.card`, `.card-img-wrap`, `.card-badge`, `.card-content`, `.card-actions`
  - Incluir: variantes `.blog-card`, `.experience-card`, `.experience-grid`
  - Incluir: todos los estilos de botones (`.btn`, `.btn-primary`, `.btn-outline`, `.btn-white`, `.btn-glass`, `.btn-secondary`, `.btn-danger`, tamaños)
  - Incluir: `.stats-strip` y `.stats-strip-item` para la sección de estadísticas del home
  - Incluir: layout admin completo (`.admin-layout`, `.admin-sidebar`, `.admin-brand`, `.admin-nav`, `.admin-wrapper`, `.admin-topbar`, `.admin-main-inner`)
  - Incluir: `.stat-card` con variantes de color (`.green`, `.blue`, `.orange`, `.purple`, `.teal`, `.red`)
  - Incluir: `.admin-section`, `.admin-table` con estilos de filas y hover
  - Incluir: `.footer` con grid 4 columnas, `.footer-social`, `.footer-section`, `.footer-bottom`
  - Incluir: animaciones (`@keyframes fadeInUp`, `@keyframes bounce`, `.animate-on-scroll`, `.fade-in`)
  - Incluir: media queries responsive para 1024px, 768px y 480px en todos los componentes
  - Mantener alias legacy: `--primary`, `--secondary`, `--accent`, `--dark`, `--light`, `--gray`
  - _Requisitos: Propiedad 1 (compatibilidad de variables), Propiedad 4 (responsive breakpoints), Propiedad 6 (parallax móvil)_

- [ ] 2. Actualizar layouts Blade
  - [ ] 2.1 Actualizar `resources/views/layouts/app.blade.php`
    - Agregar Google Fonts: Inter (300–900) + Playfair Display (700, 800, 900)
    - Agregar FontAwesome CDN si no está presente
    - Asegurar que el `<body>` soporte `@yield('body-class')` para la clase `no-hero`
    - Incluir `partials.header` y `partials.footer`
    - Incluir `public/js/script.js` antes del cierre de `</body>`
    - Agregar `@stack('scripts')` y `@stack('head')`
    - _Requisitos: Propiedad 2 (navbar transparente solo en hero)_

  - [ ] 2.2 Actualizar `resources/views/layouts/admin.blade.php`
    - Implementar estructura `.admin-layout` > `.admin-sidebar#adminSidebar` + `.admin-wrapper`
    - Sidebar: `.admin-brand` (icono + "FlowZone" + rol "Administrador") + `.admin-nav` con secciones etiquetadas (PRINCIPAL, GESTIÓN, CONTENIDO) + `.sidebar-footer`
    - Topbar: botón hamburger `#adminMenuToggle` (mobile) + `@yield('page-title')` + acciones + usuario
    - Incluir `@yield('content')` dentro de `.admin-main-inner`
    - Mostrar alertas de sesión (`success`, `error`) con estilos de alert
    - _Requisitos: Propiedad 4 (responsive sidebar)_

  - [ ] 2.3 Crear `resources/views/layouts/empresa.blade.php`
    - Mismo patrón que `admin.blade.php` pero sidebar simplificado: solo Dashboard, Blog, Gastronomía
    - Badge de rol: "Panel Empresa"
    - Color de acento `--green-600` en lugar de `--green-800` para los links activos
    - _Requisitos: Propiedad 4 (responsive sidebar)_

- [ ] 3. Actualizar partials compartidos
  - [ ] 3.1 Actualizar `resources/views/partials/header.blade.php`
    - Implementar `<nav class="navbar" id="navbar">` con estructura `.navbar-inner`
    - Brand: icono `fa-mountain-sun` + "FlowZone" con clase `.nav-brand`
    - Menú: Lugares, Hoteles, Eventos, Gastronomía, Blog con clase `.nav-menu`
    - Acciones: botones de auth (login/registro) o menú de usuario si está autenticado
    - Botón hamburger `#navToggle` con tres `<span>` para mobile
    - Marcar link activo con clase `active` usando `request()->is()`
    - _Requisitos: Propiedad 2 (navbar transparente/scrolled)_

  - [ ] 3.2 Actualizar `resources/views/partials/footer.blade.php`
    - Implementar `.footer` con grid 4 columnas: brand + 3 secciones de links
    - Columna brand: logo, descripción, iconos sociales (`.footer-social`)
    - Columnas de links: Explorar, Servicios, Contacto con iconos FontAwesome
    - `.footer-bottom` con copyright y links legales
    - _Requisitos: Propiedad 4 (responsive footer)_

- [ ] 4. Implementar `public/js/script.js`
  - Navbar scroll effect: `window.addEventListener('scroll')` → toggle clase `.scrolled` en `#navbar` cuando `scrollY > 80`
  - Mobile nav toggle: click en `#navToggle` → toggle clase `.open` en `.nav-menu` y en el botón
  - IntersectionObserver para `.animate-on-scroll`: agregar clase `.visible` al entrar en viewport, `threshold: 0.12`, `rootMargin: '0px 0px -40px 0px'`, `unobserve` tras activar
  - Admin sidebar mobile toggle: click en `#adminMenuToggle` → toggle `.open` en `#adminSidebar`; click fuera → remover `.open`
  - _Requisitos: Propiedad 2 (navbar scroll), Propiedad 4 (sidebar mobile)_

- [ ] 5. Checkpoint — Verificar base visual
  - Asegurar que todos los tests pasen y que el CSS cargue sin errores. Consultar al usuario si hay dudas.

- [ ] 6. Implementar `resources/views/pages/home.blade.php` como landing page completa
  - Extender `layouts.app`, `@section('body-class', '')` (sin `no-hero`)
  - Sección hero: `<section class="hero">` con `style="background-image: url(...)"` cargando imagen desde `HeroImage::activas()->seccion('hero')->first()` o degradado verde como fallback
  - Dentro del hero: `.hero-overlay`, `.hero-content` con eyebrow badge, `<h1>` con `<span>` dorado, párrafo, `.hero-search` (input + select categorías + botón), `.hero-badges`
  - `.hero-float-cards` con 2–3 tarjetas glassmorphism (ej: "Lugares únicos", "Hoteles top")
  - `.hero-scroll` con indicador animado
  - Sección `.stats-strip` con 4 estadísticas (lugares, hoteles, eventos, visitantes)
  - Sección "Destinos Destacados": `.section` con `.section-header` + `.grid` de cards de lugares (máx 6, con `.animate-on-scroll`)
  - Sección "Experiencias": `.experience-grid` asimétrico con 3 cards (una `.tall`)
  - Sección "Hoteles Recomendados": grid de cards de hoteles
  - Sección "Próximos Eventos": grid de cards de eventos
  - Sección "Gastronomía Local": grid de cards
  - Sección "Últimas del Blog": `.blog-grid` con blog cards
  - CTA final con fondo verde y botones `.btn-white` y `.btn-glass`
  - _Requisitos: Propiedad 3 (imagen hero desde DB con fallback)_

- [ ] 7. Implementar vistas de listado públicas
  - [ ] 7.1 Actualizar `resources/views/pages/hoteles.blade.php`
    - Extender `layouts.app`, `@section('body-class', 'no-hero')`
    - Hero mini (sin full-screen): banner con título y breadcrumb sobre fondo verde
    - Grid de cards de hoteles con `.animate-on-scroll`, badge de categoría, botones "Detalles" y "Reservar"
    - _Requisitos: Propiedad 2 (navbar sólida en no-hero)_

  - [ ] 7.2 Actualizar `resources/views/pages/lugares.blade.php`
    - Mismo patrón que hoteles: hero mini + grid de cards de lugares
    - _Requisitos: Propiedad 2_

  - [ ] 7.3 Actualizar `resources/views/pages/eventos.blade.php`
    - Hero mini + grid de cards de eventos con fecha y badge de tipo
    - _Requisitos: Propiedad 2_

  - [ ] 7.4 Actualizar `resources/views/pages/gastronomia.blade.php`
    - Hero mini + grid de cards de gastronomía con badge de tipo de cocina
    - _Requisitos: Propiedad 2_

  - [ ] 7.5 Actualizar `resources/views/pages/blog.blade.php`
    - Hero mini + `.blog-grid` con blog cards horizontales
    - _Requisitos: Propiedad 2_

- [ ] 8. Implementar vistas de detalle públicas
  - [ ] 8.1 Actualizar `resources/views/pages/detalle_hotel.blade.php`
    - Hero con imagen del hotel (full-width, min-height 60vh) + overlay + título + badges
    - Sección de contenido: descripción, galería de imágenes, mapa/ubicación, botón de reserva
    - Sección de calificaciones/comentarios
    - _Requisitos: Propiedad 5 (accesibilidad: alt en imágenes)_

  - [ ] 8.2 Actualizar `resources/views/pages/detalle_lugar.blade.php`
    - Mismo patrón que detalle_hotel adaptado para lugares
    - _Requisitos: Propiedad 5_

  - [ ] 8.3 Actualizar `resources/views/pages/blog_post.blade.php`
    - Hero con imagen del post + overlay + título + meta (autor, fecha, categoría)
    - Contenido del artículo con tipografía legible (Inter, line-height 1.8)
    - Sidebar con posts relacionados
    - _Requisitos: Propiedad 5_

- [ ] 9. Implementar vistas de usuario
  - [ ] 9.1 Actualizar `resources/views/pages/reserva.blade.php`
    - `body.no-hero`, formulario de reserva centrado con `.admin-section` style
    - Inputs con `border-radius: var(--radius-md)`, botón `.btn-primary .btn-lg .btn-block`
    - _Requisitos: Propiedad 1 (clases legacy)_

  - [ ] 9.2 Actualizar `resources/views/pages/mis_reservas.blade.php`
    - Listado de reservas del usuario con tabla o cards, badges de estado (`.estado-*`)
    - _Requisitos: Propiedad 1_

  - [ ] 9.3 Actualizar `resources/views/pages/favoritos.blade.php`
    - Grid de cards de favoritos con botón para eliminar
    - _Requisitos: Propiedad 1_

  - [ ] 9.4 Actualizar `resources/views/pages/contacto.blade.php`
    - Formulario de contacto con diseño limpio, hero mini, mapa o info de contacto lateral
    - _Requisitos: Propiedad 5 (accesibilidad: labels en inputs)_

- [ ] 10. Checkpoint — Verificar vistas públicas
  - Asegurar que todas las vistas públicas carguen correctamente con el nuevo CSS. Consultar al usuario si hay dudas.

- [ ] 11. Implementar panel admin
  - [ ] 11.1 Actualizar `resources/views/admin/dashboard.blade.php`
    - Extender `layouts.admin`, `@section('page-title', 'Dashboard')`
    - `.stats-grid` con 6 stat-cards (hoteles, lugares, eventos, gastronomía, reservas, empresas) con colores diferenciados
    - Sección de actividad reciente: tabla de últimas reservas
    - Sección de notificaciones admin con badge de conteo
    - _Requisitos: Propiedad 1_

  - [ ] 11.2 Actualizar `resources/views/admin/imagenes.blade.php`
    - Galería mejorada: grid de thumbnails grandes con indicador visual activo/inactivo superpuesto
    - Drag handle visible en cada imagen para reordenar
    - Formulario de subida dual (archivo o URL) con preview en tiempo real
    - Mini-preview de cómo se ve la imagen activa en el hero
    - Botones de toggle activa/inactiva y eliminar sobre cada thumbnail
    - _Requisitos: Propiedad 3 (imagen hero desde DB)_

  - [ ] 11.3 Actualizar `resources/views/admin/hoteles.blade.php`
    - `.admin-section` con topbar (título + botón "Nuevo Hotel")
    - `.admin-table` con columnas: imagen thumb, nombre, categoría, estado, acciones
    - Modal o formulario inline para crear/editar
    - _Requisitos: Propiedad 1_

  - [ ] 11.4 Actualizar `resources/views/admin/lugares.blade.php`
    - Mismo patrón que admin/hoteles adaptado para lugares
    - _Requisitos: Propiedad 1_

  - [ ] 11.5 Actualizar `resources/views/admin/eventos.blade.php`
    - Mismo patrón con columna de fecha del evento
    - _Requisitos: Propiedad 1_

  - [ ] 11.6 Actualizar `resources/views/admin/gastronomia.blade.php`
    - Mismo patrón con columna de empresa asociada
    - _Requisitos: Propiedad 1_

  - [ ] 11.7 Actualizar `resources/views/admin/blog.blade.php`
    - Tabla con columnas: título, autor, fecha, estado publicación, acciones
    - _Requisitos: Propiedad 1_

  - [ ] 11.8 Actualizar `resources/views/admin/reservas.blade.php`
    - Tabla con columnas: usuario, tipo, fecha, estado con badges de color (`.estado-*`)
    - _Requisitos: Propiedad 1_

  - [ ] 11.9 Actualizar `resources/views/admin/empresas.blade.php`
    - Tabla con columnas: nombre empresa, email, estado, acciones
    - _Requisitos: Propiedad 1_

- [ ] 12. Implementar panel empresa
  - [ ] 12.1 Actualizar `resources/views/empresa/dashboard.blade.php`
    - Extender `layouts.empresa`
    - Stats cards de blog posts y platos de gastronomía propios
    - _Requisitos: Propiedad 1_

  - [ ] 12.2 Actualizar `resources/views/empresa/blog.blade.php`
    - Tabla de posts del blog de la empresa con acciones CRUD
    - _Requisitos: Propiedad 1_

  - [ ] 12.3 Actualizar `resources/views/empresa/gastronomia.blade.php`
    - Tabla de platos/restaurantes de la empresa con acciones CRUD
    - _Requisitos: Propiedad 1_

- [ ] 13. Actualizar vistas de autenticación
  - [ ] 13.1 Actualizar `resources/views/auth/login.blade.php`
    - Layout sin navbar/footer (extender layout base mínimo o inline)
    - Pantalla dividida: imagen de fondo verde a la izquierda, formulario a la derecha
    - Formulario con inputs estilizados, botón `.btn-primary .btn-block`, link a registro
    - _Requisitos: Propiedad 5 (accesibilidad: labels)_

  - [ ] 13.2 Actualizar `resources/views/auth/registro.blade.php`
    - Mismo patrón que login con campos adicionales (nombre, email, password, confirmación)
    - _Requisitos: Propiedad 5_

- [ ] 14. Checkpoint final — Verificar consistencia visual completa
  - Asegurar que todos los paneles (público, admin, empresa, auth) usen el sistema de diseño de forma consistente. Verificar que las propiedades de corrección 1–6 se cumplan. Consultar al usuario si hay dudas.

## Notas

- Las tareas marcadas con `*` son opcionales y pueden omitirse para un MVP más rápido
- Cada tarea referencia propiedades de corrección del documento de diseño para trazabilidad
- El orden de las tareas garantiza que el CSS base esté listo antes de modificar cualquier vista
- Los modelos, controladores y rutas existentes NO se modifican en ninguna tarea
- Las clases legacy (`--primary`, `--secondary`, `.btn`, `.card`, `.grid`, `.admin-table`, `.badge-*`, `.estado-*`) deben mantenerse funcionales en todo momento
