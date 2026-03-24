<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-brand"><h2>🌄 FlowZone Admin</h2></div>
            <nav class="admin-nav">
                <a href="dashboardphp">📊 Dashboard</a>
                <a href="admin_lugaresphp">📍 Lugares</a>
                <a href="admin_hotelesphp">🏨 Hoteles</a>
                <a href="admin_eventosphp">📅 Eventos</a>
                <a href="admin_reservasphp" class="active">📋 Reservas</a>
                <a href="logoutphp">🚪 Cerrar Sesión</a>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header"><h1>Gestión de Reservas</h1></div>
       
                <div class="alert alert-success"></div>
         
            
            <div class="admin-section">
                <h2>Todas las Reservas</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Hotel</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Personas</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
