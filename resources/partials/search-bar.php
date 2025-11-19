<style>
#searchResults {
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
}

.search-item {
    padding: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.search-item:hover {
    background: #f4f4f4;
}

.search-item img {
    border-radius: 4px;
}

</style>

<form class="search" id="liveSearchForm" action="index.php" method="get" role="search">
    <input type="hidden" name="route" value="search">
    <input name="q" 
            id="searchInput" 
            type="search" 
            placeholder="What are you looking for?" 
            aria-label="Search" 
            autocomplete="off"
            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> SEARCH
    </button>
</form>
<div id="searchResults" class="search-dropdown"></div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
let keyword = this.value.trim();

if (keyword.length === 0) {
    document.getElementById('searchResults').innerHTML = '';
    return;
}

fetch("index.php?route=live-search&q=" + encodeURIComponent(keyword))
    .then(res => res.json())
    .then(data => {
        let html = "";
        if (data.length > 0) {
            data.forEach(item => {
                html += `<div class="search-item" data-id="${item.Pid}">
                        <img src="images/products/${item.Pimage}" width="40">
                        <span>${item.Pname}</span>
                    </div>`;
            });
        } else {
            html = "<div class='search-item no-result'>No results found</div>";
        }
        document.getElementById('searchResults').innerHTML = html;
    });
});

document.getElementById('searchResults').addEventListener('click', function(e) {
let item = e.target.closest('.search-item');
if (item && item.dataset.id) {
    window.location.href = "index.php?route=view-detail&id=" + item.dataset.id;
}
});
</script>