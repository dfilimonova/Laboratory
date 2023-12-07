let currentNumber = '';
let memoryNumber = '';
let operation = '';
let shouldClearScreen = false;

function clearAll() {
    currentNumber = '';
    memoryNumber = '';
    operation = '';
    updateScreen('');
}

function clearEntry() {
    currentNumber = '';
    shouldClearScreen = false;
    updateScreen('');
}

function add(number) {
    if (number == '.' && currentNumber.includes('.')) {
        return;
    }
    
    if (shouldClearScreen) {
        shouldClearScreen = false;
        currentNumber = '';
    }
    
    currentNumber += number;
    updateScreen(currentNumber);
}

function changeSign() {
    currentNumber = String(-parseFloat(currentNumber));
    updateScreen(currentNumber);
}

function sqrt() {
    currentNumber = String(Math.sqrt(parseFloat(currentNumber)));
    updateScreen(currentNumber);
}

function addPercent() {
    currentNumber = String(parseFloat(currentNumber) * parseFloat(memoryNumber) / 100);
    updateScreen(currentNumber);
}

function reciprocal() {
    currentNumber = String(1 / parseFloat(currentNumber));
    updateScreen(currentNumber);
}

function divide() {
    if (currentNumber === '') {
        return;
    }
    
    memoryNumber = currentNumber;
    currentNumber = '';
    operation = '/';
}

function multiply() {
    if (currentNumber === '') {
        return;
    }
    
    memoryNumber = currentNumber;
    currentNumber = '';
    operation = '*';
}

function subtract() {

if (currentNumber === '') {
        return;
    }
    
    memoryNumber = currentNumber;
    currentNumber = '';
    operation = '-';
}

function calculate() {
    let result;
    
    switch (operation) {
        case '+':
            result = parseFloat(memoryNumber) + parseFloat(currentNumber);
            break;
        case '-':
            result = parseFloat(memoryNumber) - parseFloat(currentNumber);
            break;
        case '*':
            result = parseFloat(memoryNumber) * parseFloat(currentNumber);
            break;
        case '/':
            result = parseFloat(memoryNumber) / parseFloat(currentNumber);
            break;

    }
    
    currentNumber = String(result);
    operation = '';
    updateScreen(currentNumber);
    shouldClearScreen = true;
}

function updateScreen(value) {
    document.getElementById('result').value = value;
}