// Import manon JS components
import '@minvws/manon/accordion';
import '@minvws/manon/collapsible';

// wait for the document to be loaded
document.addEventListener('DOMContentLoaded', function () {
    bindLogoutClickHandler();
    bindPresentCredentialForm();
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

function bindPresentCredentialForm()
{
    // check if load credential into wallet form exists
    if (!document.querySelector('#present-credential-form') && !document.querySelector('#load-credential-into-wallet-form')) {
        return
    }

    // bind form submit event
    const form = document.querySelector('#present-credential-form') || document.querySelector('#load-credential-into-wallet-form');
    form.addEventListener('submit', function (event) {
        // prevent default behaviour
        event.preventDefault();

        // get the form data, the formdata contains wallet_url and present_credential_uri.
        const formData = new FormData(form);

        // redirect to the wallet app with the form data
        const walletUrl = formData.get('wallet_url');
        const credentialPresentationUri = formData.get('present_credential_uri');
        const credentialOfferUri = formData.get('credential_offer_uri');
        
        if (walletUrl && credentialOfferUri) {
            window.location.href = `${walletUrl}?credential_offer_uri=${encodeURIComponent(credentialOfferUri)}`;
        } else if (walletUrl && credentialPresentationUri) {
            // Strip `openid4vp://authorize`
            window.location.href = `${walletUrl}${credentialPresentationUri.replace('openid4vp://authorize', '')}`;
        }
    });
}
