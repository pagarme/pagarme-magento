/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */

Validation.creditCartTypes = $H({
    'EL': [new RegExp('^(40117(8|9)|431274|438935|451416|457393|45763(1|2)|504175|627780|636297|636368|(506699|5067[0-6][0-9]|50677[0-8])|(50900[0-9]|5090[1-9][0-9]|509[1-9][0-9]{2})|65003[1-3]|(65003[5-9]|65004[0-9]|65005[0-1])|(65040[5-9]|6504[1-3][0-9])|(65048[5-9]|65049[0-9]|6505[0-2][0-9]|65053[0-8])|(65054[1-9]|6505[5-8][0-9]|65059[0-8])|(65070[0-9]|65071[0-8])|65072[0-7]|(65090[1-9]|65091[0-9]|650920|65092[1-9]|6509[3-6][0-9]|65097[0-8])|(65165[2-9]|6516[6-7][0-9])|(65500[0-9]|65501[0-9])|(65502[1-9]|6550[3-4][0-9]|65505[0-8]))[0-9]{10,12}$'), new RegExp('^([0-9]{3})?$'), false, new RegExp('^401178|^401179|^431274|^438935|^451416|^457393|^457631|^457632|^504175|^627780|^636297|^636368|^(506699|5067[0-6][0-9]|50677[0-8])|^(50900[0-9]|5090[1-9][0-9]|509[1-9][0-9]{2})|^65003[1-3]|^(65003[5-9]|65004[0-9]|65005[0-1])|^(65040[5-9]|6504[1-3][0-9])|^(65048[5-9]|65049[0-9]|6505[0-2][0-9]|65053[0-8])|^(65054[1-9]|6505[5-8][0-9]|65059[0-8])|^(65070[0-9]|65071[0-8])|^65072[0-7]|^(65090[1-9]|65091[0-9]|650920|65092[1-9]|6509[3-6][0-9]|65097[0-8])|^(65165[2-9]|6516[6-7][0-9])|^(65500[0-9]|65501[0-9])|^(65502[1-9]|6550[3-4][0-9]|65505[0-8])')],
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
