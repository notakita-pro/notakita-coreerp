document.addEventListener('DOMContentLoaded', function () {

    //----------------------------------------------------------
    // ELEMEN FORM UTAMA & PEMBAYARAN
    //----------------------------------------------------------
    const btnOpenPayment   = document.getElementById('btnEditPayment');
    const btnClosePayment  = document.getElementById('closePaymentModal');
    const btnCancelPayment = document.getElementById('btnCancelPayment');
    const btnApplyPayment  = document.getElementById('btnApplyPayment');
    const paymentModal     = document.getElementById('paymentModal');

    // Input di Form Utama
    const mainPaymentTerm   = document.getElementById('paymentTerm');
    const mainDownPayment   = document.getElementById('downPayment');
    const mainDueDate       = document.querySelector('input[name="due_date"]');
    const mainPaymentMethod = document.getElementById('paymentMethod');
    
    const mainGroupDueDate     = mainDueDate?.closest('.form-group') || document.getElementById('groupMainDueDate');
    const mainGroupDownPayment = mainDownPayment?.closest('.form-group') || document.getElementById('groupMainDownPayment');
    
// Input di dalam Modal Pembayaran
const modalPaymentTerm   = document.getElementById('modalPaymentTerm');
const modalDueDate       = document.getElementById('modalDueDate');
const modalDownPayment   = document.getElementById('modalDownPayment');
const modalPaymentMethod = document.getElementById('modalPaymentMethod');

if (modalDownPayment && modalPaymentMethod && modalPaymentTerm) {
    
    // Ambil elemen option "Belum Bayar" di dalam select Metode Bayar
    const unpaidOption = modalPaymentMethod.querySelector('option[value="unpaid"]');

    function checkPaymentMethodStatus() {
        const dpValue = parseFloat(modalDownPayment.value) || 0;
        const paymentTerm = modalPaymentTerm.value;

        // KONDISI 1: JIKA Sistem Bayar Tempo DAN DP-nya 0
        if (paymentTerm === 'credit' && dpValue === 0) {
            // Tampilkan kembali opsi 'unpaid' jika sebelumnya tersembunyi
            if (unpaidOption) unpaidOption.style.display = 'block';
            
            modalPaymentMethod.value = 'unpaid';
            modalPaymentMethod.style.pointerEvents = 'none';
            modalPaymentMethod.style.backgroundColor = '#f4f4f4';
        } 
        // KONDISI 2: JIKA ADA DP (> 0) ATAU SISTEM BAYAR TUNAI
        else {
            modalPaymentMethod.style.pointerEvents = 'auto';
            modalPaymentMethod.style.backgroundColor = '#ffffff';
            
            // SEMBUNYIKAN opsi 'Belum Bayar' agar tidak bisa dipilih sama sekali
            if (unpaidOption) {
                unpaidOption.style.display = 'none';
            }

            // Jika saat ini posisinya sedang 'unpaid', paksa ganti ke 'cash'
            if (modalPaymentMethod.value === 'unpaid') {
                modalPaymentMethod.value = 'cash'; // Default ke Uang Cash
            }
        }
    }

    // Jalankan fungsi pada event listener yang ada
    modalDownPayment.addEventListener('input', checkPaymentMethodStatus);
    modalPaymentTerm.addEventListener('change', checkPaymentMethodStatus);

    // Jalankan langsung di awal (Inisialisasi)
    checkPaymentMethodStatus();
}

    // Elemen Info Tambahan di Modal
    const modalInfoGrandTotal = document.getElementById('modalInfoGrandTotal');
    const modalInfoBalanceDue  = document.getElementById('modalInfoBalanceDue');
    
    //----------------------------------------------------------
    // ELEMEN KALKULASI INVOICE
    //----------------------------------------------------------
    const tbodySales       = document.getElementById('tbodySales');
    const subtotalAllInput = document.getElementById('subtotalAll');
    const discountInput    = document.getElementById('discount');
    const taxInput         = document.getElementById('tax');
    const transportInput   = document.getElementById('transport');
    const otherCostInput   = document.getElementById('other_cost');
    const grandTotalInput  = document.getElementById('grandTotal');
    const grandTotalHidden = document.getElementById('grandTotalHidden');

    const groupDueDate     = modalDueDate?.closest('.form-group');
    const groupDownPayment = modalDownPayment?.closest('.form-group');

    //----------------------------------------------------------
    // MODAL PEMBAYARAN: OPEN & CLOSE
    //----------------------------------------------------------
    if (btnOpenPayment) {
        btnOpenPayment.addEventListener('click', function () {
            modalPaymentTerm.value   = mainPaymentTerm.value;
            modalPaymentMethod.value = mainPaymentMethod.value;
            modalDueDate.value       = mainDueDate.value;
            modalDownPayment.value   = mainDownPayment.value;

            // Biar langsung menyembunyikan/menampilkan kolom saat modal terbuka
            toggleModalPaymentFields();
            paymentModal.classList.add('show');
        });
    }

    function closePaymentModal() {
        paymentModal?.classList.remove('show');
    }

    btnClosePayment?.addEventListener('click', closePaymentModal);
    btnCancelPayment?.addEventListener('click', closePaymentModal);

    // Tutup jika area luar modal diklik
    paymentModal?.addEventListener('click', function (e) {
        if (e.target === paymentModal) closePaymentModal();
    });

   //----------------------------------------------------------
    // ACTION TERAPKAN (DARI MODAL KE FORM UTAMA)
    //----------------------------------------------------------
    btnApplyPayment?.addEventListener('click', function () {
        mainPaymentTerm.value   = modalPaymentTerm.value;
        mainPaymentMethod.value = modalPaymentMethod.value;
        
        // Panggil element hidden input yang baru kita tambahkan di Blade
        const amountPaidHidden = document.getElementById('amountPaidHidden');
        const grandTotal = parseNumber(grandTotalHidden?.value || 0);
        
        if (modalPaymentTerm.value === 'cash') {
            mainDueDate.value       = '';
            mainDownPayment.value   = 0;
            
            // JIKA TUNAI (CASH): Uang yang dibayarkan otomatis penuh senilai Grand Total (Lunas)
            if (amountPaidHidden) amountPaidHidden.value = grandTotal;
            
            if (mainGroupDueDate) mainGroupDueDate.style.display = 'none';
            if (mainGroupDownPayment) mainGroupDownPayment.style.display = 'none';
        } else {
            mainDueDate.value       = modalDueDate.value;
            mainDownPayment.value   = modalDownPayment.value;
            
            // JIKA TEMPO (CREDIT): Uang yang dibayarkan di awal adalah nilai Down Payment (DP)
            if (amountPaidHidden) amountPaidHidden.value = parseNumber(modalDownPayment.value);
            
            if (mainGroupDueDate) mainGroupDueDate.style.display = 'block';
            if (mainGroupDownPayment) mainGroupDownPayment.style.display = 'block';
        }

        closePaymentModal();
        calculateInvoice();
    });

    //----------------------------------------------------------
    // LOGIKA TOGGLE DI DALAM MODAL PEMBAYARAN
    //----------------------------------------------------------
    function toggleModalPaymentFields() {
        if (modalPaymentTerm.value === 'cash') {
            if (groupDueDate) groupDueDate.style.display = 'none';
            if (groupDownPayment) groupDownPayment.style.display = 'none';
            
            modalDownPayment.value = 0;
            modalDueDate.value = '';
        } else {
            if (groupDueDate) groupDueDate.style.display = 'block';
            if (groupDownPayment) groupDownPayment.style.display = 'block';
        }
        updateModalPaymentInfo();
    }

    modalPaymentTerm?.addEventListener('change', toggleModalPaymentFields);
    modalDownPayment?.addEventListener('input', updateModalPaymentInfo);

    function updateModalPaymentInfo() {
        const grandTotal = parseNumber(grandTotalHidden?.value || 0);
        const dp         = parseNumber(modalDownPayment?.value || 0);
        const balanceDue = Math.max(0, grandTotal - dp);

        if (modalInfoGrandTotal) modalInfoGrandTotal.textContent = formatRupiah(grandTotal);
        if (modalInfoBalanceDue) modalInfoBalanceDue.textContent = formatRupiah(balanceDue);
    }

    //----------------------------------------------------------
    // LOGIKA SINKRONISASI AWAL FORM UTAMA (SAAT LOAD HALAMAN)
    //----------------------------------------------------------
    function initMainPaymentFields() {
        if (mainPaymentTerm && mainPaymentTerm.value === 'cash') {
            if (mainGroupDueDate) mainGroupDueDate.style.display = 'none';
            if (mainGroupDownPayment) mainGroupDownPayment.style.display = 'none';
        } else {
            if (mainGroupDueDate) mainGroupDueDate.style.display = 'block';
            if (mainGroupDownPayment) mainGroupDownPayment.style.display = 'block';
        }
    }

    //----------------------------------------------------------
    // LOGIKA KALKULASI FORMULA INVOICE
    //----------------------------------------------------------
    function calculateInvoice() {
        let subtotalAll = 0;

        const rows = tbodySales?.querySelectorAll('tr') || [];
        rows.forEach(row => {
            const qtyInput      = row.querySelector('.qty');
            const priceInput    = row.querySelector('.price');
            const subtotalInput = row.querySelector('.subtotal');

            if (qtyInput && priceInput) {
                const qty      = parseNumber(qtyInput.value);
                const price    = parseNumber(priceInput.value);
                const subtotal = qty * price;

                subtotalAll += subtotal;

                if (subtotalInput) {
                    subtotalInput.value = formatRupiah(subtotal);
                }
            }
        });

        const discount  = parseNumber(discountInput?.value || 0);
        const tax       = parseNumber(taxInput?.value || 0);
        const transport = parseNumber(transportInput?.value || 0);
        const otherCost = parseNumber(otherCostInput?.value || 0);

        const grandTotal = (subtotalAll - discount) + tax + transport + otherCost;

        if (subtotalAllInput) subtotalAllInput.value = formatRupiah(subtotalAll);
        if (grandTotalInput)  grandTotalInput.value  = formatRupiah(grandTotal);
        if (grandTotalHidden) grandTotalHidden.value = grandTotal;
    }

    tbodySales?.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
            calculateInvoice();
        }
    });

    [discountInput, taxInput, transportInput, otherCostInput].forEach(input => {
        input?.addEventListener('input', calculateInvoice);
    });

    //----------------------------------------------------------
    // HELPER FUNCTION (FORMATTING & PARSING)
    //----------------------------------------------------------
    function parseNumber(value) {
        const num = parseFloat(value);
        return isNaN(num) ? 0 : num;
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }

    //----------------------------------------------------------
    // EKSEKUSI AWAL SAAT LOAD
    //----------------------------------------------------------
    initMainPaymentFields();
    calculateInvoice();
});