// JavaScript to toggle the visibility of order details for each customer
document.querySelectorAll('.toggle-customer-details').forEach(button => {
    button.addEventListener('click', () => {
        const customerId = button.dataset.customerId;
        const orderDetails = document.getElementById(`order-details-${customerId}`);
        orderDetails.style.display = orderDetails.style.display === 'none' ? 'table-row' : 'none';
    });
});

// JavaScript to toggle the visibility of product details for each order
document.querySelectorAll('.toggle-details').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        const detailsRow = document.getElementById(`details-${orderId}`);
        const detailsDiv = detailsRow.querySelector('.order-details');

        if (detailsRow.classList.contains('active')) {
            detailsRow.classList.remove('active');
            detailsDiv.innerHTML = '';
            this.textContent = '+';
        } else {
            fetch('get_order_details.php?order_id=' + orderId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data); // Log the entire data object

                    // Clear previous details if any
                    detailsDiv.innerHTML = '';

                    // Loop through each product in the data array
                    data.forEach(product => {
                        // Create elements for each product and append them to detailsDiv
                        const productDiv = document.createElement('div');
                        productDiv.classList.add('product-details');

                        const img = document.createElement('img');
                        img.src = "images/"+product.image;
                        img.alt = 'Product Image';
                        img.style.maxWidth = '100px';
                        productDiv.appendChild(img);

                        const productInfo = document.createElement('div');
                        productInfo.classList.add('product-info');
                        productInfo.innerHTML = `
                            <p>${product.product_name}</p>
                            <p>$${product.price}</p>
                            <p>Quantity: ${product.quantity}</p>
                        `;
                        productDiv.appendChild(productInfo);

                        detailsDiv.appendChild(productDiv);
                    });

                    // Show the details row
                    detailsRow.classList.add('active');
                    this.textContent = '-';
                })
                .catch(error => console.error('Error fetching order details:', error));
        }
    });
});