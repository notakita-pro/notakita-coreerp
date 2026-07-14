window.confirmAction = function(message, callback){

    const modal = document.getElementById("confirmModal");

    const text = document.getElementById("confirmMessage");

    const ok = document.getElementById("confirmOk");

    const cancel = document.getElementById("confirmCancel");

    text.innerHTML = message;

    modal.classList.add("show");

    ok.onclick=function(){

        modal.classList.remove("show");

        callback();

    };

    cancel.onclick=function(){

        modal.classList.remove("show");

    };

    modal.onclick=function(e){

        if(e.target===modal){

            modal.classList.remove("show");

        }

    };

}
function confirmDelete(url, message){

    const modal = document.getElementById('confirmModal');
    const text = document.getElementById('confirmMessage');

    text.innerHTML = message;

    modal.classList.add('show');

    document.getElementById('confirmOk').onclick = function(){

        modal.classList.remove('show');

        const form = document.getElementById('globalDeleteForm');

        form.action = url;

        form.submit();

    };

    document.getElementById('confirmCancel').onclick = function(){

        modal.classList.remove('show');

    };

}