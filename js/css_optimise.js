console.log("css_optimise.js loaded")

const main = () => {
    const generateStyleSheetButton = document.getElementById('css_optimise_generate_stylesheet');
    if (!generateStyleSheetButton) return;
    generateStyleSheetButton.addEventListener('click', (e) => {
        e.preventDefault();
        console.log("generateStyleSheetButton clicked")
        const css_optimise_spinner = document.getElementById('css_optimise_spinner');
        css_optimise_spinner.style.visibility = "visible";
        generateStyleSheet(css_optimise_spinner);
    });
}
function generateStyleSheet(spinner) {
    //get URl to be optimised
    const css_optimise_page_slug = document.getElementById('css_optimise_page_slug');
    const url = css_optimise_page_slug.getAttribute('data-url');
    const errorsEl = document.getElementById('css_optimise_errors');

    if (!url) return;

    //offload to server
    const options = {
        action: 'wporg_ajax_change',
        sbp_url: url,
        post_ID: post_ID.value,
    };

    const handleReq = (response) => {
        console.log(response)
        spinner.style.visibility = "hidden";
        if (!response) return;
        if (response.err) {
            return errorsEl.innerText = response.err;
        }
        css_optimise_current_performance_file.innerText = response;
    }

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: new URLSearchParams(options).toString()
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(handleReq)
        .catch((error) => {
            console.log(error);
        });



}




window.onload = main; //run script after window loaded