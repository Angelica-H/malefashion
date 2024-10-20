function addToCartFromData(productDataJson) {
    const productData = JSON.parse(productDataJson);
    addToCart(
        productData.id,
        productData.name,
        productData.price,
        productData.sale_price,
        1, // Số lượng mặc định
        null, // Size mặc định
        null, // Màu mặc định
        productData.availableSizes,
        productData.availableColors,
        productData.image
    );
}

// Hàm để thêm sản phẩm vào giỏ hàng
function addToCart(productId, productName, productPrice, salePrice, quantity, size, color, availableSizes, availableColors, image) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Nếu size hoặc color không được truyền, mặc định giá trị đầu tiên
    size = size || document.querySelector('input[name="size"]:checked')?.value || availableSizes[0];
    color = color || document.querySelector('input[name="color"]:checked')?.value || availableColors[0];

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    const productIndex = cart.findIndex(product => 
        product.id === productId && product.size === size && product.color === color
    );
    
    if (productIndex === -1) {
        cart.push({ 
            id: productId, 
            name: productName, 
            price: productPrice,
            sale_price: salePrice,
            quantity: quantity, 
            size: size, 
            color: color,
            availableSizes: availableSizes,
            availableColors: availableColors,
            image: image
        });
    } else {
        cart[productIndex].quantity += quantity;
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert("Sản phẩm đã được thêm vào giỏ hàng");
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

    cart.forEach((product, index) => {
        const price = product.sale_price && parseFloat(product.sale_price) > 0 ? product.sale_price : product.price;
        const totalPrice = price * product.quantity;
        totalAmount += totalPrice;

        cartContent.innerHTML += `
            <tr>
                <td class="product__cart__item">
                    <div class="product__cart__item__pic">
                        <img src="${product.image}" alt="${product.name}" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    </div>
                    <div class="product__cart__item__text">
                        <h6>${product.name}</h6>
                        ${product.sale_price && parseFloat(product.sale_price) > 0 ? 
                            `<h5><span style="text-decoration: line-through;">${parseFloat(product.price).toLocaleString('vi-VN')} đ</span> ${parseFloat(product.sale_price).toLocaleString('vi-VN')} đ</h5>` :
                            `<h5>${parseFloat(product.price).toLocaleString('vi-VN')} đ</h5>`
                        }
                    </div>
                </td>
                <td class="size__item">
                    <select onchange="updateSize(${index}, this.value)">
                        ${product.availableSizes.sort((a, b) => {
                            const sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                            return sizeOrder.indexOf(a) - sizeOrder.indexOf(b);
                        }).map(size => 
                            `<option value="${size}" ${product.size === size ? 'selected' : ''}>${size}</option>`
                        ).join('')}
                    </select>
                </td>
                <td class="color__item">
                    <select onchange="updateColor(${index}, this.value)">
                        ${product.availableColors.sort().map(color => 
                            `<option value="${color}" ${product.color === color ? 'selected' : ''}>${color}</option>`
                        ).join('')}
                    </select>
                </td>
                <td class="quantity__item">
                    <div class="quantity">
                        <div class="pro-qty-2">
                            <input type="number" value="${product.quantity}" onchange="updateCart(${index}, this.value)">
                        </div>
                    </div>
                </td>
                <td class="cart__price">${totalPrice.toLocaleString('vi-VN')} đ</td>
                <td class="cart__close">
                    <i class="fa fa-close" onclick="removeFromCart(${index})"></i>
                </td>
            </tr>
        `;
    });

    totalAmountDisplay.innerHTML = `${totalAmount.toLocaleString('vi-VN')} đ`;
    updateCartDisplay();
}

// Hàm để cập nhật số lượng sản phẩm trong giỏ hàng
function updateCart(index, newQuantity) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (newQuantity <= 0) {
        alert("Số lượng phải lớn hơn 0");
        return;
    }

    if (cart.length > index) {
        cart[index].quantity = parseInt(newQuantity);
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
    }
}

// Hàm để cập nhật kích thước sản phẩm trong giỏ hàng
function updateSize(index, newSize) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length > index) {
        cart[index].size = newSize;
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
    }
}

// Hàm để cập nhật màu sắc sản phẩm trong giỏ hàng
function updateColor(index, newColor) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length > index) {
        cart[index].color = newColor;
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
    }
}

// Hàm để xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length > index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        alert("Sản phẩm đã được xóa khỏi giỏ hàng");
        displayCart();
    }
}

// Hàm để đặt hàng
function placeOrder() {
    const cart = JSON.parse(localStorage.getItem('cart'));
    if (!cart || cart.length === 0) {
        alert("Giỏ hàng của bạn đang trống");
        return;
    }

    // Gửi dữ liệu đơn hàng đến server (cần implement)
    console.log("Đang xử lý đơn hàng...");
    localStorage.removeItem('cart');
    alert("Đặt hàng thành công!");
    displayCart();
}

// Hàm để tính tổng số lượng và tổng tiền giỏ hàng
function updateCartDisplay() {
    const totalQuantity = calculateTotalCartQuantity();
    const totalPrice = calculateTotalCartPrice();

    const cartCountElement = document.getElementById('cart-count');
    const cartTotalElement = document.getElementById('cart-total');

    if (cartCountElement) {
        cartCountElement.textContent = totalQuantity;
        localStorage.setItem('cartCount', totalQuantity);
    }
    if (cartTotalElement) {
        const formattedPrice = `${parseFloat(totalPrice).toLocaleString('vi-VN')} đ`;
        cartTotalElement.textContent = formattedPrice;
        localStorage.setItem('cartTotal', formattedPrice);
    }
}

// Hàm để tính tổng số lượng sản phẩm trong giỏ hàng
function calculateTotalCartQuantity() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((total, product) => total + product.quantity, 0);
}

// Hàm để tính tổng tiền trong giỏ hàng
function calculateTotalCartPrice() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((total, product) => {
        const price = product.sale_price && parseFloat(product.sale_price) > 0 ? product.sale_price : product.price;
        return total + (price * product.quantity);
    }, 0).toFixed(2);
}

// Gọi hàm hiển thị giỏ hàng và cập nhật khi tải trang
document.addEventListener('DOMContentLoaded', function () {
    displayCart();
    updateCartDisplay();
})

function calculateCartTotal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const shippingFee = 30; // Phí ship
    const totalAmount = cart.reduce((total, product) => {
        const price = product.sale_price && parseFloat(product.sale_price) > 0 ? product.sale_price : product.price;
        return total + price * product.quantity;
    }, 0) + shippingFee;

    // Cập nhật tổng tiền vào phần tử hiển thị
    document.getElementById('total-amount').textContent = totalAmount.toLocaleString('vi-VN') + ' đ';
    document.getElementById('total_amount').value = totalAmount;
}

function submitCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.getElementById('cart_data').value = JSON.stringify(cart);
    calculateCartTotal();
}

// Tính tổng tiền và hiển thị khi tải trang
document.addEventListener('DOMContentLoaded', function () {
    calculateCartTotal();
});