document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector(".search-box input[type='text']");
    
    searchInput.addEventListener("focus", function() {
        this.dataset.placeholder = this.placeholder;
        this.placeholder = "";
    });

    searchInput.addEventListener("blur", function() {
        this.placeholder = this.dataset.placeholder;
    });
});