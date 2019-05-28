/**
 * The regex validates the following formats:
 * 'MMYY', 'MM/YY', 'MM / YY'
 */
const validDateRegex = new RegExp(/\d{2}\s?\/?\s?\d{2}/)
const hasNumber = new RegExp(/\d+/)

Validation.add('validate-card-expiration-date', 'Please, enter a valid expiration date. For example 12 / 25.', function(value) {
  return validDateRegex.test(value)
})

Validation.add('validate-card-holder-length', 'Please, enter a valid name.', function(value) {
  return value.length >= 3 && !hasNumber.test(value)
})

Validation.add('validate-card-number-length', 'Please, enter a valid credit card number.', function(value) {
  return value.length >= 10
})

Validation.add('validate-cvv-length', 'Please, enter a valid credit card verification number.', function(value) {
  return value.length >= 3 && value.length <= 4
})

Validation.add('validate-installments', 'Please, select the number of installments.', function(value) {
  return value >= 1 && value <= 12
})
