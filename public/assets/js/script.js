 window.addEventListener('scroll', function() {
    const headerMain = document.querySelector('.header-main');
    const headerTop = document.querySelector('.header-top');
    const body = document.body;
    
    const headerTopHeight = headerTop.offsetHeight;
    
    if (window.scrollY > headerTopHeight) {
        headerMain.classList.add('sticky-header');
        body.classList.add('header-sticky-active');
    } else {
        headerMain.classList.remove('sticky-header');
        body.classList.remove('header-sticky-active');
    }
});