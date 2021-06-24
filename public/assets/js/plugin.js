// const preloader = document.createElement("div");
// const preloaderImg = document.createElement("div");
// const span = document.createElement("span");
// const img = document.createElement("img");
//
// preloader.className = 'preloader';
// preloaderImg.className = 'preloader-img';
// span.className = "loading-animation animate-flicker";
// img.src = "{{ asset('assets/img/loading.GIF') }}";
// img.alt = "loading";
//
// span.appendChild(img);
// preloaderImg.appendChild(span);
// preloader.appendChild(preloaderImg);
//
// console.log(preloader);
// const currentDiv = document.querySelector('master-wrapper');
//
// document.body.insertBefore(preloader, currentDiv);



document.addEventListener("DOMContentLoaded", function (e) {
    let loader = document.querySelector('.preloader');
    loader.style.display = "none";
})