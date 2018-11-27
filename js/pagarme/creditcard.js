document.onreadystatechange = () => {
  if (document.readyState === 'complete') {
    // https://tc39.github.io/ecma262/#sec-array.prototype.includes
    if (!Array.prototype.includes) {
      Object.defineProperty(Array.prototype, 'includes', {
        value: function (searchElement, fromIndex) {

          // 1. Let O be ? ToObject(this value).
          if (this == null) {
            throw new TypeError('"this" is null or not defined');
          }

          var o = Object(this)

          // 2. Let len be ? ToLength(? Get(O, "length")).
          var len = o.length >>> 0

          // 3. If len is 0, return false.
          if (len === 0) {
            return false
          }

          // 4. Let n be ? ToInteger(fromIndex).
          //    (If fromIndex is undefined, this step produces the value 0.)
          var n = fromIndex | 0

          // 5. If n ≥ 0, then
          //  a. Let k be n.
          // 6. Else n < 0,
          //  a. Let k be len + n.
          //  b. If k < 0, let k be 0.
          var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0)

          // 7. Repeat, while k < len
          while (k < len) {
            // a. Let elementK be the result of ? Get(O, ! ToString(k)).
            // b. If SameValueZero(searchElement, elementK) is true, return true.
            // c. Increase k by 1.
            // NOTE: === provides the correct "SameValueZero" comparison needed here.
            if (o[k] === searchElement) {
              return true
            }
            k++
          }

          // 8. Return false
          return false
        },
      })
    }

    var addedEvent = false

    get('#opc-review, #checkout-step-payment').addEventListener('click', function() {
      addedEvent = false
    })

    get('#opc-review, #checkout-review-submit').addEventListener('click', function (event) {
      if (event.path) {
        var buttons = this.getElementsByClassName('btn-checkout')
        const button = buttons[0]
        const buttonOnClick = button.onclick

        for (var i = 0; i < event.path.length; i++) {
          if (event.path[i].tagName == 'BUTTON' && !addedEvent) {
            event.path[i].onclick = null
            event.path[i].addEventListener('click', function () {
              generateHash()
                .then(() => buttonOnClick())
            })

            addedEvent = true
          }
        }
        return
      }

      if (event.target.tagName === 'BUTTON' && !addedEvent) {
        var buttons = this.getElementsByClassName('btn-checkout')
        const button = buttons[0]
        const buttonOnClick = button.onclick

        event.target.onclick = null
        event.target.addEventListener('click', function () {
          generateHash()
            .then(() => buttonOnClick())
        })

        addedEvent = true
      }
    }, true)
  }
}
