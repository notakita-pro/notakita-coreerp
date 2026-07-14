document.addEventListener('DOMContentLoaded', function () {
    // Selector Elemen Utama
    const btnEditItems = document.getElementById('btnEditItems');
    const itemsModalEl = document.getElementById('itemsModal');
    const modalItemsContainer = document.getElementById('modalItemsContainer');
    const btnAddModalRow = document.getElementById('btnAddModalRow');
    const btnApplyItems = document.getElementById('btnApplyItems');
    const tbodySales = document.getElementById('tbodySales');

    // =========================================================================
    // SOLUSI SAKTI: Samakan 100% dengan mekanisme paymentModal di create.js
    // =========================================================================
    const itemsModal = {
        show: function() {
            if (itemsModalEl) itemsModalEl.classList.add('show');
        },
        hide: function() {
            if (itemsModalEl) itemsModalEl.classList.remove('show');
        }
    };

    // Fungsi tutup modal jika area luar hitam diklik (Sama seperti paymentModal)
    itemsModalEl?.addEventListener('click', function (e) {
        if (e.target === itemsModalEl) {
            itemsModal.hide();
        }
    });

    // Pasang fungsi close otomatis untuk semua tombol batal/silang di dalam modal
    itemsModalEl?.querySelectorAll('[data-bs-dismiss="modal"], .btn-close-modal').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            itemsModal.hide();
        });
    });
    // =========================================================================

    // 1. TAMPILKAN MODAL SAAT TOMBOL EDIT DIKLIK
    if (btnEditItems) {
        btnEditItems.addEventListener('click', function () {
            // Sinkronisasi data: Ambil data dari tabel utama ke dalam modal sebelum dibuka
            syncTableToModal();
            itemsModal.show(); // <--- Menampilkan modal secara rapi
        });
    }

    // 2. FUNGSI HITUNG SUBTOTAL DI DALAM MODAL
    function calculateModalSubtotal(row) {
        const qtyInput = row.querySelector('.modal-item-qty');
        const priceInput = row.querySelector('.modal-item-price');
        const subtotalInput = row.querySelector('.modal-item-subtotal');

        if (qtyInput && priceInput && subtotalInput) {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const subtotal = qty * price;

            // Format mata uang rupiah sederhana untuk visual
            subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
    }

    // 3. TAMBAH BARIS BARU DI DALAM MODAL
    if (btnAddModalRow) {
        btnAddModalRow.addEventListener('click', function () {
            const newRow = document.createElement('div');
            newRow.className = 'modal-item-row';
            newRow.style.cssText = 'border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;';
            
            newRow.innerHTML = `
                <input type="hidden" class="modal-item-id" value="">
                
                <div style="margin-bottom: 8px;">
                    <label style="font-size: 12px; color: #777; display:block; margin-bottom: 4px;">Nama Barang</label>
                    <input type="text" class="form-control-modal modal-item-name" placeholder="Pilih atau ketik nama barang">
                </div>
                <div class="flex-coloumn">
                    <div>
                        <label>Qty</label>
                        <input type="number" min="0" step="0.01" class="form-control-modal modal-item-qty" value="1">
                    </div>

                    <div>
                        <label>Satuan</label>
                        <select class="form-control-modal modal-item-unit" style="width: 75px; padding: 5px; background: #ffffef; border: 1px solid #d8d8d8; border-radius: 8px;">
                            <option value="Pcs">Pcs</option>
                            <option value="Kg">Kg</option>
                        </select>
                    </div>
                    <div>
                        <label>Harga Satuan</label>
                        <input type="number" min="0" step="0.01" class="form-control-modal modal-item-price" value="">
                    </div>
                </div>
                <div class="grid-fixed-2" style="margin-top: 15px;">
                    <div style="text-align: left; margin-top: 14px;">
                        <button type="button" class="btn-modal-delete-row" style="background: #ffe5e5; color: #cc0000; border: none; padding: 7px 10px; border-radius: 14px; font-size: 12px; cursor: pointer;">Hapus</button>
                    </div>
                    <div>
                        <label style="width: 250px;"><b>Jumlah</b></label>
                        <input type="text" class="form-control-modal modal-item-subtotal" readonly style="background-color: #f9f9f9; font-weight: 600; color: brown;">
                    </div>
                </div>
            `;
            
            modalItemsContainer.appendChild(newRow);
            bindRowEvents(newRow);
        });
    }

    // 4. BIND EVENT LISTENER UNTUK INPUT DAN TOMBOL HAPUS PER BARIS
    function bindRowEvents(row) {
        const qtyInput = row.querySelector('.modal-item-qty');
        const priceInput = row.querySelector('.modal-item-price');
        const btnDelete = row.querySelector('.btn-modal-delete-row');

        if (qtyInput) qtyInput.addEventListener('input', () => { calculateModalSubtotal(row); });
        if (priceInput) priceInput.addEventListener('input', () => { calculateModalSubtotal(row); });
        
        if (btnDelete) {
            btnDelete.addEventListener('click', function () {
                row.remove();
            });
        }
    }

    // Pasang event ke baris default bawaan modal pertama kali
    document.querySelectorAll('.modal-item-row').forEach(row => {
        bindRowEvents(row);
    });

    // 5. SINKRONISASI DATA UTAMA KE MODAL (SAAT MODAL DIBUKA)
    function syncTableToModal() {
        const tableRows = tbodySales.querySelectorAll('tr');
        modalItemsContainer.innerHTML = ''; 

        tableRows.forEach(tr => {
            const itemId = tr.querySelector('input[name*="[item_id]"]')?.value || '';
            const name = tr.querySelector('input[name*="[item_name]"]')?.value || '';
            const unit = tr.querySelector('input[name*="[unit]"]')?.value || 'Pcs';
            const qty = tr.querySelector('.qty')?.value || 1;
            const price = tr.querySelector('.price')?.value || 0;
            
            const modalRow = document.createElement('div');
            modalRow.className = 'modal-item-row';
            modalRow.style.cssText = 'border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;';
            modalRow.innerHTML = `
                <input type="hidden" class="modal-item-id" value="${itemId}">

                <div style="margin-bottom: 8px;">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control-modal modal-item-name" value="${name}" placeholder="Ketik nama barang">
                </div>
                <div class="flex-coloumn">
                    <div>
                        <label>Qty</label>
                        <input type="number" min="0" step="0.01" class="form-control-modal modal-item-qty" value="${qty}">
                    </div>
                    <div>
                        <label>Satuan</label>
                        <select class="form-control-modal modal-item-unit" style="width: 75px; padding: 5px; background: #ffffef; border: 1px solid #d8d8d8; border-radius: 8px;">
                            <option value="Pcs" ${unit.toLowerCase() === 'pcs' ? 'selected' : ''}>Pcs</option>
                            <option value="Kg" ${unit.toLowerCase() === 'kg' ? 'selected' : ''}>Kg</option>
                        </select>
                    </div>
                    <div>
                        <label>Harga Satuan</label>
                        <input type="number" min="0" step="0.01" class="form-control-modal modal-item-price" value="${price}">
                    </div>
                </div>
                <div class="grid-fixed-2" style="margin-top: 15px;">
                    <div style="text-align: left; margin-top: 14px;">
                        <button type="button" class="btn-modal-delete-row" style="background: #ffe5e5; color: #cc0000; border: none; padding: 7px 10px; border-radius: 14px; font-size: 12px; cursor: pointer;">Hapus</button>
                    </div>
                    <div>
                        <label style="width: 250px;"><b>Jumlah</b></label>
                        <input type="text" class="form-control-modal modal-item-subtotal" readonly style="background-color: #f9f9f9; font-weight: 600; color: brown;">
                    </div>
                </div>
            `;
            modalItemsContainer.appendChild(modalRow);
            bindRowEvents(modalRow);
            calculateModalSubtotal(modalRow);
        });
    }

    // 6. TERAPKAN DATA DARI MODAL KE TABEL HALAMAN UTAMA (SAVE/APPLY)
    if (btnApplyItems) {
        btnApplyItems.addEventListener('click', function () {
            const modalRows = modalItemsContainer.querySelectorAll('.modal-item-row');
            tbodySales.innerHTML = ''; 

            if (modalRows.length === 0) {
                tbodySales.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#999; padding: 15px;">Belum ada barang dipilih. Klik Edit Daftar Barang.</td></tr>`;
            } else {
                modalRows.forEach((row, index) => {
                    const itemId = row.querySelector('.modal-item-id').value; 
                    const name = row.querySelector('.modal-item-name').value;
                    const unit = row.querySelector('.modal-item-unit').value;
                    const qty = parseFloat(row.querySelector('.modal-item-qty').value) || 0;
                    const price = parseFloat(row.querySelector('.modal-item-price').value) || 0;
                    const subtotalRaw = qty * price;
                    const subtotalFormatted = 'Rp ' + subtotalRaw.toLocaleString('id-ID');

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <input type="hidden" name="items[${index}][item_id]" value="${itemId}">
                            <input type="text" name="items[${index}][item_name]" value="${name}" class="form-disabled-brown" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="qty form-disabled-brown" name="items[${index}][qty]" value="${qty}">
                        </td>
                        <td>
                            <input type="text" name="items[${index}][unit]" value="${unit}" class="form-disabled-brown">
                        </td>
                        <td>
                            <input type="number" step="0.01" class="price form-disabled-brown" name="items[${index}][unit_price]" value="${price}">
                        </td>
                        <td>
                            <input type="text" class="subtotal form-disabled-brown" value="${subtotalFormatted}">
                        </td>
                    `;
                    tbodySales.appendChild(tr);
                });
            }

            // Memicu hitung ulang Grand Total otomatis di file create.js utama
            const triggerInput = tbodySales.querySelector('.qty');
            if (triggerInput) {
                triggerInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            itemsModal.hide();
        });
    }
});