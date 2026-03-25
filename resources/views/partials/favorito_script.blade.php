@auth
<script>
document.querySelectorAll('.btn-favorito').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var tipo = this.dataset.tipo;
        var id   = this.dataset.id;
        var self = this;

        fetch('{{ route('favoritos.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ tipo: tipo, item_id: parseInt(id) }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var icon  = self.querySelector('i');
            var label = self.querySelector('.btn-fav-label');
            if (data.agregado) {
                self.classList.add('active');
                if (icon)  icon.className  = 'fa-solid fa-heart';
                if (label) label.textContent = 'En Favoritos';
                self.setAttribute('aria-label', 'Quitar de favoritos');
            } else {
                self.classList.remove('active');
                if (icon)  icon.className  = 'fa-regular fa-heart';
                if (label) label.textContent = 'Agregar a Favoritos';
                self.setAttribute('aria-label', 'Agregar a favoritos');
            }
        })
        .catch(function() {
            window.location.href = '{{ route('login') }}';
        });
    });
});
</script>
@endauth
