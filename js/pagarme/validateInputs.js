/**
 * The regex validates the following formats:
 * 'MMYY', 'MM/YY', 'MM / YY'
 */
const validDateRegex = new RegExp(/\d{2}\s?\/?\s?\d{2}/)

Validation.add('validate-card-expiration-date', 'Please enter a valid expiration date. For example 12 / 25', function(value) {
    return validDateRegex.test(value)
})
