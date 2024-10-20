// Hàm để thêm sản phẩm vào giỏ hàng
function addToCart(productId, variantId, productName, price, salePrice, quantity, image) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const existingItemIndex = cart.findIndex(item => 
        item.variant_id === variantId
    );

    if (existingItemIndex > -1) {
        cart[existingItemIndex].quantity += quantity;
    } else {
        cart.push({
            product_id: productId,
            variant_id: variantId,
            name: productName,
            price: price,
            sale_price: salePrice,
            quantity: quantity,
            image: image
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Đã thêm vào giỏ hàng');
    updateCartDisplay();
}

// Hàm để hiển thị giỏ hàng
function displayCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContent = document.getElementById('cart-content');
    const totalAmountDisplay = document.getElementById('total-amount');
    let totalAmount = 0;

    cartContent.innerHTML = '';

    if (cart.length === 0) {
        cartContent.innerHTML = "<tr><td colspan='6'>Giỏ hàng của bạn đang trống.</td></tr>";
        totalAmountDisplay.innerHTML = '0 đ';
        return;
    }

    cart.forEach((item, index) => {
        const price = item.sale_price && parseFloat(item.sale_price) > 0 ? item.sale_price : item.price;
        const totalPrice = price * item.quantity;
        totalAmount += totalPrice;

        cartContent.innerHTML += `
            <tr>
                <td class="product__cart__item">
                    <div class="product__cart__item__pic">
                        <img src="${item.image}" alt="${item.name}" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    </div>
                    <div class="product__cart__item__text">
                        <h6>${item.name}</h6>
                        <h5>${parseFloat(price).toLocaleString('vi-VN')} đ</h5>
                        <p>Variant ID: ${item.variant_id}</p>
                        <div id="variant-info-${item.variant_id}">Đang tải...</div>
                    </div>
                </td>
                <td class="quantity__item">
                    <div class="quantity">
                        <div class="pro-qty-2">
                            <input type="number" value="${item.quantity}" onchange="updateCart(${index}, this.value)">
                        </div>
                    </div>
                </td>
                <td class="cart__price">${totalPrice.toLocaleString('vi-VN')} đ</td>
                <td class="cart__close"><i class="fa fa-close" onclick="removeFromCart(${index})"></i></td>
            </tr>
        `;

        // Gọi hàm để lấy thông tin variant
        getVariantInfo(item.variant_id);
    });

    totalAmountDisplay.innerHTML = `${totalAmount.toLocaleString('vi-VN')} đ`;
}

// Hàm để lấy thông tin variant
function getVariantInfo(variantId) {
    fetch(`get_variant_info.php?variant_id=${variantId}`)
        .then(response => response.json())
        .then(data => {
            const variantInfoElement = document.getElementById(`variant-info-${variantId}`);
            if (variantInfoElement) {
                variantInfoElement.innerHTML = `
                    <p>Màu: ${data.color_name}</p>
                    <p>Kích thước: ${data.size_name}</p>
                `;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hàm để cập nhật thông tin sản phẩm khi chọn variant
function updateProductInfo() {
    const selectedVariant = document.querySelector('input[name="variant"]:checked');
    if (selectedVariant) {
        const variantId = selectedVariant.value;
        const price = selectedVariant.dataset.price;
        const salePrice = selectedVariant.dataset.salePrice;
        const stock = selectedVariant.dataset.stock;

        document.getElementById('product_price').textContent = parseFloat(price).toLocaleString('vi-VN') + ' đ';
        if (salePrice && parseFloat(salePrice) > 0) {
            document.getElementById('product_sale_price').textContent = parseFloat(salePrice).toLocaleString('vi-VN') + ' đ';
            document.getElementById('product_sale_price').style.display = 'inline';
            document.getElementById('product_price').classList.add('original-price');
        } else {
            document.getElementById('product_sale_price').style.display = 'none';
            document.getElementById('product_price').classList.remove('original-price');
        }
        document.getElementById('product_stock').textContent = stock;

        // Cập nhật thông tin màu sắc và kích thước
        fetch(`get_variant_info.php?variant_id=${variantId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('selected_color').textContent = data.color_name;
                document.getElementById('selected_size').textContent = data.size_name;
            })
            .catch(error => console.error('Error:', error));
    }
}

// Hàm để cập nhật số lượng sản phẩm trong giỏ hàng
function updateCart(index, newQuantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    newQuantity = parseInt(newQuantity);

    if (newQuantity <= 0) {
        removeFromCart(index);
    } else {
        cart[index].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
        updateCartDisplay();
    }
}

// Hàm để xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
    updateCartDisplay();
}

// Hàm để cập nhật hiển thị giỏ hàng (số lượng và tổng tiền)
function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalAmount = cart.reduce((sum, item) => {
        const price = item.sale_price && parseFloat(item.sale_price) > 0 ? item.sale_price : item.price;
        return sum + price * item.quantity;
    }, 0);

    if (cartCount) cartCount.textContent = totalQuantity;
    if (cartTotal) cartTotal.textContent = `${totalAmount.toLocaleString('vi-VN')} đ`;
}

// Hàm để tính tổng tiền giỏ hàng bao gồm phí vận chuyển
function calculateCartTotal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const shippingFee = 30000; // Phí ship
    const subtotal = cart.reduce((total, item) => {
        const price = item.sale_price && parseFloat(item.sale_price) > 0 ? item.sale_price : item.price;
        return total + price * item.quantity;
    }, 0);
    const totalAmount = subtotal + shippingFee;

    // Cập nhật tổng tiền vào phần tử hiển thị
    const totalAmountElement = document.getElementById('total-amount');
    if (totalAmountElement) {
        totalAmountElement.textContent = totalAmount.toLocaleString('vi-VN') + ' đ';
    }
    const totalAmountInput = document.getElementById('total_amount');
    if (totalAmountInput) {
        totalAmountInput.value = totalAmount;
    }

    // Cập nhật hiển thị phí vận chuyển và tổng phụ
    const subtotalElement = document.getElementById('subtotal');
    const shippingFeeElement = document.getElementById('shipping-fee');
    if (subtotalElement) subtotalElement.textContent = subtotal.toLocaleString('vi-VN') + ' đ';
    if (shippingFeeElement) shippingFeeElement.textContent = shippingFee.toLocaleString('vi-VN') + ' đ';
}

// Hàm để chuẩn bị dữ liệu giỏ hàng cho quá trình thanh toán
function submitCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartDataInput = document.getElementById('cart_data');
    if (cartDataInput) {
        cartDataInput.value = JSON.stringify(cart);
    }
    calculateCartTotal();
}

// Hàm để xóa toàn bộ giỏ hàng
function clearCart() {
    localStorage.removeItem('cart');
    displayCart();
    updateCartDisplay();
}

// Hàm để kiểm tra xem giỏ hàng có trống không
function isCartEmpty() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.length === 0;
}

// Hàm để lấy tổng số lượng sản phẩm trong giỏ hàng
function getCartItemCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((sum, item) => sum + item.quantity, 0);
}

// Gọi hàm hiển thị giỏ hàng và cập nhật khi tải trang
document.addEventListener('DOMContentLoaded', function () {
    displayCart();
    updateCartDisplay();
    calculateCartTotal();
});

// Thêm sự kiện cho nút "Tiến hành thanh toán"
const checkoutButton = document.getElementById('checkout-button');
if (checkoutButton) {
    checkoutButton.addEventListener('click', function(e) {
        e.preventDefault();
        if (isCartEmpty()) {
            alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        } else {
            submitCheckout();
            // Chuyển hướng đến trang thanh toán hoặc gửi form thanh toán
            document.getElementById('checkout-form').submit();
        }
    });
}

// Hàm để thêm sản phẩm vào giỏ hàng từ trang chi tiết sản phẩm
function addToCartFromProductPage() {
    const productId = document.getElementById('product_id').value;
    const variantId = document.querySelector('input[name="variant"]:checked').value;
    const productName = document.getElementById('product_name').textContent;
    const price = parseFloat(document.getElementById('product_price').textContent.replace(/[^\d]/g, ''));
    const salePrice = parseFloat(document.getElementById('product_sale_price').textContent.replace(/[^\d]/g, '')) || 0;
    const quantity = parseInt(document.getElementById('quantity').value);
    const image = document.getElementById('product_image').src;

    addToCart(productId, variantId, productName, price, salePrice, quantity, image);
}

// Hàm để cập nhật giá và số lượng có sẵn khi chọn variant
function updateProductInfo() {
    const selectedVariant = document.querySelector('input[name="variant"]:checked');
    if (selectedVariant) {
        const price = selectedVariant.dataset.price;
        const salePrice = selectedVariant.dataset.salePrice;
        const stock = selectedVariant.dataset.stock;

        document.getElementById('product_price').textContent = parseFloat(price).toLocaleString('vi-VN') + ' đ';
        if (salePrice && parseFloat(salePrice) > 0) {
            document.getElementById('product_sale_price').textContent = parseFloat(salePrice).toLocaleString('vi-VN') + ' đ';
            document.getElementById('product_sale_price').style.display = 'inline';
            document.getElementById('product_price').classList.add('original-price');
        } else {
            document.getElementById('product_sale_price').style.display = 'none';
            document.getElementById('product_price').classList.remove('original-price');
        }
        document.getElementById('product_stock').textContent = stock;
    }
}

// Thêm sự kiện cho các nút radio variant
const variantInputs = document.querySelectorAll('input[name="variant"]');
variantInputs.forEach(input => {
    input.addEventListener('change', updateProductInfo);
});

// Cập nhật thông tin sản phẩm khi tải trang
updateProductInfo();