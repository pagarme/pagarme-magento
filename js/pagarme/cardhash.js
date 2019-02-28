const get = id => document.querySelector(id)

const pagarmeCreditcardSelected = () => get('#p_method_pagarme_creditcard').checked

const clearHash = () => {
  get('#pagarme_card_hash').value = ''
}

const generateHash = (card) => {
  const encryptionKey = get('#pagarme_encryption_key').value
  return pagarme.client.connect({
      encryption_key: encryptionKey
    })
    .then(client => client.security.encrypt(card))
    .then((cardHash) => {
      get('#pagarme_card_hash').value = cardHash
    })
}
