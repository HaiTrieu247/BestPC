
function renderPagination(totalPages, currentPage) {
    const pagination = document.getElementById("pagination-nav");
    if (!pagination) return;
    pagination.innerHTML = "";

    if (totalPages <= 1) return;

    const ul = document.createElement("ul");
    ul.className = "pagination justify-content-center mt-4";

    function makePageUrl(page) {
        const params = new URLSearchParams(window.location.search);
        params.set("page", page); 
        return "?" + params.toString(); 
    }

    // Prev
    const prev = document.createElement("li");
    prev.className = "page-item" + (currentPage === 1 ? " disabled" : "");
    prev.innerHTML = `<a class="page-link" href="${makePageUrl(currentPage - 1)}">« Prev</a>`;
    ul.appendChild(prev);

    // Pages
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement("li");
        li.className = "page-item" + (i === currentPage ? " active" : "");
        li.innerHTML = `<a class="page-link" href="${makePageUrl(i)}">${i}</a>`;
        ul.appendChild(li);
    }

    // Next
    const next = document.createElement("li");
    next.className = "page-item" + (currentPage === totalPages ? " disabled" : "");
    next.innerHTML = `<a class="page-link" href="${makePageUrl(currentPage + 1)}">Next »</a>`;
    ul.appendChild(next);

    pagination.appendChild(ul);
}
