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
            image: image,
            color_id: colorId,
            size_id: sizeId
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Đã thêm vào giỏ hàng');
    updateCartDisplay();
}

// Biến toàn cục để lưu trữ thông tin sản phẩm
let productData = {};

// Hàm để hiển thị giỏ hàng
function displayCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContent = document.getElementById('cart-content');
    const totalAmountDisplay = document.getElementById('total-amount');
    let totalAmount = 0;

    cartContent.innerHTML = '';

    if (cart.length === 0) {
        cartContent.innerHTML = "<tr><td colspan='7'>Giỏ hàng của bạn đang trống.</td></tr>";
        totalAmountDisplay.innerHTML = '0 đ';
        return;
    }

    cart.forEach((item, index) => {
        const price = item.sale_price && parseFloat(item.sale_price) > 0 ? item.sale_price : item.price;
        const totalPrice = price * item.quantity;
        totalAmount += totalPrice;

        cartContent.innerHTML += `
            <tr class="align-middle">
                <td class="d-flex align-items-center">
                    <img src="${item.image}" alt="${item.name}" class="me-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 0;">
                    <span class="fw-bold" style="margin-left: 10px;">${item.name}</span>
                </td>
                <td>
                    <select class="form-select form-select-sm border-0" onchange="updateColor(${index}, this.value)" style="background-color: transparent; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                        ${generateColorOptions(item.product_id, item.color_id)}
                    </select>
                </td>
                <td>
                    <select class="form-select form-select-sm border-0" onchange="updateSize(${index}, this.value)" style="background-color: transparent; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                        ${generateSizeOptions(item.product_id, item.color_id, item.size_id)}
                    </select>
                </td>
                <td class="text-end">${parseFloat(price).toLocaleString('vi-VN')} đ</td>
                <td>
                    <input type="number" class="form-control form-control-sm border-0" value="${item.quantity}" min="1" onchange="updateCart(${index}, this.value)" style="background-color: transparent; width: 60px; padding: 2px 5px;">
                </td>
                <td class="text-end fw-bold">${totalPrice.toLocaleString('vi-VN')} đ</td>
                <td class="text-center">
                    <button onclick="removeFromCart(${index})" class="btn btn-outline-danger btn-sm border-0">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        // Lấy thông tin sản phẩm nếu chưa có
        if (!productData[item.product_id]) {
            getProductInfo(item.product_id);
        }
    });

    totalAmountDisplay.innerHTML = `${totalAmount.toLocaleString('vi-VN')} đ`;
}

// Hàm để lấy thông tin sản phẩm
function getProductInfo(productId) {
    fetch(`includes/get_product_info.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            productData[productId] = data;
            displayCart(); // Cập nhật lại giỏ hàng sau khi có thông tin sản phẩm
        })
        .catch(error => console.error('Error:', error));
}

// Hàm để tạo các tùy chọn màu sắc
function generateColorOptions(productId, selectedColorId) {
    if (!productData[productId]) return '<option>Đang tải...</option>';
    
    return Object.keys(productData[productId].variants).map(colorId => 
        `<option value="${colorId}" ${colorId == selectedColorId ? 'selected' : ''}>${productData[productId].variants[colorId].color_name}</option>`
    ).join('');
}

// Hàm để tạo các tùy chọn kích thước
function generateSizeOptions(productId, colorId, selectedSizeId) {
    if (!productData[productId] || !productData[productId].variants[colorId]) return '<option>Đang tải...</option>';
    
    return productData[productId].variants[colorId].sizes.map(size => 
        `<option value="${size.size_id}" ${size.size_id == selectedSizeId ? 'selected' : ''}>${size.size_name}</option>`
    ).join('');
}

// Hàm để cập nhật màu sắc
function updateColor(index, newColorId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const item = cart[index];
    item.color_id = newColorId;
    item.size_id = productData[item.product_id].variants[newColorId].sizes[0].size_id;
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

// Hàm để cập nhật kích thước
function updateSize(index, newSizeId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart[index].size_id = newSizeId;
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
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
    }
}

// Hàm để xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

// Hàm để xóa toàn bộ giỏ hàng
function clearCart() {
    localStorage.removeItem('cart');
    displayCart();
}

// Gọi hàm hiển thị giỏ hàng khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    displayCart();

    // Thêm sự kiện cho nút xóa giỏ hàng
    document.getElementById('clear-cart').addEventListener('click', clearCart);

    // Thêm sự kiện cho nút thanh toán
    document.getElementById('checkout-button').addEventListener('click', function() {
        // Thêm logic xử lý thanh toán ở đây
        alert('Chức năng thanh toán đang được phát triển.');
    });
});

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
// function addToCartFromProductPage() {
//   const productId = document.getElementById('product_id').value;
//   const variantId = document.querySelector('input[name="variant"]:checked').value;
//   const productName = document.getElementById('product_name').textContent;
//  const price = parseFloat(document.getElementById('product_price').textContent.replace(/[^\d]/g, ''));
//  const salePrice = parseFloat(document.getElementById('product_sale_price').textContent.replace(/[^\d]/g, '')) || 0;
// const quantity = parseInt(document.getElementById('quantity').value);
//  const image = document.getElementById('product_image').src;
//
//  addToCart(productId, variantId, productName, price, salePrice, quantity, image);
// }

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
function updateGlobalCartDisplay() {
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
document.addEventListener('DOMContentLoaded', function () {
    updateGlobalCartDisplay();
    // Các hàm khác nếu cần
});
// Cập nhật thông tin sản phẩm khi tải trang
updateProductInfo();