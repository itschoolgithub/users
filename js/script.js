function fetchUsers() {
    fetch('/api/users.php')
        .then(function (response) {
            return response.json()
        })
        .then(function (data) {
            if (data.users) {
                // Очистим таблицу перед добавлением новых данных
                const tableBody = document.querySelector('table tbody');
                tableBody.innerHTML = '';

                // Добавляем каждого пользователя в таблицу
                data.users.forEach(user => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
            <td>${user.first_name} ${user.last_name}</td>
            <td>${user.email}</td>
            <td>${user.created_at}</td>
            <td>${user.role}</td>
            <td>${user.status}</td>
            <td>
                <a href="edit_user.html?id=${user.id}" class="btn btn-warning btn-sm">Изменить</a>
                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Удалить</button>
            </td>
            `;

                    tableBody.appendChild(row);
                });
            } else {
                alert('Не удалось загрузить пользователей');
            }
        });
}

function deleteUser(userId) {
    // Подтверждение удаления
    const isConfirmed = confirm('Вы уверены, что хотите удалить этого пользователя?');
    if (isConfirmed) {
        fetch('/api/delete_user.php', {
            method: 'POST',
            body: JSON.stringify({ id: userId }),
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (result) {
                if (result.message) {
                    alert(result.message);
                    fetchUsers(); // Перезагружаем список пользователей после удаления
                } else {
                    alert('Ошибка при удалении пользователя.');
                }
            });
    }
}

function fetchUserData() {
    // Получаем ID пользователя из URL
    let userId = getUrlParameter("id");
    if (!userId) {
        alert("ID пользователя не найден в URL");
        return;
    }

    // Делаем запрос на сервер для получения данных пользователя
    fetch('/api/get_user.php?id=' + userId)
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.user.id) {
                // Заполняем поля формы данными пользователя
                document.querySelector('#first_name').value = data.user.first_name;
                document.querySelector('#last_name').value = data.user.last_name;
                document.querySelector('#email').value = data.user.email;
                document.querySelector('#role').value = data.user.role;
                document.querySelector('#status').value = data.user.status;
            } else {
                alert("Не удалось загрузить данные пользователя.");
            }
        });
}

function updateUser(event) {
    event.preventDefault(); // Отменяем стандартное поведение формы

    // Получаем данные из формы
    let userId = getUrlParameter("id");
    let firstName = document.querySelector('#first_name').value;
    let lastName = document.querySelector('#last_name').value;
    let password = document.querySelector('#password').value;
    let email = document.querySelector('#email').value;
    let role = document.querySelector('#role').value;
    let status = document.querySelector('#status').value;

    // Отправляем данные на сервер для обновления
    fetch('/api/update_user.php', {
        method: 'POST',
        body: JSON.stringify({
            id: userId,
            first_name: firstName,
            last_name: lastName,
            password: password,
            email: email,
            role: role,
            status: status
        })
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.message) {
                alert(data.message); // Сообщение об успешном обновлении
                window.location.href = 'index.html'; // Перенаправляем на главную страницу
            } else {
                alert("Ошибка при обновлении данных пользователя.");
            }
        });
}

function createUser(event) {
    event.preventDefault(); // Отменяем стандартное поведение формы

    // Получаем данные из формы
    let firstName = document.querySelector('#first_name').value;
    let lastName = document.querySelector('#last_name').value;
    let password = document.querySelector('#password').value;
    let email = document.querySelector('#email').value;
    let role = document.querySelector('#role').value;
    let status = document.querySelector('#status').value;

    // Отправляем данные на сервер для обновления
    fetch('/api/create_user.php', {
        method: 'POST',
        body: JSON.stringify({
            first_name: firstName,
            last_name: lastName,
            password: password,
            email: email,
            role: role,
            status: status
        })
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.message) {
                alert(data.message); // Сообщение об успешном создании
                window.location.href = 'index.html'; // Перенаправляем на главную страницу
            } else {
                alert("Ошибка при создании пользователя.");
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.indexPage')) {
        fetchUsers();
    }

    if (document.querySelector('.updatePage')) {
        fetchUserData();

        let form = document.querySelector('#edit-user-form');
        form.addEventListener('submit', updateUser);
    }

    if (document.querySelector('.createPage')) {
        let form = document.querySelector('#create-user-form');
        form.addEventListener('submit', createUser);
    }
});

// Функция для получения параметра из URL
function getUrlParameter(name) {
    let url = window.location.href;
    let paramName = name + "=";
    let start = url.indexOf(paramName);
    if (start === -1) return null;
    start = start + paramName.length;
    let end = url.indexOf("&", start);
    if (end === -1) end = url.length;
    return decodeURIComponent(url.substring(start, end));
}