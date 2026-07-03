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
        doaDiagnoses: [
            'Sudden Cardiac Arrest (Henti Jantung Mendadak)',
            'Multiple Trauma / Cedera Kepala Berat (Kecelakaan lalu lintas)',
            'Dead on Arrival (DOA) / Unspecified Cause'
        ],
        doaIcdCodes: [
            'I46.9',
            'S06.9',
            'R99'
        ],
        intervals: ['2 jam', '5 jam', '1 hari', '3 hari', '1 minggu', '2 minggu', '1 bulan', '3 bulan', '1 tahun', '5 tahun']
    };

    const getRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];
    const getRandomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const generateNIK = () => '3507' + Math.random().toString().slice(2, 14);
    const generateRM = () => Math.random().toString().slice(2, 4) + '-' + Math.random().toString().slice(4, 6) + '-' + Math.random().toString().slice(6, 8) + '-' + Math.random().toString().slice(8, 10);

    // 3. LOGIKA PENGISIAN FORMULIR
    const fillForm = (mode = 'normal') => {
        console.log(`%c[Bot] Memulai pengisian data (${mode})...`, 'color: #1da1a6; font-weight: bold;');
        
        const isBayiForm = document.getElementById('nama_bayi') !== null || document.getElementById('nrm_bayi') !== null;
        const inputs = document.querySelectorAll('input, select, textarea');
        let filledCount = 0;

        const todayStr = new Date().toISOString().split('T')[0];
        const randomName = getRandom(testData.names);
        const randomDoctor = getRandom(testData.names);

        inputs.forEach(input => {
            if (input.disabled || input.readOnly || input.type === 'hidden' || input.id === 'no_sertifikat') return;

            const id = input.id;
            const name = input.name;
            const type = input.type;

            if (isBayiForm) {
                // LOGIKA FORM BAYI
                if (id === 'nama_bayi') {
                    input.value = 'By. Ny. ' + randomName;
                } else if (id === 'nrm_bayi') {
                    input.value = generateRM();
                } else if (name === 'gender_bayi') {
                    input.checked = (input.value === 'Laki-laki');
                } else if (id === 'tanggal_lahir_bayi') {
                    const d = new Date();
                    d.setDate(d.getDate() - getRandomInt(2, 5));
                    input.value = d.toISOString().split('T')[0];
                } else if (id === 'jam_lahir_bayi') {
                    input.value = '06:30';
                } else if (id === 'berat_badan_lahir') {
                    input.value = getRandomInt(2500, 3800);
                } else if (id === 'usia_kehamilan') {
                    input.value = getRandomInt(37, 40);
                } else if (id === 'tempat_meninggal_bayi') {
                    input.value = 'Rumah Sakit';
                } else if (id === 'tanggal_meninggal_bayi') {
                    input.value = todayStr;
                } else if (id === 'jam_meninggal_bayi') {
                    input.value = '10:15';
                } else if (id === 'lama_perawatan_bayi') {
                    input.value = '2 hari';
                } else if (name === 'doa_bayi') {
                    input.checked = (mode === 'doa' ? (input.value === 'Ya') : (input.value === 'Tidak'));
                } else if (name === 'resusitasi') {
                    input.checked = (input.value === 'Tidak');
                } else if (name === 'meninggal_saat') {
                    input.checked = (input.value === 'Setelah Lahir');
                } else if (name === 'kehamilan_kembar') {
                    input.checked = (input.value === 'Tidak');
                } else if (name === 'lahir_mati') {
                    input.checked = (input.value === 'Tidak');
                } else if (id === 'lama_bayi_bertahan_jam') {
                    input.value = getRandomInt(2, 18);
                } else if (id === 'umur_ibu') {
                    input.value = getRandomInt(24, 38);
                } else if (name === 'faktor_maternal') {
                    input.checked = (input.value === 'M5');
                } else if (id === 'faktor_maternal_m6_ket') {
                    input.value = '';
                } else if (id && id.startsWith('dasar_')) {
                    // Checkbox dasar diagnosis
                    input.checked = (id === 'dasar_rekam_medis' || id === 'dasar_pemeriksaan_bayi');
                } else if (id === 'penyebab_utama_bayi') {
                    input.value = mode === 'doa' ? 'Dead on Arrival' : 'Respiratory Distress Syndrome';
                } else if (id === 'penyebab_antara_bayi') {
                    input.value = mode === 'doa' ? '' : 'Asphyxia Neonatorum';
                } else if (id === 'penyebab_dasar_bayi') {
                    input.value = mode === 'doa' ? '' : 'Hyaline Membrane Disease';
                } else if (id === 'penyebab_utama_ibu') {
                    input.value = mode === 'doa' ? '' : 'Pre-eclampsia';
                } else if (id === 'penyebab_pendukung_ibu') {
                    input.value = '';
                } else if (id === 'icd_penyebab_bayi') {
                    input.value = mode === 'doa' ? 'R99' : 'P22.0';
                } else if (id === 'icd_penyebab_maternal') {
                    input.value = mode === 'doa' ? '' : 'O14.1';
                } else if (id === 'nama_dokter') {
                    input.value = 'dr. ' + randomDoctor + ', Sp.A';
                } else if (id === 'nomor_sip') {
                    input.value = 'SIP/' + getRandomInt(1000, 9999) + '/RSWH/2026';
                } else if (id === 'tanggal_ttd') {
                    input.value = todayStr;
                } else {
                    // Fallback
                    if (type === 'checkbox' || type === 'radio') {
                        // ignore
                    } else {
                        input.value = 'Test Bayi ' + getRandomInt(1, 100);
                    }
                }
            } else {
                // LOGIKA FORM DEWASA
                if (id === 'nrm') {
                    input.value = generateRM();
                } else if (id === 'nik') {
                    input.value = generateNIK();
                } else if (id === 'nama_lengkap') {
                    input.value = randomName;
                } else if (name === 'gender') {
                    input.checked = (input.value === 'Laki-laki');
                } else if (id === 'agama') {
                    input.value = 'Islam';
                } else if (id === 'tanggal_lahir') {
                    const d = new Date();
                    d.setFullYear(d.getFullYear() - getRandomInt(45, 75));
                    input.value = d.toISOString().split('T')[0];
                } else if (id === 'alamat') {
                    input.value = getRandom(testData.addresses);
                } else if (id === 'kelurahan' || id === 'kecamatan') {
                    input.value = 'Kepanjen';
                } else if (id === 'kab_kota') {
                    input.value = 'Malang';
                } else if (id === 'status_penduduk_tetap') {
                    input.checked = true;
                } else if (id === 'status_penduduk_bukan') {
                    input.checked = false;
                } else if (id === 'hari_kematian') {
                    input.value = 'Rabu';
                } else if (id === 'tanggal_kematian') {
                    input.value = todayStr;
                } else if (id === 'jam_kematian') {
                    input.value = '23:45';
                } else if (id === 'umur_tahun') {
                    input.value = getRandomInt(45, 75);
                } else if (id && id.startsWith('kondisi_')) {
                    input.checked = false;
                } else if (id === 'lama_rawat_jam') {
                    input.value = '';
                } else if (id === 'lama_rawat_hari') {
                    input.value = getRandomInt(2, 5);
                } else if (name === 'doa') {
                    input.checked = (mode === 'doa' ? (input.value === 'Ya') : (input.value === 'Tidak'));
                } else if (id && id.startsWith('tempat_meninggal_')) {
                    input.checked = (id === 'tempat_meninggal_rs');
                } else if (id === 'tempat_lainnya_ket') {
                    input.value = '';
                } else if (id && id.startsWith('dasar_diagnosa_')) {
                    input.checked = (id === 'dasar_diagnosa_rm');
                } else if (name === 'kelompok') {
                    input.checked = (input.value === 'Penyakit tidak menular');
                } else if (id === 'penyebab_a') {
                    input.value = mode === 'doa' ? 'Dead on Arrival' : 'Acute Myocardial Infarction';
                } else if (id === 'penyebab_b') {
                    input.value = mode === 'doa' ? '' : 'Coronary Artery Disease';
                } else if (id === 'penyebab_c') {
                    input.value = mode === 'doa' ? '' : 'Hypertension';
                } else if (id === 'penyebab_d') {
                    input.value = mode === 'doa' ? '' : 'Diabetes Mellitus';
                } else if (id === 'penyakit_lain') {
                    input.value = mode === 'doa' ? '' : 'Chronic smoker';
                } else if (id === 'selang_waktu_a') {
                    input.value = mode === 'doa' ? '-' : '30 menit';
                } else if (id === 'selang_waktu_b') {
                    input.value = mode === 'doa' ? '' : '5 jam';
                } else if (id === 'selang_waktu_c') {
                    input.value = mode === 'doa' ? '' : '5 tahun';
                } else if (id === 'selang_waktu_d') {
                    input.value = mode === 'doa' ? '' : '10 tahun';
                } else if (id === 'icd_a') {
                    input.value = mode === 'doa' ? 'R99' : 'I21.9';
                } else if (id === 'icd_b') {
                    input.value = mode === 'doa' ? '' : 'I25.1';
                } else if (id === 'icd_c') {
                    input.value = mode === 'doa' ? '' : 'I10';
                } else if (id === 'icd_d') {
                    input.value = mode === 'doa' ? '' : 'E11.9';
                } else if (id === 'icd_penyerta') {
                    input.value = mode === 'doa' ? '' : 'F17.2';
                } else if (id === 'fucod') {
                    input.value = mode === 'doa' ? 'Dead on Arrival' : 'Acute Myocardial Infarction';
                } else if (id === 'icd_fucod') {
                    input.value = mode === 'doa' ? 'R99' : 'I21.9';
                } else if (id === 'nama_dokter') {
                    input.value = 'dr. ' + randomDoctor + ', Sp.PD';
                } else if (id === 'nomor_sip') {
                    input.value = 'SIP/' + getRandomInt(1000, 9999) + '/RSWH/2026';
                } else if (id === 'tanggal_ttd') {
                    input.value = todayStr;
                } else if (id === 'nama_terang_penerima') {
                    input.value = 'Ny. ' + randomName;
                } else if (id === 'hubungan_jenazah') {
                    input.value = 'Istri';
                } else {
                    // Fallback
                    if (type === 'checkbox' || type === 'radio') {
                        // ignore
                    } else {
                        input.value = 'Test Dewasa ' + getRandomInt(1, 100);
                    }
                }
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

        const btnNormal = document.createElement('button');
        btnNormal.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                <div style="font-weight: 800; font-size: 12px; letter-spacing: 0.05em;">BOT FILL NORMAL</div>
                <div style="font-weight: 800; font-size: 10px; letter-spacing: 0.05em; opacity: 0.9;">Kematian Biasa</div>
            </div> 
        `;
        btnNormal.style.cssText = `
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
        btnNormal.onmouseover = () => { btnNormal.style.backgroundColor = '#158a8e'; };
        btnNormal.onmouseout = () => { btnNormal.style.backgroundColor = '#1da1a6'; };
        btnNormal.onclick = () => fillForm('normal');

        const btnDoa = document.createElement('button');
        btnDoa.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                <div style="font-weight: 800; font-size: 12px; letter-spacing: 0.05em;">BOT FILL DOA</div>
                <div style="font-weight: 800; font-size: 10px; letter-spacing: 0.05em; opacity: 0.9;">Dead on Arrival</div>
            </div> 
        `;
        btnDoa.style.cssText = `
            background: #e65100;
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
        btnDoa.onmouseover = () => { btnDoa.style.backgroundColor = '#bf360c'; };
        btnDoa.onmouseout = () => { btnDoa.style.backgroundColor = '#e65100'; };
        btnDoa.onclick = () => fillForm('doa');

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

        container.appendChild(btnNormal);
        container.appendChild(btnDoa);
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
