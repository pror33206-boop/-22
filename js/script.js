// DOM элементы
const authModal = document.getElementById('authModal');
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const closeModal = document.querySelector('.close');
const tabBtns = document.querySelectorAll('.tab-btn');
const authForms = document.querySelectorAll('.auth-form');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const bookingForm = document.getElementById('bookingForm');
const reviewForm = document.getElementById('reviewForm');
const reviewsContainer = document.getElementById('reviewsContainer');

// Открытие модального окна авторизации
loginBtn.addEventListener('click', () => {
    authModal.style.display = 'block';
    switchTab('login');
});

registerBtn.addEventListener('click', () => {
    authModal.style.display = 'block';
    switchTab('register');
});

// Закрытие модального окна
closeModal.addEventListener('click', () => {
    authModal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === authModal) {
        authModal.style.display = 'none';
    }
});

// Переключение между вкладками
tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.getAttribute('data-tab');
        switchTab(tab);
    });
});

function switchTab(tabName) {
    // Обновляем активные кнопки
    tabBtns.forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-tab') === tabName);
    });
    
    // Показываем соответствующую форму
    authForms.forEach(form => {
        form.classList.toggle('active', form.id === `${tabName}Form`);
    });
}

// Обработка формы входа
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(loginForm);
    
    try {
        const response = await fetch('php/login.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            authModal.style.display = 'none';
            loginForm.reset();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка соединения', 'error');
    }
});

// Обработка формы регистрации
registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(registerForm);
    
    try {
        const response = await fetch('php/register.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            authModal.style.display = 'none';
            registerForm.reset();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка соединения', 'error');
    }
});

// Обработка формы записи
bookingForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(bookingForm);
    
    try {
        const response = await fetch('php/booking.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            bookingForm.reset();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка соединения', 'error');
    }
});

// Обработка формы отзыва
reviewForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(reviewForm);
    
    try {
        const response = await fetch('php/reviews.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            reviewForm.reset();
            loadReviews();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Ошибка соединения', 'error');
    }
});

// Загрузка отзывов
async function loadReviews() {
    try {
        const response = await fetch('php/reviews.php');
        const reviews = await response.json();
        
        reviewsContainer.innerHTML = '';
        
        reviews.forEach(review => {
            const reviewElement = document.createElement('div');
            reviewElement.className = 'review';
            reviewElement.innerHTML = `
                <div class="review-header">
                    <div class="review-name">${review.client_name}</div>
                    <div class="review-rating">${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</div>
                </div>
                <div class="review-text">${review.review_text}</div>
                <div class="review-date">${new Date(review.created_at).toLocaleDateString()}</div>
            `;
            reviewsContainer.appendChild(reviewElement);
        });
    } catch (error) {
        console.error('Ошибка загрузки отзывов:', error);
    }
}

// Функция показа уведомлений
function showNotification(message, type = 'success') {
    // Создаем уведомление если его нет
    let notification = document.querySelector('.notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'notification';
        document.body.appendChild(notification);
    }
    
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

// Загрузка отзывов при загрузке страницы
document.addEventListener('DOMContentLoaded', loadReviews);

// Установка минимальной даты для записи
const today = new Date().toISOString().split('T')[0];
document.getElementById('booking_date').min = today;