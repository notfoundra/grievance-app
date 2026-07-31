const pages = document.querySelectorAll('.page');

const menus = document.querySelectorAll('.menu');

menus.forEach(menu => {

    menu.addEventListener('click', function () {

        pages.forEach(page => {

            page.classList.remove('active');

        });

        menus.forEach(item => {

            item.classList.remove('active');

        });

        this.classList.add('active');

        const page = document.getElementById(
            'page-' + this.dataset.page
        );

        if(page){

            page.classList.add('active');

        }

        document.getElementById('pageTitle').innerText =
            this.innerText;

    });

});
const sidebar=document.querySelector('.sidebar');

document
.getElementById('btnSidebar')
.addEventListener('click',()=>{

sidebar.classList.toggle('collapsed');

});