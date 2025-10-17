/**
 * 通用底部导航栏组件
 * 在任何页面引入此文件即可自动渲染底部导航
 * 使用方法：在 HTML 中添加 <script src="footer.js"></script>
 */

(function() {
    // 底部导航配置
    const footerConfig = {
        items: [
            {
                text: '主页',
                icon: '🏠',
                link: 'index.html',
                id: 'home'
            },
            {
                text: '推荐',
                icon: '✨',
                link: 'host.html',
                id: 'recommend'
            },
            {
                text: '购物车',
                icon: '🛒',
                link: 'cart.html',
                id: 'cart'
            },
            {
                text: '我的',
                icon: '👤',
                link: 'user.html',
                id: 'user'
            }
        ]
    };

    // 注入 CSS 样式
    const style = document.createElement('style');
    style.textContent = `
        /* 底部导航栏样式 */
        #app-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px 15px 0 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .footer-button {
            padding: 12px;
            cursor: pointer;
            text-align: center;
            flex: 1;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            position: relative;
        }

        .footer-button:hover {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
            transform: translateY(-2px);
        }

        .footer-button.active {
            color: #007bff;
            font-weight: 600;
        }

        .footer-button.active::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: #007bff;
            border-radius: 2px;
        }

        .footer-button-icon {
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .footer-button:hover .footer-button-icon {
            transform: scale(1.2);
        }

        /* 为页面内容添加底部间距，避免被底部栏遮挡 */
        body {
            padding-bottom: 70px !important;
        }

        /* 响应式设计 */
        @media (max-width: 600px) {
            .footer-button {
                font-size: 12px;
                padding: 8px;
            }

            .footer-button-icon {
                font-size: 20px;
            }
        }
    `;
    document.head.appendChild(style);

    // 创建底部导航 HTML
    function createFooter() {
        const footer = document.createElement('div');
        footer.id = 'app-footer';

        // 获取当前页面路径
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';

        footerConfig.items.forEach(item => {
            const button = document.createElement('a');
            button.className = 'footer-button';
            button.href = item.link;
            
            // 判断是否为当前页面
            if (currentPage === item.link) {
                button.classList.add('active');
            }

            button.innerHTML = `
                <span class="footer-button-icon">${item.icon}</span>
                <span>${item.text}</span>
            `;

            footer.appendChild(button);
        });

        return footer;
    }

    // 等待 DOM 加载完成后插入底部导航
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.appendChild(createFooter());
        });
    } else {
        document.body.appendChild(createFooter());
    }

    // 如果需要动态更新购物车数量，可以导出一个全局函数
    window.updateCartCount = function(count) {
        const cartButton = document.querySelector('[href="cart.html"]');
        if (cartButton) {
            let badge = cartButton.querySelector('.cart-badge');
            
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'cart-badge';
                    badge.style.cssText = `
                        position: absolute;
                        top: 8px;
                        right: 20%;
                        background-color: #ff4757;
                        color: white;
                        border-radius: 10px;
                        padding: 2px 6px;
                        font-size: 10px;
                        font-weight: bold;
                        min-width: 18px;
                        text-align: center;
                        box-shadow: 0 2px 4px rgba(255, 71, 87, 0.4);
                        animation: badgePop 0.3s ease;
                    `;
                    cartButton.style.position = 'relative';
                    cartButton.appendChild(badge);
                }
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'block';
            } else if (badge) {
                badge.style.display = 'none';
            }
        }
    };

    // 自动加载购物车数量（所有页面）
    function loadCartCount() {
        fetch('./php/getUser.php')
            .then(response => response.json())
            .then(data => {
                if (!data.error && data.id) {
                    return fetch(`./php/cart/getCartInfo.php?user_id=${data.id}`);
                }
            })
            .then(response => response ? response.json() : [])
            .then(cartItems => {
                if (Array.isArray(cartItems)) {
                    window.updateCartCount(cartItems.length);
                }
            })
            .catch(error => {
                console.log('购物车数量加载失败:', error);
                // 静默失败，不影响页面使用
            });
    }

    // 页面加载完成后自动获取购物车数量
    setTimeout(loadCartCount, 500);

    // 添加徽章弹出动画样式
    const badgeStyle = document.createElement('style');
    badgeStyle.textContent = `
        @keyframes badgePop {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
    `;
    document.head.appendChild(badgeStyle);
})();

