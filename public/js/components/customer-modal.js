document.addEventListener('DOMContentLoaded', function () {

    //----------------------------------------------------------
    // ELEMENT
    //----------------------------------------------------------

    const modal = document.getElementById('customerModal');

    if (!modal) return;

    const btnOpen =
    document.getElementById('btnCreateSale') ||
    document.getElementById('btnCreateSaleEmpty');
    const btnClose = document.getElementById('closeCustomerModal');

    const btnContinue = document.getElementById('btnContinueSale');

    const btnShowForm = document.getElementById('btnShowCustomerForm');
    const btnUseNew = document.getElementById('btnUseNewCustomer');
    const btnCancel = document.getElementById('btnCancelCustomer');
    const btnSave = document.getElementById('btnSaveCustomer');

    const customerSelector = document.getElementById('customerSelector');
    const customerForm = document.getElementById('customerForm');

    const customerSelect = document.getElementById('customerSelect');

    const inputName = document.getElementById('modalCustomerName');
    const inputAddress = document.getElementById('modalCustomerAddress');

    const createRoute = window.salesCreateRoute || '';

    //----------------------------------------------------------
    // OPEN
    //----------------------------------------------------------

    if (btnOpen) {

        btnOpen.addEventListener('click', function () {

            modal.classList.add('show');

        });

    }

    //----------------------------------------------------------
    // CLOSE
    //----------------------------------------------------------

    function closeModal() {

        modal.classList.remove('show');

    }

    btnClose?.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {

        if (e.target === modal) {

            closeModal();

        }

    });
    
    

    document.addEventListener('keydown', function (e) {

        if (e.key === 'Escape' && modal.classList.contains('show')) {

            closeModal();

        }

    });

    //----------------------------------------------------------
    // PINDAH KE FORM CUSTOMER BARU
    //----------------------------------------------------------

    btnShowForm?.addEventListener('click', function () {

        customerSelector.style.display = 'none';

        customerForm.style.display = 'block';

        inputName.focus();

    });

    //----------------------------------------------------------
    // KEMBALI
    //----------------------------------------------------------

    btnCancel?.addEventListener('click', function () {

        customerForm.style.display = 'none';

        customerSelector.style.display = 'block';

    });

//----------------------------------------------------------
// GUNAKAN CUSTOMER BARU
//----------------------------------------------------------

btnUseNew?.addEventListener('click', function () {

    const name = inputName.value.trim();

    if (name === '') {

        alert('Nama Customer wajib diisi.');

        inputName.focus();

        return;

    }

    if (createRoute === '') {

        alert('salesCreateRoute belum ditemukan.');

        return;

    }

    const url = new URL(createRoute, window.location.origin);

    url.searchParams.set(
        'new_customer_name',
        name
    );

    url.searchParams.set(
        'new_customer_address',
        inputAddress.value.trim()
    );

    window.location.href = url.toString();

});

    //----------------------------------------------------------
    // LANJUT
    //----------------------------------------------------------
    btnContinue?.addEventListener('click', function () {
        if (createRoute === '') {
            alert('salesCreateRoute belum ditemukan.');
            return;
        }

        const url = new URL(createRoute, window.location.origin);
        const selected = customerSelect.selectedOptions[0];

        //------------------------------------------------------
        // CUSTOMER BARU
        //------------------------------------------------------
        if (customerSelect.value === 'new') {
            url.searchParams.set('new_customer_name', selected.dataset.name);
            url.searchParams.set('new_customer_address', selected.dataset.address);
        }
        //------------------------------------------------------
        // CUSTOMER LAMA (PERBAIKAN DI SINI)
        //------------------------------------------------------
        else if (customerSelect.value !== '') {
            // Ubah dari 'customer' menjadi 'customer_id' agar sinkron
            url.searchParams.set('customer_id', customerSelect.value);
        }

        window.location.href = url.toString();
    });

});