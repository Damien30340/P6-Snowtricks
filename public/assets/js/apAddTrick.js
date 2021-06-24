document.addEventListener("DOMContentLoaded", function (e) {

    const newItem = (e) => {
        const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);
        console.log(collectionHolder);
        const item = document.createElement(`div`);
        item.classList.add("col-4");
        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );
        console.log(item);

        item.querySelector(".btn-remove").addEventListener("click", () => item.remove());

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;
    };

    document
        .querySelectorAll('.btn-remove')
        .forEach(btn => {
            console.log(btn);
            btn.addEventListener("click", (e) => e.currentTarget.closest(".col-4").remove())
        });

    document
        .querySelectorAll('.btn-new')
        .forEach(btn => btn.addEventListener("click", newItem));
})


