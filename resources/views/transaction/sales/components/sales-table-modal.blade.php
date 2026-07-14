{{-- ==========================================================
     SALES TABLE MODAL (EDIT DAFTAR BARANG / JASA)
========================================================== --}}
<div id="itemsModal" class="payment-modal">
    <div class="payment-modal-card" style="max-width: 800px; width: 100%;">

        {{-- ==========================================================
             HEADER MODAL
        ========================================================== --}}
        <div class="payment-modal-header">
            <h3 style="font-weight: 600; font-size: 1.1rem; color: #333; margin: 0;">Edit Daftar Barang / Jasa</h3>
            <button type="button" class="payment-close btn-close-modal">✕</button>
        </div>

        {{-- ==========================================================
             BODY MODAL
        ========================================================== --}}
        <div class="payment-modal-body" style="padding: 20px;">
            
            <div id="modalItemsContainer" style="max-height: 55vh; overflow-y: auto; padding-right: 5px;">
                {{-- Baris item di dalam modal akan di-generate otomatis secara dinamis lewat JavaScript --}}
            </div>

            <div class="grid-fixed-2">
<button type="button" id="btnAddModalRow" class="button btn-blue" style="padding: 8px 15px; font-size: 13px; background-color: #007bff; border: none; border-radius: 6px; color: white; cursor: pointer;">
                    + Tambah Barang
                </button>
                
                <button class="button btn-green style="margin: 10px; border-top: 1px solid #ececec; padding: 10px;">
                
                <type="button" id="btnApplyItems" ">SIMPAN</button>
            </div>
                
                
            </div>

            {{-- ==========================================================
                 FOOTER / TOMBOL AKSI
            ========================================================== --}}
            

        </div>
    </div>
</div>
