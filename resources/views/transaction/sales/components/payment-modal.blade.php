{{-- ==========================================================
     PAYMENT MODAL
========================================================== --}}
<div id="paymentModal" class="payment-modal">
    <div class="payment-modal-card">

        {{-- ==========================================================
             HEADER
        ========================================================== --}}
        <div class="payment-modal-header">
            <h3>Detail Pembayaran</h3>
            <button type="button" id="closePaymentModal" class="payment-close">✕</button>
        </div>

        {{-- ==========================================================
             BODY
        ========================================================== --}}
        <div class="payment-modal-body">
            
            <div class="form-group">
                <label>Sistem Bayar</label>
                <select id="modalPaymentTerm">
                    <option value="cash">Tunai</option>
                    <option value="credit">Tempo</option>
                </select>
            </div>
            <div class="form-group">
                <label>DP (Uang Muka)</label>
                <input type="number" id="modalDownPayment" min="0" placeholder="0">
            </div>
            <div class="form-group">
                <label>Jatuh Tempo</label>
                <input type="date" id="modalDueDate">
            </div>


            <div class="form-group">
                <label>Metode Bayar</label>
                <select id="modalPaymentMethod">
                    <option value="cash">Uang Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="unpaid">Belum Bayar</option>
                </select>
            </div>

            {{-- Ringkasan info tambahan di dalam modal (Opsional, sangat berguna untuk Mobile UX) --}}
            
            <div class="payment-info-box" style="margin-top: 15px; padding: 12px; background: #f9f9f9; border-radius: 8px; font-size: 13px;">
               
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Total Invoice:</span>
                    <strong id="modalInfoGrandTotal">Rp 0</strong> 
                </div> 
                <div style="display: flex; justify-content: space-between; color: #c0392b;">
                    <span>Sisa Tagihan:</span>
                    <strong id="modalInfoBalanceDue">Rp 0</strong>
                </div>
            </div>

            {{-- ==========================================================
                 FOOTER / TOMBOL AKSI
            ========================================================== --}}
            <div class="payment-modal-footer">
                {{-- ===== <button type="button" id="btnCancelPayment" class="button btn-blue">Batal</button> === --}}
                <button type="button" id="btnApplyPayment" class="button btn-green">Terapkan</button>
            </div>

        </div>
    </div>
</div>

<style>
.payment-modal {
    position: fixed;
    inset: 0;
    display: none;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: rgba(0,0,0,.45);
    z-index: 9999;
}

.payment-modal.show {
    display: flex;
}

.payment-modal-card {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,.20);
    animation: paymentPopup .18s ease;
}

@keyframes paymentPopup {
    from {
        opacity: 0;
        transform: translateY(10px) scale(.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.payment-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 22px;
    border-bottom: 1px solid #ececec;
}

.payment-modal-header h3 {
    margin: 0;
    font-size: 20px;
}

.payment-close {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    background: #f3f3f3;
    font-size: 18px;
}

.payment-close:hover {
    background: #e8e8e8;
}

.payment-modal-body {
    padding: 24px;
}

.payment-modal .form-group {
    margin-bottom: 18px;
}

.payment-modal label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}

.payment-modal select,
.payment-modal input {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #d8d8d8;
    border-radius: 8px;
    background: #ffffef;
    font-size: 14px;
}

.payment-modal-footer {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 25px;
}

@media(max-width:768px){
    .payment-modal {
        padding: 12px;
    }
    .payment-modal-card {
        max-width: 100%;
    }
    .payment-modal-footer {
        flex-direction: column-reverse;
    }
    .payment-modal-footer .button {
        width: 100%;
    }
}
</style>