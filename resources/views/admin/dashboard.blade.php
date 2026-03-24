<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - FlowZone</title>
   <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2>FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="admindashboardphp" class="active">📊 Dashboard</a>
            <a href="admin_empresasphp">
                🏢 Empresas
              
                    <span class="admin-notif-badge"></span>
            </a>
            <a href="admin_lugaresphp">📍 Lugares</a>
            <a href="admin_hotelesphp">🏨 Hoteles</a>
            <a href="admin_eventosphp">📅 Eventos</a>
            <a href="reservasphp">📋 Reservas</a>
            <a href="logoutphp">🚪 Cerrar Sesión</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Dashboard</h1>
                <p>Bienvenido, </p>
            </div>

                <a href="admin_empresasphp" class="btn btn-primary">
                    🔔
                </a>
       
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info"><h3></h3><p>Usuarios</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏢</div>
                <div class="stat-info">
                    <h3></h3>
                    <p>Empresas <span style="color:var(--danger);font-size:0.8rem">( pend.)</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📍</div>
                <div class="stat-info"><h3></h3><p>Lugares</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏨</div>
                <div class="stat-info"><h3></h3><p>Hoteles</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-info"><h3></h3><p>Reservas Pend.</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💬</div>
                <div class="stat-info"><h3></h3><p>Comentarios</p></div>
            </div>
        </div>

  
        <div class="admin-section">
            <h2>🔔 Notificaciones recientes <a href="admin_empresasphp" style="font-size:0.85rem;font-weight:normal;margin-left:1rem;">Ver todas →</a></h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Empresa</th><th>Mensaje</th><th>Fecha</th></tr></thead>
                    <tbody>
                  
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
             
                    </tbody>
                </table>
            </div>
        </div>
     

        <div class="admin-section">
            <h2>Últimas Reservas</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr><th>ID</th><th>Usuario</th><th>Hotel</th><th>Entrada</th><th>Salida</th><th>Personas</th><th>Total</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                 
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
