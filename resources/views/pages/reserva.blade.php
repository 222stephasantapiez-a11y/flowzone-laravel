<section class="page-header">
    <div class="container">
        <h1>Reservar Hotel</h1>
        <p><?php echo htmlspecialchars($hotel['nombre']); ?></p>
    </div>
</section>

<section class="container section">
    <div class="reserva-container">
        <div class="hotel-info-card">
            <img src="<?php echo htmlspecialchars($hotel['imagen']); ?>" alt="<?php echo htmlspecialchars($hotel['nombre']); ?>">
            <h3><?php echo htmlspecialchars($hotel['nombre']); ?></h3>
            <p class="precio">$<?php echo number_format($hotel['precio'], 0, ',', '.'); ?> COP / noche</p>
            <p>📍 <?php echo htmlspecialchars($hotel['ubicacion']); ?></p>
            <p>👥 Capacidad: <?php echo $hotel['capacidad']; ?> personas</p>
        </div>
        
        <div class="reserva-form-card">
            <h2>Datos de la Reserva</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <a href="/FLOWZONE/hoteles.php" class="btn btn-primary">Ver más hoteles</a>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de Entrada</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_salida">Fecha de Salida</label>
                        <input type="date" id="fecha_salida" name="fecha_salida" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="num_personas">Número de Personas</label>
                        <input type="number" id="num_personas" name="num_personas" required min="1" max="<?php echo $hotel['capacidad']; ?>">
                    </div>
                    
                    <div id="precio-calculado" class="precio-calculado"></div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Confirmar Reserva</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>