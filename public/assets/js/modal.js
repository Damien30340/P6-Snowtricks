// Modal for ACCOUNT Delete
if (document.querySelector("#button-remove-account")) {
    console.log('condition remove account')
    let myButtonAccount = document.querySelector("#button-remove-account");
    let myModalAccount = new bootstrap.Modal(document.getElementById('modal'));

    myButtonAccount.addEventListener("click", function (e) {
        let buttonConfirm = myModalAccount._element.querySelector("#confirm");
        buttonConfirm.href = myButtonAccount.dataset.link;
        myModalAccount._element.querySelector("#titleModal").innerHTML = "Supprimer mon compte"
        myModalAccount._element.querySelector(".modal-body").innerHTML = `Attention !!!! <br> Vous êtes sur le point de supprimer votre compte,<br> cette action est irréversible !`
        myModalAccount.show();
    })

}

// Modal for TRICK Delete
if (document.querySelector("#button-remove-trick")) {
    console.log('condition remove trick')
    let myButtonTrick = document.querySelectorAll("#button-remove-trick");
    let myModalTrick = new bootstrap.Modal(document.getElementById('modal'));

    for (let i = 0; i < myButtonTrick.length; i++) {
        myButtonTrick[i].addEventListener("click", function (e) {
            let buttonConfirm = myModalTrick._element.querySelector("#confirm");
            buttonConfirm.href = myButtonTrick[i].dataset.link;
            myModalTrick._element.querySelector("#titleModal").innerHTML = "Supprimer la figure"
            myModalTrick.show();
        })
    }
}


// Modal for COMMENT Delete
if (document.querySelector("#button-remove-comment")) {
    console.log('condition remove comment')
    let myButtonComment = document.querySelectorAll("#button-remove-comment");
    let myModal = new bootstrap.Modal(document.getElementById('modal'));

    for (let i = 0; i < myButtonComment.length; i++) {
        myButtonComment[i].addEventListener("click", function (e) {
            let buttonConfirm = myModal._element.querySelector("#confirm");
            buttonConfirm.href = myButtonComment[i].dataset.link;
            myModal._element.querySelector("#titleModal").innerHTML = "Supprimer votre commentaire"
            myModal.show();
        })
    }
}