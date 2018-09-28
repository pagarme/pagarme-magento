var eventAdded = false

const pagarmeCreditcardSelected = () => {
  return get('#p_method_pagarme_creditcard').checked
}

//imposes a order in the click event
//tested only on click event
const eventBefore = (newFunction, event, prototypeElement) => {
  if (eventAdded === false) {
    const originalObservers = prototypeElement.getStorage()
      .get('prototype_event_registry')
      .get(event)
    const newObserver = () => {
      newFunction()
        .then(() => {
          originalObservers.each((wrapper) => {
            wrapper.handler()
          })
        })
        .catch((error) => {
          console.error(error)
        })
    }

    prototypeElement.stopObserving(event)
    prototypeElement.observe(event, newObserver)
    eventAdded = true
  }
  return prototypeElement
}

document.onreadystatechange = () => {
  const { placeOrderButton } = OSCForm
  eventBefore(() => {
    if (pagarmeCreditcardSelected()) {
      clearHash()
      return generateHash()
    }
    return Promise.reject(new Error('Can\'t generate the cardHash'))
  }, 'click', placeOrderButton)
}
