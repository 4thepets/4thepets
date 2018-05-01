(function(){
    var grid;
    function init() {
        grid = new Minigrid({
            container: '.pageContentPets',
            item: '.card',
            gutter: 5
        });
        grid.mount();
    }
    // mount
    function update() {
        grid.mount();
    }
    document.addEventListener('DOMContentLoaded', init);
    window.addEventListener('resize', update);
})();