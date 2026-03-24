<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Empresa - FlowZone</title>
    <!-- <link rel="stylesheet" href="/FLOWZONE/assets/css/style.css"> -->
</head>
<body>
<section class="page-header">
    <div class="container">
        <h1>🏢 Panel de Empresa</h1>
        <p>Gestiona tu información y comunícate con el administrador</p>
    </div>
</section>

<div class="container section">
    <div style="max-width:800px;margin:0 auto;">

     
            <div class="alert alert-" style="margin-bottom:1.5rem;">
         
            </div>
        



            <!-- Info empresa -->
            <div style="background:#fff;border-radius:12px;padding:2rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="color:var(--primary);margin-bottom:1.2rem;font-size:1.3rem;">📋 Información registrada</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                   
                    <div style="background:#f8f8f6;border-radius:8px;padding:0.9rem 1rem;">
                        <div style="font-size:0.72rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;"></div>
                        <div style="font-weight:600;color:#1a1a1a;font-size:0.9rem;"></div>
                    </div>
               
                    <div style="background:#f8f8f6;border-radius:8px;padding:0.9rem 1rem;">
                        <div style="font-size:0.72rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Estado</div>
                        <span class="badge badge-"></span>
                    </div>
                    <div style="background:#f8f8f6;border-radius:8px;padding:0.9rem 1rem;">
                        <div style="font-size:0.72rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Aprobada</div>
                        <span class="badge ">
                     
                        </span>
                    </div>
                </div>
            </div>

            <!-- Solicitar cambio -->
            <div style="background:#fff;border-radius:12px;padding:2rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="color:var(--primary);margin-bottom:0.5rem;font-size:1.3rem;">📨 Enviar solicitud al administrador</h2>
                <p style="color:#666;font-size:0.9rem;margin-bottom:1.2rem;">Solicita cambios en tus datos, reporta novedades o pide información.</p>
                <form method="POST">
                    <input type="hidden" name="solicitud" value="1">
                    <div class="form-group">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required maxlength="1000"
                                  style="width:100%;padding:0.8rem;border:1px solid #ddd;border-radius:8px;font-family:inherit;font-size:0.95rem;resize:vertical;"
                                  placeholder="Ej: Necesito actualizar el número de teléfono a 3201234567..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                </form>
            </div>

            <!-- Historial -->
          
            <div style="background:#fff;border-radius:12px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="color:var(--primary);margin-bottom:1.2rem;font-size:1.3rem;">📂 Historial de solicitudes</h2>
                <table style="width:100%;border-collapse:collapse;font-size:0.88rem;">
                    <thead>
                        <tr style="background:#f4f4f4;">
                            <th style="padding:0.7rem 1rem;text-align:left;font-weight:600;">Mensaje</th>
                            <th style="padding:0.7rem 1rem;text-align:left;font-weight:600;white-space:nowrap;">Fecha</th>
                            <th style="padding:0.7rem 1rem;text-align:left;font-weight:600;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                        <tr style="border-bottom:1px solid #f0f0f0;">
                            <td style="padding:0.7rem 1rem;"></td>
                            <td style="padding:0.7rem 1rem;white-space:nowrap;color:#666;"></td>
                            <td style="padding:0.7rem 1rem;">
                                <span class="badge ">
                               
                                </span>
                            </td>
                        </tr>
              
                    </tbody>
                </table>
            </div>



            <div class="alert alert-error">
                No se encontraron datos de empresa asociados a tu cuenta.
                Contacta al administrador en <a href="/contactophp">esta página</a>.
            </div>
      

    </div>
</div>

