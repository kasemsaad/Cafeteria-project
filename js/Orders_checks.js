$(document).ready(function() {
    $('.deliver-btn').click(function() {
        var orderId = $(this).data('order-id');
        $.ajax({
            url: 'update_order_status.php',
            type: 'POST',
            data: { order_id: orderId },
            success: function(response) {
                // Assuming response is the new status
                if (response === 'Completed') {
                    alert('Order delivered successfully.');
                    // Optionally, update UI to reflect the change in status
                    // You can hide the delivered order or update its status indicator
                } else {
                    alert('Failed to deliver order. Please try again.');
                }
            },
            error: function() {
                alert('Error occurred while delivering order. Please try again.');
            }
        });
    });
});