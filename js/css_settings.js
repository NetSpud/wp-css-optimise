console.log("css_settings.js | loaded")



function main() {
    const form = document.getElementById("settings-form");
    form.addEventListener("submit", e => handleFormSubmit(e))

}


function createErrorBox(message) {
    const div = document.createElement("div");
    div.setAttribute("class", "notice notice-error");
    const p = document.createElement("p");
    p.innerText = message;
    div.appendChild(p);
    return div;
}

function validateInput(input, errorMessage, validator, e) {
    if (input.value) {
        const values = input.value.split(",");
        if (values.some(validator)) {
            e.preventDefault();
            const errorBox = createErrorBox(errorMessage);
            document.querySelector(".wrap").prepend(errorBox);
        }
    }
}

function handleFormSubmit(e) {
    //remove any existing error boxes
    const errorBoxes = document.querySelectorAll(".notice-error");
    errorBoxes.forEach(box => box.remove());

    const excluded_urlsInput = document.querySelector("input[name='excluded_urls']");
    const permitted_stylesheetsInput = document.querySelector("input[name='permitted_stylesheets']");
    const api_endpointInput = document.querySelector("input[name='endpoint_url']");

    if (!api_endpointInput.value) {
        e.preventDefault();
        const errorBox = createErrorBox("Please enter an API endpoint URL");
        document.querySelector(".wrap").prepend(errorBox);
    }

    validateInput(
        excluded_urlsInput,
        "Invalid excluded stylesheets. URLs must end in .css",
        url => !url.endsWith(".css"),
        e,
    );

    validateInput(
        permitted_stylesheetsInput,
        "Invalid permitted stylesheets. permitted stylesheets must be separated by commas",
        x => x.trim().includes(" "),
        e,
    );
}


window.onload = main; //run script after window loaded