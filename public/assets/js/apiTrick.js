let data
let template
let trickListDefault

document.addEventListener("DOMContentLoaded", function (e) {

    const button = document.querySelector('.ajaxButton')
    const gif = document.querySelector('#loadMoreGif')
    trickListDefault = document.querySelector(".trickListDefault")
    template = document.querySelector("#trickList")


    let currentPage = 1

    button.addEventListener('click', async (e) => {
        e.preventDefault()
        button.style.display = "none"
        gif.style.display = "inline-block"
        currentPage += 1
        const response = await fetch(`/api/tricks?page=${currentPage}`)
        data = await response.json()
        const tricks = data.tricks
        const picture = data.pictures
        const category = data.categories
        const comment = data.comments

        const totalPages = data.totalPages
        const user = data.connected;

        if (tricks.length > 0) {
            for (let i = 0; i < tricks.length; i++) {
                let clone = document.importNode(template.content, true);
                let a = clone.querySelectorAll("a");
                if (user === true) {
                    clone.querySelector(".post-title").innerHTML = `${tricks[i].name}<a href="trick/edit/${tricks[i].id}"><i class="bi bi-pen" style="color: black;"></i></a>
                    <button class="btn btn-danger btn-sm" id="button-remove-trick-js" data-link="/trick/delete/${tricks[i].id}"><i class="bi bi-trash"></i></button>`
                    clone.querySelector(".post-content").innerHTML = `${tricks[i].content.substr(0, 50)}`
                } else {
                    clone.querySelector(".post-title").innerHTML = `${tricks[i].name}`
                    clone.querySelector(".post-content").innerHTML = `${tricks[i].content.substr(0, 50)}`
                }
                a[0].outerHTML = `<a href="trick/show/${tricks[i].id}/${tricks[i].slug}"><img src="${picture[i]}"></a>`
                a[2].outerHTML = `<a href="trick/show/${tricks[i].id}/${tricks[i].slug}" class="btn btn-primary">Lire plus</a>`
                clone.querySelector(".post-category").innerHTML = `${category[i]}`
                clone.querySelector(".post-comment").innerHTML = `${comment[i]}`

                trickListDefault.appendChild(clone)
            }

            if (document.querySelector("#button-remove-trick-js")) {
                console.log('condition remove trick')
                let myButtonTrick = document.querySelectorAll("#button-remove-trick-js");
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
        }
   
        if (currentPage < totalPages) {

            button.style.display = "initial"
            gif.style.display = "none"
        } else {
            button.style.display = "none"
            gif.style.display = "none"
        }
    })

})
