// Biến toàn cục để lưu trữ thông tin sản phẩm
let productData = {};

// Hàm để hiển thị giỏ hàng
function displayCart() {
    // Lấy dữ liệu giỏ hàng từ localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContent = document.getElementById('cart-content');
    const totalAmountDisplay = document.getElementById('total-amount');

    // Kiểm tra nếu giỏ hàng trống
    if (cart.length === 0) {
        cartContent.innerHTML = "<tr><td colspan='7'>Giỏ hàng của bạn đang trống.</td></tr>";
        totalAmountDisplay.innerHTML = '0 đ';
        return;
    }

    // Hiển thị các mục trong giỏ hàng
    cartContent.innerHTML = cart.map((item, index) => createCartItemHTML(item, index)).join('');

    // Lấy thông tin sản phẩm nếu chưa có
    cart.forEach(item => {
        if (!productData[item.product_id]) {
            getProductInfo(item.product_id);
        }
    });

    // Cập nhật tổng giá trị giỏ hàng
    updateCartTotal();
}

// Hàm tạo HTML cho mỗi mục trong giỏ hàng
function createCartItemHTML(item, index) {
    const price = getItemPrice(item);
    const totalPrice = price * item.quantity;
    return `
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
            <td class="text-end">${price.toLocaleString('vi-VN')} đ</td>
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
}

// Hàm lấy thông tin sản phẩm từ server
function getProductInfo(productId) {
    fetch(`includes/get_product_info.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            productData[productId] = data;
            displayCart();
        })
        .catch(error => console.error('Error:', error));
}

// Hàm tạo các tùy chọn màu sắc
function generateColorOptions(productId, selectedColorId) {
    if (!productData[productId]) return '<option>Đang tải...</option>';
    return Object.entries(productData[productId].variants)
        .map(([colorId, variant]) => `<option value="${colorId}" ${colorId == selectedColorId ? 'selected' : ''}>${variant.color_name}</option>`)
        .join('');
}

// Hàm tạo các tùy chọn kích thước
function generateSizeOptions(productId, colorId, selectedSizeId) {
    if (!productData[productId] || !productData[productId].variants[colorId]) return '<option>Đang tải...</option>';
    return productData[productId].variants[colorId].sizes
        .map(size => `<option value="${size.size_id}" ${size.size_id == selectedSizeId ? 'selected' : ''}>${size.size_name}</option>`)
        .join('');
}

// Hàm cập nhật màu sắc sản phẩm trong giỏ hàng
function updateColor(index, newColorId) {
    updateCartItem(index, item => {
        item.color_id = newColorId;
        item.size_id = productData[item.product_id].variants[newColorId].sizes[0].size_id;
    });
}

// Hàm cập nhật kích thước sản phẩm trong giỏ hàng
function updateSize(index, newSizeId) {
    updateCartItem(index, item => {
        item.size_id = newSizeId;
    });
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng
function updateCart(index, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (newQuantity <= 0) {
        removeFromCart(index);
    } else {
        updateCartItem(index, item => {
            item.quantity = newQuantity;
        });
    }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    saveCartAndUpdate(cart);
}

// Hàm cập nhật một mục trong giỏ hàng
function updateCartItem(index, updateFn) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateFn(cart[index]);
    saveCartAndUpdate(cart);
}

// Hàm lưu giỏ hàng và cập nhật giao diện
function saveCartAndUpdate(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
    updateGlobalCartDisplay();
}

// Hàm cập nhật tổng giá trị giỏ hàng
function updateCartTotal() {
    const { subtotal, shippingFee, totalAmount } = calculateCartTotal();
    updateElement('total-amount', `${totalAmount.toLocaleString('vi-VN')} đ`);
    updateElement('total_amount', totalAmount, 'value');
    updateElement('subtotal', `${subtotal.toLocaleString('vi-VN')} đ`);
    updateElement('shipping-fee', `${shippingFee.toLocaleString('vi-VN')} đ`);
}

// Hàm tính toán tổng giá trị giỏ hàng
function calculateCartTotal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const shippingFee = 30000;
    const subtotal = cart.reduce((total, item) => total + getItemPrice(item) * item.quantity, 0);
    const totalAmount = subtotal + shippingFee;
    return { subtotal, shippingFee, totalAmount };
}

// Hàm lấy giá của một sản phẩm
function getItemPrice(item) {
    return item.sale_price && parseFloat(item.sale_price) > 0 ? parseFloat(item.sale_price) : parseFloat(item.price);
}

// Hàm cập nhật nội dung của một phần tử HTML
function updateElement(id, value, property = 'textContent') {
    const element = document.getElementById(id);
    if (element) element[property] = value;
}

// Hàm xóa toàn bộ giỏ hàng
function clearCart() {
    localStorage.removeItem('cart');
    saveCartAndUpdate([]);
}

// Hàm kiểm tra xem giỏ hàng có trống không
function isCartEmpty() {
    return (JSON.parse(localStorage.getItem('cart')) || []).length === 0;
}

// Hàm chuẩn bị dữ liệu để gửi đơn hàng
function submitCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateElement('cart_data', JSON.stringify(cart), 'value');
    calculateCartTotal();
}

// Hàm cập nhật hiển thị giỏ hàng toàn cục
function updateGlobalCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalAmount = cart.reduce((sum, item) => sum + getItemPrice(item) * item.quantity, 0);

    updateElement('cart-count', totalQuantity);
    updateElement('cart-total', `${totalAmount.toLocaleString('vi-VN')} đ`);
}

// Hàm cập nhật thông tin sản phẩm trên trang chi tiết
function updateProductInfo() {
    const selectedVariant = document.querySelector('input[name="variant"]:checked');
    if (selectedVariant) {
        const { price, salePrice, stock } = selectedVariant.dataset;
        updateElement('product_price', `${parseFloat(price).toLocaleString('vi-VN')} đ`);
        updateElement('product_stock', stock);

        if (salePrice && parseFloat(salePrice) > 0) {
            updateElement('product_sale_price', `${parseFloat(salePrice).toLocaleString('vi-VN')} đ`);
            document.getElementById('product_sale_price').style.display = 'inline';
            document.getElementById('product_price').classList.add('original-price');
        } else {
            document.getElementById('product_sale_price').style.display = 'none';
            document.getElementById('product_price').classList.remove('original-price');
        }
    }
}

// Sự kiện khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function () {
    displayCart();
    updateGlobalCartDisplay();
    updateProductInfo();

    // Xử lý sự kiện xóa giỏ hàng
    document.getElementById('clear-cart')?.addEventListener('click', clearCart);
    
    // Xử lý sự kiện thanh toán
    document.getElementById('checkout-button')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (isCartEmpty()) {
            alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        } else {
            submitCheckout();
            document.getElementById('checkout-form').submit();
        }
    });

    // Xử lý sự kiện thay đổi biến thể sản phẩm
    document.querySelectorAll('input[name="variant"]').forEach(input => {
        input.addEventListener('change', updateProductInfo);
    });
});

// Sự kiện khi trang đã tải xong (đảm bảo cập nhật hiển thị giỏ hàng toàn cục)
document.addEventListener('DOMContentLoaded', function () {
    updateGlobalCartDisplay();
    // Các hàm khác nếu cần
});