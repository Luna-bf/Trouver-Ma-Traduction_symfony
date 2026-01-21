let slider = document.getElementById("myRange");
let output = document.getElementById("number-input");

// Je voudrais lier la valeur de l'input number et celle de range pour que si je change l'une des deux, l'autre change aussi
// Lien utile ? : https://stackoverflow.com/questions/25825843/restrict-user-to-put-value-in-range-in-html-input-type-number
slider.oninput = function () {
    output.value = this.value;
}