// public/js/app.js

document.addEventListener('DOMContentLoaded', function () {
    // --- Elemen DOM ---
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggleTop = document.getElementById('sidebarToggleTop');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const installPWAButton = document.getElementById('installPWA');
    const installPWABtn = document.getElementById('installPWABtn');
    const pwaInstructions = document.getElementById('pwaInstructions');
    const pwaInstallModal = document.getElementById('pwaInstallModal');
    const installPWAButtonModal = document.getElementById('installPWAButton');
    const scannerFingerprintModal = document.getElementById('scannerFingerprintModal');

    // --- Sidebar Toggle Functionality ---
    if (sidebarToggleTop && sidebar) {
        sidebarToggleTop.addEventListener('click', function (e) {
            e.stopPropagation(); // Mencegah event bubbling
            sidebar.classList.toggle('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('show');
            }
            // Tambahkan/ hapus class 'collapsed' pada body atau elemen lain jika diperlukan
            // document.body.classList.toggle('sidebar-toggled');
        });
    }

    // --- Close Sidebar when clicking overlay ---
    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            // document.body.classList.remove('sidebar-toggled');
        });
    }

    // --- Theme Toggle Functionality ---
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-bs-theme', initialTheme);
    updateThemeIcon(initialTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);

            // Tambahkan animasi kecil pada tombol saat di-klik
            themeToggle.style.transform = 'rotate(0deg)';
            setTimeout(() => {
                themeToggle.style.transition = 'transform 0.5s ease';
                themeToggle.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    themeToggle.style.transition = '';
                    themeToggle.style.transform = '';
                }, 500);
            }, 10);
        });
    }

    function updateThemeIcon(theme) {
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.classList.remove('bi-moon-stars');
                themeIcon.classList.add('bi-brightness-high');
                themeIcon.title = 'Switch to Light Mode';
            } else {
                themeIcon.classList.remove('bi-brightness-high');
                themeIcon.classList.add('bi-moon-stars');
                themeIcon.title = 'Switch to Dark Mode';
            }
        }
    }

    // --- Initialize Tooltips (Bootstrap) ---
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            // Opsi tooltip tambahan bisa ditambahkan di sini
        });
    });

    // --- Auto-hide Alerts ---
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
    }, 5000);

    // --- PWA Integration ---
    let deferredPrompt;
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered: ', registration);
                })
                .catch(function(registrationError) {
                    console.log('Service Worker registration failed: ', registrationError);
                });
        });
    }

    // PWA Install Prompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (installPWABtn) {
            installPWABtn.classList.remove('d-none');
        }
        if (!/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            if (installPWABtn) {
                installPWABtn.classList.add('desktop');
            }
            setTimeout(() => {
                if (installPWABtn && !installPWABtn.classList.contains('d-none')) {
                    if (pwaInstructions) {
                        pwaInstructions.style.display = 'block';
                    }
                }
            }, 3000);
        }
    });

    // --- PWA Install Button (Floating) Click Handler ---
    if (installPWAButton) {
        installPWAButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (pwaInstructions) {
                pwaInstructions.style.display = 'none';
            }
            if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            } else {
                // Untuk desktop, tampilkan instruksi atau modal
                if (pwaInstructions) {
                    pwaInstructions.style.display = 'block';
                }
                // Atau, tampilkan modal
                // if (pwaInstallModal) {
                //     const modal = new bootstrap.Modal(pwaInstallModal);
                //     modal.show();
                // }
            }
        });
    }

    // --- PWA Install Button (Modal) Click Handler ---
    if (installPWAButtonModal) {
        installPWAButtonModal.addEventListener('click', (e) => {
            e.preventDefault();
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt via modal');
                        // Sembunyikan modal setelah install
                        if (pwaInstallModal) {
                            const modal = bootstrap.Modal.getInstance(pwaInstallModal);
                            if (modal) {
                                modal.hide();
                            }
                        }
                    } else {
                        console.log('User dismissed the A2HS prompt via modal');
                    }
                    deferredPrompt = null;
                });
            } else {
                console.log("No deferred prompt available for modal install");
                 // Sembunyikan modal jika tidak ada prompt
                if (pwaInstallModal) {
                    const modal = bootstrap.Modal.getInstance(pwaInstallModal);
                    if (modal) {
                        modal.hide();
                    }
                }
                // Tampilkan pesan error atau instruksi alternatif jika diperlukan
            }
        });
    }

    window.addEventListener('appinstalled', (evt) => {
        console.log('PWA was installed');
        if (installPWABtn) {
            installPWABtn.classList.add('d-none');
        }
        if (pwaInstructions) {
            pwaInstructions.style.display = 'none';
        }
        deferredPrompt = null;
    });

    window.addEventListener('DOMContentLoaded', () => {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('PWA dijalankan dalam mode standalone');
            if (installPWABtn) {
                installPWABtn.classList.add('d-none');
            }
        }
    });

    // --- Online/Offline detection ---
    window.addEventListener('online', () => {
        console.log('Online');
        // Anda bisa menampilkan notifikasi atau mengubah UI untuk menunjukkan status online
    });
    window.addEventListener('offline', () => {
        console.log('Offline');
        alert('Anda sedang offline. Beberapa fitur mungkin tidak tersedia.');
        // Anda bisa menampilkan notifikasi atau mengubah UI untuk menunjukkan status offline
    });

    // --- Fungsi Tambahan untuk Interaktivitas ---

    // Smooth scrolling untuk anchor links (jika ada)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // --- Animasi halus untuk tombol saat di-klik ---
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(1px)';
            this.style.boxShadow = '0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)';
        });
        button.addEventListener('mouseup', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        button.addEventListener('mouseleave', function() {
            // Reset jika mouse keluar saat tombol ditekan
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    // --- (Opsional) Fungsi untuk menangani scanner fingerprint jika ada di halaman ini ---
    // Misalnya, jika Anda memiliki logika khusus untuk modal scanner
    if (scannerFingerprintModal) {
        scannerFingerprintModal.addEventListener('shown.bs.modal', function () {
            console.log('Scanner Fingerprint Modal Shown');
            // Tambahkan logika jika diperlukan saat modal ditampilkan
        });
    }

    // --- Logging untuk debugging ---
    console.log("Custom JS app.js loaded and initialized.");

});

// --- Fungsi global yang bisa dipanggil dari HTML atau JS lain ---
// Fungsi untuk menampilkan pesan dengan animasi
function showMessage(message, type = 'info') {
    // Implementasi menampilkan pesan (bisa menggunakan toast, alert, dll)
    console.log(`[${type.toUpperCase()}] ${message}`);
     // Contoh sederhana menggunakan alert
    // alert(`[${type.toUpperCase()}] ${message}`);

    // Atau buat elemen toast dinamis
    // ...
}

// Fungsi untuk konfirmasi aksi (misalnya hapus)
function confirmAction(message, callback) {
    if (confirm(message)) {
        if (typeof callback === 'function') {
            callback();
        }
    }
}