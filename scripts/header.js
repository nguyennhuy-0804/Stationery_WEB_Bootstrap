
const searchInput = document.getElementById('search-input');
const dropdownMenu = document.getElementById('searchbar-dropdown-menu');

// khi gõ sách thanh tìm kiếm thì hiển thị dropdown
searchInput.addEventListener('input', function() {
    if (searchInput.value.length > 0) {
        dropdownMenu.style.display = 'block';
    } else {
        dropdownMenu.style.display = 'none';
    }
});

// Ản dropdown khi click ra ngoài
document.addEventListener('click', function(event) {
    if (!searchInput.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
    }
});