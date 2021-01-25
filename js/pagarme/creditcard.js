function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

const getCardNumber = () => get('#pagarme_creditcard_creditcard_number').value;

const getCardExpirationDate = () => get('#pagarme_creditcard_creditcard_expiration_date').value;

const isFilled = (input) => {
  return input.type !== 'select-one' ? input.value.length >= 3 : true;
}

const getCardData = () => {
  return {
    card_number: getCardNumber().replace(/\s/g, ''),
    card_holder_name: get('#pagarme_creditcard_creditcard_owner').value,
    card_expiration_date: getCardExpirationDate().replace(/\s|\//g, ''),
    card_cvv: get('#pagarme_creditcard_creditcard_cvv').value,
  };
}

const isValidCardExpirationDate = () => {
  /**
   * The regex validates the following formats:
   * MMYY, MM/YY, MM / YY
   */
  const validDateRegex = new RegExp(/\d{2}\s?\/?\s?\d{2}/);

  return validDateRegex.test(getCardExpirationDate());
}

const shouldGenerateCardHash = (card) => {
  if (!pagarmeCreditcardSelected()){
    return false;
  }

  const pagarmeInputsNodeList = document.querySelectorAll('.card-data input');
  const pagarmeCardInputs = Array.from(pagarmeInputsNodeList);

  if (!pagarmeCardInputs.every(isFilled)) {
    return false;
  }

  const cardValidations = pagarme.validate({
    card
  });

  const { card_holder_name, card_cvv } = cardValidations.card;

  return card_holder_name && card_cvv && isValidCardExpirationDate();
}

const tryGenerateCardHash = debounce(() => {
  if (shouldGenerateCardHash(getCardData())) {
    clearHash();
    return generateHash(getCardData());
  }
}, 600);
