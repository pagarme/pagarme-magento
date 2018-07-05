const get = id => document.querySelector(id)

const generateHash = () => {
  const card = {
    card_number: document.getElementById('pagarme_creditcard_creditcard_number').value,
    card_holder_name: document.getElementById('pagarme_creditcard_creditcard_owner').value,
    card_expiration_date: document.getElementById('pagarme_creditcard_creditcard_expiration_date').value,
    card_cvv: document.getElementById('pagarme_creditcard_creditcard_cvv').value,
  }
  const encryptionKey = document.getElementById('pagarme_encryption_key').value
  return pagarme.client.connect({
    encryption_key: encryptionKey
  })
    .then(client => client.security.encrypt(card))
    .then((card_hash) => {
      document.getElementById('pagarme_card_hash').value = card_hash
      console.log(card_hash)
    })
}

const clearHash = () => {
  get('#pagarme_card_hash').value = ''
}

const pagarmeCreditcardSelected = () => {
  return document.getElementById('p_method_pagarme_creditcard').checked
}

//imposes a order in the click event
//tested only on click event
eventAdded = false
const eventBefore = (newFunction, event, prototypeElement) => {
  if (eventAdded === false) {
    const originalObservers = prototypeElement.getStorage()
      .get('prototype_event_registry')
      .get(event);
    const newObserver = () => {
      newFunction().then(() => {
          originalObservers.each((wrapper) => {
          wrapper.handler()
        })
      })
    }

    prototypeElement.stopObserving(event)
    prototypeElement.observe(event, newObserver)
    eventAdded = true
  }
  return prototypeElement
}

document.onreadystatechange = () => {
  const placeOrderButton = OSCForm.placeOrderButton
  eventBefore(() => {
    if (pagarmeCreditcardSelected()) {
      clearHash()
      return generateHash()
    }
  }, 'click', placeOrderButton)
}
