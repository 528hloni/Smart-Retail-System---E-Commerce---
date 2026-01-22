    const searchInput = document.getElementById("search_input");
    const autocompleteList = document.getElementById("autocomplete-results");
    const filterSelect = document.getElementById("filter");
    const rows = Array.from(document.querySelectorAll("#orders tbody tr"));
    const paginationContainer = document.getElementById("pagination");

    let currentPage = 1;
    const rowsPerPage = 10;

    // JAVASCRIPT AUTOCOMPLETE SEARCH 
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        autocompleteList.innerHTML = "";
        if (!query) {
            autocompleteList.style.display = "none";
            applyFilters();
            return;
        }

        const matches = [];
        rows.forEach(row => {
            const customer = row.cells[1].innerText.toLowerCase();
            if (customer.includes(query)) matches.push(customer);
        });

        const uniqueMatches = [...new Set(matches)];
        if (uniqueMatches.length > 0) {
            autocompleteList.style.display = "block";
            uniqueMatches.forEach(name => {
                const div = document.createElement("div");
                div.classList.add("autocomplete-item");
                div.innerText = name;
                div.onclick = () => {
                    searchInput.value = name;
                    autocompleteList.style.display = "none";
                    applyFilters();
                };
                autocompleteList.appendChild(div);
            });
        } else {
            autocompleteList.style.display = "none";
        }

        applyFilters();
    });

    document.addEventListener("click", e => {
        if (e.target !== searchInput) autocompleteList.style.display = "none";
    });

    // JAVASCRIPT FILTER ORDERS
    filterSelect.addEventListener("change", applyFilters);

    function applyFilters() {
        const searchQuery = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value.toLowerCase();

        rows.forEach(row => {
            const customer = row.cells[1].innerText.toLowerCase();
            const status = row.cells[4].innerText.toLowerCase();

            const matchesSearch = customer.includes(searchQuery);
            const matchesStatus = filterValue === "" || status === filterValue;

            if (matchesSearch && matchesStatus) {
                row.classList.remove("hide");
            } else {
                row.classList.add("hide");
            }
        });

        currentPage = 1;
        displayPage();
    }

    //  PAGINATION 
    function displayPage() {
        const visibleRows = rows.filter(row => !row.classList.contains("hide"));
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

        visibleRows.forEach((row, index) => {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            if (index >= start && index < end) row.style.display = "";
            else row.style.display = "none";
        });

        paginationContainer.innerHTML = "";
        if (totalPages > 1) {
            const prev = document.createElement("button");
            prev.innerText = "Prev";
            prev.disabled = currentPage === 1;
            prev.classList.toggle("disabled", currentPage === 1);
            prev.onclick = () => { if (currentPage > 1) { currentPage--; displayPage(); } };
            paginationContainer.appendChild(prev);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.innerText = i;
                if (i === currentPage) btn.classList.add("active");
                btn.onclick = () => { currentPage = i; displayPage(); };
                paginationContainer.appendChild(btn);
            }

            const next = document.createElement("button");
            next.innerText = "Next";
            next.disabled = currentPage === totalPages;
            next.classList.toggle("disabled", currentPage === totalPages);
            next.onclick = () => { if (currentPage < totalPages) { currentPage++; displayPage(); } };
            paginationContainer.appendChild(next);
        }
    }

    
    applyFilters();