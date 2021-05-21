let data
let template
let trickListDefault

document.addEventListener("DOMContentLoaded", function (e) {

    const button = document.querySelector('.ajaxButton')
    const gif = document.querySelector('#loadMoreGif')
    trickListDefault = document.querySelector(".tricklistDefault")
    template = document.querySelector("#tricklist")


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

        if (tricks.length > 0) {
            for (let i = 0; i < tricks.length; i++) {
                let clone = document.importNode(template.content, true)
                clone.querySelector(".post-title").innerHTML = `${tricks[i].name}`
                clone.querySelector(".post-content").innerHTML = `${tricks[i].content.substr(0,50)}`
                let a = clone.querySelectorAll("a")
                a[0].outerHTML = `<a class="smoothie btn btn-primary page-scroll" title="view article" href="trick/${tricks[i].id}">Voir</a>`
                a[1].outerHTML = `<a class="btn btn-primary mt30" href="trick/${tricks[i].id}">Lire plus</a>`
                clone.querySelector("img").outerHTML = `<img src="${picture[i]}" class="img-responsive smoothie" alt="title">`
                clone.querySelector(".post-category").innerHTML = `${category[i]}`

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
