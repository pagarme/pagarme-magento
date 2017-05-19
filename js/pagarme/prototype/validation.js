/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */

Validation.add('validate-pagarme-cc-number', 'Please enter a valid credit card number.', function(v, elm) {
    if (pagarmeIsValidCardNumber(v)) {
        return true;
    }

    return false;
});

function pagarmeIsValidCardNumber(cardNumber) {
    if (!cardNumber) {
        return false;
    }

    cardNumber = cardNumber.replace(/[^0-9]/g, '');

    var luhnDigit = parseInt(cardNumber.substring(cardNumber.length-1, cardNumber.length));
    var luhnLess = cardNumber.substring(0, cardNumber.length-1);

    var sum = 0;

    for (i = 0; i < luhnLess.length; i++) {
        sum += parseInt(luhnLess.substring(i, i+1));
    }

    var delta = new Array (0,1,2,3,4,-4,-3,-2,-1,0);

    for (i = luhnLess.length - 1; i >= 0; i -= 2) {
        var deltaIndex = parseInt(luhnLess.substring(i, i+1));
        var deltaValue = delta[deltaIndex];
        sum += deltaValue;
    }

    var mod10 = sum % 10;
    mod10 = 10 - mod10;

    if (mod10 == 10) {
        mod10 = 0;
    }

    return (mod10 == parseInt(luhnDigit));
}

Validation.add('validate-pagarme-cc-exp', 'Incorrect credit card expiration date.', function(v, elm){
    var ccExpMonth   = v;
    var ccExpYear    = $(elm.id.substr(0,elm.id.indexOf('_expiration')) + '_expiration_yr').value;

    if (ccExpMonth == "" && ccExpYear == "") {
        return true;
    }

    if (ccExpMonth && ccExpYear && Validation.get('validate-cc-exp').test(v, elm)) {
        return true;
    }
    return false;
});

Validation.add('validate-pagarme-cc-cvn', 'Please enter a valid credit card verification number.', function(v, elm){
    var ccTypeContainer = $(elm.id.substr(0,elm.id.indexOf('_cc_cid')) + '_cc_type');
    if (!ccTypeContainer) {
        return true;
    }
    var ccType = ccTypeContainer.value;

    return true;
});