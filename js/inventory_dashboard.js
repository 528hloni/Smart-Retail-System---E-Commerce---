document.addEventListener('DOMContentLoaded', function() {

    // Autocomplete search logic 
       
    const searchInput = document.getElementById('search_input');
    const table = document.getElementById('inventory_table');
    const rows = table.getElementsByTagName('tr');

    // Create suggestion box
    const suggestionBox = document.createElement('div');
    suggestionBox.style.border = "1px solid #ccc";
    suggestionBox.style.position = "absolute";
    suggestionBox.style.background = "white";
    suggestionBox.style.zIndex = "999";
    searchInput.parentNode.insertBefore(suggestionBox, searchInput.nextSibling);

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        suggestionBox.innerHTML = ''; // Clear suggestions

        if (filter === '') {
            suggestionBox.style.display = 'none';
        } else {
            let matches = [];

            for (let i = 1; i < rows.length; i++) {
                const name = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                const model = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
                if (name.includes(filter) || model.includes(filter)) {
                    matches.push(rows[i].getElementsByTagName('td')[1].textContent);
                }
            }

            // Show up to 5 suggestions
            matches.slice(0, 5).forEach(m => {
                const div = document.createElement('div');
                div.textContent = m;
                div.style.padding = '5px';
                div.style.cursor = 'pointer';
                div.addEventListener('click', function() {
                    searchInput.value = m;
                    suggestionBox.innerHTML = '';
                    suggestionBox.style.display = 'none';
                    searchInput.dispatchEvent(new Event('keyup'));
                });
                suggestionBox.appendChild(div);
            });

            suggestionBox.style.display = matches.length > 0 ? 'block' : 'none';
        }

        // Filter table rows
        for (let i = 1; i < rows.length; i++) {
            const name = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            const model = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
            rows[i].style.display = (name.includes(filter) || model.includes(filter)) ? '' : 'none';
        }
    });



    // Real time stock update logic 
        
    function updateStock() {
        fetch('fetch_stock.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const stockCell = document.getElementById('stock_' + item.rim_id);
                    if (stockCell) {
                        const oldValue = stockCell.textContent;
                        if (oldValue != item.quantity) {
                            //Update value
                            stockCell.textContent = item.quantity;

                            //Flash background color to indicate change
                            stockCell.style.backgroundColor = '#d4edda';
                            setTimeout(() => stockCell.style.backgroundColor = '', 600);
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching stock:', error));
    }

    //Run immediately on load
    updateStock();

    //Update stock every 10 seconds
    setInterval(updateStock, 10000);

});