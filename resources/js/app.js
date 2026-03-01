import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

const installBtn = document.getElementById('pwaInstallBtn');
const iosGuide = document.getElementById('pwaIosGuide');
const closeInstallBannerBtn = document.getElementById('closeInstallBannerBtn');
const installBanner = document.getElementById('pwaInstallBanner');
const isStandaloneMode = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
const isIOS = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
const isSafari = /safari/i.test(window.navigator.userAgent) && !/chrome|android/i.test(window.navigator.userAgent);
let deferredInstallPrompt = null;

const hideInstallBanner = () => {
    if (installBanner) {
        installBanner.classList.add('hidden');
    }
};

const showInstallBanner = () => {
    if (installBanner) {
        installBanner.classList.remove('hidden');
    }
};

if (isStandaloneMode) {
    hideInstallBanner();
}

if (closeInstallBannerBtn) {
    closeInstallBannerBtn.addEventListener('click', hideInstallBanner);
}

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredInstallPrompt = event;
    if (!isStandaloneMode) {
        showInstallBanner();
    }
});

if (installBtn) {
    installBtn.addEventListener('click', async () => {
        if (!deferredInstallPrompt) {
            return;
        }

        deferredInstallPrompt.prompt();
        await deferredInstallPrompt.userChoice;
        deferredInstallPrompt = null;
        hideInstallBanner();
    });
}

if (isIOS && isSafari && !isStandaloneMode) {
    if (installBtn) {
        installBtn.classList.add('hidden');
    }
    if (iosGuide) {
        iosGuide.classList.remove('hidden');
    }
    showInstallBanner();
}

window.addEventListener('appinstalled', hideInstallBanner);

const posFilterForm = document.getElementById('posFilterForm');
const posSearchInput = document.getElementById('posSearchInput');
const posCategoryInput = document.getElementById('posCategoryInput');

if (posFilterForm && posSearchInput) {
    let debounceTimer;

    posSearchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => posFilterForm.submit(), 300);
    });
}

if (posFilterForm && posCategoryInput) {
    posCategoryInput.addEventListener('change', () => posFilterForm.submit());
}

const paymentMethodInput = document.getElementById('paymentMethodInput');
const paidInput = document.getElementById('paidInput');
const paymentProofWrap = document.getElementById('paymentProofWrap');
const paymentProofInput = document.getElementById('paymentProofInput');

if (paymentMethodInput && paidInput) {
    const syncPaidField = () => {
        const isCash = paymentMethodInput.value === 'tunai';
        const needsProof = paymentMethodInput.value === 'transfer' || paymentMethodInput.value === 'qris';

        paidInput.required = isCash;
        paidInput.readOnly = !isCash;
        paidInput.placeholder = isCash ? 'Bayar (Tunai)' : 'Bayar otomatis = total';

        if (!isCash) {
            paidInput.value = '';
        }

        if (paymentProofWrap) {
            paymentProofWrap.classList.toggle('hidden', !needsProof);
        }

        if (!needsProof && paymentProofInput) {
            paymentProofInput.value = '';
        }
    };

    syncPaidField();
    paymentMethodInput.addEventListener('change', syncPaidField);
}
