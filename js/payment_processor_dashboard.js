//Javascript real time update
const paymentsBody = document.getElementById('paymentsBody');

function fetchPayments() {
    fetch(PAYMENTS_AJAX_URL)
        .then(res => res.json())
        .then(data => {
            paymentsBody.innerHTML = '';
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.payment_id}</td>
                    <td>${row.order_id}</td>
                    <td>${row.customer}</td>
                    <td>${parseFloat(row.amount).toFixed(2)}</td>
                    <td>${row.method}</td>
                    <td><a href="pp_verification.php?payment_id=${row.payment_id}">Verify Payment</a></td>
                `;
                paymentsBody.appendChild(tr);
            });
        })
        .catch(err => console.error(err));
}

// Refresh every 5 seconds
setInterval(fetchPayments, 5000);

// Initial load
fetchPayments();