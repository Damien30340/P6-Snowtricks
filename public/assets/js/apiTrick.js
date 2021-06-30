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
        console.log(data);
        const tricks = data.tricks
        const picture = data.pictures
        const category = data.categories
        const comment = data.comments


        if (tricks.length > 0) {
            for (let i = 0; i < tricks.length; i++) {
                let clone = document.importNode(template.content, true)
                clone.querySelector(".post-title").innerHTML = `${tricks[i].name} <a href="trick/edit/${tricks[i].id}"><i class="bi bi-pen" style="color: black;"></i></a>
                                            <a href="trick/delete/${tricks[i].id}"><i class="bi bi-trash" style="color: black;"></i></a>`
                clone.querySelector(".post-content").innerHTML = `${tricks[i].content.substr(0, 50)}`
                let a = clone.querySelectorAll("a")
                a[0].outerHTML = `<a href="trick/show/${tricks[i].id}"><img src="${picture[i]}"></a>`
                a[3].outerHTML = `<a href="trick/show/${tricks[i].id}" class="btn btn-primary">Lire plus</a>`
                clone.querySelector(".post-category").innerHTML = `${category[i]}`
                clone.querySelector(".post-comment").innerHTML = `${comment[i]}`

                trickListDefault.appendChild(clone)
            }

            button.style.display = "initial"
            gif.style.display = "none"
        } else {
            button.style.display = "none"
            gif.style.display = "none"
        }
    })


})
