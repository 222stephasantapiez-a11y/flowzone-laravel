// ============================================================
// FlowZone — script.js
// ============================================================

// 1. Navbar scroll effect
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 80);
    }, { passive: true });
    // Trigger on load
    navbar.classList.toggle('scrolled', window.scrollY > 80);
}

// 2. Mobile nav toggle
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('open');
        navToggle.classList.toggle('open');
    });
    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
            navMenu.classList.remove('open');
            navToggle.classList.remove('open');
        }
    });
}

// 3. Animate on scroll (IntersectionObserver)
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
}

// 4. Admin sidebar mobile toggle
const adminToggle = document.getElementById('adminMenuToggle');
const adminSidebar = document.getElementById('adminSidebar');
if (adminToggle && adminSidebar) {
    adminToggle.addEventListener('click', () => adminSidebar.classList.toggle('open'));
    document.addEventListener('click', (e) => {
        if (!adminSidebar.contains(e.target) && !adminToggle.contains(e.target)) {
            adminSidebar.classList.remove('open');
        }
    });
}

// 5. Image preview (admin imagenes)
const fileInput = document.getElementById('imagen_file');
const previewImg = document.getElementById('preview-img');
if (fileInput && previewImg) {
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => { previewImg.src = ev.target.result; previewImg.style.display = 'block'; };
            reader.readAsDataURL(file);
        }
    });
}

const urlInput = document.getElementById('imagen_url');
if (urlInput && previewImg) {
    urlInput.addEventListener('input', (e) => {
        const url = e.target.value.trim();
        if (url) { previewImg.src = url; previewImg.style.display = 'block'; }
    });
}

// 6. Drag & drop reorder (admin imagenes)
const sortableList = document.getElementById('sortable-images');
if (sortableList) {
    let dragSrc = null;
    sortableList.querySelectorAll('[draggable]').forEach(item => {
        item.addEventListener('dragstart', (e) => {
            dragSrc = item;
            e.dataTransfer.effectAllowed = 'move';
            item.style.opacity = '0.5';
        });
        item.addEventListener('dragend', () => { item.style.opacity = '1'; });
        item.addEventListener('dragover', (e) => { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; });
        item.addEventListener('drop', (e) => {
            e.preventDefault();
            if (dragSrc !== item) {
                const allItems = [...sortableList.querySelectorAll('[draggable]')];
                const srcIdx = allItems.indexOf(dragSrc);
                const tgtIdx = allItems.indexOf(item);
                if (srcIdx < tgtIdx) item.after(dragSrc);
                else item.before(dragSrc);
                // Send new order
                const ids = [...sortableList.querySelectorAll('[data-id]')].map(el => el.dataset.id);
                fetch('/admin/imagenes/orden', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                    body: JSON.stringify({ ids })
                });
            }
        });
    });
}

// 7. Star rating
document.querySelectorAll('.stars .star').forEach(star => {
    star.addEventListener('click', () => {
        const val = star.dataset.value;
        const container = star.closest('.stars');
        container.querySelectorAll('.star').forEach(s => {
            s.style.color = s.dataset.value <= val ? 'var(--gold-500)' : 'var(--gray-200)';
        });
        const input = container.nextElementSibling;
        if (input && input.type === 'hidden') input.value = val;
    });
});
