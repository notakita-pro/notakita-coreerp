{{-- ==========================================================
     CUSTOMER MODAL
========================================================== --}}

<div
    id="customerModal"
    class="customer-modal">

    <div class="customer-modal-card">

        {{-- ==========================================================
             HEADER
        ========================================================== --}}

        <div class="customer-modal-header">

            <h3>

                Pilih Customer

            </h3>

            <button
                type="button"
                id="closeCustomerModal"
                class="customer-close">

                ✕

            </button>

        </div>

        {{-- ==========================================================
             BODY
        ========================================================== --}}

        <div class="customer-modal-body">

            {{-- ======================================================
                 PANEL PILIH CUSTOMER
            ======================================================= --}}

            <div id="customerSelector">

                <div class="form-group">

                    <label>

                        Pilih Pelanggan

                    </label>

                    <select
                        id="customerSelect">

                        <option value="">

                            Customer Umum

                        </option>

                        @foreach($customers as $customer)

                            <option
                                value="{{ $customer->id }}">

                                {{ $customer->name }}
                                @if(!empty($customer->address))
                                    - {{ $customer->address }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>

                <div style="margin-top:18px;">

                    <button
                        type="button"
                        id="btnShowCustomerForm"
                        class="button btn-blue">

                        + Customer Baru

                    </button>

                </div>

                <div class="customer-modal-footer">

                    <button
                        type="button"
                        id="btnContinueSale"
                        class="button btn-green">

                        Lanjut

                    </button>

                </div>

            </div>

            {{-- ======================================================
                 PANEL CUSTOMER BARU
            ======================================================= --}}

            <div
                id="customerForm"
                style="display:none;">

                <div class="form-group">

                    <label>

                        Nama Customer

                    </label>

                    <input
                        type="text"
                        id="modalCustomerName"
                        placeholder="Contoh : Andi">

                </div>

                <div class="form-group">

                    <label>

                        Alamat

                    </label>

                    <input
                        type="text"
                        id="modalCustomerAddress"
                        placeholder="Contoh : Pekanbaru">

                </div>
                                <div class="customer-modal-footer">

                    <button
                        type="button"
                        id="btnCancelCustomer"
                        class="button btn-blue">

                        ← Kembali

                    </button>

                    <button
                        type="button"
                        id="btnUseNewCustomer"
                        class="button btn-green">

                        Gunakan Customer

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.customer-modal{

    position:fixed;
    inset:0;
    display:none;
    justify-content:center;
    align-items:center;
    padding:20px;

    background:rgba(0,0,0,.45);

    z-index:9999;

}

.customer-modal.show{

    display:flex;

}

.customer-modal-card{

    width:100%;
    max-width:520px;

    background:#fff;

    border-radius:18px;

    overflow:hidden;

    box-shadow:0 20px 50px rgba(0,0,0,.20);

    animation:customerPopup .18s ease;

}

@keyframes customerPopup{

    from{

        opacity:0;

        transform:translateY(10px) scale(.98);

    }

    to{

        opacity:1;

        transform:translateY(0) scale(1);

    }

}

.customer-modal-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:18px 22px;

    border-bottom:1px solid #ececec;

}

.customer-modal-header h3{

    margin:0;

    font-size:20px;

}

.customer-close{

    width:38px;

    height:38px;

    border:none;

    border-radius:50%;

    cursor:pointer;

    background:#f3f3f3;

    font-size:18px;

}

.customer-close:hover{

    background:#e8e8e8;

}

.customer-modal-body{

    padding:24px;

}

.customer-modal .form-group{

    margin-bottom:18px;

}

.customer-modal label{

    display:block;

    margin-bottom:8px;

    font-weight:600;

}

.customer-modal select,

.customer-modal input{

    width:100%;

    padding:11px 12px;

    border:1px solid #d8d8d8;

    border-radius:8px;

    background:#ffffef;

    font-size:14px;

}

.customer-modal-footer{

    display:flex;

    justify-content:flex-end;

    gap:10px;

    margin-top:28px;

}

#customerSelector .customer-modal-footer{

    justify-content:flex-end;

}

#customerForm .customer-modal-footer{

    justify-content:space-between;

}

@media(max-width:768px){

    .customer-modal{

        padding:12px;

    }

    .customer-modal-card{

        max-width:100%;

    }

    .customer-modal-footer{

        flex-direction:column-reverse;

    }

    .customer-modal-footer .button{

        width:100%;

    }

}

</style>
