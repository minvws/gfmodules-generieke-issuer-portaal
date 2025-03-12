// Import manon JS components
import '@minvws/manon/accordion';
import '@minvws/manon/collapsible';

// wait for the document to be loaded
document.addEventListener('DOMContentLoaded', function() {
    bindLogoutClickHandler();
});

function bindLogoutClickHandler() {
    // check if logout button exists
    if (!document.querySelector('#logout-link')
        || !document.querySelector('#logout-form')) {
        return
    }

    // get logout button
    const logoutButton = document.querySelector('#logout-link');

    // add click event listener
    logoutButton.addEventListener('click', function(event) {
        // prevent default behaviour
        event.preventDefault();

        // get logout form
        const logoutForm = document.querySelector('#logout-form');

        // submit form
        logoutForm.submit();
    });
}
