@php
    $regionValues = $regionValues ?? ['provinsi' => null, 'kota' => null, 'kecamatan' => null, 'kelurahan' => null];
@endphp

<label class="space-y-2">
    <span class="font-medium">Provinsi</span>
    <select id="provinsi" name="provinsi" class="w-full rounded-xl border px-4 py-3" required disabled>
        <option value="">Memuat provinsi…</option>
    </select>
</label>
<label class="space-y-2">
    <span class="font-medium">Kota / Kabupaten</span>
    <select id="kota" name="kota" class="w-full rounded-xl border px-4 py-3" required disabled>
        <option value="">Pilih Kota/Kabupaten</option>
    </select>
</label>
<label class="space-y-2">
    <span class="font-medium">Kecamatan</span>
    <select id="kecamatan" name="kecamatan" class="w-full rounded-xl border px-4 py-3" required disabled>
        <option value="">Pilih Kecamatan</option>
    </select>
</label>
<label class="space-y-2">
    <span class="font-medium">Kelurahan / Desa</span>
    <select id="kelurahan" name="kelurahan" class="w-full rounded-xl border px-4 py-3" required disabled>
        <option value="">Pilih Kelurahan/Desa</option>
    </select>
</label>
<p id="region-error" class="hidden md:col-span-2 text-sm text-rose-600" role="alert"></p>

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseUrl = @json(url('gudang/wilayah'));
        const values = @json($regionValues);
        const levels = ['provinsi', 'kota', 'kecamatan', 'kelurahan'];
        const placeholders = {
            provinsi: 'Pilih Provinsi', kota: 'Pilih Kota/Kabupaten',
            kecamatan: 'Pilih Kecamatan', kelurahan: 'Pilih Kelurahan/Desa'
        };
        const selects = Object.fromEntries(levels.map(level => [level, document.getElementById(level)]));
        const error = document.getElementById('region-error');
        const records = Object.fromEntries(levels.map(level => [level, []]));

        const setDisabled = (select, disabled) => {
            select.disabled = disabled;
            if (select.tomselect) disabled ? select.tomselect.disable() : select.tomselect.enable();
        };

        const resetAfter = (level) => {
            const index = levels.indexOf(level);
            levels.slice(index + 1).forEach(child => {
                records[child] = [];
                selects[child].innerHTML = `<option value="">${placeholders[child]}</option>`;
                if (selects[child].tomselect) {
                    selects[child].tomselect.clear(true);
                    selects[child].tomselect.clearOptions();
                }
                setDisabled(selects[child], true);
            });
        };

        const showError = (message = '') => {
            error.textContent = message;
            error.classList.toggle('hidden', !message);
        };

        const fill = (level, items, selected = '') => {
            records[level] = items;
            const select = selects[level];
            if (select.tomselect) {
                select.tomselect.clear(true);
                select.tomselect.clearOptions();
                select.tomselect.addOptions(items.map(item => ({ value: item.name, text: item.name })));
                select.tomselect.setValue(selected, true);
            } else {
                select.innerHTML = `<option value="">${placeholders[level]}</option>`;
                items.forEach(item => select.add(new Option(item.name, item.name, false, item.name === selected)));
                new TomSelect(select, {
                    create: false,
                    allowEmptyOption: true,
                    placeholder: placeholders[level],
                    maxOptions: null,
                });
            }
            setDisabled(select, false);
        };

        const getSelected = (level) => records[level].find(item => item.name === selects[level].value);

        async function load(level, parentId = null, selected = '') {
            const select = selects[level];
            setDisabled(select, true);
            select.innerHTML = `<option value="">Memuat…</option>`;
            try {
                const response = await fetch(parentId ? `${baseUrl}/${level}/${parentId}` : `${baseUrl}/${level}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error();
                fill(level, await response.json(), selected);
                showError();
            } catch (_) {
                select.innerHTML = `<option value="">Data tidak tersedia</option>`;
                showError('Data wilayah gagal dimuat. Periksa koneksi lalu muat ulang halaman.');
            }
        }

        levels.forEach((level, index) => selects[level].addEventListener('change', async () => {
            resetAfter(level);
            const selected = getSelected(level);
            if (!selected) return;
            if (index === levels.length - 1) {
                document.dispatchEvent(new CustomEvent('gudang:wilayah-lengkap', {
                    detail: { wilayah: levels.map(item => selects[item].value).filter(Boolean).join(', ') }
                }));
                return;
            }
            await load(levels[index + 1], selected.id);
        }));

        (async () => {
            await load('provinsi', null, values.provinsi || '');
            for (let index = 0; index < levels.length - 1; index++) {
                const selected = getSelected(levels[index]);
                if (!selected) break;
                await load(levels[index + 1], selected.id, values[levels[index + 1]] || '');
            }
        })();
    });
    </script>
@endonce
