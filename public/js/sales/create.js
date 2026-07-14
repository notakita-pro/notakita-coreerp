/**
 * ==========================================================
 * SALES CREATE
 * CoreERP Gen-Z
 * ==========================================================
 */

let rowIndex = document.querySelectorAll('#tbodySales tr').length;

/* ==========================================================
   TAMBAH BARIS
========================================================== */

const addRowButton = document.getElementById('addRow');

if (addRowButton) {

    addRowButton.addEventListener('click', addRow);

}

function addRow() {

    const tbody = document.getElementById('tbodySales');

    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td>
            <input
                type="hidden"
                name="items[${rowIndex}][item_id]">

            <input
                type="text"
                name="items[${rowIndex}][item_name]"
                placeholder="Nama Barang"
                required>
        </td>

        <td>
            <input
                type="text"
                name="items[${rowIndex}][unit]"
                placeholder="pcs">
        </td>

        <td>
            <input
                type="number"
                class="qty"
                name="items[${rowIndex}][qty]"
                value="1"
                min="0"
                step="0.01">
        </td>

        <td>
            <input
                type="number"
                class="price"
                name="items[${rowIndex}][unit_price]"
                value="0"
                min="0"
                step="0.01">
        </td>

        <td>
            <input
                type="text"
                class="subtotal"
                value="Rp 0"
                readonly>
        </td>

        <td style="text-align:center">

            <button
                type="button"
                class="btn-delete">

                ❌

            </button>

        </td>
    `;

    tbody.appendChild(tr);

    rowIndex++;

}

/* ==========================================================
   HITUNG TOTAL
========================================================== */

document.addEventListener('input', function (e) {

    if (

        e.target.classList.contains('qty') ||

        e.target.classList.contains('price') ||

        [
            'discount',
            'tax',
            'transport',
            'other_cost'
        ].includes(e.target.id)

    ) {

        calculate();

    }

});

function calculate() {

    let subtotal = 0;

    document.querySelectorAll('#tbodySales tr').forEach(function (row) {

        const qty = parseFloat(row.querySelector('.qty').value) || 0;

        const price = parseFloat(row.querySelector('.price').value) || 0;

        const total = qty * price;

        subtotal += total;

        row.querySelector('.subtotal').value =
            'Rp ' + total.toLocaleString('id-ID');

    });

    document.getElementById('subtotalAll').value =
        'Rp ' + subtotal.toLocaleString('id-ID');

    const discount =
        parseFloat(document.getElementById('discount').value) || 0;

    const tax =
        parseFloat(document.getElementById('tax').value) || 0;

    const transport =
        parseFloat(document.getElementById('transport').value) || 0;

    const other =
        parseFloat(document.getElementById('other_cost').value) || 0;

    const grandTotal =
        subtotal
        - discount
        + tax
        + transport
        + other;

    document.getElementById('grandTotal').value =
        'Rp ' + grandTotal.toLocaleString('id-ID');

    document.getElementById('grandTotalHidden').value =
        grandTotal;

}

/* ==========================================================
   HAPUS BARIS
========================================================== */

document.addEventListener('click', function (e) {

    if (!e.target.classList.contains('btn-delete')) {

        return;

    }

    if (document.querySelectorAll('#tbodySales tr').length <= 1) {

        return;

    }

    e.target.closest('tr').remove();

    reindexRows();

    calculate();

});

/* ==========================================================
   REINDEX
========================================================== */

function reindexRows() {

    document.querySelectorAll('#tbodySales tr').forEach(function (row, index) {

        row.querySelectorAll('input').forEach(function (input) {

            if (!input.name) {

                return;

            }

            input.name = input.name.replace(

                /items\[\d+\]/,

                'items[' + index + ']'

            );

        });

    });

    rowIndex =
        document.querySelectorAll('#tbodySales tr').length;

}

/* ==========================================================
   ENTER OTOMATIS TAMBAH BARIS
========================================================== */

document.addEventListener('keydown', function (e) {

    if (

        e.key !== 'Enter' ||

        !e.target.classList.contains('price')

    ) {

        return;

    }

    const rows =
        document.querySelectorAll('#tbodySales tr');

    const current =
        e.target.closest('tr');

    if (current !== rows[rows.length - 1]) {

        return;

    }

    e.preventDefault();

    addRow();

    setTimeout(function () {

        const lastRow =
            document.querySelectorAll('#tbodySales tr');

        lastRow[lastRow.length - 1]
            .querySelector('input[name*="[item_name]"]')
            .focus();

    }, 30);

});

/* ==========================================================
   INIT
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    calculate();

});