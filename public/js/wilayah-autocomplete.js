document.addEventListener('DOMContentLoaded', () => {
    // Definisi grup input alamat untuk kedua form
    const addressGroups = [
        { prov: 'provinsi', kab: 'kab_kota', kec: 'kecamatan', kel: 'kelurahan' },
        { prov: 'provinsi_ibu', kab: 'kab_kota_ibu', kec: 'kecamatan_ibu', kel: 'kelurahan_ibu' }
    ];

    addressGroups.forEach(group => {
        setupAutocomplete(group);
    });

    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) return null;
            return await response.json();
        } catch (e) {
            console.error('Error fetching data:', e);
            return null;
        }
    }

    function setupAutocomplete(group) {
        const inputProv = document.getElementById(group.prov);
        const inputKab = document.getElementById(group.kab);
        const inputKec = document.getElementById(group.kec);
        const inputKel = document.getElementById(group.kel);

        if (!inputProv || !inputKab || !inputKec || !inputKel) return;

        // Auto-recover IDs jika ada isian (seperti saat load draft)
        async function recoverIds() {
            if (inputProv.value && !inputProv.dataset.id) {
                const provData = await fetchData('/IndoArea/provinsi/provinsi.json');
                if (provData) {
                    for (const [id, name] of Object.entries(provData)) {
                        if (name.toUpperCase() === inputProv.value.toUpperCase()) {
                            inputProv.dataset.id = id;
                            break;
                        }
                    }
                }
            }
            if (inputKab.value && !inputKab.dataset.id && inputProv.dataset.id) {
                const kabData = await fetchData(`/IndoArea/kabupaten_kota/kab-${inputProv.dataset.id}.json`);
                if (kabData) {
                    for (const [id, name] of Object.entries(kabData)) {
                        if (name.toUpperCase() === inputKab.value.toUpperCase()) {
                            inputKab.dataset.id = id;
                            break;
                        }
                    }
                }
            }
            if (inputKec.value && !inputKec.dataset.id && inputProv.dataset.id && inputKab.dataset.id) {
                const kecData = await fetchData(`/IndoArea/kecamatan/kec-${inputProv.dataset.id}-${inputKab.dataset.id}.json`);
                if (kecData) {
                    for (const [id, name] of Object.entries(kecData)) {
                        if (name.toUpperCase() === inputKec.value.toUpperCase()) {
                            inputKec.dataset.id = id;
                            break;
                        }
                    }
                }
            }
        }

        function createDropdown(input, getUrl, onSelect, checkDependency) {
            const dropdown = document.getElementById(`dropdown-${input.id}`);
            let currentData = null;
            let debounceTimer;

            input.addEventListener('focus', async () => {
                const depError = checkDependency();
                if (depError) {
                    showDropdown(dropdown, [{ id: '', name: depError }], input, false);
                    return;
                }
                const url = getUrl();
                if (!url) return;

                if (!currentData || dropdown.dataset.url !== url) {
                    showDropdown(dropdown, [{ id: '', name: 'Memuat data...' }], input, false);
                    currentData = await fetchData(url);
                    dropdown.dataset.url = url;
                }
                filterAndShow(input.value);
            });

            input.addEventListener('input', (e) => {
                if (input._isScriptUpdate) {
                    input._isScriptUpdate = false;
                    return; // Abaikan event ini agar tidak menghapus ID
                }

                if (input.dataset.id) {
                    // Jika user mengubah teks secara manual, hapus ID dan reset child
                    input.dataset.id = '';
                    onSelect(null);
                }
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filterAndShow(input.value);
                }, 150);
            });

            input.addEventListener('blur', () => {
                // Beri jeda agar event click pada item dropdown sempat dieksekusi
                setTimeout(() => {
                    let isValid = false;
                    if (input.dataset.id) {
                        if (currentData && currentData[input.dataset.id]) {
                            if (input.value.toUpperCase() === currentData[input.dataset.id].toUpperCase()) {
                                isValid = true;
                                input.value = currentData[input.dataset.id]; // Auto-correct case
                            }
                        } else if (input.value.trim() !== '') {
                            // currentData belum diload tapi ID sudah ada (misal dari recoverIds)
                            isValid = true;
                        }
                    }
                    
                    if (!isValid && input.value !== '') {
                        input.value = '';
                        input.dataset.id = '';
                        onSelect(null);
                        input._isScriptUpdate = true;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }, 200);
            });

            // Tutup dropdown jika klik di luar
            document.addEventListener('click', (e) => {
                if (e.target !== input && e.target !== dropdown && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            function filterAndShow(query) {
                if (!currentData) return;
                const lowerQuery = query.toLowerCase();
                const matches = [];
                for (const [id, name] of Object.entries(currentData)) {
                    if (name.toLowerCase().includes(lowerQuery)) {
                        matches.push({ id, name });
                    }
                }
                
                // Urutkan alphabet agar rapi
                matches.sort((a, b) => a.name.localeCompare(b.name));

                if (matches.length === 0) {
                    showDropdown(dropdown, [{ id: '', name: 'Tidak ada data cocok' }], input, false);
                } else {
                    showDropdown(dropdown, matches, input, true);
                }
            }

            function showDropdown(el, items, inp, selectable) {
                el.innerHTML = '';
                items.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-1.5 text-[11px] text-slate-700 transition-colors duration-150 ease-in-out';
                    
                    if (selectable && item.id) {
                        div.classList.add('cursor-pointer', 'hover:bg-teal-50', 'hover:text-teal-800', 'font-medium');
                        div.textContent = item.name;
                        div.addEventListener('click', () => {
                            inp.value = item.name;
                            inp.dataset.id = item.id;
                            el.classList.add('hidden');
                            onSelect(item.id);
                            // Trigger event agar fungsi simpan draft berjalan
                            inp._isScriptUpdate = true;
                            inp.dispatchEvent(new Event('input', { bubbles: true }));
                            inp.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    } else {
                        div.classList.add('cursor-default', 'bg-slate-50', 'text-slate-400', 'italic');
                        div.textContent = item.name;
                    }
                    el.appendChild(div);
                });
                el.classList.remove('hidden');
            }
        }

        // 1. Provinsi
        createDropdown(inputProv, 
            () => '/IndoArea/provinsi/provinsi.json',
            (id) => {
                inputKab.value = ''; inputKab.dataset.id = '';
                inputKec.value = ''; inputKec.dataset.id = '';
                inputKel.value = ''; inputKel.dataset.id = '';
                inputKab._isScriptUpdate = true; inputKab.dispatchEvent(new Event('input', { bubbles: true }));
                inputKec._isScriptUpdate = true; inputKec.dispatchEvent(new Event('input', { bubbles: true }));
                inputKel._isScriptUpdate = true; inputKel.dispatchEvent(new Event('input', { bubbles: true }));
            },
            () => null
        );

        // 2. Kab/Kota
        createDropdown(inputKab,
            () => `/IndoArea/kabupaten_kota/kab-${inputProv.dataset.id}.json`,
            (id) => {
                inputKec.value = ''; inputKec.dataset.id = '';
                inputKel.value = ''; inputKel.dataset.id = '';
                inputKec._isScriptUpdate = true; inputKec.dispatchEvent(new Event('input', { bubbles: true }));
                inputKel._isScriptUpdate = true; inputKel.dispatchEvent(new Event('input', { bubbles: true }));
            },
            () => {
                if (!inputProv.dataset.id) {
                    recoverIds();
                    return "Pilih Provinsi terlebih dahulu";
                }
                return null;
            }
        );

        // 3. Kecamatan
        createDropdown(inputKec,
            () => `/IndoArea/kecamatan/kec-${inputProv.dataset.id}-${inputKab.dataset.id}.json`,
            (id) => {
                inputKel.value = ''; inputKel.dataset.id = '';
                inputKel._isScriptUpdate = true; inputKel.dispatchEvent(new Event('input', { bubbles: true }));
            },
            () => {
                if (!inputKab.dataset.id) {
                    recoverIds();
                    return "Pilih Kab/Kota terlebih dahulu";
                }
                return null;
            }
        );

        // 4. Kelurahan
        createDropdown(inputKel,
            () => `/IndoArea/kelurahan_desa/keldesa-${inputProv.dataset.id}-${inputKab.dataset.id}-${inputKec.dataset.id}.json`,
            (id) => {
                // Selesai
            },
            () => {
                if (!inputKec.dataset.id) {
                    recoverIds();
                    return "Pilih Kecamatan terlebih dahulu";
                }
                return null;
            }
        );
        
        // Recover IDs saat pertama kali diload jika ada value dari draft/database
        setTimeout(recoverIds, 500);
    }
});
