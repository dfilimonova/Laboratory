function validateForm() {
  
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let phone = document.getElementById('phone').value;
    let model = document.getElementById('model').value;
    let issue = document.getElementById('issue').value;
    let priority = document.querySelector('input[name="quantity"]').value;
    let term = document.getElementById('term').value;
    let agree = document.getElementById('agree_terms').checked;
    let output = document.getElementById('output');
  
    if (name == ''|| email == '' || phone == '' || model == '' || issue == '' || priority == '' || term == '' || !agree) {
        alert('Пожалуйста, заполните все обязательные поля');
        return false;
    }
  
    if (isNaN(phone)) {
        alert('Пожалуйста, введите корректный номер телефона');
        return false;
    }
  
    if (priority < 1 || priority > 10) {
        alert('Пожалуйста, укажите корректное количество лет устройству (от 1 до 10)');
        return false;
    }
  
    output.innerHTML = 'Имя: ' + name + '<br>' +
                      'Email: ' + email + '<br>' +
                      'Телефон: ' + phone + '<br>' +
                      'Модель ноутбука: ' + model + '<br>' +
                      'Проблема: ' + issue + '<br>' +
                      'Возраст устройства: ' + priority + ' год(а, лет)<br>' +
                      'Срок гарантии: ' + term;
  
    // Очищаем поля формы
    let RepairForm = document.getElementById('RepairForm').reset();
  }