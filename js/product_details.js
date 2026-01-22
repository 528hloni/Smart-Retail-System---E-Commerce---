const decreaseBtn = document.getElementById('decrease');
const increaseBtn = document.getElementById('increase');
const quantityInput = document.getElementById('quantity');

decreaseBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    if (value > 1) quantityInput.value = value - 1;
});

increaseBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    quantityInput.value = value + 1;
});