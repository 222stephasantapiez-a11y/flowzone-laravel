
<section class="container section">
    <div class="detalle-content">
        <div class="detalle-main">
            <div class="detalle-info">
                <h2>Descripción</h2>
                <p></p>
                
                <div class="info-grid">
                    <div class="info-item">
                        <strong>📍 Ubicación:</strong>
                        <p></p>
                    </div>
                    
            
                        <div class="info-item">
                            <strong>🕐 Horario:</strong>
                            <p></p>
                        </div>
              
                    
                    <div class="info-item">
                        <strong>💵 Entrada:</strong>
                        <p></p>
                    </div>
                </div>
                
    
                    <div class="mapa">
                        <h3>Ubicación en el mapa</h3>
                        <iframe 
                            width="100%" 
                            height="400" 
                            frameborder="0" 
                            style="border:0" 
                            src="https://www.google.com/maps?q=&output=embed"
                            allowfullscreen>
                        </iframe>
                    </div>
            
            </div>
            
            <div class="comentarios-section">
                <h2>Comentarios</h2>
                
         
                    <form id="form-comentario" class="comentario-form">
                        <input type="hidden" name="lugar_id" value="">
                        <textarea name="comentario" placeholder="Escribe tu comentario..." required></textarea>
                        <button type="submit" class="btn btn-primary">Publicar Comentario</button>
                    </form>
                    <p class="login-prompt">
                        <a href="loginphp">Inicia sesión</a> para dejar un comentario
                    </p>
        
                
                <div id="lista-comentarios">
                        <div class="comentario">
                            <div class="comentario-header">
                                <strong></strong>
                                <span class="fecha"></span>
                            </div>
                            <p></p>
                        </div>
        
                </div>
            </div>
        </div>
        
        <div class="detalle-sidebar">
            <div class="calificacion-box">
                <h3>Calificación</h3>
                <div class="rating-display">
                    <span class="rating-number"></span>
                    <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                    <span class="rating-count">()</span>
                </div>
                
             
                    <div class="rating-form">
                        <p>Tu calificación:</p>
                        <div class="stars" data-tipo="lugar" data-id="">
                            <span class="star" data-value="1">⭐</span>
                            <span class="star" data-value="2">⭐</span>
                            <span class="star" data-value="3">⭐</span>
                            <span class="star" data-value="4">⭐</span>
                            <span class="star" data-value="5">⭐</span>
                        </div>
                    </div>
              </div>
            
       
                <button class="btn btn-favorito " 
                        data-tipo="lugar" 
                        data-id="">
                  
                </button>
        </div>
    </div>
</section>
