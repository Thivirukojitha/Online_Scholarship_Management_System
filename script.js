function showMessage() {
    alert("Welcome to the Online Scholarship Management System!");
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            alert("Application submitted successfully!");
            this.reset();
        });
    }
});