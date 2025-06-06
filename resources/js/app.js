// Import manon JS components
import '@minvws/manon/accordion';
import '@minvws/manon/collapsible';

// wait for the document to be loaded
document.addEventListener('DOMContentLoaded', function () {
    bindLogoutClickHandler();
    bindLoadCredentialIntoWalletForm();
});

function bindLogoutClickHandler()
{
    // check if logout button exists
    if (!document.querySelector('#logout-link')
        || !document.querySelector('#logout-form')) {
        return
    }

    // get logout button
    const logoutButton = document.querySelector('#logout-link');

    // add click event listener
    logoutButton.addEventListener('click', function (event) {
        // prevent default behaviour
        event.preventDefault();

        // get logout form
        const logoutForm = document.querySelector('#logout-form');

        // submit form
        logoutForm.submit();
    });
}

function bindLoadCredentialIntoWalletForm()
{
    // check if load credential into wallet form exists
    if (!document.querySelector('#load-credential-into-wallet-form')) {
        return
    }

    // bind form submit event
    const form = document.querySelector('#load-credential-into-wallet-form');
    form.addEventListener('submit', function (event) {
        // prevent default behaviour
        event.preventDefault();

        // get the form data, the formdata contains wallet_url and credential_offer_uri.
        const formData = new FormData(form);

        // redirect to the wallet app with the form data
        const walletUrl = formData.get('wallet_url');
        const credentialOfferUri = formData.get('credential_offer_uri');

        if (walletUrl && credentialOfferUri) {
            window.location.href = `${walletUrl}?credential_offer_uri=${encodeURIComponent(credentialOfferUri)}`;
        }
    });
}
