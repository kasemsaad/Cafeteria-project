
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-details');
    toggleButtons.forEach(button => {
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

                        // Loop through each product in the data array
                        data.forEach(product => {
                            // Create elements for each product and append them to detailsDiv
                            const productDiv = document.createElement('div');
                            productDiv.classList.add('product-details');

                            const img = document.createElement('img');
                            img.src = 'images/' + product.image;
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
                    })
                    .catch(error => console.error('Error fetching order details:', error));

                detailsRow.classList.add('active');
                this.textContent = '-';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
        const cancelButtons = document.querySelectorAll('.cancel-order');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                
                // Send an AJAX request to cancel_order.php with the order ID
                fetch('cancel_order.php?order_id=' + orderId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        // Handle the response, e.g., show a success message or reload the page
                        console.log('Order cancelled:', data);
                        // Reload the page to reflect the updated order status
                        window.location.reload();
                    })
                    .catch(error => console.error('Error cancelling order:', error));
            });
        });
    });