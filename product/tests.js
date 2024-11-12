document.addEventListener("DOMContentLoaded", function () {
    console.log("Running Tests...");

    // Test: Form elements should be present
    console.assert(document.getElementById("productName"), "Test Failed: Product Name input missing");
    console.assert(document.getElementById("productPrice"), "Test Failed: Product Price select missing");
    console.assert(document.getElementById("productColour"), "Test Failed: Product Colour input missing");
    console.assert(document.getElementById("productSize"), "Test Failed: Product Size input missing");
    console.assert(document.getElementById("productType"), "Test Failed: Product Type input missing");
    console.assert(document.getElementById("productStyleDate"), "Test Failed: Product Style date input missing");
    console.assert(document.getElementById("productBrand"), "Test Failed: Product Brand select missing");
    console.assert(document.getElementById("productSupplier"), "Test Failed: Product Supplier input missing");

    // Test: Form submission event
    const form = document.getElementById("registrationForm");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        console.log("Form submission test: PASS");
    });
    form.submit();

    // Test: Customer details should be readonly
    const customerDetails = document.getElementById("customerDetails").elements;
    for (let i = 0; i < customerDetails.length; i++) {
        console.assert(customerDetails[i].readOnly || customerDetails[i].disabled, `Test Failed: ${customerDetails[i].id} should be readonly`);
    }

    // Test: Close, Update, and Delete buttons on Customer Details
    console.assert(document.querySelector(".close-btn"), "Test Failed: Close button missing in Customer Details");
    console.assert(document.querySelector(".register-btn"), "Test Failed: Update button missing in Customer Details");
    console.assert(document.querySelector(".register-btn[style*='background-color: red']"), "Test Failed: Delete button missing in Customer Details");

    console.log("All Tests Completed");
});
