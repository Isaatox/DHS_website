let link = document.getElementById('icon');
let burger = document.getElementById('burger');
let menuBtn = document.querySelector('.menu_button');

link.addEventListener('click', function (e) {
  burger.classList.toggle('open');
  menuBtn.classList.toggle('open');
});
