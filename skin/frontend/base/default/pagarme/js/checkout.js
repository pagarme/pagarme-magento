/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */

function pagarmeDocumentHeight()
{
    var D = document;

    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}

function pagarmeShowLoader ()
{
    $("pagarme-overlay").setStyle({ height: pagarmeDocumentHeight() + "px" });
    $("pagarme-overlay").show ();
    $("pagarme-mask").show ();
}

function pagarmeHideLoader ()
{
    $("pagarme-mask").hide ();
    $("pagarme-overlay").hide ();
}

function pagarmeDisableAll(element)
{
    $$(element).each(function(obj){
        $(obj).disable();
        $(obj).setStyle({background: 'red'});
    });
}

function pagarmeCreditCard()
{
    var creditCard = new PagarMe.creditCard();
    creditCard.cardHolderName = $(OSCPayment.currentMethod+'_cc_owner').value;
    creditCard.cardExpirationMonth = $(OSCPayment.currentMethod+'_expiration').value;
    creditCard.cardExpirationYear = $(OSCPayment.currentMethod+'_expiration_yr').value;
    creditCard.cardNumber = $(OSCPayment.currentMethod+'_cc_number').value;
    creditCard.cardCVV = $(OSCPayment.currentMethod+'_cc_cid').value;

    if(!creditCard.cardHolderName.length
        || !creditCard.cardExpirationMonth.length || !creditCard.cardExpirationYear.length
        || !creditCard.cardNumber.length || !creditCard.cardCVV.length) return;

    return creditCard;
}

function pagarmeInitCheckout()
{
    console.log('Pagarme: initPagarmeCheckout');

    PagarMe.encryption_key = pagarme_encryption_key;

    // PagarMe._ajax = PagarMe.ajax;
    PagarMe.ajax = function (url, callback) {
        var httpRequest,
            xmlDoc;

        if (window.XMLHttpRequest) {
            httpRequest = new XMLHttpRequest();
        } else {
            httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
        }

        pagarmeShowLoader ();

        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState != 4) {
                return;
            }

            if (httpRequest.status != 200 && httpRequest.status != 304) {
                return;
            }
            callback(JSON.parse(httpRequest.responseText));

            pagarmeHideLoader ();
        };

        httpRequest.open("GET", url, true);
        httpRequest.send(null);
    };

} // pagarmeInitCheckout

function pagarmeJSEvent()
{
    pagarmeInitCheckout ();

    console.log('Pagarme: Ready');

    pagarmeHideLoader ();
}

document.observe("dom:loaded",function(){

pagarmeShowLoader ();

var pagarmeJS = document.createElement('script');
pagarmeJS.type = "text/javascript";
pagarmeJS.async = true;
pagarmeJS.src = 'https://assets.pagar.me/js/pagarme.min.js';
if(pagarmeJS.attachEvent) {
    // pagarmeJS.attachEvent('onreadystatechange', function(){
    pagarmeJS.onreadystatechange = function(){
        if(this.readyState === 'loaded' || this.readyState === 'complete') pagarmeJSEvent();
    };
} else {
    pagarmeJS.addEventListener('load', function(){ pagarmeJSEvent(); }, false);
}

var head = document.getElementsByTagName('head')[0];
head.appendChild(pagarmeJS);

});

