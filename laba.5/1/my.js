function getDeliverySchedule() {
  let day = document.getElementById("day").value;
  let time = parseInt(document.getElementById("time").value);
  
    let workingDays = ["понедельник", "вторник", "среда", "четверг", "пятница", "суббота"];
  
    let result = "";
    
    if (workingDays.includes(day.toLowerCase())) {
      if (time >= 8 && time < 16) {
        result = "Вы можете получить заказ после 16.00 в этот же день.";
      } else if (time < 8) {
        result = "Вы можете получить заказ с 8.00 до 16.00 в этот же день.";
      } else {
        result = "Вы можете получить заказ на следующий рабочий день с 8.00 до 16.00.";
      }
    } else {
      result = "Вы можете получить заказ на следующий рабочий день с 8.00 до 16.00.";
    }
  
    document.getElementById("result").innerText = result;
  }
  