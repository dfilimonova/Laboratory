function validateForm() {
  var name = document.getElementById("name").value;
  var email = document.getElementById("email").value;
  var phone = document.getElementById("phone").value;
  var model = document.getElementById("model").value;
  var issue = document.getElementById("issue").value;
  var quantity = document.getElementsByName("quantity")[0].value;
  var term = document.getElementById("term").value;

  if (name == "" || email == "" || phone == "" || model == "" || issue == "" || quantity == "" || term == "") {
    alert("Все поля должны быть заполнены");
    return false;
  }
  
  if (isNaN(phone)){
    alert("Введите корректный номер телефона");
    return false;
  }
  
  if (isNaN(email)) {
    alert("Введите корректный email");
    return false;
  }

  output.innerHTML = 'Имя: ' + name + '<br>' +
  'Email: ' + email + '<br>' +
  'Телефон: ' + phone + '<br>' +
  'Модель ноутбука: ' + model + '<br>' +
  'Проблема: ' + issue + '<br>' +
  'Возраст устройства: ' + priority + ' год(а, лет)<br>' +
  'Срок гарантии: ' + term;
  let RepairFrom = document.getElementById('RepairFrom').requestFullscreen();
}