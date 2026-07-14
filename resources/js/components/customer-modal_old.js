document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('customerModal');

    if (!modal) return;

    const btnOpen = document.getElementById('btnCreateSale');

    const btnClose = document.getElementById('closeCustomerModal');

    const btnContinue = document.getElementById('btnContinueSale');

    const customerSelect = document.getElementById('customerSelect');

    const inputName = document.getElementById('modalCustomerName');

    const inputAddress = document.getElementById('modalCustomerAddress');

    //----------------------------------------------------------
    // ROUTE CREATE
    //----------------------------------------------------------

    const createRoute = window.salesCreateRoute;

    //----------------------------------------------------------
    // OPEN
    //----------------------------------------------------------

    btnOpen.addEventListener('click', function () {

        modal.classList.add('show');

    });

    //----------------------------------------------------------
    // CLOSE
    //----------------------------------------------------------

    btnClose.addEventListener('click', function () {

        modal.classList.remove('show');

    });

    modal.addEventListener('click', function (e) {

        if (e.target === modal) {

            modal.classList.remove('show');

        }

    });

    //----------------------------------------------------------
    // SHOW FORM
    //----------------------------------------------------------

    btnShowForm.addEventListener('click', function () {

        form.style.display = 'block';

        inputName.focus();

    });

    //----------------------------------------------------------
    // CANCEL
    //----------------------------------------------------------

    btnCancel.addEventListener('click', function () {

        form.style.display = 'none';

        inputName.value = '';

        inputAddress.value = '';

    });

    //----------------------------------------------------------
    // SAVE CUSTOMER (sementara local)
    //----------------------------------------------------------

    btnSave.addEventListener('click', function () {

        const name = inputName.value.trim();

        const address = inputAddress.value.trim();

        if (name === '') {

            alert('Nama customer wajib diisi');

            return;

        }

        const option = document.createElement('option');

        option.value = 'new';

        option.dataset.name = name;

        option.dataset.address = address;

        option.textContent = address
            ? `${name} - ${address}`
            : name;

        select.appendChild(option);

        select.value = 'new';

        form.style.display = 'none';

    });

    //----------------------------------------------------------
    // LANJUT
    //----------------------------------------------------------

    btnContinue.addEventListener('click', function () {

        const value = select.value;

        if (value === '') {

            window.location.href = createRoute;

            return;

        }

        if (value === 'new') {

            const name = encodeURIComponent(inputName.value);

            const address = encodeURIComponent(inputAddress.value);

            window.location.href =
                createRoute +
                '?new_customer_name=' +
                name +
                '&new_customer_address=' +
                address;

            return;

        }

        window.location.href =
            createRoute +
            '?customer=' +
            value;

    });

});