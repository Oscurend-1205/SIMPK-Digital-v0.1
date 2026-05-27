/**
 * AUTO FORM FILLER BOT - RS WAVA HUSADA (SIMPK-Digital)
 * -----------------------------------------------------
 * Deskripsi: Bot pengisian formulir otomatis untuk mempermudah proses testing dan pengembangan.
 * Penggunaan: Panggil script ini di akhir body pada file Blade utama atau partials.
 * 
 * Cara Penggunaan:
 * 1. Tambahkan <script src="{{ asset('js/auto-form-filler.bot.js') }}"></script> di file layout.
 * 2. Klik tombol melayang "BOT ILL TEST DATA" di pojok kanan bawah.
 * 
 * Keamanan: Hanya berjalan di domain localhost, 127.0.0.1, atau staging.
 */

(function() {
    'use strict';

    // 1. PENGECEKAN KEAMANAN (Hanya berjalan di dev/staging & halaman form)
    const allowedDomains = ['localhost', '127.0.0.1', 'staging.wavahusada.com'];
    const currentDomain = window.location.hostname;
    const currentPath = window.location.pathname;

    // Hanya muncul di halaman form kematian (baru maupun edit)
    const isFormPage = currentPath.includes('/form/kematian-') || currentPath.includes('/form/edit/');

    if (!allowedDomains.includes(currentDomain) || !isFormPage) {
        if (!isFormPage && allowedDomains.includes(currentDomain)) {
            // Diam saja jika bukan di halaman form di lingkungan dev
        } else {
            console.log('%c[Bot] Deteksi domain produksi atau bukan halaman form. Bot dinonaktifkan.', 'color: orange; font-weight: bold;');
        }
        return;
    }

    // Hindari inisialisasi ganda
    if (window.__AUTO_FILL_BOT_INITIALIZED__) return;
    window.__AUTO_FILL_BOT_INITIALIZED__ = true;

    // 2. DATA PENGUJIAN REALISTIS (Variasi Lebih Banyak)
    const testData = {
        names: [
            'Ahmad Fauzi', 'Siti Aminah', 'Budi Santoso', 'Dewi Lestari', 'Pratama Wijaya', 
            'Anisa Rahmawati', 'Hendra Kusuma', 'Lusi Indah', 'Rahmat Hidayat', 'Mulyadi',
            'Eko Prasetyo', 'Ratna Sari', 'Dedi Kurniawan', 'Indah Permata', 'Bambang Utomo',
            'Slamet Riadi', 'Kartini', 'Agus Setiawan', 'Sri Wahyuni', 'Joko Widodo',
            'Susilo Bambang', 'Megawati Sukarno', 'Abdurrahman Wahid', 'Bacharuddin Jusuf',
            'Sri Mulyani', 'Luhut Binsar', 'Retno Marsudi', 'Ganjar Pranowo', 'Anies Baswedan'
        ],
        relatives: ['Ayah', 'Ibu', 'Suami', 'Istri', 'Anak', 'Saudara Kandung', 'Wali', 'Keponakan', 'Paman', 'Bibi'],
        addresses: [
            'Jl. Panglima Sudirman No. 12, Kepanjen, Malang',
            'Jl. Merdeka No. 45, Dilem, Kepanjen',
            'Jl. Diponegoro No. 88, Kepanjen, Malang',
            'Dusun Krajan RT 01 RW 02, Kec. Kepanjen',
            'Perumahan Kepanjen Permai Blok A-10',
            'Jl. Raya Jalibar No. 99, Kepanjen',
            'Jl. Sukarno Hatta No. 1, Malang',
            'Jl. Ijen No. 25, Malang',
            'Jl. Borobudur No. 15, Blimbing, Malang',
            'Jl. Kawi No. 10, Malang',
            'Desa Sengguruh RT 05 RW 01, Kepanjen',
            'Kelurahan Arirejo, Kepanjen, Malang'
        ],
        hospitals: [
            'RS Wava Husada', 'Puskesmas Kepanjen', 'RSUD Kanjuruhan', 
            'RS Hermina Malang', 'RS Saiful Anwar', 'RS Panti Nirmala'
        ],
        icdCodes: [
            'A00.0', 'B01.9', 'C00.0', 'D00.0', 'E11.9', 'I10', 'J45.9', 'P22.0', 'O14.1',
            'A01.0', 'B20', 'C34.9', 'E10.9', 'I21.9', 'I64', 'J18.9', 'K74.6', 'N18.9',
            'V01-Y98', 'Q00-Q99', 'P00-P96', 'R99', 'U07.1'
        ],
        diagnoses: [
            'Diabetes Mellitus Type 2',
            'Hypertension Essential',
            'Respiratory Distress Syndrome',
            'Severe Pre-eclampsia',
            'Acute Myocardial Infarction',
            'Cerebrovascular Accident (Stroke)',
            'Chronic Kidney Disease Stage 5',
            'Pneumonia Bacterial',
            'Septic Shock',
            'Congestive Heart Failure',
            'Pulmonary Tuberculosis',
            'Liver Cirrhosis',
            'COVID-19 (Confirmed)',
            'Asphyxia Neonatorum',
            'Low Birth Weight',
            'Congenital Malformation'
        ],
        intervals: ['2 jam', '5 jam', '1 hari', '3 hari', '1 minggu', '2 minggu', '1 bulan', '3 bulan', '1 tahun', '5 tahun']
    };

    const getRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];
    const getRandomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const generateNIK = () => '3507' + Math.random().toString().slice(2, 14);
    const generateRM = () => Math.random().toString().slice(2, 4) + '-' + Math.random().toString().slice(4, 6) + '-' + Math.random().toString().slice(6, 8) + '-' + Math.random().toString().slice(8, 10);

    // 3. LOGIKA PENGISIAN FORMULIR
    const fillForm = () => {
        console.log('%c[Bot] Memulai pengisian data...', 'color: #1da1a6; font-weight: bold;');
        
        const inputs = document.querySelectorAll('input, select, textarea');
        let filledCount = 0;

        inputs.forEach(input => {
            if (input.disabled || input.readOnly || input.type === 'hidden' || input.id === 'no_sertifikat') return;

            const id = input.id.toLowerCase();
            const name = input.name.toLowerCase();
            const type = input.type;

            // Logika berdasarkan ID atau Tipe
            if (type === 'radio' || type === 'checkbox') {
                // Pilih secara acak (50/50 chance for checkboxes)
                if (Math.random() > 0.5 || type === 'radio') {
                    input.checked = true;
                    // Trigger event
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            } else if (input.tagName === 'SELECT') {
                if (input.options.length > 1) {
                    input.selectedIndex = getRandomInt(1, input.options.length - 1);
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            } else if (type === 'date') {
                const date = new Date();
                date.setDate(date.getDate() - getRandomInt(0, 30));
                input.value = date.toISOString().split('T')[0];
            } else if (type === 'time') {
                input.value = '08:00';
            } else if (type === 'number') {
                input.value = getRandomInt(1, 100);
            } else {
                // Text, Textarea, etc.
                if (id.includes('nik')) input.value = generateNIK();
                else if (id.includes('nrm') || id.includes('rekam_medis')) input.value = generateRM();
                else if (id.includes('nama') || id.includes('lengkap')) input.value = getRandom(testData.names);
                else if (id.includes('alamat')) input.value = getRandom(testData.addresses);
                else if (id.includes('icd')) input.value = getRandom(testData.icdCodes);
                else if (id.includes('penyebab') || id.includes('diagnosa') || id.includes('fucod')) input.value = getRandom(testData.diagnoses);
                else if (id.includes('selang_waktu')) input.value = getRandom(testData.intervals);
                else if (id.includes('sip')) input.value = '446/' + getRandomInt(1000, 9999) + '/35.07.103/202' + getRandomInt(0, 4);
                else if (id.includes('dokter')) input.value = 'dr. ' + getRandom(testData.names) + ', Sp.A';
                else if (id.includes('kelurahan') || id.includes('kecamatan') || id.includes('kab')) input.value = 'Kepanjen';
                else if (id.includes('telp') || id.includes('phone')) input.value = '0812' + Math.random().toString().slice(2, 10);
                else if (id.includes('email')) input.value = 'test-' + getRandomInt(100, 999) + '@example.com';
                else input.value = 'Test Data ' + getRandomInt(1, 100);
            }

            // Trigger events untuk sinkronisasi framework (Vue/React/etc if any)
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            filledCount++;
        });

        console.log(`%c[Bot] Berhasil mengisi ${filledCount} field.`, 'color: green; font-weight: bold;');
        
        // Custom notification if exists in app
        if (window.showToast) {
            window.showToast(`Bot berhasil mengisi ${filledCount} field!`, 'success');
        } else {
            alert(`Bot berhasil mengisi ${filledCount} field!`);
        }
    };

    // 4. ANTARMUKA PENGGUNA (Floating Widget)
    const createUI = () => {
        const container = document.createElement('div');
        container.id = 'auto-fill-bot-container';
        container.style.cssText = `
            position: fixed;
            bottom: 100px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-family: 'Inter', sans-serif;
        `;

        const btn = document.createElement('button');
        btn.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                <div style="font-weight: 800; font-size: 12px; letter-spacing: 0.05em;">BOT FILL TEST DATA</div>
                <div style="font-weight: 800; font-size: 12px; letter-spacing: 0.05em;">Testing Only</div>
            </div> 
        `;
        btn.style.cssText = `
            background: #1da1a6;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        `;

        btn.onmouseover = () => { btn.style.backgroundColor = '#158a8e'; };
        btn.onmouseout = () => { btn.style.backgroundColor = '#1da1a6'; };
        btn.onclick = fillForm;

        const closeBtn = document.createElement('div');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = `
            position: absolute;
            top: -8px;
            right: -8px;
            background: #f44336;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        `;
        closeBtn.onclick = () => container.style.display = 'none';

        container.appendChild(btn);
        container.appendChild(closeBtn);
        document.body.appendChild(container);
    };

    // Jalankan saat DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createUI);
    } else {
        createUI();
    }
})();
