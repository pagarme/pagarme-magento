/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */

Validation.creditCartTypes = $H({
    'EL': [new RegExp('^(40117(8|9)|431274|438935|636297|451416|45763(1|2)|504175|5067((17)|(18)|(22)|(25)|(26)|(27)|(28)|(29)|(30)|(33)|(39)|(40)|(41)|(42)|(44)|(45)|(46)|(47)|(48))|627780|636297|636368)[0-9]{10}$'), new RegExp('^([0-9]{3})?$'), false, new RegExp('^(40117(8|9)|431274|438935|636297|451416|45763(1|2)|504175|5067((17)|(18)|(22)|(25)|(26)|(27)|(28)|(29)|(30)|(33)|(39)|(40)|(41)|(42)|(44)|(45)|(46)|(47)|(48))|627780|636297|636368)')],
    'HC': [new RegExp('^(606282[0-9]{10}([0-9]{3})?)|(3841[0-9]{15})$'), new RegExp('^[0-9]{3}$'), false, new RegExp('^(606282|3841)')],
    'DI': [new RegExp('^6011[0-9]{12}$'), new RegExp('^[0-9]{3}$'), true, new RegExp('^6011')],
    'DC': [new RegExp('^3(?:0[0-5]|[68][0-9])[0-9]{11}$'), new RegExp('^[0-9]{3}$'), true, new RegExp('^3(?:0[0-5]|[68][0-9])')],
    'JCB': [new RegExp('^35[0-9]{14}$'), new RegExp('^[0-9]{3,4}$'), true, new RegExp('^35')],
    'AU': [new RegExp('^50[0-9]{17}$'), new RegExp('^[0-9]{3}$'), false, new RegExp('^50')],
    'AE': [new RegExp('^3[47][0-9]{13}$'), new RegExp('^[0-9]{4}$'), true, new RegExp('^3[47]')],
    'VI': [new RegExp('^4[0-9]{12}([0-9]{3})?$'), new RegExp('^[0-9]{3}$'), true, new RegExp('^4')],
    'MC': [new RegExp('^5[1-5][0-9]{14}$'), new RegExp('^[0-9]{3}$'), true, new RegExp('^5[1-5]')]
});

Validation.add('validate-pagarme-cc-number', 'Please enter a valid credit card number.', function(v, elm) {

    if (pagarmeIsValidCardNumber(v) && Validation.get('validate-cc-type').test(v, elm)) {
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

    if (typeof Validation.creditCartTypes.get(ccType) == 'undefined') {
        return true;
    }

    var re = Validation.creditCartTypes.get(ccType)[1];

    if (v.match(re)) {
        return true;
    }

    return false;
});
