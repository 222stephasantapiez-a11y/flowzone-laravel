<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas - FlowZone Admin</title>
      <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2> FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="dashboardphp">📊 Dashboard</a>
            <a href="admin_empresasphp" class="active">
                🏢 Empresas
           
                    <span class="admin-notif-badge"></span>
              
            </a>
            <a href="admin_lugaresphp">📍 Lugares</a>
            <a href="{{ route('admin.hoteles.index') }}">🏨 Hoteles</a>
            <a href="admin_eventosphp">📅 Eventos</a>
            <a href="admin_reservasphp">📋 Reservas</a>
            <a href="logoutphp">🚪 Cerrar Sesión</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>🏢 Gestión de Empresas</h1>
        </div>

        
            <div class="alert alert"></div>
       

        <!-- Notificaciones pendientes -->
        <div class="admin-section">
            <h2>
                🔔 Notificaciones sin leer
                <php if ($notif_count > 0): ?>
                    <span class="badge badge-pendiente" style="font-size:0.9rem;margin-left:0.5rem;"></span>
                    <form method="POST" style="display:inline;margin-left:1rem;">
                        <input type="hidden" name="accion" value="marcar_todas_leidas">
                        <button class="btn-small btn-edit" type="submit">Marcar todas como leídas</button>
                    </form>
               
            </h2>

            <!-- <php if (empty($notificaciones)): ?> -->
                <p style="color:var(--gray)">No hay notificaciones pendientes. ✅</p>
            <php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>#</th><th>Empresa</th><th>Mensaje</th><th>Fecha</th><th>Acción</th></tr></thead>
                        <tbody>
                        <!-- <php foreach ($notificaciones as $n): ?> -->
                            <!-- <tr>
                                <td><?= (int)$n['id'] ?></td>
                                <td><?= htmlspecialchars($n['empresa_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($n['mensaje'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars(substr($n['creado_en'], 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <form method="POST" style="display:inline">
                                        <input type="hidden" name="accion"   value="marcar_leida">
                                        <input type="hidden" name="notif_id" value="<?= (int)$n['id'] ?>">
                                        <button class="btn-small btn-success" type="submit">✓ Leída</button>
                                    </form>
                                </td>
                            </tr>
                        <php endforeach; ?> -->
                        </tbody>
                    </table>
                </div>
           
        </div>

        <!-- Listado de empresas -->
        <!-- <div class="admin-section">
            <h2>Empresas registradas (<?= count($empresas) ?>)</h2>
            <php if (empty($empresas)): ?>
                <p style="color:var(--gray)">No hay empresas registradas aún.</p>
            <php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Empresa</th><th>Responsable</th><th>Correo</th><th>Teléfono</th><th>Estado usuario</th><th>Aprobada</th><th>Registro</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                        <php foreach ($empresas as $e): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($e['empresa_nombre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td><?= htmlspecialchars($e['usuario_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($e['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($e['telefono'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span class="badge badge-<?= $e['estado'] ?>"><?= $e['estado'] ?></span></td>
                                <td>
                                    <php if ($e['aprobado']): ?>
                                        <span class="badge badge-aprobado">Sí</span>
                                    <php else: ?>
                                        <span class="badge badge-pendiente">Pendiente</span>
                                   
                                </td>
                                <td><?= date('d/m/Y', strtotime($e['empresa_creada'])) ?></td>
                                <td>
                                    <php if (!$e['aprobado']): ?>
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="accion"     value="aprobar_empresa">
                                            <input type="hidden" name="empresa_id" value="<?= (int)$e['id'] ?>">
                                            <button class="btn-small btn-success" type="submit">✓ Aprobar</button>
                                        </form>
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="accion"     value="rechazar_empresa">
                                            <input type="hidden" name="empresa_id" value="<?= (int)$e['id'] ?>">
                                            <button class="btn-small btn-delete" type="submit"
                                                    onclick="return confirm('¿Rechazar esta empresa?')">✕ Rechazar</button>
                                        </form>
                                    <php elseif ($e['estado'] === 'activo'): ?>
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="accion"     value="bloquear_usuario">
                                            <input type="hidden" name="usuario_id" value="<?= (int)$e['usuario_id'] ?>">
                                            <button class="btn-small btn-warning" type="submit"
                                                    onclick="return confirm('¿Bloquear este usuario?')">Bloquear</button>
                                        </form>
                                    <php elseif ($e['estado'] === 'bloqueado'): ?>
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="accion"     value="activar_usuario">
                                            <input type="hidden" name="usuario_id" value="<?= (int)$e['usuario_id'] ?>">
                                            <button class="btn-small btn-success" type="submit">Reactivar</button>
                                        </form>
                                   
                                </td>
                            </tr>
                        <php endforeach; ?>
                        </tbody>
                    </table>
                </div>
           
        </div>
    </main>
</div>
</body>
</html> -->
