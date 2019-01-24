const get = id => document.querySelector(id)

const clearHash = () => {
  get('#pagarme_card_hash').value = ''
}

const generateHash = () => {
  const card_number_value = get('#pagarme_creditcard_creditcard_number').value
  const card_expiration_date_value = get('#pagarme_creditcard_creditcard_expiration_date').value

  const card = {
    card_number: card_number_value.replace(/\s/g, ''),
    card_holder_name: get('#pagarme_creditcard_creditcard_owner').value,
    card_expiration_date: card_expiration_date_value.replace(/\s|\//g, ''),
    card_cvv: get('#pagarme_creditcard_creditcard_cvv').value,
  }
  const encryptionKey = get('#pagarme_encryption_key').value
  return pagarme.client.connect({ encryption_key: encryptionKey })
    .then(client => client.security.encrypt(card))
    .then((cardHash) => {
      get('#pagarme_card_hash').value = cardHash
    })
}
