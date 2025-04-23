const options = document.querySelectorAll(".option-box");

options.forEach(option => {
    option.addEventListener("click", function() {
        window.location.href = "niveles.html";
    });
});